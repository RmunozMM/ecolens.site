<?php

namespace app\controllers;

use app\models\Herramienta;
use app\models\HerramientaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

/**
 * HerramientaController implements the CRUD actions for Herramientas model.
 */
class HerramientaController extends Controller
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
     * Lists all Herramientas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HerramientaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Desactivar la paginación
        $dataProvider->pagination = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Herramientas model.
     * @param int $her_id ID de la herramienta
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($her_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($her_id),
        ]);
    }

    /**
     * Creates a new Herramientas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Herramienta();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'her_id' => $model->her_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Herramientas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $her_id ID de la herramienta
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($her_id)
    {
        $model = $this->findModel($her_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'her_id' => $model->her_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Herramientas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $her_id ID de la herramienta
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($her_id)
    {
        $this->findModel($her_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Herramientas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $her_id ID de la herramienta
     * @return Herramienta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($her_id)
    {
        if (($model = Herramienta::findOne(['her_id' => $her_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionActualizarNivel()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $id = Yii::$app->request->post('id');
        $nuevo_nivel = Yii::$app->request->post('nivel');

        $herramienta = Herramienta::findOne($id);
        if ($herramienta) {
            $herramienta->her_nivel = $nuevo_nivel;
            if ($herramienta->save()) {
                return ['success' => true];
            }
        }
        
        return ['success' => false];
    }
}
