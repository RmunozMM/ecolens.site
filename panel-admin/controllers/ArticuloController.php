<?php

namespace app\controllers;

use Yii;
use app\models\Articulo;
use app\models\ArticuloSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\web\UploadedFile;
use app\helpers\LibreriaHelper;

class ArticuloController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario, [1, 2, 3]);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ArticuloSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($art_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($art_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Articulo();
        

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->art_slug = LibreriaHelper::generateSlug($model->art_titulo);
            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->identity->usu_id;

            if ($model->save()) {
                $model->art_imagen =LibreriaHelper::subirFoto($model, 'art_imagen', 'articulos');

                if ($model->art_imagen) {
                    $model->save(false); // guarda solo imagen
                }

                return $this->redirect(['view', 'art_id' => $model->art_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($art_id)
    {
        $model = $this->findModel($art_id);
        
        $imagenAntigua = $model->art_imagen;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->art_slug = LibreriaHelper::generateSlug($model->art_titulo);
            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->usu_id;

            $rutaImagen =LibreriaHelper::subirFoto($model, 'art_imagen', 'articulos');

            if ($rutaImagen) {
                if (!empty($imagenAntigua)) {
                    $ruta = Yii::getAlias("@app/../recursos/uploads/articulos/{$imagenAntigua}");
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                }
                $model->art_imagen = $rutaImagen;
            } else {
                $model->art_imagen = $imagenAntigua;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'art_id' => $model->art_id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($art_id)
    {
        $model = $this->findModel($art_id);
        $ruta = Yii::getAlias("@app/../recursos/uploads/{$model->art_imagen}");

        if (!empty($model->art_imagen) && file_exists($ruta)) {
            unlink($ruta);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($art_id)
    {
        if (($model = Articulo::findOne(['art_id' => $art_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El artículo solicitado no existe.');
    }

    public function actionPublish($art_id)
    {
        $model = $this->findModel($art_id);

        if ($model->art_estado === 'publicado') {
            $model->art_estado = 'borrador';
            $mensaje = 'El artículo ha sido despublicado.';
        } else {
            $model->art_estado = 'publicado';
            $mensaje = 'El artículo ha sido publicado.';
        }

        $model->updated_at = date('Y-m-d H:i:s');
        $model->updated_by = Yii::$app->user->identity->usu_id;

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', $mensaje);
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo cambiar el estado del artículo.');
        }

        return $this->redirect(['index']);
    }
}
