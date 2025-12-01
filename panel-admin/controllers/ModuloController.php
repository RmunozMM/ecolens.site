<?php

namespace app\controllers;

use Yii;
use app\models\Modulo;
use app\models\ModuloSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\models\Curso;
use app\helpers\LibreriaHelper; // Importamos la librería

/**
 * ModuloController implements the CRUD actions for Modulo model.
 */
class ModuloController extends Controller
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
     * Lists all Modulo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ModuloSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Modulo model.
     * @param int $mod_id ID único del módulo
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($mod_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($mod_id),
        ]);
    }

    /**
     * Creates a new Modulo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Modulo();
         // Instanciamos la librería

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->mod_slug = LibreriaHelper::generateSlug($model->mod_titulo); // Generamos el slug
                if ($model->save()) {
                    return $this->redirect(['view', 'mod_id' => $model->mod_id]);
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
     * Updates an existing Modulo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $mod_id ID único del módulo
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($mod_id)
    {
        $model = $this->findModel($mod_id);
         // Instanciamos la librería

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->mod_slug = LibreriaHelper::generateSlug($model->mod_titulo); // Generamos el slug
            if ($model->save()) {
                return $this->redirect(['view', 'mod_id' => $model->mod_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Modulo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $mod_id ID único del módulo
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($mod_id)
    {
        $this->findModel($mod_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Modulo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $mod_id ID único del módulo
     * @return Modulo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($mod_id)
    {
        if (($model = Modulo::findOne(['mod_id' => $mod_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Obtiene información del curso asociado al módulo seleccionado.
     * @param int $mod_id ID del módulo
     * @return array JSON con la información del curso
     */
    public function actionGetInfo($mod_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $modulo = Modulo::findOne($mod_id);

        if ($modulo && $modulo->curso) {
            return [
                'curso' => $modulo->curso->cur_titulo ?? 'Sin curso'
            ];
        }

        return ['curso' => 'Sin curso'];
    }
}