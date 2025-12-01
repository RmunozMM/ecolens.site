<?php

namespace app\controllers;

use app\models\CategoriaServicio;
use app\models\CategoriaServicioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

/**
 * CategoriaServicioController implements the CRUD actions for the CategoriaServicio model.
 */
class CategoriaServicioController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['index','view','create','update','delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario, [1,2,3]);                                
                        },
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all CategoriaServicio models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CategoriaServicioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoriaServicio model.
     * @param int $cas_id ID de la categoría de servicios
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cas_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cas_id),
        ]);
    }

    /**
     * Creates a new CategoriaServicio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CategoriaServicio();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cas_id' => $model->cas_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CategoriaServicio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cas_id ID de la categoría de servicios
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cas_id)
    {
        $model = $this->findModel($cas_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'cas_id' => $model->cas_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CategoriaServicio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cas_id ID de la categoría de servicios
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cas_id)
    {
        $this->findModel($cas_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CategoriaServicio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cas_id ID de la categoría de servicios
     * @return CategoriaServicio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cas_id)
    {
        if (($model = CategoriaServicio::findOne(['cas_id' => $cas_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}