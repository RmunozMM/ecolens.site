<?php

namespace app\controllers;

use Yii;
use app\models\Taxonomia;
use app\models\TaxonomiaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\helpers\LibreriaHelper;

class TaxonomiaController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel = new TaxonomiaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($tax_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($tax_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Taxonomia();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->identity->usu_id;

            // Generar slug automáticamente si viene vacío
            if (empty($model->tax_slug)) {
                $base = $model->tax_nombre_comun ?: $model->tax_nombre;
                $model->tax_slug = LibreriaHelper::generateSlug($base);
            }

            if ($model->save()) {
                $model->tax_imagen = LibreriaHelper::subirFoto($model, 'tax_imagen', 'taxonomias');
                if ($model->tax_imagen) {
                    $model->save(false); // Guarda solo imagen
                }

                return $this->redirect(['view', 'tax_id' => $model->tax_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($tax_id)
    {
        $model = $this->findModel($tax_id);
        $imagenAntigua = $model->tax_imagen;
        $slugAntiguo   = $model->tax_slug;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->usu_id;

            // Regenerar slug si ha cambiado el nombre y slug estaba vacío
            if (empty($model->tax_slug) || $slugAntiguo !== $model->tax_slug) {
                $base = $model->tax_nombre_comun ?: $model->tax_nombre;
                $model->tax_slug = LibreriaHelper::generateSlug($base);
            }

            $rutaImagen = LibreriaHelper::subirFoto($model, 'tax_imagen', 'taxonomias');

            if ($rutaImagen) {
                if (!empty($imagenAntigua)) {
                    $ruta = Yii::getAlias("@app/../recursos/uploads/taxonomias/{$imagenAntigua}");
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                }
                $model->tax_imagen = $rutaImagen;
            } else {
                $model->tax_imagen = $imagenAntigua;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'tax_id' => $model->tax_id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($tax_id)
    {
        $model = $this->findModel($tax_id);
        $ruta = Yii::getAlias("@app/../recursos/uploads/taxonomias/{$model->tax_imagen}");

        if (!empty($model->tax_imagen) && file_exists($ruta)) {
            unlink($ruta);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($tax_id)
    {
        if (($model = Taxonomia::findOne(['tax_id' => $tax_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La taxonomía solicitada no existe.');
    }
 
    
    public function getCreatedByUser()
    {
        return $this->hasOne(\app\models\User::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(\app\models\User::class, ['usu_id' => 'updated_by']);
    }
}