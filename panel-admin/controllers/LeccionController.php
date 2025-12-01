<?php

namespace app\controllers;

use Yii;
use app\models\Leccion;
use app\models\LeccionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\helpers\LibreriaHelper;

/**
 * LeccionController implements the CRUD actions for Leccion model.
 */
class LeccionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Leccion models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LeccionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Leccion model.
     * @param int $lec_id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($lec_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($lec_id),
        ]);
    }

    /**
     * Creates a new Leccion model.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Leccion();
        

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->lec_slug = LibreriaHelper::generateSlug($model->lec_titulo);
                if ($model->save()) {
                    return $this->redirect(['view', 'lec_id' => $model->lec_id]);
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
     * Updates an existing Leccion model.
     * @param int $lec_id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($lec_id)
    {
        $model = $this->findModel($lec_id);
        

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->lec_slug = LibreriaHelper::generateSlug($model->lec_titulo);
            if ($model->save()) {
                return $this->redirect(['view', 'lec_id' => $model->lec_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Leccion model.
     * @param int $lec_id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($lec_id)
    {
        $this->findModel($lec_id)->delete();

        return $this->redirect(['index']);
    }



    /**
     * Devuelve el Módulo y Curso asociados a una Lección.
     * @param int $lec_id
     * @return array JSON
     */
    public function actionGetInfo($lec_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $leccion = Leccion::findOne($lec_id);

        if ($leccion && $leccion->modulo) {
            return [
                'modulo' => $leccion->modulo->mod_titulo ?? 'Sin módulo',
                'curso' => $leccion->modulo->curso->cur_titulo ?? 'Sin curso',
            ];
        }

        return [
            'modulo' => 'Sin módulo',
            'curso' => 'Sin curso',
        ];
    }

        /**
     * Finds the Leccion model based on its primary key value.
     * @param int $lec_id
     * @return Leccion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($lec_id)
    {
        if (($model = Leccion::findOne(['lec_id' => $lec_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}