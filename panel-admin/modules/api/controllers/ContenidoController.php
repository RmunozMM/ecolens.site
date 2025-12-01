<?php
namespace app\modules\api\controllers;

use yii\rest\Controller;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use Yii;
use yii\db\Expression;

use app\models\Articulo;
use app\models\Servicio;
use app\models\Pagina;
use app\models\Herramienta;
use app\models\Habilidad;
use app\models\Formacion;
use app\models\Experiencia;
use app\models\Asunto;
use app\models\Proyecto;
use app\models\Cliente;
use app\models\Redes;
use app\models\Media;
use app\models\Perfil;
use app\models\Opcion;
use app\models\Testimonio;    
use app\models\ImagenesGaleria;
use app\models\Galerias;
use app\models\Taxonomia;
use app\models\Especie;

use app\helpers\LibreriaHelper;

/**
 * ContenidoController
 *
 * Antes de ejecutar cualquier acción:
 *   1) Si el usuario está logueado con rol = 1 (SuperAdmin), pasa sin pedir API key.
 *   2) En caso contrario, si es GET y NO hay api_key, muestra un formulario HTML para ingresar la clave.
 *   3) Si el cliente envía api_key (header X-Api-Key o parámetro GET), la compara con la opción 'api_secret_token'.
 *      • Si no coincide y es GET, devuelve una página HTML “Acceso denegado” (HTTP 401).
 *      • Si no coincide y NO es GET (POST/PUT/DELETE/etc.), devuelve un JSON con código 401.
 *   4) Si la clave coincide, continúa normalmente y entrega datos JSON.
 */
class ContenidoController extends Controller
{
    /**
     * Se ejecuta antes de cualquier action*(). Aquí validamos la API key.
     */
    public function beforeAction($action)
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        // 1) Si hay usuario logueado y su rol = 1 (SuperAdmin), permitimos pasar
        if (!Yii::$app->user->isGuest) {
            $rolId = Yii::$app->user->identity->usu_rol_id ?? null;
            if ($rolId === 1) {
                return parent::beforeAction($action);
            }
        }

        // 2) Obtener la clave esperada desde la opción 'api_secret_token'
        $option = Opcion::find()
            ->select('opc_valor')
            ->where(['opc_nombre' => 'api_secret_token'])
            ->one();
        $expectedToken = $option ? $option->opc_valor : null;

        // 3) Extraer la clave provista (header X-Api-Key o GET api_key)
        $providedToken = $request->getHeaders()->get('X-Api-Key', null);
        if ($providedToken === null) {
            $providedToken = $request->get('api_key', null);
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 4) Si es GET y NO hay api_key → mostramos formulario HTML
        // ─────────────────────────────────────────────────────────────────────────
        if ($request->isGet && $providedToken === null) {
            // Fuerzo la respuesta como HTML
            $response->format = Response::FORMAT_HTML;
            $response->statusCode = 200;
            echo '<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>API Contenido – Introduce tu clave</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
    .contenedor {
      max-width: 400px;
      margin: 80px auto;
      padding: 20px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    input[type="password"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }
    button {
      background: #007bff;
      color: white;
      border: none;
      padding: 10px 15px;
      font-size: 14px;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
    }
    button:hover { background: #0056b3; }
    h2 { margin-top: 0; font-size: 18px; color: #333; }
  </style>
</head>
<body>
  <div class="contenedor">
    <h2>Necesitas una API Key</h2>
    <form method="GET" action="">
      <input
        type="password"
        name="api_key"
        placeholder="Tu clave secreta"
        required
        autofocus
      />
      <button type="submit">Enviar</button>
    </form>
  </div>
</body>
</html>';
            Yii::$app->end(); // Detenemos aquí la ejecución
        }

        // ─────────────────────────────────────────────────────────────────────────
        // 5) Si ya envió api_key pero es INVÁLIDA:
        //    • Si es GET → devolvemos HTML “Acceso denegado” (status 401)
        //    • Si NO es GET → devolvemos JSON con status 401
        // ─────────────────────────────────────────────────────────────────────────
        if ($expectedToken === null || $providedToken !== $expectedToken) {
            if ($request->isGet) {
                // Devuelvo HTML “Acceso denegado”
                $response->format = Response::FORMAT_HTML;
                $response->statusCode = 401;
                echo '<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Acceso Denegado</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; text-align: center; }
    .mensaje { display: inline-block; margin-top: 100px; padding: 20px; background: #fff; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .mensaje h1 { margin: 0 0 10px; font-size: 24px; color: #333; }
    .mensaje p { margin: 0; color: #666; }
  </style>
</head>
<body>
  <div class="mensaje">
    <h1>Acceso denegado</h1>
    <p>La clave API proporcionada no es válida.</p>
  </div>
</body>
</html>';
                Yii::$app->end();
            }

            // Si NO es GET (por ejemplo POST/AJAX), devuelvo JSON 401
            $response->format = Response::FORMAT_JSON;
            $response->statusCode = 401;
            echo json_encode([
                'success' => false,
                'error'   => 'API key inválida o ausente.'
            ], JSON_UNESCAPED_UNICODE);
            Yii::$app->end();
        }

        // 6) Si la clave coincide, dejamos que siga la acción normal
        return parent::beforeAction($action);
    }

    /**
     * Configura CORS y forzamos respuestas JSON
     * **IMPORTANTE**: NO mapeamos “text/html” a JSON. Dejar solo “application/json”.
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // 1) CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors'  => [
                'Origin'                          => ['*'],
                'Access-Control-Allow-Credentials'=> true,
                'Access-Control-Allow-Methods'    => ['GET', 'POST', 'OPTIONS', 'PUT', 'DELETE'],
                'Access-Control-Allow-Headers'    => ['*'],
                'Access-Control-Expose-Headers'   => ['*'],
            ],
        ];

        // 2) Forzar JSON al aceptar application/json
        $behaviors['contentNegotiator'] = [
            'class'   => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                // <--   ‘text/html’  **NO**   => Response::FORMAT_JSON
            ],
        ];

        return $behaviors;
    }

    // =========================================================================
    // ACCIONES “PÚBLICAS” (se ejecutan solo si la clave API es válida)
    // =========================================================================

    /**
     * GET /api/contenido
     */
    public function actionIndex(): array
    {
        $sitioOnline = strtolower(Yii::$app->params['sitio_online'] ?? 'yes');

        if ($sitioOnline !== 'yes') {
            $offline = $this->getPaginaOffline();
            $emailAdmin = Opcion::find()
                ->select('opc_valor')
                ->where(['opc_nombre' => 'email_admin'])
                ->scalar();

            return [
                'pagina_offline' => $offline,
                'email_admin'    => $emailAdmin,
            ];
        }

        return [
            'articulos'           => $this->getArticulos(),
            'paginas'             => $this->getPaginasMenu(),
            'paginas_secundarias' => $this->getPaginasSecundario(),
            'paginas_sin_menu'    => $this->getPaginasSinMenu(),
            'servicios'           => $this->getServiciosPublicados(),
            'herramientas'        => $this->getHerramientasPublicadas(),
            'habilidades'         => $this->getHabilidadesPublicadas(),
            'formacion'           => $this->getFormacion(),
            'experienciaLaboral'  => $this->getExperienciaLaboral(),
            'asuntos'             => $this->getAsuntos(),
            'proyectos'           => $this->getProyectosPublicados(),
            'clientes'            => $this->getClientesDestacados(),
            'redesSociales'       => $this->getRedesSociales(),
            'perfil'              => $this->getPerfil(),
            'media'               => $this->getMedia(),
            'opciones'            => $this->getOpciones(),
            'testimonios'         => $this->getTestimonios(),
            'taxonomias'          => $this->getTaxonomias(), 
            'especies_taxonomia'  => $this->getTaxonomiasConEspecies(),
            
        ];
    }



    /**
     * GET /api/contenido/articulos
     */
    public function actionArticulos(): array
    {
        $articulos = Articulo::find()
            ->where(['art_estado' => 'publicado'])
            ->orderBy(['art_fecha_publicacion' => SORT_DESC])
            ->limit(10)
            ->all();

        foreach ($articulos as $a) {
            $a->art_imagen = $this->resolveArchivoUrl($a->art_imagen, 'articulo', $a->art_id);
        }

        return $articulos;
    }

    public function actionArticulo(string $slug): Articulo
    {
        $model = Articulo::findOne([
            'art_slug'   => $slug,
            'art_estado' => 'publicado',
        ]);
        if (!$model) {
            throw new NotFoundHttpException("Artículo “{$slug}” no existe.");
        }
        $model->art_imagen = $this->resolveArchivoUrl($model->art_imagen, 'articulo', $model->art_id);
        return $model;
    }

    /**
     * GET /api/contenido/servicios
     */
    public function actionServicios(): array
    {
        $servicios = Servicio::find()
            ->where(['ser_publicado' => 'SI'])
            ->orderBy(['ser_titulo' => SORT_ASC])
            ->all();

        foreach ($servicios as $s) {
            $s->ser_imagen = $this->resolveArchivoUrl($s->ser_imagen, 'servicio', $s->ser_id);
        }

        return $servicios;
    }

    public function actionServicio(string $slug): Servicio
    {
        $model = Servicio::findOne([
            'ser_slug'      => $slug,
            'ser_publicado' => 'SI',
        ]);
        if (!$model) {
            throw new NotFoundHttpException("Servicio “{$slug}” no existe.");
        }
        $model->ser_imagen = $this->resolveArchivoUrl($model->ser_imagen, 'servicio', $model->ser_id);
        return $model;
    }

    /**
     * GET /api/contenido/opciones
     */
    public function actionOpciones(): array
    {
        return $this->getOpciones();
    }

    // ========================================================================
    // MÉTODOS AUXILIARES
    // ========================================================================

    private function getOpciones(): array
    {
        return Opcion::find()
            ->where(['>', 'opc_rol_id', 1])
            ->orderBy(['opc_id' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public function getPaginasMenu(): array
    {
        $paginas = Pagina::find()
            ->where(['pag_estado' => 'publicado', 'pag_mostrar_menu' => 'SI'])
            ->orderBy(['pag_posicion' => SORT_ASC])
            ->asArray()
            ->all();

        $paginas = $this->procesarPaginacion($paginas);
        return $this->agregarGalerias($paginas);
    }

    public function getPaginasSecundario(): array
    {
        $paginas = Pagina::find()
            ->where(['pag_estado' => 'publicado', 'pag_mostrar_menu_secundario' => 'SI'])
            ->orderBy(['pag_posicion' => SORT_ASC])
            ->asArray()
            ->all();

        $paginas = $this->procesarPaginacion($paginas);
        return $this->agregarGalerias($paginas);
    }

    private function procesarPaginacion(array $paginas): array
    {
        foreach ($paginas as &$p) {
            if (!empty($p['pag_contenido_programador'])) {
                $p['pag_contenido_programador'] = $this->fixContentUrls($p['pag_contenido_programador']);
            }
            if (!empty($p['pag_contenido_antes'])) {
                $p['pag_contenido_antes'] = $this->fixContentUrls($p['pag_contenido_antes']);
            }
            if (!empty($p['pag_contenido_despues'])) {
                $p['pag_contenido_despues'] = $this->fixContentUrls($p['pag_contenido_despues']);
            }
        }
        unset($p);
        return $paginas;
    }

    /**
     * Para cada página, busca su galería y construye un array
     * de URLs relativas de las imágenes.
     *
     * @param array $paginas Array de páginas (asArray)
     * @return array Mismo array de páginas con key 'imagenes_galeria'
     */
        /**
     * Para cada página, busca su galería y construye un array
     * de URLs relativas de las imágenes.
    */
    private function agregarGalerias(array $paginas): array
    {
        // Antes era '/recursos/uploads/', ahora sin slash inicial:
        $baseUrl = 'recursos/uploads/';

        foreach ($paginas as &$pagina) {
            $gal = Galerias::findOne([
                'gal_id_registro'   => $pagina['pag_id'],
                'gal_tipo_registro' => 'pagina',
                'gal_estado'        => 'publicado',
            ]);

            if ($gal) {
                $imgs = ImagenesGaleria::find()
                    ->where([
                        'img_gal_id' => $gal->gal_id,
                        'img_estado' => 'publicado',
                    ])
                    ->orderBy(['img_id' => SORT_ASC])
                    ->asArray()
                    ->all();

                $pagina['imagenes_galeria'] = array_map(function($img) use ($baseUrl) {
                    // Ahora 'recursos/uploads/imagenes_galeria/…' en lugar de '/recursos/...'
                    return ['url' => $baseUrl . $img['img_ruta']];
                }, $imgs);
            } else {
                $pagina['imagenes_galeria'] = [];
            }
        }
        unset($pagina);

        return $paginas;
    }

    /**
     * GET /api/contenido/herramientas
     */
    public function actionHerramientas(): array
    {
        return $this->getHerramientasPublicadas();
    }

    /**
     * GET /api/contenido/habilidades
     */
    public function actionHabilidades(): array
    {
        return $this->getHabilidadesPublicadas();
    }

    /**
     * GET /api/contenido/formacion
     */
    public function actionFormacion(): array
    {
        return $this->getFormacion();
    }

    /**
     * GET /api/contenido/experiencia-laboral
     */
    public function actionExperienciaLaboral(): array
    {
        $exp = Experiencia::find()
            ->where(['exp_publicada' => 'SI'])
            ->orderBy(['exp_fecha_inicio' => SORT_DESC])
            ->asArray()
            ->all();

        // Formateo de fechas igual que antes
        $fmt = Yii::$app->formatter;
        $fmt->locale     = 'es-ES';
        $fmt->dateFormat = 'LLLL yyyy';

        foreach ($exp as &$e) {
            $e['exp_fecha_inicio'] = ucfirst($fmt->asDate($e['exp_fecha_inicio']));
            $e['exp_fecha_fin']    = empty($e['exp_fecha_fin'])
            ? 'Actualidad'
            : $fmt->asDate($e['exp_fecha_fin']);
        }
        unset($e);

        return $exp;
    }

    /**
     * GET /api/contenido/asuntos
     */
    public function actionAsuntos(): array
    {
        return $this->getAsuntos();
    }

    /**
     * GET /api/contenido/proyectos
     */
    public function actionProyectos(): array
    {
        return $this->getProyectosPublicados();
    }

    /**
     * GET /api/contenido/clientes
     */
    public function actionClientes(): array
    {
        return $this->getClientesDestacados();
    }

    /**
     * GET /api/contenido/redes
     */
    public function actionRedes(): array
    {
        return $this->getRedesSociales();
    }

    // =========================================================================
    // Resto de métodos auxiliares (idénticos a los anteriores)
    // =========================================================================

    public function getArticulos(): array
    {
        $articulos = Articulo::find()
            ->where(['art_estado' => 'publicado'])
            ->orderBy(['art_fecha_publicacion' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();

        foreach ($articulos as &$a) {
            $a['art_imagen']    = $this->resolveArchivoUrl($a['art_imagen'], 'articulo', $a['art_id']);
            $a['art_contenido'] = $this->fixContentUrls($a['art_contenido']);
        }
        unset($a);

        return $articulos;
    }

    public function getServiciosPublicados(): array
    {
        $servicios = Servicio::find()
            ->where(['ser_publicado'=>'SI'])
            ->orderBy(['ser_titulo'=>SORT_ASC])
            ->all();

        foreach ($servicios as $s) {
            $s->ser_imagen = $this->resolveArchivoUrl($s->ser_imagen, 'servicio', $s->ser_id);
        }

        return $servicios;
    }

    public function getHerramientasPublicadas(): array
    {
        return Herramienta::find()
            ->where(['her_publicada'=>'SI'])
            ->orderBy(['her_nombre'=>SORT_ASC])
            ->all();
    }

    public function getHabilidadesPublicadas(): array
    {
        return Habilidad::find()
            ->where(['hab_publicada'=>'SI'])
            ->orderBy(['hab_nombre'=>SORT_ASC])
            ->all();
    }

    public function getFormacion(): array
    {
        $todos = Formacion::find()
            ->orderBy([
                new Expression('ISNULL(for_fecha_fin), for_fecha_fin DESC'),
                'for_fecha_inicio' => SORT_DESC,
            ])
            ->all();

        $cursos = $formaciones = $certificaciones = [];
        foreach ($todos as $item) {
            switch ($item->for_categoria) {
                case 'Curso':         $cursos[]          = $item; break;
                case 'Certificación': $certificaciones[] = $item; break;
                default:              $formaciones[]     = $item; break;
            }
        }

        $now = new \DateTime();
        $fmt = function($o) use ($now) {
            $ini = new \DateTime($o->for_fecha_inicio);
            $o->for_fecha_inicio = $ini->format('m-Y');
            if ($o->for_fecha_fin) {
                $fin = new \DateTime($o->for_fecha_fin);
                $o->for_fecha_fin = $fin > $now
                    ? $fin->format('Y') . ' esperado'
                    : $fin->format('m-Y');
            } else {
                $o->for_fecha_fin = $o->for_fecha_inicio;
            }
        };
        array_walk($cursos, $fmt);
        array_walk($formaciones, $fmt);
        array_walk($certificaciones, $fmt);

        return [
            'cursos'          => $cursos,
            'formaciones'     => $formaciones,
            'certificaciones' => $certificaciones,
        ];
    }

    public function getExperienciaLaboral(): array
    {
        return $this->actionExperienciaLaboral();
    }

    public function getAsuntos(): array
    {
        return Asunto::find()
            ->where(['asu_publicado'=>'SI'])
            ->orderBy(['asu_nombre'=>SORT_ASC])
            ->all();
    }

    public function getProyectosPublicados(): array
    {
        $proyectos = Proyecto::find()
            ->where(['pro_estado' => 'PUBLICADO'])
            ->orderBy(['pro_fecha_inicio' => SORT_DESC])
            ->all();

        foreach ($proyectos as $p) {

            // — Resolver URL de imagen (BD → carpeta entidad → fallback) —
            $p->pro_imagen = $this->resolveArchivoUrl(
                $p->pro_imagen,
                'proyecto',
                $p->pro_id
            );
        }

        return $proyectos;
    }

    public function getClientesDestacados(): array
    {
        $clientes = Cliente::find()
            ->where(['cli_estado' => 'SI'])
            ->all();

        foreach ($clientes as $c) {
            $c->cli_logo = $this->resolveArchivoUrl($c->cli_logo, 'cliente', $c->cli_id);
        }

        return $clientes;
    }

    public function getRedesSociales(): array
    {
        return Redes::find()
            ->where(['red_publicada'=>'SI'])
            ->orderBy(['red_nombre'=>SORT_ASC])
            ->all();
    }

    public function getPerfil(): array
    {
        $perfil = Perfil::find()
            ->where(['per_id' => 1])
            ->asArray()
            ->one() ?? [];

        if (!empty($perfil['per_imagen'])) {
            $perfil['per_imagen'] = $this->resolveArchivoUrl($perfil['per_imagen'], 'perfil', $perfil['per_id']);
        } else {
            // fallback explícito si per_imagen está vacío
            $perfil['per_imagen'] = $this->resolveArchivoUrl(null, 'perfil', $perfil['per_id']);
        }

        return $perfil;
    }

    public function getMedia(): object
    {
        $items = Media::find()
            ->orderBy(['med_id' => SORT_ASC])
            ->asArray()
            ->all();

        $baseRecursos = rtrim($this->getBaseRecursos(), '/') . '/uploads';

        $result = [];
        foreach ($items as $m) {
            $nombre = $m['med_nombre'];
            $m['url'] = $baseRecursos . '/' . ltrim($m['med_ruta'], '/');
            $m['filename'] = basename($m['med_ruta']);
            $result[$nombre] = $m;
        }
        // Convertir el array asociativo en objeto recursivamente
        return json_decode(json_encode($result), false);
    }

    public function getBaseRecursos(): string
    {
        $hostInfo = \Yii::$app->request->hostInfo;     // ej. http://localhost:8888
        $baseUrl  = \Yii::$app->request->baseUrl;      // ej. /CMS_V4/panel-admin/web

        // Quita el segmento "/panel-admin/web" para quedarte en "/CMS_V4"
        $siteRoot = preg_replace('#/panel-admin/web$#', '', $baseUrl);

        return rtrim($hostInfo . $siteRoot, '/') . '/recursos';
    }

    public function resolveArchivoUrl(?string $rutaDb, string $entidad, $id = null): string
    {
        $uploadsUrl  = $this->getBaseRecursos() . '/uploads';
        $uploadsPath = dirname(dirname(Yii::getAlias('@webroot'))) . '/recursos/uploads';

        $exts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        $personal = null;

        if ($id) {
            foreach ($exts as $ext) {
                $localPath = "$uploadsPath/$entidad/$id.$ext";
                if (is_file($localPath)) {
                    $personal = "$uploadsUrl/$entidad/$id.$ext";
                    break;
                }
            }
        }

        $dbUrl = $rutaDb ? "$uploadsUrl/$rutaDb" : null;

        $sinEntidad = null;
        foreach ($exts as $ext) {
            $localPath = "$uploadsPath/default/entidad/$entidad/sin_imagen.$ext";
            if (is_file($localPath)) {
                $sinEntidad = "$uploadsUrl/default/entidad/$entidad/sin_imagen.$ext";
                break;
            }
        }

        $fallback = "$uploadsUrl/default/no_disponible.png";

        return $personal ?: ($dbUrl ?: ($sinEntidad ?: $fallback));
    }

    private function getPaginaOffline(): ?array
    {
        $model = Pagina::find()
            ->where([
                'pag_slug'   => 'offline',
                'pag_estado' => 'publicado',
            ])
            ->asArray()
            ->one();

        if (!$model) {
            return null;
        }

        $arr = $this->agregarGalerias([$model]);
        return $arr[0];
    }
    /**
     * GET /api/contenido/testimonios
     * Devuelve todos los testimonios publicados, con imagenes en ruta relativa
     */
    public function actionTestimonios(): array
    {
        $testimonios = Testimonio::find()
            ->where(['tes_estado' => 'publicado'])
            ->orderBy(['tes_orden' => SORT_ASC])
            ->asArray()
            ->all();

        // Ruta base relativa a la carpeta de uploads
        $baseUploads = '../recursos/uploads/';

        foreach ($testimonios as &$t) {
            // si tes_imagen = 'testimonios/1.jpg', lo prefijamos
            if (!empty($t['tes_imagen'])) {
                $t['tes_imagen'] = $baseUploads . $t['tes_imagen'];
            } else {
                // fallback genérico
                $t['tes_imagen'] = $baseUploads . 'default/no_disponible.png';
            }
        }
        unset($t);

        return $testimonios;
    }

    /**
     * GET /api/contenido/testimonio/{id}
     * Devuelve un testimonio por ID, con imagen en ruta relativa
     */
    public function actionTestimonio(int $id): Testimonio
    {
        $model = Testimonio::findOne([
            'tes_id'     => $id,
            'tes_estado' => 'publicado',
        ]);
        if (!$model) {
            throw new NotFoundHttpException("Testimonio con ID {$id} no existe o no está publicado.");
        }

        // Prefijar ruta relativa
        $baseUploads = '/recursos/uploads/';
        $model->tes_imagen = $model->tes_imagen
            ? $baseUploads . $model->tes_imagen
            : $baseUploads . 'default/no_disponible.png';

        return $model;
    }

    /**
     * Auxiliar para incluir testimonios en actionIndex()
     * Devuelve array con rutas relativas
     */
    private function getTestimonios(): array
    {
        $testimonios = Testimonio::find()
            ->where(['tes_estado' => 'publicado'])
            ->orderBy(['tes_orden' => SORT_ASC])
            ->asArray()
            ->all();

        return array_map(function($t) {
            $t['tes_imagen'] = $this->resolveArchivoUrl(
                $t['tes_imagen'] ?? null,
                'testimonios',
                $t['tes_id'] ?? null
            );
            return $t;
        }, $testimonios);
    }


    public function actionTaxonomia($slug) {
        // Buscar la taxonomía por slug
        $taxonomia = Taxonomia::find()
            ->where(['tax_slug' => $slug, 'tax_estado' => 'activo'])
            ->one();

        if (!$taxonomia) {
            throw new NotFoundHttpException("Taxonomía no encontrada.");
        }

        return $this->render('taxonomia_detalle', [
            'taxonomia' => $taxonomia,
        ]);
    }

    /**
     * Devuelve todas las taxonomías activas disponibles para clasificación.
     * Incluye nombre en español, slug y la imagen resuelta desde /recursos/uploads/taxonomia/.
     */
    private function getTaxonomias(): array
    {
        // 1️⃣ Obtener todas las taxonomías activas
        $taxonomias = \app\models\Taxonomia::find()
            ->where(['tax_estado' => 'activo'])
            ->orderBy(['tax_nombre' => SORT_ASC])
            ->all(); // Ojo: ya no usamos asArray() para usar objetos

        // 2️⃣ Aplicar resolveArchivoUrl()
        foreach ($taxonomias as $t) {
            $t->tax_imagen = $this->resolveArchivoUrl(
                $t->tax_imagen,
                'taxonomia',
                $t->tax_id
            );
        }

        return $taxonomias;
    }

    /**
     * Devuelve todas las taxonomías activas con sus especies asociadas.
     * Este método puede ser usado por el frontend o embebido en actionIndex().
     */
        
    private function getTaxonomiasConEspecies(): array
    {
        $taxonomias = Taxonomia::find()
            ->where(['tax_estado' => 'activo'])
            ->orderBy(['tax_nombre' => SORT_ASC])
            ->all(); // objetos

        $resultado = [];

        foreach ($taxonomias as $tax) {
            $taxArray = $tax->toArray();

            $taxArray['tax_imagen'] = $this->resolveArchivoUrl(
                $tax->tax_imagen,
                'taxonomia',
                $tax->tax_id
            );

            $especies = Especie::find()
                ->where(['esp_tax_id' => $tax->tax_id, 'esp_estado' => 'activo'])
                ->orderBy(['esp_nombre_cientifico' => SORT_ASC])
                ->asArray()
                ->all();

            foreach ($especies as &$esp) {
                $esp['esp_imagen'] = $this->resolveArchivoUrl(
                    $esp['esp_imagen'] ?? null,
                    'especie',
                    $esp['esp_id'] ?? null
                );
            }

            $taxArray['especies'] = $especies;

            $resultado[] = $taxArray;
        }

        return $resultado;
    }

    /**
     * GET /api/contenido/taxonomias-con-especies
     * Devuelve todas las taxonomías activas junto a sus especies publicadas.
     */
    public function actionTaxonomiasConEspecies(): array
    {
        return $this->getTaxonomiasConEspecies();
    }



    private function fixContentUrls(?string $contenido): string
    {
        if (empty($contenido)) {
            return '';
        }
        $base = $this->getBaseRecursos();
        // Esto reemplaza TODAS las rutas (incluso imágenes, PDF, etc.)
        return preg_replace_callback(
            '#((\.\./)+|)recursos/([^\s"\']+)#i',
            function($matches) use ($base) {
                // $matches[3] es la parte después de recursos/
                return $base . '/' . $matches[3];
            },
            $contenido
        );
    }


    /**
     * GET /api/contenido/especie/{slug}
     * Devuelve una especie activa según su slug, con su taxonomía asociada.
     */
    public function actionEspecie(string $slug): array
    {
        // Buscar la especie por slug
        $especie = Especie::find()
            ->where(['esp_slug' => $slug, 'esp_estado' => 'activo'])
            ->one();

        if (!$especie) {
            throw new NotFoundHttpException("Especie “{$slug}” no encontrada o inactiva.");
        }

        // Resolver imagen principal
        $especie->esp_imagen = $this->resolveArchivoUrl(
            $especie->esp_imagen,
            'especie',
            $especie->esp_id
        );

        // Obtener taxonomía asociada
        $taxonomia = Taxonomia::find()
            ->where(['tax_id' => $especie->esp_tax_id])
            ->one();

        if ($taxonomia) {
            $taxonomia->tax_imagen = $this->resolveArchivoUrl(
                $taxonomia->tax_imagen,
                'taxonomia',
                $taxonomia->tax_id
            );
        }

        // Retornar estructura completa
        return [
            'especie'   => $especie,
            'taxonomia' => $taxonomia,
        ];
    }

    /**
     * Método auxiliar: obtener especie con su taxonomía (usado internamente si se necesita).
     */
    private function getEspecie(string $slug): ?array
    {
        $especie = Especie::find()
            ->where(['esp_slug' => $slug, 'esp_estado' => 'activo'])
            ->one();

        if (!$especie) {
            return null;
        }

        $especie->esp_imagen = $this->resolveArchivoUrl(
            $especie->esp_imagen,
            'especie',
            $especie->esp_id
        );

        $taxonomia = Taxonomia::find()
            ->where(['tax_id' => $especie->esp_tax_id])
            ->one();

        if ($taxonomia) {
            $taxonomia->tax_imagen = $this->resolveArchivoUrl(
                $taxonomia->tax_imagen,
                'taxonomia',
                $taxonomia->tax_id
            );
        }

        return [
            'especie'   => $especie,
            'taxonomia' => $taxonomia,
        ];
    }


    /**
     * GET /api/contenido/slug-especie?nombre=Canis_latrans
     * Devuelve slug, taxonomía y id de la especie (tolerante a mayúsculas y guiones bajos)
     */
    public function actionSlugEspecie(string $nombre): array
    {
        $slug = LibreriaHelper::generateSlug($nombre);

        $especie = \app\models\Especie::find()
            ->where(['esp_slug' => $slug, 'esp_estado' => 'activo'])
            ->one();

        if (!$especie) {
            return [
                'success' => false,
                'message' => "No se encontró una especie con el nombre científico '{$nombre}'"
            ];
        }

        $taxonomia = \app\models\Taxonomia::find()
            ->where(['tax_id' => $especie->esp_tax_id])
            ->one();

        return [
            'success'   => true,
            'id'        => (int)$especie->esp_id,
            'slug'      => $especie->esp_slug,
            'tax'       => $taxonomia->tax_nombre ?? null,
            'taxSlug'   => $taxonomia->tax_slug ?? null,
        ];
    }

        /**
     * Devuelve las páginas publicadas que no pertenecen a ningún menú.
     * (Ejemplo: login, registro, error404, políticas, etc.)
     */
    public function getPaginasSinMenu(): array
    {
        $paginas = Pagina::find()
            ->where(['pag_estado' => 'publicado'])
            ->andWhere(['pag_mostrar_menu' => 'NO'])
            ->andWhere(['pag_mostrar_menu_secundario' => 'NO'])
            ->orderBy(['pag_titulo' => SORT_ASC])
            ->asArray()
            ->all();

        $paginas = $this->procesarPaginacion($paginas);
        return $this->agregarGalerias($paginas);
    }
        
}