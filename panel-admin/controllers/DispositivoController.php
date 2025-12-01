<?php

namespace app\controllers;

use Yii;
use app\models\Dispositivo;
use app\models\DispositivoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

class DispositivoController extends Controller
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
        $searchModel = new DispositivoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($dis_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($dis_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Dispositivo();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->identity->usu_id;

            if ($model->save()) {
                return $this->redirect(['view', 'dis_id' => $model->dis_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($dis_id)
    {
        $model = $this->findModel($dis_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->usu_id;

            if ($model->save()) {
                return $this->redirect(['view', 'dis_id' => $model->dis_id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($dis_id)
    {
        $this->findModel($dis_id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($dis_id)
    {
        if (($model = Dispositivo::findOne(['dis_id' => $dis_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El dispositivo solicitado no existe.');
    }
}