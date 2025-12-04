<?php
namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\db\Query;

use app\models\Deteccion;
use app\models\Especie;

class MonitoreoController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $b = parent::behaviors();
        $b['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Methods' => ['GET', 'OPTIONS'],
                'Access-Control-Allow-Headers' => ['*'],
            ],
        ];
        $b['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => ['application/json' => Response::FORMAT_JSON],
        ];
        return $b;
    }

    /**
     * Métricas generales del sistema basadas en datos reales
     * (visión global, para panel admin / monitoreo de sistema).
     */
    public function actionMetricaGeneral()
    {
        // Total de detecciones
        $totalDetecciones = (int) Deteccion::find()->count();

        // Promedio de precisión (det_confianza_experto)
        $promedioPrecision = (float) Deteccion::find()
            ->average('det_confianza_experto') ?: 0;

        // Promedio de latencia (router)
        $latenciaPromedioMs = (int) Deteccion::find()
            ->average('det_tiempo_router_ms') ?: 0;

        // TRL (fijo por etapa de desarrollo)
        $trl = "TRL 4";

        // Top 5 especies detectadas
        $topEspecies = (new Query())
            ->select(['e.esp_nombre_comun', 'COUNT(d.det_id) AS conteo'])
            ->from(['d' => Deteccion::tableName()])
            ->leftJoin(['e' => 'especies'], 'd.det_esp_id = e.esp_id')
            ->groupBy('e.esp_nombre_comun')
            ->orderBy(['conteo' => SORT_DESC])
            ->limit(5)
            ->all();

        // Latencia promedio por hora (últimas 24h)
        $latenciaData = (new Query())
            ->select([
                "HOUR(d.det_fecha) AS hora",
                "ROUND(AVG(d.det_tiempo_router_ms), 2) AS valor"
            ])
            ->from(['d' => Deteccion::tableName()])
            ->where(['>', 'd.det_fecha', date('Y-m-d H:i:s', strtotime('-24 hours'))])
            ->groupBy(["HOUR(d.det_fecha)"])
            ->orderBy(["HOUR(d.det_fecha)" => SORT_ASC])
            ->all();

        // Distribución geográfica (últimas 300 detecciones con coordenadas)
        $geoData = (new Query())
            ->select(['d.det_latitud AS lat', 'd.det_longitud AS lng'])
            ->from(['d' => Deteccion::tableName()])
            ->where(['not', ['d.det_latitud' => null]])
            ->andWhere(['not', ['d.det_longitud' => null]])
            ->orderBy(['d.det_id' => SORT_DESC])
            ->limit(300)
            ->all();

        // ─────────────────────────────────────────────
        // Métricas de feedback de usuarios (like / dislike)
        // ─────────────────────────────────────────────
        $tbl = Deteccion::tableName();

        $feedbackRow = (new Query())
            ->select([
                'total'        => 'COUNT(*)',
                'con_feedback' => "SUM(CASE WHEN d.det_feedback_usuario IN ('like','dislike') THEN 1 ELSE 0 END)",
                'likes'        => "SUM(CASE WHEN d.det_feedback_usuario = 'like' THEN 1 ELSE 0 END)",
                'dislikes'     => "SUM(CASE WHEN d.det_feedback_usuario = 'dislike' THEN 1 ELSE 0 END)",
            ])
            ->from(['d' => $tbl])
            ->one();

        $feedback = [
            'total'         => (int)($feedbackRow['total'] ?? 0),
            'con_feedback'  => (int)($feedbackRow['con_feedback'] ?? 0),
            'likes'         => (int)($feedbackRow['likes'] ?? 0),
            'dislikes'      => (int)($feedbackRow['dislikes'] ?? 0),
            'porc_like'     => 0.0,
            'porc_dislike'  => 0.0,
            'porc_cubierto' => 0.0,
        ];

        if ($feedback['con_feedback'] > 0) {
            $feedback['porc_like']    = round($feedback['likes']    * 100 / $feedback['con_feedback'], 1);
            $feedback['porc_dislike'] = round($feedback['dislikes'] * 100 / $feedback['con_feedback'], 1);
        }

        if ($feedback['total'] > 0) {
            $feedback['porc_cubierto'] = round($feedback['con_feedback'] * 100 / $feedback['total'], 1);
        }

        return [
            'success'              => true,
            'scope'                => 'global',
            'total_detecciones'    => $totalDetecciones,
            'promedio_precision'   => round($promedioPrecision, 3),
            'latencia_promedio_ms' => $latenciaPromedioMs,
            'trl'                  => $trl,
            'top_especies'         => $topEspecies,
            'latencia_24h'         => $latenciaData,
            'geo_puntos'           => $geoData,
            'feedback_usuarios'    => $feedback,
        ];
    }

    /**
     * Métricas filtradas por observador (usuario logueado en el sitio).
     * Usa el mismo criterio que DeteccionController::actionListar():
     *  - observer_id por GET (si viene)
     *  - fallback a sesión (observador_id / usuario_id)
     */
    public function actionMetricaUsuario()
    {
        $req = Yii::$app->request;

        // Asegurar sesión
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        // 1) Intentar por parámetro GET
        $observerId = (int)$req->get('observer_id', 0);

        // 2) Fallback a sesión (mismo patrón que actionListar)
        if ($observerId <= 0) {
            $observerId = (int)($session->get('observador_id') ?? $session->get('usuario_id') ?? 0);
        }

        if ($observerId <= 0) {
            Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'message' => 'Usuario no autenticado o sin observador asociado.',
            ];
        }

        // Determinar columna real del observador en la tabla detecciones
        $probe = new Deteccion();
        $observerCol = $probe->hasAttribute('det_observador_id')
            ? 'det_observador_id'
            : ($probe->hasAttribute('det_obs_id') ? 'det_obs_id' : null);

        if ($observerCol === null) {
            Yii::$app->response->statusCode = 500;
            return [
                'success' => false,
                'message' => 'No se encontró columna de observador en detecciones.',
            ];
        }

        $tbl         = Deteccion::tableName();
        $ahora       = date('Y-m-d H:i:s');
        $hace24h     = date('Y-m-d H:i:s', strtotime('-24 hours'));

        // Total de detecciones del usuario
        $totalDetecciones = (int) Deteccion::find()
            ->where([$observerCol => $observerId])
            ->count();

        // Promedio de precisión (usuario)
        $promedioPrecision = (float) Deteccion::find()
            ->where([$observerCol => $observerId])
            ->average('det_confianza_experto') ?: 0;

        // Promedio de latencia (usuario)
        $latenciaPromedioMs = (int) Deteccion::find()
            ->where([$observerCol => $observerId])
            ->average('det_tiempo_router_ms') ?: 0;

        // TRL es del sistema, no del usuario
        $trl = "TRL 4";

        // Top 5 especies detectadas por este observador
        $topEspecies = (new Query())
            ->select(['e.esp_nombre_comun', 'COUNT(d.det_id) AS conteo'])
            ->from(['d' => $tbl])
            ->leftJoin(['e' => 'especies'], 'd.det_esp_id = e.esp_id')
            ->where(['d.' . $observerCol => $observerId])
            ->groupBy('e.esp_nombre_comun')
            ->orderBy(['conteo' => SORT_DESC])
            ->limit(5)
            ->all();

        // Latencia promedio por hora (últimas 24h) solo de este observador
        $latenciaData = (new Query())
            ->select([
                "HOUR(d.det_fecha) AS hora",
                "ROUND(AVG(d.det_tiempo_router_ms), 2) AS valor"
            ])
            ->from(['d' => $tbl])
            ->where(['d.' . $observerCol => $observerId])
            ->andWhere(['>', 'd.det_fecha', $hace24h])
            ->groupBy(["HOUR(d.det_fecha)"])
            ->orderBy(["HOUR(d.det_fecha)" => SORT_ASC])
            ->all();

        // Distribución geográfica (detecciones de este observador)
        $geoData = (new Query())
            ->select(['d.det_latitud AS lat', 'd.det_longitud AS lng'])
            ->from(['d' => $tbl])
            ->where(['d.' . $observerCol => $observerId])
            ->andWhere(['not', ['d.det_latitud' => null]])
            ->andWhere(['not', ['d.det_longitud' => null]])
            ->orderBy(['d.det_id' => SORT_DESC])
            ->limit(300)
            ->all();

        // Feedback solo de detecciones de este observador
        $feedbackRow = (new Query())
            ->select([
                'total'        => 'COUNT(*)',
                'con_feedback' => "SUM(CASE WHEN d.det_feedback_usuario IN ('like','dislike') THEN 1 ELSE 0 END)",
                'likes'        => "SUM(CASE WHEN d.det_feedback_usuario = 'like' THEN 1 ELSE 0 END)",
                'dislikes'     => "SUM(CASE WHEN d.det_feedback_usuario = 'dislike' THEN 1 ELSE 0 END)",
            ])
            ->from(['d' => $tbl])
            ->where(['d.' . $observerCol => $observerId])
            ->one();

        $feedback = [
            'total'         => (int)($feedbackRow['total'] ?? 0),
            'con_feedback'  => (int)($feedbackRow['con_feedback'] ?? 0),
            'likes'         => (int)($feedbackRow['likes'] ?? 0),
            'dislikes'      => (int)($feedbackRow['dislikes'] ?? 0),
            'porc_like'     => 0.0,
            'porc_dislike'  => 0.0,
            'porc_cubierto' => 0.0,
        ];

        if ($feedback['con_feedback'] > 0) {
            $feedback['porc_like']    = round($feedback['likes']    * 100 / $feedback['con_feedback'], 1);
            $feedback['porc_dislike'] = round($feedback['dislikes'] * 100 / $feedback['con_feedback'], 1);
        }

        if ($feedback['total'] > 0) {
            $feedback['porc_cubierto'] = round($feedback['con_feedback'] * 100 / $feedback['total'], 1);
        }

        return [
            'success'              => true,
            'scope'                => 'usuario',
            'observer_id'          => (int)$observerId,
            'observer_column'      => $observerCol,
            'total_detecciones'    => $totalDetecciones,
            'promedio_precision'   => round($promedioPrecision, 3),
            'latencia_promedio_ms' => $latenciaPromedioMs,
            'trl'                  => $trl,
            'top_especies'         => $topEspecies,
            'latencia_24h'         => $latenciaData,
            'geo_puntos'           => $geoData,
            'feedback_usuarios'    => $feedback,
        ];
    }
}
