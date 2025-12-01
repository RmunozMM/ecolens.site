<?php
namespace app\services;

use Yii;
use yii\httpclient\Client;
use yii\web\ServerErrorHttpException;

class ContenidoService
{
    const CACHE_KEY = 'api_contenido_full';
    const CACHE_TTL = 3600;

    /**
     * @return \stdClass
     * @throws ServerErrorHttpException
     */
    public static function getAll(): \stdClass
    {
        $cache = Yii::$app->cache;
        if (($obj = $cache->get(self::CACHE_KEY)) instanceof \stdClass) {
            return $obj;
        }

        // Llamada a la API (baseUrl ya incluye '/api/contenido')
        $client   = new Client([
            'baseUrl' => Yii::$app->params['apiUrl'],
        ]);
        $response = $client->get('', [
            'api_key' => Yii::$app->params['apiKey'],
        ])->send();

        // Si responde 404, devuelvo stdClass vacío sin excepción
        if ($response->statusCode === 404) {
            Yii::warning("API contenido no encontrada (404)");
            $empty = new \stdClass();
            $cache->set(self::CACHE_KEY, $empty, self::CACHE_TTL);
            return $empty;
        }
        if (!$response->isOk) {
            throw new ServerErrorHttpException("Error consumiendo API ({$response->statusCode})");
        }

        // Tomo el array mixto
        $data = $response->data;

        // ─── Parche: mapeo explícito de opciones a clave=>valor ─────────────────
        if (isset($data['opciones']) && is_array($data['opciones'])) {
            $map = [];
            foreach ($data['opciones'] as $opt) {
                if (isset($opt['opc_nombre'], $opt['opc_valor'])) {
                    $map[ $opt['opc_nombre'] ] = $opt['opc_valor'];
                }
            }
            // Reemplazo el array numerado por un array asociativo
            $data['opciones'] = $map;
        }
        // ─────────────────────────────────────────────────────────────────────────

        // Función recursiva para convertir **todo** array a stdClass
        $toObject = function($v) use (&$toObject) {
            if (is_array($v)) {
                $o = new \stdClass();
                foreach ($v as $k => $x) {
                    $o->{$k} = $toObject($x);
                }
                return $o;
            }
            if (is_object($v)) {
                foreach ($v as $k => $x) {
                    $v->{$k} = $toObject($x);
                }
                return $v;
            }
            return $v;
        };

        /** @var \stdClass $obj */
        $obj = $toObject($data);

        // Aseguro que exista obj->opciones como stdClass
        if (!isset($obj->opciones) || !is_object($obj->opciones)) {
            $obj->opciones = new \stdClass();
        }

        // Fallbacks mínimos para las claves que usa tu layout
        foreach ([
            'idioma_sitio'     => 'es-ES',
            'site_name'        => Yii::$app->name,
            'meta_author'      => Yii::$app->params['meta_author'] ?? Yii::$app->name,
            'meta_description' => '',
            'viewport'         => 'width=device-width, initial-scale=1',
            'cliente_nombre'   => Yii::$app->name,
        ] as $key => $default) {
            if (!property_exists($obj->opciones, $key) || $obj->opciones->{$key} === null) {
                $obj->opciones->{$key} = $default;
            }
        }

        // Cacheo y retorno
        $cache->set(self::CACHE_KEY, $obj, self::CACHE_TTL);
        return $obj;
    }
}