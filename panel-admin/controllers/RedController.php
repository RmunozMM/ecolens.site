<?php

namespace app\controllers;

use app\models\Redes;
use app\models\RedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;


/**
 * RedController implements the CRUD actions for Redes model.
 */
class RedController extends Controller
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
     * Lists all Redes models.
     *
     * @return string
     */

    public function actionIndex()
    {
        $searchModel = new RedSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Ordenar primero por red_publicada = 'SI' y luego por red_publicada = 'no'
        $dataProvider->query->orderBy([
            new \yii\db\Expression('FIELD(red_publicada, "no")'), // Orden ascendente por 'no'
            'red_publicada' => SORT_DESC, // Orden descendente por 'SI' después de 'no'
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    

    /**
     * Displays a single Redes model.
     * @param int $red_id ID de la red social
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($red_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($red_id),
        ]);
    }

    /**
     * Creates a new Redes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Redes();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'red_id' => $model->red_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Redes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $red_id ID de la red social
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($red_id)
    {
        $model = $this->findModel($red_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'red_id' => $model->red_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Redes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $red_id ID de la red social
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($red_id)
    {
        $this->findModel($red_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Redes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $red_id ID de la red social
     * @return Redes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($red_id)
    {
        if (($model = Redes::findOne(['red_id' => $red_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPublicar($red_id, $red_publicada)
    {
        // Verificar el valor actual de red_publicada
        if ($red_publicada == 'SI') {
            // Actualizar a "NO"
            $nuevoValor = 'NO';
        } elseif ($red_publicada == 'NO') {
            // Actualizar a "SI"
            $nuevoValor = 'SI';
        } else {
            // Valor inválido, realizar alguna acción de manejo de error
            // ...

            // Redirigir a una página de error, por ejemplo
            throw new \yii\web\BadRequestHttpException('Valor de red_publicada inválido');
        }

        // Realizar la actualización en la base de datos
        $redSocial = Redes::findOne($red_id);
        if ($redSocial) {
            $redSocial->red_publicada = $nuevoValor;
            $redSocial->save();
        } else {
            // Registro no encontrado, realizar alguna acción de manejo de error
            // ...

            // Redirigir a una página de error, por ejemplo
            throw new \yii\web\NotFoundHttpException('Red social no encontrada');
        }

        // Redirigir a la página de origen
        return $this->redirect(Yii::$app->request->referrer);
    }


}
