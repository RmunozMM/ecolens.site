<?php

namespace app\controllers;

use app\models\Layouts;
use app\models\LayoutSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Opcion;
use Yii;

/**
 * LayoutController implements the CRUD actions for Layouts model.
 */
class LayoutController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    

    /**
     * Lists all Layouts models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LayoutSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Layouts model.
     * @param int $lay_id Lay ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($lay_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($lay_id),
        ]);
    }

    /**
     * Creates a new Layouts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

     public function actionCreate()
     {
         $model = new Layouts();
     
         if ($this->request->isPost) {
             if ($model->load($this->request->post()) && $model->save()) {
                 // Insertar registros en la tabla colores
                 $connection = Yii::$app->getDb();
                 $layId = $model->lay_id;
     
                 // Tu consulta SQL para insertar registros en la tabla colores
                 $sql = "INSERT INTO colores (col_nombre, col_descripcion, col_valor, col_layout_id)
                         SELECT col_nombre, col_descripcion, col_valor, :layId 
                         FROM colores 
                         WHERE col_layout_id = 1";
     
                 // Ejecutar la consulta SQL con el nuevo lay_id
                 $command = $connection->createCommand($sql, [':layId' => $layId]);
                 $command->execute();

                // Crear la carpeta en /web/temas
                $this->createFolder($model->lay_nombre);
                 
     
                 return $this->redirect(['view', 'lay_id' => $model->lay_id]);
             }
         } else {
             $model->loadDefaultValues();
         }
     
         return $this->render('create', [
             'model' => $model,
         ]);
     }
     

     private function createFolder($layNombre)
     {
         // Convertir a mayúsculas
         $carpetaEnMayus = strtoupper($layNombre);
 
         // Ruta física en el servidor (carpeta 'temas' dentro de /web)
         // @webroot => la carpeta "web" de tu aplicación
         $ruta = Yii::getAlias('@webroot/temas/' . $carpetaEnMayus);
 
         // Crear si no existe
         if (!is_dir($ruta)) {
             // 0775 = permisos de lectura/escritura y 'true' para crear subdirectorios si no existen
             mkdir($ruta, 0775, true);
         }
     }

    /**
     * Updates an existing Layouts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $lay_id Lay ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($lay_id)
    {
        $model = $this->findModel($lay_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'lay_id' => $model->lay_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Layouts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $lay_id Lay ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

     
     public function actionDelete($lay_id)
     {
         // 1) Cargar el modelo (buscando por primary key)
         $model = $this->findModel($lay_id);
     
         // 2) Determinar la carpeta (nótese que usamos strtoupper para asegurar mayúsculas)
         $carpeta = Yii::getAlias('@webroot/temas/' . strtoupper($model->lay_nombre));
     
         // 3) Si la carpeta existe en el servidor, llamamos a deleteFolder()
         if (is_dir($carpeta)) {
             $this->deleteFolder($carpeta);
         }
     
         // 4) Borrar el registro en la base de datos
         $model->delete();
     
         // 5) Redirigir (por ejemplo, al index)
         return $this->redirect(['index']);
     }
     
     /**
      * Elimina de forma recursiva la carpeta y todos sus archivos/subcarpetas.
      * @param string $dir Ruta absoluta de la carpeta a eliminar.
      */
     private function deleteFolder($dir)
     {
         // Si no existe, nada que eliminar
         if (!file_exists($dir)) {
             return;
         }
     
         // Si es un archivo, lo borramos directamente
         if (!is_dir($dir)) {
             @unlink($dir);
             return;
         }
     
         // Si es un directorio, recorrer su contenido
         foreach (scandir($dir) as $item) {
             // Omitir referencias "." y ".."
             if ($item === '.' || $item === '..') {
                 continue;
             }
             // Llamada recursiva para subcarpetas/archivos
             $this->deleteFolder($dir . DIRECTORY_SEPARATOR . $item);
         }
     
         // Finalmente, eliminar la carpeta vacía
         @rmdir($dir);
     }



    /**
     * Finds the Layouts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $lay_id Lay ID
     * @return Layouts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($lay_id)
    {
        if (($model = Layouts::findOne(['lay_id' => $lay_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionTema()
    {
        // Primera consulta para obtener el valor de lay_nombre del sitio_layout
        $sitioLayout = Opcion::find()
            ->select('opc_valor')
            ->where(['opc_nombre' => 'sitio_layout'])
            ->scalar(); // Obtenemos un solo valor
    
        // Segunda consulta para obtener el registro con lay_nombre igual al valor obtenido
        $layoutSeleccionado = Layouts::find()
            ->where(['lay_nombre' => $sitioLayout])
            ->one();
    
        // Tercera consulta para obtener todos los layouts excepto el seleccionado, ordenados alfabéticamente
        $layoutsOtros = Layouts::find()
            ->where(['not', ['lay_nombre' => $sitioLayout]])
            ->orderBy(['lay_nombre' => SORT_ASC])
            ->all();
    
        // Combinar los resultados en un único array
        $layouts = [];
        if ($layoutSeleccionado !== null) {
            $layouts[] = $layoutSeleccionado;
        }
        foreach ($layoutsOtros as $layout) {
            $layouts[] = $layout;
        }
    
        return $this->render('tema', [
            'layouts' => $layouts,
        ]);
    }
    
    
    public function actionSeleccionar($lay_id)
    {
        $layout = Layouts::findOne($lay_id);

        if ($layout) {
            // Actualiza la tabla sitio_opciones
            $sitioOpcion = Sitio::findOne(['sit_nombre' => 'sitio_layout']);
            if (!$sitioOpcion) {
                $sitioOpcion = new Sitio();
                $sitioOpcion->sit_nombre = 'sitio_layout';
            }

            $sitioOpcion->sit_valor = $layout->lay_nombre;
            $sitioOpcion->save();

            // Redirige a la página de temas después de la selección
            return $this->redirect(['tema']);
        } else {
            // Maneja el caso donde el layout no se encuentra
            throw new NotFoundHttpException('El tema no se encontró.');
        }
    }

    
}
