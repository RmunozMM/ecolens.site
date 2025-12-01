<?php

namespace app\controllers;

use app\models\Curso;
use app\models\CursoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\LibreriaHelper; // Importamos la librería

/**
 * CursoController implements the CRUD actions for Curso model.
 */
class CursoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Curso models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CursoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Curso model.
     * @param int $cur_id ID único del curso
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cur_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cur_id),
        ]);
    }

    /**
     * Creates a new Curso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Curso();
         // Instanciamos la librería

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->cur_slug = LibreriaHelper::generateSlug($model->cur_titulo); // Generamos el slug
                if ($model->save()) {
                    return $this->redirect(['view', 'cur_id' => $model->cur_id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Curso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cur_id ID único del curso
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cur_id)
    {
        $model = $this->findModel($cur_id);
         // Instanciamos la librería

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->cur_slug = LibreriaHelper::generateSlug($model->cur_titulo); // Generamos el slug
            if ($model->save()) {
                return $this->redirect(['view', 'cur_id' => $model->cur_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Curso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cur_id ID único del curso
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cur_id)
    {
        $this->findModel($cur_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Curso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cur_id ID único del curso
     * @return Curso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cur_id)
    {
        if (($model = Curso::findOne(['cur_id' => $cur_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}