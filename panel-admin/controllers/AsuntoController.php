<?php

namespace app\controllers;

use app\models\Asunto;
use app\models\AsuntoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

/**
 * AsuntoController implements the CRUD actions for Asuntos model.
 */
class AsuntoController extends Controller
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
                            return User::checkRoleByUserId($usuario,[1,2,3]);                                
                        },
                    ],
                    // everything else is denied

                ],
            ],
        ];
    }

    /**
     * Lists all Asuntos models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AsuntoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Asuntos model.
     * @param int $asu_id ID del asunto
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($asu_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($asu_id),
        ]);
    }

    /**
     * Creates a new Asuntos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Asunto();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'asu_id' => $model->asu_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Asuntos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $asu_id ID del asunto
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($asu_id)
    {
        $model = $this->findModel($asu_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'asu_id' => $model->asu_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Asuntos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $asu_id ID del asunto
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($asu_id)
    {
        $this->findModel($asu_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Asuntos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $asu_id ID del asunto
     * @return Asunto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($asu_id)
    {
        if (($model = Asunto::findOne(['asu_id' => $asu_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
