<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;

use app\models\Deteccion;
use app\models\Especie;
use app\models\Taxonomia;
use app\models\Modelo;
use app\helpers\LibreriaHelper;

class DeteccionController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $b = parent::behaviors();

        // CORS
        $b['corsFilter'] = [
            'class' => Cors::class,
            'cors'  => [
                'Origin' => ['*'],
                'Access-Control-Allow-Methods' => ['POST', 'GET', 'OPTIONS'],
                'Access-Control-Allow-Headers' => ['*'],
            ],
        ];

        // Forzar JSON
        $b['contentNegotiator'] = [
            'class'   => ContentNegotiator::class,
            'formats' => ['application/json' => Response::FORMAT_JSON],
        ];

        return $b;
    }

    /**
     * Construye URL absoluta pública a un archivo dentro de /recursos/uploads
     * Ej: 'detecciones/17.jpg' -> https://ecolens.site/recursos/uploads/detecciones/17.jpg
     */
    private function buildUploadUrl(?string $relativePath): ?string
    {
        if (!$relativePath) {
            return null;
        }
        $clean = ltrim($relativePath, "/");
        $clean = preg_replace('~^(\.\./)+~', '', $clean); // limpia ../../
        $host = rtrim(Yii::$app->request->hostInfo, '/'); // https://ecolens.site
        return $host . '/recursos/uploads/' . $clean;
    }

    /**
     * Construye URL pública de la ficha especie:
     * https://ecolens.site/sitio/web/taxonomias/{tax_slug}/{esp_slug}
     */
    private function buildFichaUrl($taxSlug, $espSlug): ?string
    {
        if (!$taxSlug || !$espSlug) {
            return null;
        }
        $host = rtrim(Yii::$app->request->hostInfo, '/');
        return $host . '/sitio/web/taxonomias/' . $taxSlug . '/' . $espSlug;
    }

    
    // ─────────────────────────────────────────────
    // REGISTRAR
    // ─────────────────────────────────────────────
    public function actionRegistrar()
    {
        $req  = Yii::$app->request;
        $body = $req->isPost ? $_POST : $req->getBodyParams();

        // asegurar sesión (por si necesitamos usuario observado)
        $session = Yii::$app->session;
        if (!$session->isActive) { $session->open(); }

        $db  = Yii::$app->db;
        $det = new Deteccion();

        try {
            $tx = $db->beginTransaction();

            // 1. Campos base
            // Guardamos exactamente los mismos nombres que tuviste antes,
            // con los mismos redondeos/casts.
            $det->det_confianza_router   = isset($body['det_confianza_router'])  ? round((float)$body['det_confianza_router'],  6) : null;
            $det->det_confianza_experto  = isset($body['det_confianza_experto']) ? round((float)$body['det_confianza_experto'], 6) : null;
            $det->det_tiempo_router_ms   = isset($body['det_tiempo_router_ms'])  ? (int)$body['det_tiempo_router_ms']  : null;
            $det->det_tiempo_experto_ms  = isset($body['det_tiempo_experto_ms']) ? (int)$body['det_tiempo_experto_ms'] : null;

            $det->det_latitud            = $body['det_latitud']           ?? null;
            $det->det_longitud           = $body['det_longitud']          ?? null;
            $det->det_ubicacion_textual  = $body['det_ubicacion_textual'] ?? null;

            // quién hizo la detección
            $userId = null;
            if (isset($body['det_obs_id']) && $body['det_obs_id'] !== '') {
                $userId = (int)$body['det_obs_id'];
            }
            if (!$userId) {
                $userId = (int)($session->get('observador_id') ?? $session->get('usuario_id') ?? 0);
            }
            if ($det->hasAttribute('det_obs_id')) {
                $det->det_obs_id = $userId ?: null;
            }

            $det->det_ip_cliente         = $req->userIP ?? null;
            $det->det_dispositivo_tipo   = $body['det_dispositivo_tipo']  ?? 'desktop';
            $det->det_sistema_operativo  = $body['det_sistema_operativo'] ?? 'desconocido';
            $det->det_navegador          = $body['det_navegador']         ?? 'desconocido';
            $det->det_fuente             = $body['det_fuente']            ?? 'web';
            $det->det_estado             = $body['det_estado']            ?? 'pendiente';
            $det->det_revision_estado    = $body['det_revision_estado']   ?? 'sin_revisar';
            $det->det_fecha              = date('Y-m-d H:i:s');
            $det->created_at             = date('Y-m-d H:i:s');
            $det->updated_at             = date('Y-m-d H:i:s');

            // 2. Taxonomía / Especie (misma resolución que ya tenías)
            $taxId = $body['det_tax_id'] ?? null;
            if (!$taxId && !empty($body['taxon_predicted'])) {
                $taxSlug = LibreriaHelper::generateSlug(trim((string)$body['taxon_predicted']));
                $tax = Taxonomia::find()->where(['tax_slug' => $taxSlug])->one();
                if ($tax) { $taxId = $tax->tax_id; }
            }

            $espId = $body['det_esp_id'] ?? null;
            $esp   = null;
            if (!$espId && !empty($body['species_predicted'])) {
                $espSlug = LibreriaHelper::generateSlug(
                    str_replace('_',' ', trim((string)$body['species_predicted']))
                );
                $esp = Especie::find()->where(['esp_slug' => $espSlug])->one();
                if ($esp) { $espId = $esp->esp_id; }
            } elseif ($espId) {
                $esp = Especie::findOne($espId);
            }

            if (!$taxId && $esp) {
                $fk = $esp->hasAttribute('esp_tax_id') ? 'esp_tax_id'
                     : ($esp->hasAttribute('tax_id')    ? 'tax_id'
                     : null);
                if ($fk && !empty($esp->$fk)) {
                    $taxId = $esp->$fk;
                }
            }

            $det->det_tax_id = $taxId;
            $det->det_esp_id = $espId;

            // 3. Modelos IA (router / experto), manteniendo tu patrón
            if (!empty($body['router_model'])) {
                $m = Modelo::findOne([
                        'mod_nombre' => $body['router_model'],
                        'mod_tipo'   => 'router'
                    ])
                    ?: new Modelo([
                        'mod_nombre' => $body['router_model'],
                        'mod_tipo'   => 'router'
                    ]);
                $m->save(false);
                $det->det_modelo_router_id = $m->mod_id;
            }
            if (!empty($body['expert_model'])) {
                $m = Modelo::findOne([
                        'mod_nombre' => $body['expert_model'],
                        'mod_tipo'   => 'experto'
                    ])
                    ?: new Modelo([
                        'mod_nombre' => $body['expert_model'],
                        'mod_tipo'   => 'experto'
                    ]);
                $m->save(false);
                $det->det_modelo_experto_id = $m->mod_id;
            }

            // 4. Guardar fila deteccion
            if (!$det->save(false)) {
                throw new \RuntimeException('Fallo al guardar la detección.');
            }
            $id = (int)$det->det_id;
            if ($id <= 0) {
                throw new \RuntimeException('PK no asignado tras save().');
            }

            // Refuerzo: asegurar det_obs_id si lo obtuvimos después
            if ($userId > 0 && $det->hasAttribute('det_obs_id')) {
                Yii::$app->db->createCommand()
                    ->update($det::tableName(), ['det_obs_id' => $userId], ['det_id' => $id])
                    ->execute();
                $det->refresh();
            }

            // 5. Subir imagen
            // En tu versión original, tú hacías todo a mano (UploadedFile::getInstanceByName(...),
            // mkdir, borrado, resize jpeg, set det_imagen, det_origen_archivo, etc.)
            //
            // Lo vamos a mantener, pero usando parte del helper para la conversión HEIC->JPG
            // y para estandarizar el nombre final {id}.{ext}.
            //
            // Truco: montamos un objeto "falso" compatible con subirFoto:
            // necesitamos que el campo se llame 'imagen' en el FormData (ya lo tienes).
            // 5. Subir imagen

            $archivo = UploadedFile::getInstanceByName('imagen');
            if ($archivo) {

                // Guardar archivo en disco (conversión HEIC->JPG si aplica)
                // Nota: el campo real en tu tabla es det_imagen
                $rutaDB = \app\helpers\LibreriaHelper::subirFoto(
                    $det,
                    'det_imagen',        // este es el atributo REAL del modelo Deteccion
                    'detecciones',       // subcarpeta en recursos/uploads
                    $archivo             // le pasamos el UploadedFile explícitamente
                );

                if ($rutaDB) {
                    // Guardar metadata en la fila
                    $det->det_imagen         = $rutaDB;
                    $det->det_origen_archivo = $archivo->name ?? null;

                    // Resize suave si quedó en jpg/jpeg
                    $partes   = explode('.', $rutaDB);
                    $extFinal = strtolower(end($partes));
                    if (in_array($extFinal, ['jpg','jpeg'], true)) {

                        // Ruta absoluta al archivo recién guardado
                        $webroot = rtrim(Yii::getAlias('@webroot'), '/'); // /home/.../panel-admin/web
                        $dirFs   = $webroot . '/../../recursos/uploads/'; // /home/.../recursos/uploads/
                        $absPath = $dirFs . $rutaDB;                      // .../recursos/uploads/detecciones/{id}.jpg

                        if (is_file($absPath)) {
                            // redimensionar a ancho máximo 1600px
                            self::smartResizeJpeg($absPath);
                        }
                    }

                    // persistimos cambios en la fila detecciones ahora que ya tenemos ruta
                    $det->save(false);
                }
            }

            $tx->commit();

            // preparar respuesta
            $imagenPublica = $this->buildUploadUrl($det->det_imagen);

            // generar link a la ficha especie
            $fichaUrl = null;
            if ($det->det_esp_id && $esp) {
                $tax = null;
                $fk = $esp->hasAttribute('esp_tax_id') ? 'esp_tax_id'
                     : ($esp->hasAttribute('tax_id')    ? 'tax_id'
                     : null);
                if ($fk && !empty($esp->$fk)) {
                    $tax = Taxonomia::findOne($esp->$fk);
                }
                if ($tax && $tax->tax_slug && $esp->esp_slug) {
                    $fichaUrl = $this->buildFichaUrl($tax->tax_slug, $esp->esp_slug);
                }
            }

            return [
                'success'      => true,
                'message'      => '✅ Detección registrado exitosamente.',
                'id'           => $id,
                'taxon_id'     => $det->det_tax_id,
                'especie_id'   => $det->det_esp_id,
                'imagen'       => $imagenPublica,
                'url_especie'  => $fichaUrl,
            ];

        } catch (\Throwable $e) {
            if (isset($tx) && $tx->isActive) $tx->rollBack();
            Yii::error("❌ Error en detección: " . $e->getMessage(), __METHOD__);
            throw new BadRequestHttpException("Error al registrar: " . $e->getMessage());
        }
    }

    private static function smartResizeJpeg(string $absPath): void
    {
        // LibreriaHelper::resizeImage($ruta, $nombre, $maxWidth, $maxHeight)
        // OJO: en tu helper, resizeImage ignora $ruta y usa $nombre directamente,
        // pero igual se la pasamos prolija.
        $dir  = dirname($absPath) . '/';
        $file = $absPath;
        \app\helpers\LibreriaHelper::resizeImage($dir, $file, 1600, null);
    }

    // LISTAR y DETALLE los dejamos tal como los tenías antes.
    // No los repito aquí para no inventarte cambios no solicitados. // ─────────────────────────────────────────────
    // DETALLE
    // ─────────────────────────────────────────────
    public function actionDetalle($id)
    {
        $det = Deteccion::findOne($id);
        if (!$det) {
            return ['success' => false, 'message' => "No se encontró la detección #$id"];
        }

        $especie = $det->det_esp_id ? Especie::findOne($det->det_esp_id) : null;
        $taxonomia = null;
        if ($especie && ($especie->esp_tax_id ?? null)) {
            $taxonomia = Taxonomia::findOne($especie->esp_tax_id);
        }

        // 🔍 Determinar columna de observador
        $observerCol = null;
        foreach (['det_obs_id', 'det_observador_id'] as $col) {
            if ($det->hasAttribute($col)) {
                $observerCol = $col;
                break;
            }
        }

        $observador = null;
        if ($observerCol && !empty($det->$observerCol)) {
            $obs = \app\models\Observador::findOne($det->$observerCol);
            if ($obs) {
                $observador = [
                    'id'      => (int)$obs->obs_id,
                    'nombre'  => $obs->obs_nombre,
                    'usuario' => $obs->obs_usuario,
                    'correo'  => $obs->obs_email ?? null,
                ];
            }
        }

        return [
            'success'          => true,
            'id'               => (int)$det->det_id,
            'fecha'            => $det->det_fecha,
            'latitud'          => $det->det_latitud,
            'longitud'         => $det->det_longitud,
            'ubicacion'        => $det->det_ubicacion_textual,
            'conf_router'      => $det->det_confianza_router,
            'conf_experto'     => $det->det_confianza_experto,
            'fuente'           => $det->det_fuente,
            'dispositivo'      => $det->det_dispositivo_tipo,
            'sistema'          => $det->det_sistema_operativo,
            'navegador'        => $det->det_navegador,

            // 🖼️ Imágenes
            'imagen_deteccion' => $this->buildUploadUrl($det->det_imagen),
            'imagen_especie'   => ($especie && $especie->esp_imagen)
                ? $this->buildUploadUrl($especie->esp_imagen)
                : null,

            // 🧬 Especie
            'especie' => $especie ? [
                'id'                => $especie->esp_id,
                'nombre_cientifico' => $especie->esp_nombre_cientifico,
                'nombre_comun'      => $especie->esp_nombre_comun,
                'descripcion'       => $especie->esp_descripcion,
                'imagen'            => $especie->esp_imagen
                    ? $this->buildUploadUrl($especie->esp_imagen)
                    : null,
            ] : null,

            // 🧭 Taxonomía
            'taxonomia' => $taxonomia ? [
                'id'     => $taxonomia->tax_id,
                'nombre' => $taxonomia->tax_nombre,
                'slug'   => $taxonomia->tax_slug,
            ] : null,

            // 👤 Observador
            'observador' => $observador,
        ];
    }

    // ─────────────────────────────────────────────
    // LISTAR
    // ─────────────────────────────────────────────
    public function actionListar()
    {
        $req     = Yii::$app->request;
        $page    = max(1, (int)$req->get('page', 1));
        $perPage = min(100, max(1, (int)$req->get('per_page', 24)));

        // ID del observador
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        $observerId = (int)$req->get('observer_id', 0);
        if ($observerId <= 0) {
            $observerId = (int)($session->get('observador_id') ?? $session->get('usuario_id') ?? 0);
        }

        // Query principal: detecciones con posibles relaciones
        $q = Deteccion::find()
            ->orderBy(['det_id' => SORT_DESC])
            ->with(['especie.taxonomia']); // mantiene las relaciones sin excluir nulos

        // Determinar columna de observador según estructura
        $probe = new Deteccion();
        $observerCol = $probe->hasAttribute('det_observador_id')
            ? 'det_observador_id'
            : ($probe->hasAttribute('det_obs_id') ? 'det_obs_id' : null);

        if ($observerId > 0 && $observerCol) {
            $q->andWhere([$observerCol => $observerId]);
        }

        // Paginación
        $total = (clone $q)->count();
        $rows  = $q->offset(($page - 1) * $perPage)->limit($perPage)->all();

        $items = [];
        foreach ($rows as $d) {
            $esp = $d->especie ?? null;
            $tax = ($esp && method_exists($esp, 'getTaxonomia')) ? $esp->taxonomia : null;

            // Fallbacks cuando no hay especie
            $nombreCientifico = $esp->esp_nombre_cientifico
                ?? $d->taxon_predicted
                ?? 'No definida';
            $nombreComun = $esp->esp_nombre_comun ?? 'Sin nombre común';
            $slugEspecie = $esp->esp_slug ?? null;

            // Ficha solo si existen slugs válidos
            $urlFicha = ($tax && $tax->tax_slug && $slugEspecie)
                ? $this->buildFichaUrl($tax->tax_slug, $slugEspecie)
                : null;

            $items[] = [
                'id'                => (int)$d->det_id,
                'fecha'             => $d->det_fecha,
                'latitud'           => $d->det_latitud,
                'longitud'          => $d->det_longitud,
                'confianza_router'  => $d->det_confianza_router,
                'confianza_experto' => $d->det_confianza_experto,
                'ubicacion'         => $d->det_ubicacion_textual,

                // Imagen subida por el usuario
                'imagen_deteccion'  => $this->buildUploadUrl($d->det_imagen),

                // Imagen referencial de la especie (si existe)
                'imagen_especie'    => ($esp && $esp->esp_imagen)
                    ? $this->buildUploadUrl($esp->esp_imagen)
                    : null,

                // Bloque especie (o fallback)
                'especie' => [
                    'id'                 => $esp->esp_id ?? null,
                    'nombre_cientifico'  => $nombreCientifico,
                    'nombre_comun'       => $nombreComun,
                    'slug'               => $slugEspecie,
                    'descripcion'        => $esp->esp_descripcion ?? null,
                    'imagen'             => ($esp && $esp->esp_imagen)
                        ? $this->buildUploadUrl($esp->esp_imagen)
                        : null,
                    'taxonomia'          => $tax ? [
                        'id'     => $tax->tax_id,
                        'nombre' => $tax->tax_nombre,
                        'slug'   => $tax->tax_slug,
                        'icono'  => $tax->tax_icono ?? null,
                    ] : null,
                ],

                'url_especie' => $urlFicha,
            ];
        }

        return [
            'success'          => true,
            'page'             => $page,
            'per_page'         => $perPage,
            'total'            => (int)$total,
            'items'            => $items,
            'observer_id_used' => $observerId,
            'observer_column'  => $observerCol,
        ];
    }

}