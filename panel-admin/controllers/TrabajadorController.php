<?php

namespace app\controllers;

use app\models\Trabajador;
use app\models\TrabajadorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\helpers\LibreriaHelper;
use app\models\User;
use Yii;

/**
 * TrabajadorController implements the CRUD actions for Trabajador model.
 */
class TrabajadorController extends Controller
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
     * Lists all Trabajadores models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TrabajadorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Trabajadores model.
     * @param int $tra_id Identificador único del trabajador
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($tra_id)
    {
        $msg = "";
        return $this->render('view', [
            'model' => $this->findModel($tra_id), "msg" => $msg,
        ]);
    }

    /**
     * Creates a new Trabajadores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    


 
    public function actionCreate()
    {
        $model = new Trabajador();
        $msg = "";
        $valida_foto = "";
    
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Ahora que los datos del formulario están cargados en el modelo,
                // generamos el slug utilizando el título
                
    
                if ($model->save()) {
                    $tra_id = $model->tra_id;
                    $valida_foto = $this->subirFoto($model, $tra_id); // Pasar el ID del registro
                    if ($valida_foto != "") {
                        $model->tra_foto_perfil = $valida_foto;
                        $model->updateAttributes(['tra_foto_perfil']);  // Actualizar solo el campo ser_imagen en el registro
                    }
    
                    return $this->redirect(['view', 'tra_id' => $tra_id]);
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
     * Updates an existing Trabajadores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $tra_id Identificador único del trabajador
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

     public function actionUpdate($tra_id)
     {
         $model = $this->findModel($tra_id);
         $msg = "";
     
         if ($this->request->isPost) {
             if ($model->load($this->request->post())) {
     
                 
     
                 $uploadedFile = \yii\web\UploadedFile::getInstance($model, 'tra_foto_perfil');
     
                 if ($uploadedFile !== null) {
                     // Eliminar archivo anterior solo si se ha subido una nueva foto
                     if (!empty($model->tra_foto_perfil)) {
                         $rutaArchivoAnterior = LibreriaHelper::getRecursos(). 'uploads/' .  $model->tra_foto_perfil;
                         if (file_exists($rutaArchivoAnterior)) {
                             unlink($rutaArchivoAnterior);
                         }
                     }
     
                     // Subir la nueva foto
                     $valida_foto = $this->subirFoto($model, $tra_id);
     
                     if ($valida_foto === null) {
                         // Error al subir la foto
                         $msg = "Hubo un error al subir la imagen.";
                     } else {
                         // La foto se subió correctamente
                         $model->tra_foto_perfil = $valida_foto;
                     }
                 } else {
                     // No se subió una nueva foto, no es necesario eliminar la anterior
                     $model->tra_foto_perfil = $model->getOldAttribute('tra_foto_perfil');
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
     * Deletes an existing Trabajadores model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $tra_id Identificador único del trabajador
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($tra_id)
    {
        $this->findModel($tra_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Trabajadores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $tra_id Identificador único del trabajador
     * @return Trabajador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($tra_id)
    {
        if (($model = Trabajador::findOne(['tra_id' => $tra_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function subirFoto(Trabajador $model, $tra_id){

        
        if ($model->load($this->request->post())) {
            $model->tra_foto_perfil = UploadedFile::getInstance($model, 'tra_foto_perfil');
            if ($model->validate() && $model->tra_foto_perfil) {
                $rutaArchivo = "../../recursos/uploads/trabajadores/" . $tra_id.".".$model->tra_foto_perfil->extension;

                if ($model->tra_foto_perfil->saveAs($rutaArchivo)) {
                    $model->tra_foto_perfil = $rutaArchivo;

                    $rutaDB = explode ("uploads/" , $rutaArchivo);
                    $rutaDB = $rutaDB[1];
                    $model->tra_foto_perfil = $rutaDB;

                    LibreriaHelper::resizeImage($rutaArchivo, $rutaArchivo, 600, 600);

                    return $rutaDB;                 
                }
            }
        }
        
        return "";
    }
}
