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
                'only' => ['index', 'view', 'create', 'update', 'delete', 'revisar'],
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
                    'delete'  => ['GET', 'POST'],
                    // Si quisieras forzar POST en revisar, podrías agregar:
                    // 'revisar' => ['GET', 'POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel  = new DeteccionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
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

            // TimestampBehavior y BlameableBehavior se encargan de:
            // - created_at / updated_at
            // - det_obs_id / det_validado_por (según configuración del modelo)
            if ($model->save()) {

                // Subir imagen asociada
                $rutaImagen = LibreriaHelper::subirFoto($model, 'det_imagen', 'detecciones');

                if ($rutaImagen) {
                    $model->det_imagen = $rutaImagen;
                    $model->save(false, ['det_imagen']);
                }

                return $this->redirect(['view', 'det_id' => $model->det_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($det_id)
    {
        $model         = $this->findModel($det_id);
        $imagenAntigua = $model->det_imagen;

        if ($this->request->isPost && $model->load($this->request->post())) {

            // Subimos nueva imagen (si viene)
            $rutaImagen = LibreriaHelper::subirFoto($model, 'det_imagen', 'detecciones');

            if ($rutaImagen) {
                // Eliminamos imagen anterior si existe
                if (!empty($imagenAntigua)) {
                    $ruta = Yii::getAlias("@app/../recursos/uploads/detecciones/{$imagenAntigua}");
                    if (is_file($ruta)) {
                        @unlink($ruta);
                    }
                }
                $model->det_imagen = $rutaImagen;
            } else {
                // Si no se subió una nueva, mantenemos la anterior
                $model->det_imagen = $imagenAntigua;
            }

            // TimestampBehavior / BlameableBehavior se encargan de updated_at / det_validado_por
            if ($model->save()) {
                return $this->redirect(['view', 'det_id' => $model->det_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($det_id)
    {
        $model = $this->findModel($det_id);

        if (!empty($model->det_imagen)) {
            $ruta = Yii::getAlias("@app/../recursos/uploads/detecciones/{$model->det_imagen}");
            if (is_file($ruta)) {
                @unlink($ruta);
            }
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionRevisar($det_id)
    {
        $model = $this->findModel($det_id);

        // Usamos escenario 'revisar' que está definido en el modelo
        $model->scenario = 'revisar';

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            // Solo seteamos explícitamente la fecha de validación.
            // El usuario validador lo maneja BlameableBehavior.
            $model->det_validacion_fecha = date('Y-m-d H:i:s');

            if ($model->save(false, [
                'det_estado',
                'det_revision_estado',
                'det_observaciones',
                'det_validado_por',       // lo rellena el behavior
                'det_validacion_fecha',
                'updated_at',
            ])) {
                Yii::$app->session->setFlash('success', '✅ La detección fue revisada correctamente.');
                return $this->redirect(['view', 'det_id' => $model->det_id]);
            }

            Yii::$app->session->setFlash('error', '⚠️ No se pudo guardar la revisión.');
        }

        return $this->render('revisar', [
            'model' => $model,
        ]);
    }



    protected function findModel($det_id)
    {
        if (($model = Deteccion::findOne(['det_id' => $det_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La detección solicitada no existe.');
    }
}
