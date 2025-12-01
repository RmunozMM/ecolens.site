<?php

namespace app\controllers;

use app\models\Habilidad;
use app\models\HabilidadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

/**
 * HabilidadController implements the CRUD actions for habilidad model.
 */
class HabilidadController extends Controller
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
     * Lists all Habilidades models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HabilidadSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        // Desactivar la paginación
        $dataProvider->pagination = false;


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single habilidad model.
     * @param int $hab_id ID de la habilidad
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($hab_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($hab_id),
        ]);
    }

    /**
     * Creates a new habilidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Habilidad();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'hab_id' => $model->hab_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Habilidades model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $hab_id ID de la habilidad
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($hab_id)
    {
        $model = $this->findModel($hab_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'hab_id' => $model->hab_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Habilidades model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $hab_id ID de la habilidad
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($hab_id)
    {
        $this->findModel($hab_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Habilidades model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $hab_id ID de la habilidad
     * @return Habilidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($hab_id)
    {
        if (($model = Habilidad::findOne(['hab_id' => $hab_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionActualizarNivel()
    {
        if (Yii::$app->request->isPost) {
            $hab_id = Yii::$app->request->post('id');
            $nuevo_nivel = Yii::$app->request->post('nivel');
            
            $habilidad = Habilidad::findOne($hab_id);
            if ($habilidad) {
                $habilidad->hab_nivel = $nuevo_nivel;
                if ($habilidad->save()) {
                    return json_encode(['status' => 'success']);
                }
            }
        }
        
        return json_encode(['status' => 'error']);
    }
}
