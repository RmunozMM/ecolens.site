<?php

namespace app\controllers;

use app\models\CategoriaArticulo;
use app\models\CategoriaArticuloSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\helpers\LibreriaHelper;

/**
 * CategoriaArticuloController implements the CRUD actions for CategoriaArticulo model.
 */
class CategoriaArticuloController extends Controller
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
     * Lists all CategoriaArticulo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CategoriaArticuloSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoriaArticulo model.
     * @param int $caa_id ID único de la categoría
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($caa_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($caa_id),
        ]);
    }

    /**
     * Creates a new CategoriaArticulo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */


    public function actionCreate()
    {
        $model = new CategoriaArticulo();
    
        if ($this->request->isPost && $model->load($this->request->post())) {
    
            // generamos el slug utilizando el nombre después de cargar los datos del formulario
            
            $model->caa_slug = LibreriaHelper::generateSlug($model->caa_nombre);
    
            if ($model->save()) {
                return $this->redirect(['view', 'caa_id' => $model->caa_id]);
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    

    /**
     * Updates an existing CategoriaArticulo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $caa_id ID único de la categoría
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($caa_id)
    {
        $model = $this->findModel($caa_id);

        // generamos el slug utilizando el nombre
        
        $model->caa_slug = LibreriaHelper::generateSlug($model->caa_nombre);
        

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'caa_id' => $model->caa_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CategoriaArticulo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $caa_id ID único de la categoría
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($caa_id)
    {
        $this->findModel($caa_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CategoriaArticulo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $caa_id ID único de la categoría
     * @return CategoriaArticulo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($caa_id)
    {
        if (($model = CategoriaArticulo::findOne(['caa_id' => $caa_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
