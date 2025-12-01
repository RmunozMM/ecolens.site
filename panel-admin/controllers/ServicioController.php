<?php

namespace app\controllers;

use app\models\Servicio;
use app\models\ServicioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\User;
use Yii;

//incorporo libreria nueva
use yii\web\UploadedFile;
use yii\data\Pagination;

use app\helpers\LibreriaHelper;

/**
 * ServicioController implements the CRUD actions for Servicios model.
 */
class ServicioController extends Controller
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
     * Lists all Servicios models.
     *
     * @return string
     */
    public function actionIndex()
    {


        $searchModel = new ServicioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

 
    public function actionCreate()
    {
        $model = new Servicio();
        $msg = "";
        $valida_foto = "";
    
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Ahora que los datos del formulario están cargados en el modelo,
                // generamos el slug utilizando el título
                
                $model->ser_slug = LibreriaHelper::generateSlug($model->ser_titulo);
    
                if ($model->save()) {
                    $ser_id = $model->ser_id;
                    $valida_foto = $this->subirFoto($model, $ser_id); // Pasar el ID del registro
                    if ($valida_foto != "") {
                        $model->ser_imagen = $valida_foto;
                        $model->updateAttributes(['ser_imagen']);  // Actualizar solo el campo ser_imagen en el registro
                    }
    
                    return $this->redirect(['view', 'ser_id' => $ser_id]);
                } else {
                    $msg = "Hubo un error al crear el registro";
                }
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
            'model' => $model,
            'msg' => $msg,
        ]);
    }
    
    
    

    /**
     * Displays a single Servicios model.
     * @param int $ser_id Identificador único de Artículos
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ser_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($ser_id),
        ]);
    }

    /* Permite buscar un artículo por el título en lugar del id, se usa para el sitio web fronted */
    public function actionPreview($ser_titulo)
    {
        $Servicio = Servicio::findOne(['ser_titulo' => $ser_titulo]);
        if (!$Servicio) {
            throw new NotFoundHttpException('El artículo no fue encontrado.');
        }

        return $this->render('view', [
            'model' => $Servicio,
        ]);
    }

    /**
     * Creates a new Servicios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */



    /**
     * Updates an existing Servicios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $ser_id Identificador único de Servicios
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

     public function actionUpdate($ser_id)
{
    $model = $this->findModel($ser_id);
    $msg = "";
    $valida_foto = "";

    if ($this->request->isPost) {
        if ($model->load($this->request->post())) {

            
            $model->ser_slug = LibreriaHelper::generateSlug($model->ser_titulo);

            $uploadedFile = \yii\web\UploadedFile::getInstance($model, 'ser_imagen');


            if ($uploadedFile !== null) {
                // Eliminar archivo anterior

                if (!empty($model->ser_imagen)) {
                    $rutaArchivoAnterior = LibreriaHelper::getRecursos(). 'uploads/' .  $model->ser_imagen;

                    if (file_exists($rutaArchivoAnterior)) {
                        unlink($rutaArchivoAnterior);
                    }
                }

                $valida_foto = $this->subirFoto($model, $ser_id);

                if ($valida_foto != "") {
                    $model->ser_imagen = $valida_foto;
                }
            }else {
                // Si no se ha subido un nuevo archivo, mantener el valor original del campo art_imagen
                $model->ser_imagen = $model->getOldAttribute('ser_imagen');
            }

            if ($model->save()) {
                $msg = "Registro Actualizado";
            } else {
                $msg = "Hubo un error al actualizar el registro";
            }

            return $this->render("view", ["model" => $model, "msg" => $msg]);
        }
    } else {
        $model->loadDefaultValues();
    }

    return $this->render('update', [
        'model' => $model,
        'msg' => $msg,
    ]);
}


    /**
     * Deletes an existing Servicios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $ser_id Identificador único de Artículos
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    
    public function actionDelete($ser_id)
    {
        $model = $this->findModel($ser_id);

        // Obtener la ruta de la imagen
        $rutaImagen = $model->ser_imagen;

        // Eliminar el modelo
        $model->delete();

        // Verificar si existe la imagen y eliminarla
        if (!empty($rutaImagen) && file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the Servicios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $ser_id Identificador único de Artículos
     * @return Servicios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ser_id)
    {
        if (($model = Servicio::findOne(['ser_id' => $ser_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function subirFoto(Servicio $model, $ser_id){

        
        if ($model->load($this->request->post())) {
            $model->ser_imagen = UploadedFile::getInstance($model, 'ser_imagen');
            if ($model->validate() && $model->ser_imagen) {
                $rutaArchivo = "../../recursos/uploads/servicios/" . $ser_id.".".$model->ser_imagen->extension;

                if ($model->ser_imagen->saveAs($rutaArchivo)) {
                    $model->ser_imagen = $rutaArchivo;

                    $rutaDB = explode ("uploads/" , $rutaArchivo);
                    $rutaDB = $rutaDB[1];
                    $model->ser_imagen = $rutaDB;

                    LibreriaHelper::resizeImage($rutaArchivo, $rutaArchivo, 600, 600);

                    return $rutaDB;                 
                }
            }
        }
        
        return "";
    }
}
