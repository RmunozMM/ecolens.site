<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
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

            // 2. Taxonomía / Especie
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

            // 3. Modelos IA (router / experto)
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
            $archivo = UploadedFile::getInstanceByName('imagen');
            if ($archivo) {
                $rutaDB = \app\helpers\LibreriaHelper::subirFoto(
                    $det,
                    'det_imagen',
                    'detecciones',
                    $archivo
                );

                if ($rutaDB) {
                    $det->det_imagen         = $rutaDB;
                    $det->det_origen_archivo = $archivo->name ?? null;

                    $partes   = explode('.', $rutaDB);
                    $extFinal = strtolower(end($partes));
                    if (in_array($extFinal, ['jpg','jpeg'], true)) {
                        $webroot = rtrim(Yii::getAlias('@webroot'), '/');
                        $dirFs   = $webroot . '/../../recursos/uploads/';
                        $absPath = $dirFs . $rutaDB;

                        if (is_file($absPath)) {
                            self::smartResizeJpeg($absPath);
                        }
                    }

                    $det->save(false);
                }
            }

            $tx->commit();

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
                'message'      => '✅ Detección registrada exitosamente.',
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
        $dir  = dirname($absPath) . '/';
        $file = $absPath;
        \app\helpers\LibreriaHelper::resizeImage($dir, $file, 1600, null);
    }

    // ─────────────────────────────────────────────
    // DETALLE
    // ─────────────────────────────────────────────
    public function actionDetalle($id)
    {
        $det = Deteccion::findOne($id);
        if (!$det) {
            return ['success' => false, 'message' => "No se encontró la detección #$id"];
        }

        $especie   = $det->det_esp_id ? Especie::findOne($det->det_esp_id) : null;
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

        // URL ficha especie (si tenemos slug de especie + taxonomía)
        $fichaUrl = null;
        if ($especie && $especie->esp_slug) {
            $tax = $taxonomia;
            if (!$tax && ($especie->esp_tax_id ?? null)) {
                $tax = Taxonomia::findOne($especie->esp_tax_id);
            }
            if ($tax && $tax->tax_slug) {
                $fichaUrl = $this->buildFichaUrl($tax->tax_slug, $especie->esp_slug);
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
                'slug'              => $especie->esp_slug,
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

            // 🔗 URL directa a la ficha de especie (si existe)
            'url_especie' => $fichaUrl,

            // 🧩 Feedback del observador (para paneles futuros, analytics, etc.)
            'feedback' => [
                'usuario' => $det->det_feedback_usuario,
                'fecha'   => $det->det_feedback_fecha,
            ],
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
            ->with(['especie.taxonomia']);

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

            $nombreCientifico = $esp->esp_nombre_cientifico
                ?? $d->taxon_predicted
                ?? 'No definida';
            $nombreComun = $esp->esp_nombre_comun ?? 'Sin nombre común';
            $slugEspecie = $esp->esp_slug ?? null;

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
                'feedback_usuario'  => $d->det_feedback_usuario ?? null,

                'imagen_deteccion'  => $this->buildUploadUrl($d->det_imagen),

                'imagen_especie'    => ($esp && $esp->esp_imagen)
                    ? $this->buildUploadUrl($esp->esp_imagen)
                    : null,

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

    // ─────────────────────────────────────────────
    // FEEDBACK USUARIO (like / dislike)
    // ─────────────────────────────────────────────
    public function actionFeedback()
    {
        $req = Yii::$app->request;

        if (!$req->isPost) {
            throw new BadRequestHttpException('Método no permitido. Usa POST.');
        }

        $id       = (int)($req->post('id') ?? 0);
        $feedback = trim((string)$req->post('feedback', ''));

        if ($id <= 0) {
            throw new BadRequestHttpException('ID de detección inválido.');
        }

        $validos = ['', 'like', 'dislike'];
        if (!in_array($feedback, $validos, true)) {
            throw new BadRequestHttpException('Valor de feedback no válido.');
        }

        $session = Yii::$app->session;
        if (!$session->isActive) { $session->open(); }

        $observerId = (int)($session->get('observador_id') ?? $session->get('usuario_id') ?? 0);

        $det = Deteccion::findOne($id);
        if (!$det) {
            return [
                'success' => false,
                'message' => "No se encontró la detección #$id",
            ];
        }

        // Asegurar que la detección pertenece al observador (si tenemos columna)
        $observerCol = null;
        foreach (['det_obs_id', 'det_observador_id'] as $col) {
            if ($det->hasAttribute($col)) {
                $observerCol = $col;
                break;
            }
        }

        if ($observerCol && $observerId > 0 && (int)$det->$observerCol !== $observerId) {
            throw new UnauthorizedHttpException('No puedes registrar feedback sobre una detección que no es tuya.');
        }

        // Armar payload de actualización
        if ($feedback === '') {
            // El usuario “borra” su respuesta
            $data = [
                'det_feedback_usuario' => null,
                'det_feedback_fecha'   => null,
            ];
        } else {
            $data = [
                'det_feedback_usuario' => $feedback,
                'det_feedback_fecha'   => date('Y-m-d H:i:s'),
            ];
        }

        Yii::$app->db->createCommand()
            ->update($det::tableName(), $data, ['det_id' => $id])
            ->execute();

        return [
            'success'  => true,
            'id'       => $id,
            'feedback' => $feedback === '' ? null : $feedback,
        ];
    }
}
