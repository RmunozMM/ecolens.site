<?php

namespace app\controllers;

use Yii;
use app\models\Deteccion;
use app\models\DeteccionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\helpers\LibreriaHelper;

class DeteccionController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new DeteccionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($det_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($det_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Deteccion();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->identity->usu_id;

            if ($model->save()) {
                $model->det_imagen = LibreriaHelper::subirFoto($model, 'det_imagen', 'detecciones');

                if ($model->det_imagen) {
                    $model->save(false); // Guarda imagen solamente
                }

                return $this->redirect(['view', 'det_id' => $model->det_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($det_id)
    {
        $model = $this->findModel($det_id);
        $imagenAntigua = $model->det_imagen;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->usu_id;

            $rutaImagen = LibreriaHelper::subirFoto($model, 'det_imagen', 'detecciones');

            if ($rutaImagen) {
                if (!empty($imagenAntigua)) {
                    $ruta = Yii::getAlias("@app/../recursos/uploads/detecciones/{$imagenAntigua}");
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                }
                $model->det_imagen = $rutaImagen;
            } else {
                $model->det_imagen = $imagenAntigua;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'det_id' => $model->det_id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($det_id)
    {
        $model = $this->findModel($det_id);
        $ruta = Yii::getAlias("@app/../recursos/uploads/detecciones/{$model->det_imagen}");

        if (!empty($model->det_imagen) && file_exists($ruta)) {
            unlink($ruta);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($det_id)
    {
        if (($model = Deteccion::findOne(['det_id' => $det_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La detección solicitada no existe.');
    }
public function actionRevisar($det_id)
{
    $model = $this->findModel($det_id);

    // Definimos explícitamente que este flujo no crea, solo revisa/valida
    $model->scenario = 'revisar';

    if (Yii::$app->request->isPost) {
        if ($model->load(Yii::$app->request->post())) {
            // Campos que sí se pueden actualizar durante la revisión
            $model->det_validado_por = Yii::$app->user->id ?? null;
            $model->det_validacion_fecha = date('Y-m-d H:i:s');

            if ($model->save(false, [
                'det_estado',
                'det_revision_estado',
                'det_observaciones',
                'det_validado_por',
                'det_validacion_fecha',
                'updated_at'
            ])) {
                Yii::$app->session->setFlash('success', '✅ La detección fue revisada correctamente.');
                return $this->redirect(['view', 'det_id' => $model->det_id]);
            } else {
                Yii::$app->session->setFlash('error', '⚠️ No se pudo guardar la revisión.');
            }
        }
    }

    return $this->render('revisar', [
        'model' => $model,
    ]);
}
}