<?php

namespace app\controllers;

use Yii;
use app\models\Especie;
use app\models\EspecieSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\helpers\LibreriaHelper;

class EspecieController extends Controller
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
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new EspecieSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($esp_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($esp_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Especie();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->identity->usu_id;

            // Generar slug antes de guardar
            $model->esp_slug = $this->generateUniqueSlug($model->esp_nombre_cientifico);

            if ($model->save()) {
                $model->esp_imagen = LibreriaHelper::subirFoto($model, 'esp_imagen', 'especies');
                if ($model->esp_imagen) {
                    $model->save(false); // solo guarda la imagen
                }

                return $this->redirect(['view', 'esp_id' => $model->esp_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($esp_id)
    {
        $model = $this->findModel($esp_id);
        $imagenAntigua = $model->esp_imagen;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->usu_id;

            // Si cambió el nombre científico, regenerar el slug
            $slugNuevo = $this->generateUniqueSlug($model->esp_nombre_cientifico, $model->esp_id);
            if ($model->esp_slug !== $slugNuevo) {
                $model->esp_slug = $slugNuevo;
            }

            // Procesar imagen
            $rutaImagen = LibreriaHelper::subirFoto($model, 'esp_imagen', 'especies');
            if ($rutaImagen) {
                if (!empty($imagenAntigua)) {
                    $ruta = Yii::getAlias("@app/../recursos/uploads/especies/{$imagenAntigua}");
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                }
                $model->esp_imagen = $rutaImagen;
            } else {
                $model->esp_imagen = $imagenAntigua;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'esp_id' => $model->esp_id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($esp_id)
    {
        $model = $this->findModel($esp_id);
        $ruta = Yii::getAlias("@app/../recursos/uploads/especies/{$model->esp_imagen}");

        if (!empty($model->esp_imagen) && file_exists($ruta)) {
            unlink($ruta);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($esp_id)
    {
        if (($model = Especie::findOne(['esp_id' => $esp_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La especie solicitada no existe.');
    }

    /**
     * Genera un slug único basado en el nombre científico
     */
    private function generateUniqueSlug(string $nombreCientifico, int $idActual = null): string
    {
        // Normaliza el nombre
        $slugBase = strtolower(trim($nombreCientifico));
        $slugBase = str_replace([' ', '_'], '-', $slugBase);
        $slugBase = preg_replace('/[^a-z0-9\-]/', '', $slugBase);
        $slug = $slugBase;

        // Verifica duplicados
        $contador = 1;
        while (Especie::find()
            ->where(['esp_slug' => $slug])
            ->andFilterWhere(['!=', 'esp_id', $idActual])
            ->exists()
        ) {
            $slug = $slugBase . '-' . ++$contador;
        }

        return $slug;
    }
}