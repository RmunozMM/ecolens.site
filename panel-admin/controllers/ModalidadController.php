<?php

namespace app\controllers;

use app\models\Modalidad;
use app\models\ModalidadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

/**
 * ModalidadController implements the CRUD actions for Modalidad model.
 */
class ModalidadController extends Controller
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
     * Lists all Modalidad models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ModalidadSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Modalidad model.
     * @param int $mod_id Identificador único de modalidad
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
     * Creates a new Modalidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Modalidad();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'mod_id' => $model->mod_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Modalidad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $mod_id Identificador único de modalidad
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($mod_id)
    {
        $model = $this->findModel($mod_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'mod_id' => $model->mod_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Modalidad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $mod_id Identificador único de modalidad
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($mod_id)
    {
        $this->findModel($mod_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Modalidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $mod_id Identificador único de modalidad
     * @return Modalidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($mod_id)
    {
        if (($model = Modalidad::findOne(['mod_id' => $mod_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
