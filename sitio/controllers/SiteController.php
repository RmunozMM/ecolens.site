<?php
namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;
use app\services\ContenidoService;
use yii\helpers\Inflector;
use yii\helpers\Url;

class SiteController extends BaseController
{
    public function actionPagina(string $slug): string
    {
        /** @var \stdClass $contenido */
        $contenido = $this->view->params['contenido'];

        // 🟡 1️⃣ CONTROL DE MODO OFFLINE (devuelto por la API)
        if (isset($contenido->pagina_offline) && is_object($contenido->pagina_offline)) {
            $po = $contenido->pagina_offline;

            $htmlOffline = $po->pag_contenido_programador
                ?? (($po->pag_contenido_antes ?? '') . ($po->pag_contenido_despues ?? ''));

            // Si viene HTML completo, lo devolvemos tal cual (sin layout)
            Yii::$app->response->format = Response::FORMAT_HTML;
            return $htmlOffline;
        }

        // 🟢 2️⃣ FLUJO NORMAL: buscar y renderizar páginas según slug
        $allPages = array_merge(
            (array)($contenido->paginas ?? []),
            (array)($contenido->paginas_secundarias ?? []),
            (array)($contenido->paginas_sin_menu ?? []) // 🆕 añadidas
        );

        foreach ($allPages as $p) {
            if (($p->pag_slug ?? null) === $slug) {
                $modo            = strtolower(trim($p->pag_modo_contenido ?? ''));
                $fuente          = strtolower(trim($p->pag_fuente_contenido ?? ''));
                $imagenesGaleria = $p->imagenes_galeria ?? [];

                // 🔹 Autoadministrables (editor visual)
                if (in_array($modo, ['auto_administrable', 'autoadministrable'], true)) {
                    return $this->render('pagina', [
                        'pagina'   => $p,
                        'imagenes' => $imagenesGaleria,
                    ]);
                }

                // 🔹 Administradas por programador
                if ($modo === 'administrado_programador') {
                    if ($fuente === 'usar_plantilla' && !empty($p->pag_plantilla)) {
                        $vista = preg_replace('/\.php$/', '', $p->pag_plantilla);
                        return $this->render($vista, [
                            'pagina'   => $p,
                            'imagenes' => $imagenesGaleria,
                        ]);
                    }

                    if ($fuente === 'editar_directo') {
                        return $this->render('pagina', [
                            'pagina'   => $p,
                            'imagenes' => $imagenesGaleria,
                        ]);
                    }
                }

                throw new NotFoundHttpException("No se pudo determinar cómo renderizar esta página (modo={$modo})");
            }
        }

        throw new NotFoundHttpException("Página con slug '{$slug}' no encontrada.");
    }

    public function actionDebug()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ContenidoService::getAll();
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception) {
            if ($exception->statusCode === 404) {
                return $this->render('error404', ['exception' => $exception]);
            }
            return $this->render('error', ['exception' => $exception]);
        }
        return '';
    }

    public function actionServicios($slug = null)
    {
        $contenido = $this->view->params['contenido'];
        $servicios = $contenido->servicios ?? [];

        if ($slug) {
            $servicio = null;
            foreach ($servicios as $s) {
                if (($s->ser_slug ?? $s['ser_slug'] ?? null) === $slug) {
                    $servicio = $s;
                    break;
                }
            }
            if (!$servicio) {
                throw new NotFoundHttpException('Servicio no encontrado');
            }
            return $this->render('view_servicio_detalle', [
                'servicio' => $servicio,
            ]);
        }

        $pagina = null;
        foreach ((array)($contenido->paginas ?? []) as $p) {
            if ($p->pag_slug === 'servicios') {
                $pagina = $p;
                break;
            }
        }

        return $this->render('view_servicios', [
            'servicios' => $servicios,
            'pagina'    => $pagina,
        ]);
    }

    public function actionArticulos($slug = null)
    {
        $contenido = $this->view->params['contenido'];
        $articulos = $contenido->articulos ?? [];

        if ($slug) {
            $articulo = null;
            foreach ($articulos as $a) {
                if (($a->art_slug ?? $a['art_slug'] ?? null) === $slug) {
                    $articulo = $a;
                    break;
                }
            }
            if (!$articulo) {
                throw new NotFoundHttpException('Artículo no encontrado');
            }
            return $this->render('view_articulo_detalle', [
                'articulo' => $articulo,
            ]);
        }

        return $this->render('view_blog', [
            'articulos' => $articulos,
        ]);
    }

    public function actionProyectos($slug = null)
    {
        $contenido = $this->view->params['contenido'];
        $proyectos = $contenido->proyectos ?? [];

        if ($slug) {
            $proyecto = null;
            foreach ($proyectos as $p) {
                if (($p->pro_slug ?? null) === $slug) {
                    $proyecto = $p;
                    break;
                }
            }
            if (!$proyecto) {
                throw new NotFoundHttpException('Proyecto no encontrado');
            }
            return $this->render('view_proyecto_detalle', [
                'proyecto' => $proyecto,
            ]);
        }

        $pagina = null;
        foreach ((array)($contenido->paginas ?? []) as $pg) {
            if ($pg->pag_slug === 'proyectos') {
                $pagina = $pg;
                break;
            }
        }

        return $this->render('view_portafolio', [
            'proyectos' => $proyectos,
            'pagina'    => $pagina,
        ]);
    }

    public function actionTaxonomias()
    {
        $contenido   = Yii::$app->view->params['contenido'] ?? (object)[];
        $taxonomias  = $contenido->taxonomias ?? [];

        return $this->render('taxonomias', [
            'taxonomias' => $taxonomias,
        ]);
    }

    public function actionTaxonomia($slug)
    {
        $contenido  = Yii::$app->view->params['contenido'] ?? (object)[];
        $taxonomias = $contenido->especies_taxonomia ?? [];

        foreach ($taxonomias as $tax) {
            if (($tax->tax_slug ?? null) === $slug) {
                $especies = $tax->especies ?? [];

                return $this->render('taxonomia_detalle', [
                    'taxonomia' => $tax,
                    'especies'  => $especies,
                ]);
            }
        }

        throw new NotFoundHttpException("Taxonomía no encontrada");
    }

    public function actionEspecie($slugTax, $slugEspecie)
    {
        $contenido    = Yii::$app->view->params['contenido'] ?? (object)[];
        $taxonomias   = $contenido->especies_taxonomia ?? [];

        foreach ($taxonomias as $tax) {
            if (($tax->tax_slug ?? null) === $slugTax) {
                foreach ($tax->especies ?? [] as $esp) {
                    $slugCientifico = Inflector::slug($esp->esp_nombre_cientifico);
                    if ($slugCientifico === $slugEspecie) {
                        return $this->render('especie_detalle', [
                            'taxonomia' => $tax,
                            'especie'   => $esp,
                        ]);
                    }
                }
            }
        }

        throw new NotFoundHttpException('Especie no encontrada en esta taxonomía.');
    }

    
    public function actionLogout()
{
    // 1) Cargar entorno (para saber paths y host)
    $envPath = Yii::getAlias('@app') . '/config/ecolens_env.php';
    $env     = file_exists($envPath) ? require $envPath : [];
    $siteBase = $env['SITE_BASE'] ?? 'https://ecolens.site/sitio/web';
    $apiBase  = $env['API_BASE']  ?? 'https://ecolens.site/panel-admin/web';

    // 2) Limpiar sesión del SITIO
    $s = Yii::$app->session;
    $s->remove('observador_id');
    $s->remove('observador_nombre');
    $s->regenerateID(true);

    // 3) Borrar cookies del PANEL que mantienen viva la sesión de whoami
    //    Probamos varios paths por si el panel cambió el path al setear la cookie.
    $host = parse_url($apiBase, PHP_URL_HOST) ?: 'ecolens.site';
    $paths = ['/', '/panel-admin', '/panel-admin/web'];

    // helper para expirar cookies (HttpOnly no impide borrarlas desde PHP)
    $expireCookie = function(string $name) use ($host, $paths) {
        foreach ($paths as $p) {
            // con dominio explícito
            setcookie($name, '', time() - 3600, $p, $host, true, true);
            // y sin dominio (por si el set original no lo traía)
            setcookie($name, '', time() - 3600, $p, '', true, true);
        }
    };

    // Claves típicas de Yii
    $expireCookie('_identity');
    $expireCookie('PHPSESSID');
    $expireCookie('_csrf');  // por si la API lo dejó separado

    // 4) Responder: si viene por fetch POST devolvemos JSON; si no, redirigimos
    if (Yii::$app->request->isAjax || Yii::$app->request->isPost) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['ok' => true];
    }

    return $this->redirect($siteBase . '/inicio');
}


public function actionLimpiarCache()
{
    try {
        // Limpiar cache app actual
        if (Yii::$app->cache) {
            Yii::$app->cache->flush();
        }

        // Limpiar directorios de cache
        $paths = [
            Yii::getAlias('@app/runtime/cache'),
            dirname(Yii::getAlias('@app')) . '/sitio/runtime/cache',
            dirname(Yii::getAlias('@app')) . '/panel-admin/runtime/cache',
        ];

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $this->borrarDirectorio($path);
            }
        }

        Yii::$app->session->setFlash('success', 'Caché del sistema limpiada correctamente.');
    } catch (\Throwable $e) {
        Yii::$app->session->setFlash('error', 'Error al limpiar la caché: ' . $e->getMessage());
    }

    return $this->redirect(['site/index']);
}

private function borrarDirectorio($dir)
{
    if (!is_dir($dir)) return;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = "$dir/$file";
        if (is_dir($path)) $this->borrarDirectorio($path);
        else @unlink($path);
    }
    @rmdir($dir);
}

    
}