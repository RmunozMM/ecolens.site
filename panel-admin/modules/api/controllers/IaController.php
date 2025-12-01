<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;

class IaController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $b = parent::behaviors();

        // CORS abierto (para frontend y localhost)
        $b['corsFilter'] = [
            'class' => Cors::class,
            'cors'  => [
                'Origin' => ['*'],
                'Access-Control-Allow-Methods' => ['POST', 'OPTIONS'],
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
     * Acción principal: envía la imagen a FastAPI y devuelve la predicción.
     */
    public function actionPredict()
    {
        define('ECO_ENV_INCLUDED', true);
        $envPath = Yii::getAlias('@app') . '/config/ecolens_env.php';
        $env = is_file($envPath) ? require $envPath : [];

        // --- Detectar entorno actual ---
        $hostname = $_SERVER['SERVER_NAME'] ?? gethostname();
        $isLocal = in_array($hostname, ['localhost', '127.0.0.1'], true);

        // --- URL de predicción ---
        if ($isLocal) {
            $predictUrl = 'http://127.0.0.1:8001/predict';
        } else {
            $predictUrl = 'http://64.176.10.15:9000/predict';
        }
        if (!empty($env['endpoints']['predict'])) {
            $predictUrl = $env['endpoints']['predict'];
        }

        // === DEPURACIÓN TEMPORAL (runtime/ios_upload_debug.log) ===
        $logFile = Yii::getAlias('@runtime') . '/ios_upload_debug.log';
        file_put_contents($logFile, "==== " . date('Y-m-d H:i:s') . " ====\n", FILE_APPEND);
        file_put_contents($logFile, print_r($_FILES, true) . "\n", FILE_APPEND);
        // === FIN DEBUG ===

        // --- Validar archivo recibido ---
        if (empty($_FILES['image'])) {
            return ['error' => 'No se recibió la imagen (form-data vacío o incorrecto).'];
        }

        $file = $_FILES['image'];
        $tmpPath = $file['tmp_name'] ?? null;

        // Fallback: a veces PHP no genera tmp_name si el MIME es raro (HEIC, AVIF)
        if (!$tmpPath || !is_file($tmpPath)) {
            $rawInput = file_get_contents('php://input');
            if ($rawInput && strlen($rawInput) > 50) {
                $tmpPath = tempnam(sys_get_temp_dir(), 'ios_');
                file_put_contents($tmpPath, $rawInput);
            }
        }

        if (!$tmpPath || !is_file($tmpPath)) {
            file_put_contents($logFile, "❌ Archivo temporal inválido.\n", FILE_APPEND);
            return ['error' => 'Archivo temporal no válido (no se pudo escribir en el directorio temporal).'];
        }

        // --- Normalizar extensión / MIME ---
        $mime = @mime_content_type($tmpPath) ?: ($file['type'] ?? 'application/octet-stream');
        $nombreOriginal = $file['name'] ?? 'archivo';
        $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

        // Si viene de iPhone y el MIME no es reconocible
        if (in_array($mime, ['application/octet-stream', 'image/heic', 'image/heif'], true)) {
            if ($ext === '' || !preg_match('/^[a-z0-9]{3,5}$/', $ext)) {
                $ext = 'heic';
            }
        }

        // --- Conversión automática HEIC→JPG (solo si Imagick disponible) ---
        if (in_array($ext, ['heic', 'heif', 'avif', 'jxl'], true) && class_exists('\\Imagick')) {
            try {
                $im = new \Imagick($tmpPath);
                $im->setImageFormat('jpg');
                $nuevoTmp = sys_get_temp_dir() . '/' . uniqid('conv_', true) . '.jpg';
                $im->writeImage($nuevoTmp);
                $tmpPath = $nuevoTmp;
                $mime = 'image/jpeg';
                $ext = 'jpg';
                file_put_contents($logFile, "✅ Convertido a JPG mediante Imagick.\n", FILE_APPEND);
            } catch (\Throwable $e) {
                file_put_contents($logFile, "⚠️ Error conversión: " . $e->getMessage() . "\n", FILE_APPEND);
            }
        }

        // --- Preparar petición a FastAPI ---
        $cfile = new \CURLFile($tmpPath, $mime, "upload.$ext");
        $postFields = ['image' => $cfile];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $predictUrl,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_TIMEOUT        => 60,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // --- Manejo de errores de conexión ---
        if ($error) {
            file_put_contents($logFile, "❌ CURL error: $error\n", FILE_APPEND);
            return ['error' => "Error al conectar con el modelo en $predictUrl: $error"];
        }

        if ($status !== 200) {
            file_put_contents($logFile, "⚠️ FastAPI HTTP $status\n", FILE_APPEND);
            return [
                'error' => "FastAPI devolvió código HTTP $status",
                'url'   => $predictUrl,
                'raw'   => substr($response, 0, 400),
            ];
        }

        // --- Intentar parsear JSON ---
        $json = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            file_put_contents($logFile, "⚠️ Respuesta no JSON\n", FILE_APPEND);
            return [
                'error' => 'Respuesta no JSON del modelo.',
                'url'   => $predictUrl,
                'raw'   => substr($response, 0, 400),
            ];
        }

        // --- Post-procesamiento ligero del JSON de FastAPI ---
        $result = $json;

        $speciesPredicted = $result['species_predicted'] ?? null;
        $speciesTop1      = $result['species_top1'] ?? null;

        $esConcluyente = $speciesPredicted !== null;

        $result['_meta'] = [
            'es_concluyente' => $esConcluyente,
            'tiene_top1'     => $speciesTop1 !== null,
        ];

        file_put_contents($logFile, "✅ Predicción completada correctamente.\n", FILE_APPEND);
        return $result;
    }
}