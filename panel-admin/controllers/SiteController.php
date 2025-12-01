<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\LoginForm;
use app\models\ContactForm;
use app\models\FormRegister;
use app\models\User;
use app\models\Experiencias;
use app\models\Formacion;
use app\models\Opcion;
use app\models\Correo;
use app\models\Menu;
use yii\widgets\ActiveForm;
use yii\data\Pagination;

use app\helpers\LibreriaHelper;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario, [1, 2, 3]);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function getIndicators()
    {
        $apiBaseUrl = 'https://mindicador.cl/api';
        $url = $apiBaseUrl . '/';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function actionIndex()
    {
        

        $online = Opcion::find()->where(['opc_nombre' => 'sitio_online'])->one();
        $debug = Opcion::find()->where(['opc_nombre' => 'debug'])->one();
        $cantidadCorreosPendientes = Correo::find()->where(['cor_estado' => 'pendiente'])->count();
        $fechas = $this->obtenerFechas();
        $indicadores = $this->getIndicators();

        return $this->render('index', [
            'online' => $online,
            'debug' => $debug,
            'cantidadCorreosPendientes' => $cantidadCorreosPendientes,
            'fechas' => $fechas,
            'indicadores' => $indicadores,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->usu_password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {
                $model->saveToDatabase(); // Guarda en BD
                $model->sendEmailToClient(); // Email al cliente
                Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('contactFormError');
            }
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionCv()
    {
        return $this->render('../../cv/index.php');
    }

    public function obtenerFechas()
    {
        $timestamp = time();

        return [
            'Dia de la Semana' => date('l', $timestamp),
            'Semana del Año' => date('W', $timestamp),
            'Año' => date('Y', $timestamp),
            'Día del Año' => date('z', $timestamp) + 1,
            'Hora' => date('H:i:s', $timestamp),
        ];
    }

    public function actionSearchGlobal($q = null)
    {
        if (empty($q)) {
            return $this->render('search-global', [
                'q' => $q,
                'results' => [],
            ]);
        }

        $results = [];
        $dir = Yii::getAlias('@app/models');
        $files = scandir($dir);

        foreach ($files as $file) {
            if (substr($file, -10) === 'Search.php') {
                $className = 'app\\models\\' . substr($file, 0, -4);
                if (class_exists($className) && method_exists($className, 'search')) {
                    $model = new $className();
                    if (property_exists($model, 'globalSearch')) {
                        $model->globalSearch = $q;
                        $dataProvider = $model->search([$model->formName() => []]);
                        $dataProvider->pagination->pageSize = 20;

                        $results[] = [
                            'className' => $className,
                            'dataProvider' => $dataProvider,
                        ];
                    }
                }
            }
        }

        return $this->render('search-global', [
            'q' => $q,
            'results' => $results,
        ]);
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
