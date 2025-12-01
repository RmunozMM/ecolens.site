<?php
namespace app\controllers;

use app\models\Experiencia;
use app\models\ExperienciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use Yii;

class ExperienciaController extends Controller
{
    /**
     * Control de acceso: sólo usuarios con rol 1,2 o 3
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class'   => AccessControl::class,
                'only'    => ['index','view','create','update','delete'],
                'rules'   => [
                    [
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => fn() => \app\models\User::checkRoleByUserId(
                            Yii::$app->user->identity->usu_id,
                            [1,2,3]
                        ),
                    ],
                ],
            ],
        ];
    }

    /**
     * Listado de experiencias
     */
    public function actionIndex()
    {
        $searchModel  = new ExperienciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Detalle de una experiencia
     * @param int $exp_id
     */
    public function actionView($exp_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($exp_id),
        ]);
    }

    /**
     * Crear nueva experiencia
     */
    public function actionCreate()
    {
        $model = new Experiencia();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'exp_id' => $model->exp_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualizar experiencia existente
     * @param int $exp_id
     */
    public function actionUpdate($exp_id)
    {
        $model = $this->findModel($exp_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'exp_id' => $model->exp_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Eliminar experiencia
     * @param int $exp_id
     */
    public function actionDelete($exp_id)
    {
        $this->findModel($exp_id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Busca el modelo o lanza 404
     * @param int $exp_id
     * @return Experiencia
     */
    protected function findModel($exp_id): Experiencia
    {
        if (($model = Experiencia::findOne($exp_id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La experiencia solicitada no existe.');
    }
}