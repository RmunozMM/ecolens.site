<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\db\Expression;
use app\models\User;
use app\models\Deteccion;
use app\models\Observador;

class MonitoreoController extends Controller
{
    /* -----------------------------
     |  Behaviors: verbs + access
     * -----------------------------*/
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index'    => ['GET'],
                    'usuarios' => ['GET'],
                    'sistema'  => ['GET'],
                    'api'      => ['GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['index','usuarios','sistema','api'],
                'rules' => [[
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function () {
                        $id  = Yii::$app->user->identity->usu_id ?? null;
                        $rol = (int)($this->safeGet(Yii::$app->user->identity ?? (object)[], 'usu_rol_id', 0));
                        if (method_exists(User::class, 'checkRoleByUserId')) {
                            return User::checkRoleByUserId($id, [1,2,3]);
                        }
                        return in_array($rol, [1,2,3], true);
                    },
                ]],
            ],
        ];
    }

    /* -----------------------------
     |  Helpers pequeños
     * -----------------------------*/
    private function safeGet($obj, $k, $def = null) {
        return is_object($obj) ? ($obj->$k ?? $def) : (is_array($obj) ? ($obj[$k] ?? $def) : $def);
    }

    /** Percentiles rápidos en PHP (evita depender de funciones MySQL 8). */
    private function percentiles(array $values, array $pcts = [50,90,95,99]): array
    {
        $out = [];
        if (empty($values)) {
            foreach ($pcts as $p) $out["p$p"] = 0.0;
            return $out;
        }
        sort($values);
        $n = count($values);
        foreach ($pcts as $p) {
            $idx = max(0, min($n-1, (int)ceil($p/100 * $n) - 1));
            $out["p$p"] = (float)$values[$idx];
        }
        return $out;
    }

    /** Carga de entorno robusta para endpoints (panel-admin / sitio). */
    private function loadEnv(): array
    {
        $candidatos = [
            Yii::getAlias('@app') . '/config/ecolens_env.php',
            dirname(Yii::getAlias('@app')) . '/panel-admin/config/ecolens_env.php',
            dirname(Yii::getAlias('@app')) . '/sitio/config/ecolens_env.php',
        ];
        foreach ($candidatos as $p) {
            if (is_file($p)) {
                $env = require $p;
                if (is_array($env)) return $env;
            }
        }
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $local  = (bool)preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $host);
        $prefix = $local ? '/ecolens.site' : '';
        return [
            'isLocal'   => $local,
            'API_BASE'  => ($local ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/panel-admin/web',
            'SITE_BASE' => ($local ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/sitio/web',
            'endpoints' => [],
        ];
    }

    /** HEAD/GET breve para chequear salud de un endpoint. */
    private function pingUrl(string $url, int $timeoutMs = 700): array
    {
        if (!$url) return ['ok' => false, 'status' => 0, 'time_ms' => 0];

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER     => true,
                CURLOPT_NOBODY             => true,
                CURLOPT_TIMEOUT_MS         => $timeoutMs,
                CURLOPT_CONNECTTIMEOUT_MS  => $timeoutMs,
                CURLOPT_FOLLOWLOCATION     => true,
                CURLOPT_MAXREDIRS          => 2,
            ]);
            $t0 = microtime(true);
            @curl_exec($ch);
            $code = (int)@curl_getinfo($ch, CURLINFO_HTTP_CODE);
            @curl_close($ch);
            return [
                'ok'      => ($code >= 200 && $code < 500),
                'status'  => $code,
                'time_ms' => (int)((microtime(true)-$t0)*1000),
            ];
        }

        $ctx = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'timeout' => max(1, (int)ceil($timeoutMs/1000)),
            ]
        ]);
        $t0 = microtime(true);
        $h  = @fopen($url, 'r', false, $ctx);
        $ok = $h !== false;
        if ($h) fclose($h);
        return ['ok' => $ok, 'status' => $ok ? 200 : 0, 'time_ms' => (int)((microtime(true)-$t0)*1000)];
    }

    private function secondsToHuman(int $sec): string
    {
        $d = intdiv($sec, 86400); $sec %= 86400;
        $h = intdiv($sec, 3600);  $sec %= 3600;
        $m = intdiv($sec, 60);    $s = $sec % 60;
        $parts = [];
        if ($d) $parts[] = $d . 'd';
        if ($h) $parts[] = $h . 'h';
        if ($m) $parts[] = $m . 'm';
        if ($s || empty($parts)) $parts[] = $s . 's';
        return implode(' ', $parts);
    }

    /* -----------------------------
     |  Dashboard (detecciones)
     * -----------------------------*/
    public function actionIndex()
    {
        // Rango: ?rango=24h|7d|30d|todo
        $rango = Yii::$app->request->get('rango', '24h');
        $now   = time();

        switch ($rango) {
            case '7d':
                $desde = date('Y-m-d H:i:s', $now - 7 * 86400);
                break;
            case '30d':
                $desde = date('Y-m-d H:i:s', $now - 30 * 86400);
                break;
            case 'todo':
                // Mínima fecha de la tabla; si no hay datos, 1 año atrás
                $minFecha = (new Query())
                    ->from('detecciones')
                    ->min('det_fecha');
                if ($minFecha) {
                    $desde = $minFecha;
                } else {
                    $desde = date('Y-m-d H:i:s', $now - 365 * 86400);
                }
                break;
            case '24h':
            default:
                $rango = '24h';
                $desde = date('Y-m-d H:i:s', $now - 24 * 3600);
                break;
        }

        // KPIs
        $totalDetecciones = (int) Deteccion::find()
            ->andWhere(['>=','det_fecha',$desde])
            ->count();

        $promedioPrecision = (float) (Deteccion::find()
            ->andWhere(['>=','det_fecha',$desde])
            ->average('det_confianza_experto') ?: 0);

        $latenciaPromedioMs = (int) (Deteccion::find()
            ->andWhere(['>=','det_fecha',$desde])
            ->average('det_tiempo_router_ms') ?: 0);

        $trl = 'TRL 4';

        // ─────────────────────────────
        // Métricas de feedback usuarios
        // ─────────────────────────────
        $feedbackRow = (new Query())
            ->select([
                'total'        => 'COUNT(*)',
                'con_feedback' => "SUM(CASE WHEN det_feedback_usuario IN ('like','dislike') THEN 1 ELSE 0 END)",
                'likes'        => "SUM(CASE WHEN det_feedback_usuario = 'like' THEN 1 ELSE 0 END)",
                'dislikes'     => "SUM(CASE WHEN det_feedback_usuario = 'dislike' THEN 1 ELSE 0 END)",
            ])
            ->from(['d' => 'detecciones'])
            ->where(['>=', 'd.det_fecha', $desde])
            ->one();

        $feedbackUsuarios = [
            'total'         => (int)($feedbackRow['total'] ?? 0),
            'con_feedback'  => (int)($feedbackRow['con_feedback'] ?? 0),
            'likes'         => (int)($feedbackRow['likes'] ?? 0),
            'dislikes'      => (int)($feedbackRow['dislikes'] ?? 0),
            'porc_like'     => 0.0,
            'porc_dislike'  => 0.0,
            'porc_cubierto' => 0.0,
        ];

        if ($feedbackUsuarios['con_feedback'] > 0) {
            $feedbackUsuarios['porc_like']    = round($feedbackUsuarios['likes']    * 100 / $feedbackUsuarios['con_feedback'], 1);
            $feedbackUsuarios['porc_dislike'] = round($feedbackUsuarios['dislikes'] * 100 / $feedbackUsuarios['con_feedback'], 1);
        }

        if ($feedbackUsuarios['total'] > 0) {
            $feedbackUsuarios['porc_cubierto'] = round($feedbackUsuarios['con_feedback'] * 100 / $feedbackUsuarios['total'], 1);
        }

        // Latencias crudas para percentiles
        $latencias = (new Query())
            ->select(['ms' => 'det_tiempo_router_ms'])
            ->from('detecciones')
            ->where(['>=','det_fecha',$desde])
            ->andWhere(['not', ['det_tiempo_router_ms' => null]])
            ->orderBy(['det_id' => SORT_DESC])
            ->limit(20000)
            ->column();

        $latP = $this->percentiles(array_map('floatval', $latencias), [50,90,95,99]);

        // Serie temporal (por hora para 24h/7d, por día para 30d/todo)
        $agrupaPorHora = ($rango === '24h' || $rango === '7d');
        $bucketExpr = $agrupaPorHora
            ? new Expression("DATE_FORMAT(det_fecha, '%Y-%m-%d %H:00:00')")
            : new Expression("DATE_FORMAT(det_fecha, '%Y-%m-%d 00:00:00')");

        $serieDet = (new Query())
            ->select([
                'bucket' => $bucketExpr,
                'c'      => new Expression('COUNT(*)'),
            ])
            ->from('detecciones')
            ->where(['>=','det_fecha',$desde])
            ->groupBy($bucketExpr)
            ->orderBy($bucketExpr)
            ->all();

        // Top 5 especies
        $topEspecies = (new Query())
            ->select(['e.esp_nombre_comun', 'conteo' => 'COUNT(d.det_id)'])
            ->from(['d' => 'detecciones'])
            ->leftJoin(['e' => 'especies'], 'd.det_esp_id = e.esp_id')
            ->where(['>=','d.det_fecha',$desde])
            ->groupBy(['e.esp_nombre_comun'])
            ->orderBy(['conteo' => SORT_DESC])
            ->limit(5)
            ->all();

        // Precisión por especie (n mínimo)
        $minPorEspecie = 5;
        $precisionEspecie = (new Query())
            ->select([
                'e.esp_nombre_comun',
                'prom' => new Expression('ROUND(AVG(d.det_confianza_experto), 3)'),
                'n'    => new Expression('COUNT(*)'),
            ])
            ->from(['d' => 'detecciones'])
            ->leftJoin(['e' => 'especies'], 'd.det_esp_id = e.esp_id')
            ->where(['>=','d.det_fecha',$desde])
            ->groupBy(['e.esp_nombre_comun'])
            ->having(new Expression('COUNT(*) >= :n', [':n' => $minPorEspecie]))
            ->orderBy(['prom' => SORT_DESC])
            ->limit(10)
            ->all();

        // Top observadores por actividad
        $topObservadores = (new Query())
            ->select([
                'o.obs_nombre',
                'o.obs_usuario',
                'conteo' => 'COUNT(d.det_id)'
            ])
            ->from(['d' => 'detecciones'])
            ->leftJoin(['o' => 'observadores'], 'd.det_obs_id = o.obs_id')
            ->where(['>=','d.det_fecha',$desde])
            ->groupBy(['o.obs_id'])
            ->orderBy(['conteo' => SORT_DESC])
            ->limit(10)
            ->all();

        // Últimas detecciones
        $ultimas = (new Query())
            ->select([
                'd.det_id','d.det_fecha','d.det_latitud','d.det_longitud','d.det_tiempo_router_ms',
                'e.esp_nombre_comun','o.obs_nombre'
            ])
            ->from(['d' => 'detecciones'])
            ->leftJoin(['e' => 'especies'], 'd.det_esp_id = e.esp_id')
            ->leftJoin(['o' => 'observadores'], 'd.det_obs_id = o.obs_id')
            ->where(['>=','d.det_fecha',$desde])
            ->orderBy(['d.det_id' => SORT_DESC])
            ->limit(20)
            ->all();

        // Geo puntos
        $geoPuntos = (new Query())
            ->select(['lat' => 'd.det_latitud', 'lng' => 'd.det_longitud'])
            ->from(['d' => 'detecciones'])
            ->where(['not', ['d.det_latitud' => null]])
            ->andWhere(['not', ['d.det_longitud' => null]])
            ->andWhere(['>=','d.det_fecha',$desde])
            ->orderBy(['d.det_id' => SORT_DESC])
            ->limit(500)
            ->all();

        return $this->render('index', [
            'rango'              => $rango,
            'desde'              => $desde,
            'totalDetecciones'   => $totalDetecciones,
            'promedioPrecision'  => round($promedioPrecision, 3),
            'latenciaPromedioMs' => $latenciaPromedioMs,
            'latP'               => $latP,
            'serieDet'           => $serieDet,
            'agrupaPorHora'      => $agrupaPorHora,
            'trl'                => $trl,
            'topEspecies'        => $topEspecies,
            'precisionEspecie'   => $precisionEspecie,
            'topObservadores'    => $topObservadores,
            'ultimas'            => $ultimas,
            'geoPuntos'          => $geoPuntos,
            'feedbackUsuarios'   => $feedbackUsuarios,
        ]);
    }

    /* -----------------------------
     |  Usuarios (métricas)
     * -----------------------------*/
    public function actionUsuarios()
    {
        $desde = date('Y-m-d 00:00:00', strtotime('-30 days'));

        $totalUsuarios = (int) Observador::find()->count();

        $nuevos30d = (int) Observador::find()
            ->where(['>=','created_at',$desde])
            ->count();

        // Serie crecimiento diario (expresión consistente en select/group/order)
        $bucketCrecExpr = new Expression("DATE_FORMAT(created_at, '%Y-%m-%d 00:00:00')");
        $crecimiento = (new Query())
            ->select([
                'bucket' => $bucketCrecExpr,
                'c'      => new Expression('COUNT(*)'),
            ])
            ->from('observadores')
            ->where(['>=','created_at',$desde])
            ->groupBy($bucketCrecExpr)
            ->orderBy($bucketCrecExpr)
            ->all();

        // Top 10 por #detecciones
        $topPorDetecciones = (new Query())
            ->select([
                'o.obs_nombre','o.obs_usuario',
                'detecciones' => 'COUNT(d.det_id)'
            ])
            ->from(['d' => 'detecciones'])
            ->leftJoin(['o' => 'observadores'], 'd.det_obs_id = o.obs_id')
            ->groupBy(['o.obs_id'])
            ->orderBy(['detecciones' => SORT_DESC])
            ->limit(10)
            ->all();

        // Activos últimos 7 días
        $desde7 = date('Y-m-d H:i:s', strtotime('-7 days'));
        $activos7d = (new Query())
            ->select(['activos' => 'COUNT(DISTINCT d.det_obs_id)'])
            ->from(['d' => 'detecciones'])
            ->where(['>=','d.det_fecha',$desde7])
            ->scalar();

        return $this->render('usuarios', [
            'totalUsuarios'     => $totalUsuarios,
            'nuevos30d'         => $nuevos30d,
            'crecimiento'       => $crecimiento,
            'topPorDetecciones' => $topPorDetecciones,
            'activos7d'         => (int)$activos7d,
            'desde'             => $desde,
        ]);
    }

    /* -----------------------------
     |  Sistema (salud VPS / app)
     * -----------------------------*/
    public function actionSistema()
    {
        $phpVersion  = PHP_VERSION;
        $yiiVersion  = Yii::getVersion();
        $appEnv      = YII_ENV ?? 'prod';
        $appDebug    = YII_DEBUG ? 'on' : 'off';

        // MySQL versión
        try {
            $mysqlVersion = Yii::$app->db->createCommand("SELECT VERSION()")->queryScalar();
        } catch (\Throwable $e) {
            $mysqlVersion = 'n/a';
        }

        // Disco
        $diskTotal = @disk_total_space('/') ?: 0;
        $diskFree  = @disk_free_space('/') ?: 0;

        // Memoria y carga
        $memUsageMb = round(memory_get_usage(true)/1024/1024, 2);
        $loadAvgArr = @sys_getloadavg();
        $loadAvg    = $loadAvgArr ? implode(', ', array_map(fn($v)=>number_format($v,2), $loadAvgArr)) : 'n/a';

        // Uptime
        $uptimeSec = null;
        if (@is_readable('/proc/uptime')) {
            $upt = @file_get_contents('/proc/uptime');
            if ($upt) {
                $parts = explode(' ', trim($upt));
                $uptimeSec = (int)floatval($parts[0] ?? 0);
            }
        }
        $uptimeHuman = $uptimeSec !== null ? $this->secondsToHuman($uptimeSec) : 'n/a';

        // Tamaño DB (aprox)
        try {
            $dbName = Yii::$app->db->createCommand('SELECT DATABASE()')->queryScalar();
            $dbSize = (new Query())
                ->select(['bytes' => 'SUM(data_length + index_length)'])
                ->from('information_schema.tables')
                ->where(['table_schema' => $dbName])
                ->scalar();
        } catch (\Throwable $e) {
            $dbSize = null;
        }

        return $this->render('sistema', [
            'phpVersion'  => $phpVersion,
            'yiiVersion'  => $yiiVersion,
            'mysqlVersion'=> $mysqlVersion,
            'appEnv'      => $appEnv,
            'appDebug'    => $appDebug,
            'diskTotal'   => $diskTotal,
            'diskFree'    => $diskFree,
            'memUsageMb'  => $memUsageMb,
            'loadAvg'     => $loadAvg,
            'uptimeHuman' => $uptimeHuman,
            'dbSizeBytes' => $dbSize ? (int)$dbSize : null,
        ]);
    }

    /* -----------------------------
     |  API/Servicios (estado)
     * -----------------------------*/
    public function actionApi()
    {
        $env = $this->loadEnv();

        $apiBase   = rtrim($env['API_BASE']  ?? '', '/');
        $siteBase  = rtrim($env['SITE_BASE'] ?? '', '/');

        $endpoints = $env['endpoints'] ?? [];
        $predict   = $endpoints['predict'] ?? null;
        $whoami    = $endpoints['whoami']  ?? ($apiBase ? $apiBase . '/api/observador/whoami' : null);

        $pingPredict = $predict ? $this->pingUrl($predict) : ['ok'=>false,'status'=>0,'time_ms'=>0];
        $pingWhoami  = $whoami  ? $this->pingUrl($whoami)  : ['ok'=>false,'status'=>0,'time_ms'=>0];

        // DB check rápido
        try {
            Yii::$app->db->createCommand('SELECT 1')->queryScalar();
            $dbOk = true;
        } catch (\Throwable $e) {
            $dbOk = false;
        }

        return $this->render('api', [
            'env'         => $env,
            'apiBase'     => $apiBase,
            'siteBase'    => $siteBase,
            'predictUrl'  => $predict,
            'whoamiUrl'   => $whoami,
            'pingPredict' => $pingPredict,
            'pingWhoami'  => $pingWhoami,
            'dbOk'        => $dbOk,
        ]);
    }
}
