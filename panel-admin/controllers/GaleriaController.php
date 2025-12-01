<?php

namespace app\controllers;

use app\models\Galerias;
use app\models\GaleriaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ImagenesGaleria; // Asegúrate de importar el modelo ImagenesGaleria aquí
use Yii;
use app\models\User;

use app\helpers\LibreriaHelper;

/**
 * GaleriaController implements the CRUD actions for Galerias model.
 */
class GaleriaController extends Controller
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
     * Lists all Galerias models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GaleriaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        
        // Obtener los modelos de Galerias con las imágenes asociadas cargadas
        $galerias = $dataProvider->query->with('imagenesGaleria')->all();

        
        // Cargar las imágenes asociadas a cada modelo de Galerias
        foreach ($galerias as $galeria) {
            $galeria->imagenesGaleria;
        }
    
        // Registra un mensaje informativo en el log
        Yii::info('Los datos se han cargado correctamente', 'app.controllers.GaleriaController');
    
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'galerias' => $galerias, // Pasar los modelos de Galerias a la vista con imágenes cargadas
        ]);
    }
    
    

    /**
     * Displays a single Galerias model.
     * @param int $gal_id Identificador único de la galería
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */


    public function actionView($gal_id)
    {
        // Realizar la consulta usando el modelo ImagenesGaleria y el parámetro gal_id
        $imagenes = ImagenesGaleria::find()->where(['img_gal_id' => $gal_id])->all();

        return $this->render('view', [
            'model' => $this->findModel($gal_id),
            'imagenes' => $imagenes, // Pasar los resultados de la consulta a la vista
        ]);
    }


    /**
     * Creates a new Galerias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    
    
     public function actionCreate($tipo_registro = null, $id = null)
     {
         // Verificar si se han recibido los parámetros correctamente
         if ($tipo_registro !== null && $id !== null) {
             // Generar el título automáticamente
             $titulo = "Galería del Tipo de Registro: $tipo_registro con ID = $id";
     
             // Crear el modelo con los valores de gal_img_id y gal_id_registro
             $model = new Galerias([
                 'gal_titulo' => $titulo,
                 'gal_id_registro' => $id, // Asignar el valor de gal_img_id
                 'gal_tipo_registro' => $tipo_registro // Asignar el valor de gal_id_registro
             ]);
     
             // Guardar el modelo
             if ($model->save()) {
                 // Obtener el ID recién creado
                 $gal_id = $model->gal_id;
     
                 // Redirigir a la acción de Imágenes-Galería con el parámetro img_gal_id
                 return $this->redirect(['imagen-galeria/create', 'gal_id' => $gal_id ]);
             }
         }
     
         // Si llega a este punto, algo salió mal, redirigir a alguna otra acción o mostrar un mensaje de error
         return $this->redirect(['error']);
     }
     

    
    /**
     * Updates an existing Galerias model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $gal_id Identificador único de la galería
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($gal_tipo_registro = null, $gal_id_registro = null, $gal_id = null)
    {
        // Realizar la consulta usando el modelo ImagenesGaleria y el parámetro gal_id
        $imagenes = ImagenesGaleria::find()->where(['img_gal_id' => $gal_id])->all();


        if ($gal_tipo_registro !== null && $gal_id_registro !== null) {
            $model = $this->findModelByTipoRegistroId($gal_tipo_registro, $gal_id_registro);
        } elseif ($gal_id !== null) {
            $model = $this->findModel($gal_id);
        } else {
            // No se proporcionaron los parámetros necesarios, redirigir o manejar el error según sea necesario
            // Por ejemplo, redirigir a una página de error con un mensaje adecuado
            return $this->redirect(['error', 'message' => 'Parámetros incorrectos']);
        }
    
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'gal_id' => $model->gal_id]);
        }
    
        return $this->render('update', [
            'model' => $model,
            'imagenes' => $imagenes,
        ]);
    }
    
    protected function findModelByTipoRegistroId($tipo_registro, $id_registro)
    {
        $model = Galerias::findOne(['gal_tipo_registro' => $tipo_registro, 'gal_id_registro' => $id_registro]);
    
        if ($model === null) {
            // Manejar el caso cuando la galería no se encuentra, por ejemplo, redirigir a una página de error
            return $this->redirect(['error', 'message' => 'Galería no encontrada']);
        }
    
        return $model;
    }
    

    /**
     * Deletes an existing Galerias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $gal_id Identificador único de la galería
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */


     public function actionDelete($gal_id)
{
    // Buscar la galería por su ID
    $galeria = $this->findModel($gal_id);

    // Verificar si se encontró la galería
    if ($galeria) {
        // Recuperar imágenes asociadas a la galería
        $imagenes = ImagenesGaleria::find()->where(['img_gal_id' => $gal_id])->all();

        // Variable para verificar si todas las imágenes se eliminaron correctamente
        $todasLasImagenesEliminadas = true;

        // Iterar y eliminar imágenes del sistema de archivos y de la tabla ImagenesGaleria
        foreach ($imagenes as $imagen) {
            // Construir la ruta del archivo en el sistema de archivos
            $rutaArchivo = "../../recursos/" . 'uploads' .  "/" . $imagen->img_ruta;

            // Verificar si el archivo existe antes de intentar eliminarlo
            if (file_exists($rutaArchivo)) {
                // Eliminar la imagen del sistema de archivos
                if (!unlink($rutaArchivo)) {
                    // No se pudo eliminar la imagen
                    Yii::error('No se pudo eliminar la imagen: ' . $rutaArchivo, 'app.controllers.TuControlador');
                    $todasLasImagenesEliminadas = false;
                }
            }

            // Eliminar la entrada en la tabla ImagenesGaleria
            if (!$imagen->delete()) {
                // No se pudo eliminar la entrada en la tabla ImagenesGaleria
                Yii::error('No se pudo eliminar la entrada de imagen de la base de datos.', 'app.controllers.TuControlador');
                $todasLasImagenesEliminadas = false;
            }
        }

        // Si todas las imágenes se eliminaron correctamente, eliminar la galería
        if ($todasLasImagenesEliminadas) {
            // Eliminar la galería
            if ($galeria->delete()) {
                Yii::info('Galería eliminada de la base de datos.', 'app.controllers.TuControlador');
            } else {
                // No se pudo eliminar la galería
                Yii::error('No se pudo eliminar la galería de la base de datos.', 'app.controllers.TuControlador');
            }

            // Redirigir a la página de índice
            return $this->redirect(['index']);
        } else {
            // No se pudieron eliminar todas las imágenes
            Yii::error('No se pudieron eliminar todas las imágenes asociadas a la galería.', 'app.controllers.TuControlador');
            // Puedes manejar este caso según tus necesidades, como mostrar un mensaje de error.
            // Por ejemplo, puedes redirigir a una página de error con un mensaje adecuado.
            return $this->redirect(['error']);
        }
    } else {
        // La galería no se encontró
        Yii::error('La galería no se encontró.', 'app.controllers.TuControlador');
        // Puedes manejar este caso según tus necesidades, como lanzar una excepción o mostrar un mensaje de error.
        // Por ejemplo, puedes redirigir a una página de error con un mensaje adecuado.
        return $this->redirect(['error']);
    }
}


    /**
     * Finds the Galerias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $gal_id Identificador único de la galería
     * @return Galerias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($gal_id)
    {
        if (($model = Galerias::findOne(['gal_id' => $gal_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
}
