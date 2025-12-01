<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

/**
 * ApiExplorerController
 *
 * Muestra un listado de los controladores en modules/api/controllers y:
 *  - Para ContenidoController: solo expone GET /api/contenido (actionIndex).
 *  - Para ContactoController: solo expone POST /api/contacto (actionContacto).
 *  - Para los demás, usa reflection para listar todos los actionXxx públicos y genera rutas GET por convención.
 */
class ApiExplorerController extends Controller
{
    /**
     * Renderiza la vista index y le pasa el array de controladores.
     */
    public function actionIndex()
    {
        $apiControllers = $this->listarApiControllers();
        return $this->render('index', [
            'apiControllers' => $apiControllers,
        ]);
    }

    /**
     * Recorre @app/modules/api/controllers y arma el listado:
     *  - ContenidoController → GET /api/contenido
     *  - ContactoController  → POST /api/contacto
     *  - Resto → reflection sobre sus actionXxx.
     *
     * @return array
     */
    private function listarApiControllers()
    {
        $controllersPath = Yii::getAlias('@app/modules/api/controllers');
        $lista = [];

        foreach (glob($controllersPath . '/*Controller.php') as $file) {
            $basename     = basename($file, '.php'); 
            $className    = $basename; // Ej: "ContactoController"
            $controllerId = strtolower(str_replace('Controller', '', $basename));
            // Ej: "contacto", "contenido", "articulo", etc.

            // 1) Caso especial: ContenidoController → GET /api/contenido
            if ($className === 'ContenidoController') {
                $lista[] = [
                    'name'     => $controllerId,
                    'acciones' => [
                        [
                            'method'      => 'GET',
                            'url'         => '/api/' . $controllerId,
                            'description' => 'actionIndex – Devuelve todo el contenido',
                        ],
                    ],
                ];
                continue;
            }

            // 2) Caso especial: ContactoController → POST /api/contacto
            if ($className === 'ContactoController') {
                $lista[] = [
                    'name'     => $controllerId,
                    'acciones' => [
                        [
                            'method'      => 'POST',
                            'url'         => '/api/' . $controllerId,
                            'description' => 'actionContacto (envío de formulario de contacto)',
                        ],
                    ],
                ];
                continue;
            }

            // 3) Para el resto de controladores: reflection sobre actionXxx
            $fullClass = "app\\modules\\api\\controllers\\{$className}";

            if (!class_exists($fullClass)) {
                /** @noinspection PhpIncludeInspection */
                require_once($file);
            }
            if (!class_exists($fullClass)) {
                continue;
            }

            $refClass = new \ReflectionClass($fullClass);
            $acciones = [];

            foreach ($refClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                // Solo los métodos definidos en esta clase (no heredados)
                if ($method->class !== $fullClass) {
                    continue;
                }
                $methodName = $method->getName();

                // Deben empezar con "action"
                if (strpos($methodName, 'action') !== 0) {
                    continue;
                }

                // Suffix después de "action": e.g. "Index", "View", "FooBar"
                $suffix = substr($methodName, 6);
                if (empty($suffix)) {
                    continue;
                }

                // CamelCase → kebab-case
                $kebab = ltrim(
                    strtolower(
                        preg_replace('/([a-z])([A-Z])/', '$1-$2', $suffix)
                    ),
                    '-'
                );

                // Verificar si el método espera parámetros (tomamos el primero)
                $params    = $method->getParameters();
                $paramName = null;
                if (!empty($params)) {
                    $paramName = $params[0]->getName(); // "id", "slug", etc.
                }

                // URL base: "/api/{controllerId}"
                $url = "/api/{$controllerId}";

                // Si no es "index", agregamos "/{kebab}"
                if ($kebab !== 'index') {
                    $url .= "/{$kebab}";
                }
                // Si hay parámetro, agregamos "/<param>"
                if ($paramName !== null) {
                    $url .= "/<{$paramName}>";
                }

                // Descripción: nombre del método + parámetro si existe
                $descripcion = $methodName;
                if ($paramName !== null) {
                    $descripcion .= " (params: {$paramName})";
                }

                // Por convención asumimos GET para estos actionXxx
                $httpMethod = 'GET';

                $acciones[] = [
                    'method'      => $httpMethod,
                    'url'         => $url,
                    'description' => $descripcion,
                ];
            }

            if (!empty($acciones)) {
                $lista[] = [
                    'name'     => $controllerId,
                    'acciones' => $acciones,
                ];
            }
        }

        return $lista;
    }
}