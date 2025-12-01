<?php

namespace app\controllers;

use Yii;
use app\models\Observador;
use app\models\ObservadorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\helpers\LibreriaHelper;

class ObservadorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario, [1, 2, 3]);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ObservadorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($obs_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($obs_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Observador();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->identity->usu_id;

            if ($model->save()) {
                $model->obs_foto = LibreriaHelper::subirFoto($model, 'obs_foto', 'observadores');
                if ($model->obs_foto) {
                    $model->save(false); // guarda solo foto
                }

                return $this->redirect(['view', 'obs_id' => $model->obs_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($obs_id)
    {
        $model = $this->findModel($obs_id);
        $fotoAntigua = $model->obs_foto;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->usu_id;

            $nuevaFoto = LibreriaHelper::subirFoto($model, 'obs_foto', 'observadores');

            if ($nuevaFoto) {
                if (!empty($fotoAntigua)) {
                    $ruta = Yii::getAlias("@app/../recursos/uploads/observadores/{$fotoAntigua}");
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                }
                $model->obs_foto = $nuevaFoto;
            } else {
                $model->obs_foto = $fotoAntigua;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'obs_id' => $model->obs_id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($obs_id)
    {
        $model = $this->findModel($obs_id);
        $ruta = Yii::getAlias("@app/../recursos/uploads/observadores/{$model->obs_foto}");

        if (!empty($model->obs_foto) && file_exists($ruta)) {
            unlink($ruta);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($obs_id)
    {
        if (($model = Observador::findOne(['obs_id' => $obs_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El observador solicitado no existe.');
    }
}