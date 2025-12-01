<?php
namespace app\controllers;

use app\models\Proyecto;
use app\models\ProyectoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\helpers\LibreriaHelper;
use app\models\User;
use Yii;

class ProyectoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only'  => ['index','view','create','update','delete'],
                'rules' => [
                    [
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => function () {
                            return User::checkRoleByUserId(
                                Yii::$app->user->identity->usu_id,
                                [1,2,3]
                            );
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class'   => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel  = new ProyectoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($pro_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($pro_id),
        ]);
    }

    public function actionCreate()
    {
        $model     = new Proyecto();
        $msg       = '';
        

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Generar slug usando instancia de Libreria
            $model->pro_slug = LibreriaHelper::generateSlug($model->pro_titulo);

            if ($model->save()) {
                // Subir y procesar foto
                $valida_foto = $this->subirFoto($model, $model->pro_id);
                if ($valida_foto !== "") {
                    $model->pro_imagen = $valida_foto;
                    $model->updateAttributes(['pro_imagen']);
                }
                return $this->redirect(['view', 'pro_id' => $model->pro_id]);
            } else {
                $msg = "Hubo un error al crear el proyecto.";
            }
        }

        return $this->render('create', [
            'model' => $model,
            'msg'   => $msg,
        ]);
    }

    public function actionUpdate($pro_id)
    {
        $model     = $this->findModel($pro_id);
        $msg       = '';
        

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Regenerar slug
            $model->pro_slug = LibreriaHelper::generateSlug($model->pro_titulo);

            // Procesar subida de nueva imagen si la hay
            $uploaded = UploadedFile::getInstance($model, 'pro_imagen');
            if ($uploaded !== null) {
                // eliminar anterior
                $old = $model->getOldAttribute('pro_imagen');
                @unlink(Yii::getAlias('@webroot') . "/recursos/uploads/{$old}");

                $valida_foto = $this->subirFoto($model, $model->pro_id);
                if ($valida_foto !== "") {
                    $model->pro_imagen = $valida_foto;
                }
            } else {
                // mantener la existente
                $model->pro_imagen = $model->getOldAttribute('pro_imagen');
            }

            if ($model->save()) {
                return $this->redirect(['view', 'pro_id' => $model->pro_id]);
            } else {
                $msg = "Hubo un error al actualizar el proyecto.";
            }
        }

        return $this->render('update', [
            'model' => $model,
            'msg'   => $msg,
        ]);
    }

    public function actionDelete($pro_id)
    {
        $model = $this->findModel($pro_id);
        $old   = $model->pro_imagen;
        $model->delete();
        @unlink(Yii::getAlias('@webroot') . "/recursos/uploads/{$old}");
        return $this->redirect(['index']);
    }

    protected function findModel($pro_id)
    {
        if (($model = Proyecto::findOne($pro_id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El proyecto solicitado no existe.');
    }

    /**
     * Sube y redimensiona la imagen de proyecto.
     * Devuelve la ruta relativa para almacenar en BD, o "" si falla.
     */
    protected function subirFoto(Proyecto $model, $pro_id)
    {
        

        // Carga la instancia UploadedFile
        $file = UploadedFile::getInstance($model, 'pro_imagen');
        if ($model->validate() && $file) {
            $ext         = $file->extension;
            $rutaTmp     = "../../recursos/uploads/proyectos/{$pro_id}.{$ext}";
            if ($file->saveAs($rutaTmp)) {
                // ruta para BD: "proyectos/ID.ext"
                $rutaDb = "proyectos/{$pro_id}.{$ext}";
                // redimensionar con Libreria
                LibreriaHelper::resizeImage($rutaTmp, $rutaTmp, 600, 600);
                return $rutaDb;
            }
        }

        return "";
    }
}