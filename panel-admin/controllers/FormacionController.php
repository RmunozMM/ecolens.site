<?php

namespace app\controllers;

use app\models\Formacion;
use app\models\FormacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\web\UploadedFile;
use Yii;

/**
 * FormacionController implements the CRUD actions for Formacion model.
 */
class FormacionController extends Controller
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
     * Lists all Formacion models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FormacionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Formacion model.
     * @param int $for_id ID de la formación
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($for_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($for_id),
        ]);
    }

    /**
     * Creates a new Formacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Formacion();
        
        $valida_certificado = "";
    
        if ($this->request->isPost) {
            $postData = $this->request->post();
    
            // Ajustar las fechas si es necesario
            if (strlen($postData['Formacion']['for_fecha_inicio']) == 7) {
                $postData['Formacion']['for_fecha_inicio'] .= '-01';
            }
            if (strlen($postData['Formacion']['for_fecha_fin']) == 7) {
                $postData['Formacion']['for_fecha_fin'] .= '-01';
            }
    
            // Cargar los datos en el modelo
            if ($model->load($postData)) {
    
                // Procesar el archivo PDF si se sube uno
                $uploadedFile = UploadedFile::getInstance($model, 'for_certificado');
                if ($uploadedFile !== null) {
                    // Renombrar el archivo con el título del grado
                    $nombreLimpio = preg_replace('/[^A-Za-z0-9_-]/', '', str_replace(' ', '_', $model->for_grado_titulo));
                    $rutaArchivo = "../../recursos/uploads/formacion/" . $nombreLimpio . "." . $uploadedFile->extension;
    
    
                    // Guardar el archivo
                    if ($uploadedFile->saveAs($rutaArchivo)) {
                        $rutaDB = explode("uploads/", $rutaArchivo);
                        $rutaDB = $rutaDB[1];
                        $model->for_certificado = $rutaDB;
                    }
                }
    
                // Guardar el modelo con la ruta del certificado y fechas ajustadas
                if ($model->save()) {
                    return $this->redirect(['view', 'for_id' => $model->for_id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    

    /**
     * Updates an existing Formacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $for_id ID de la formación
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */




    public function actionUpdate($for_id)
    {
        
        $model = $this->findModel($for_id);
        $msg = "";
        $valida_certificado = "";
    
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $postData = $this->request->post();
                if (strlen($postData['Formacion']['for_fecha_inicio']) == 7) {
                    $postData['Formacion']['for_fecha_inicio'] .= '-01';
                }
                if (strlen($postData['Formacion']['for_fecha_fin']) == 7) {
                    $postData['Formacion']['for_fecha_fin'] .= '-01';
                }

                $model->for_fecha_inicio = $postData['Formacion']['for_fecha_inicio'];
                $model->for_fecha_fin = $postData['Formacion']['for_fecha_fin'];
    
                $uploadedFile = \yii\web\UploadedFile::getInstance($model, 'for_certificado');
                
    
                if ($uploadedFile !== null) {
                    // Eliminar archivo anterior
                    if (!empty($model->for_certificado)) {
                        $rutaArchivoAnterior = LibreriaHelper::getRecursos(). 'uploads/' .  $model->for_certificado;
                        if (file_exists($rutaArchivoAnterior)) {
                            unlink($rutaArchivoAnterior);
                        }
                    }
    
                    $valida_certificado = $this->subirCertificado($model, $for_id);
    
                    if ($valida_certificado != "") {
                        $model->for_certificado = $valida_certificado;
                    }
                }else {
                    // Si no se ha subido un nuevo archivo, mantener el valor original del campo art_imagen
                    $model->for_certificado = $model->getOldAttribute('for_certificado');
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
     * Deletes an existing Formacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $for_id ID de la formación
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($for_id)
    {
        $this->findModel($for_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Formacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $for_id ID de la formación
     * @return Formacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($for_id)
    {
        if (($model = Formacion::findOne(['for_id' => $for_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function subirCertificado(Formacion $model, $for_id)
    {
        
        $model->for_certificado = UploadedFile::getInstance($model, 'for_certificado');
        

        $nombreLimpio = preg_replace('/[^A-Za-z0-9_-]/', '', str_replace(' ', '_', $model->for_grado_titulo));


        if ($model->validate() && $model->for_certificado) {
            $rutaArchivo = "../../recursos/uploads/formacion/" . $nombreLimpio . "." . $model->for_certificado->extension;
    
            if ($model->for_certificado->saveAs($rutaArchivo)) {
                $rutaDB = explode("uploads/", $rutaArchivo);
                $rutaDB = $rutaDB[1];
                $model->for_certificado = $rutaDB;
    
                return $rutaDB;      
            }
        }
        
        return "";
    }
    


}
