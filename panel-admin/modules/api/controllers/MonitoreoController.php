<?php
namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
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
     */
    public function actionMetricaGeneral()
    {
        $db = Yii::$app->db;

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
        $topEspecies = (new \yii\db\Query())
            ->select(['e.esp_nombre_comun', 'COUNT(d.det_id) AS conteo'])
            ->from(['d' => 'detecciones'])
            ->leftJoin(['e' => 'especies'], 'd.det_esp_id = e.esp_id')
            ->groupBy('e.esp_nombre_comun')
            ->orderBy(['conteo' => SORT_DESC])
            ->limit(5)
            ->all();

        // Latencia promedio por hora (últimas 24h)
        $latenciaData = (new \yii\db\Query())
            ->select([
                "HOUR(d.det_fecha) AS hora",
                "ROUND(AVG(d.det_tiempo_router_ms), 2) AS valor"
            ])
            ->from(['d' => 'detecciones'])
            ->where(['>', 'd.det_fecha', date('Y-m-d H:i:s', strtotime('-24 hours'))])
            ->groupBy(["HOUR(d.det_fecha)"])
            ->orderBy(["HOUR(d.det_fecha)" => SORT_ASC])
            ->all();

        // Distribución geográfica (últimas 300 detecciones con coordenadas)
        $geoData = (new \yii\db\Query())
            ->select(['d.det_latitud AS lat', 'd.det_longitud AS lng'])
            ->from(['d' => 'detecciones'])
            ->where(['not', ['d.det_latitud' => null]])
            ->andWhere(['not', ['d.det_longitud' => null]])
            ->orderBy(['d.det_id' => SORT_DESC])
            ->limit(300)
            ->all();

        return [
            'success' => true,
            'total_detecciones' => $totalDetecciones,
            'promedio_precision' => round($promedioPrecision, 3),
            'latencia_promedio_ms' => $latenciaPromedioMs,
            'trl' => $trl,
            'top_especies' => $topEspecies,
            'latencia_24h' => $latenciaData,
            'geo_puntos' => $geoData,
        ];
    }
}
