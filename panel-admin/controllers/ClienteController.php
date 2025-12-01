<?php

namespace app\controllers;

use app\models\Cliente;
use app\models\ClienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\helpers\LibreriaHelper;
use app\models\User;
use Yii;

class ClienteController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario, [1, 2, 3]);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($cli_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cli_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Cliente();
        

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->cli_slug = LibreriaHelper::generateSlug($model->cli_nombre);

            if ($model->save()) {
                $imagenGuardada =LibreriaHelper::subirFoto($model, 'cli_logo', 'clientes');
                if ($imagenGuardada) {
                    $model->cli_logo = $imagenGuardada;
                    $model->updateAttributes(['cli_logo']);
                }

                return $this->redirect(['view', 'cli_id' => $model->cli_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($cli_id)
    {
        $model = $this->findModel($cli_id);
        

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->cli_slug = LibreriaHelper::generateSlug($model->cli_nombre);

            $imagenGuardada =LibreriaHelper::subirFoto($model, 'cli_logo', 'clientes');
            $model->cli_logo = $imagenGuardada ?: $model->getOldAttribute('cli_logo');

            if ($model->save()) {
                return $this->redirect(['view', 'cli_id' => $model->cli_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($cli_id)
    {
        $model = $this->findModel($cli_id);
        

        if (!empty($model->cli_logo) && file_exists("../../recursos/uploads/" . $model->cli_logo)) {
            unlink("../../recursos/uploads/" . $model->cli_logo);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($cli_id)
    {
        if (($model = Cliente::findOne(['cli_id' => $cli_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}