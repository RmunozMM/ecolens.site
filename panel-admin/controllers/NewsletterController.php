<?php

namespace app\controllers;

use app\models\Newsletter;
use app\models\NewsletterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsletterController implements the CRUD actions for Newsletter model.
 */
class NewsletterController extends Controller
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
     * Lists all Newsletter models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new NewsletterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Newsletter model.
     * @param int $new_id ID único del suscriptor
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($new_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($new_id),
        ]);
    }

    /**
     * Creates a new Newsletter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Newsletter();
    
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'new_id' => $model->new_id]);
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Newsletter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $new_id ID único del suscriptor
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($new_id)
    {
        $model = $this->findModel($new_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'new_id' => $model->new_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Newsletter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $new_id ID único del suscriptor
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($new_id)
    {
        $this->findModel($new_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Newsletter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $new_id ID único del suscriptor
     * @return Newsletter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($new_id)
    {
        if (($model = Newsletter::findOne(['new_id' => $new_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $fechaActual = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->new_fecha_creacion = $fechaActual;
            }
            $this->new_fecha_modificacion = $fechaActual;
            return true;
        }
        return false;
    }
}
