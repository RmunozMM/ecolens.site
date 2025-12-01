<?php

namespace app\controllers;

use app\models\Opcion;
use app\models\OpcionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * OpcionController implements the CRUD actions for Opcion model.
 */
class OpcionController extends Controller
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
     * Lists all Opcion models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OpcionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Opcion model.
     * @param int $opc_id Opc ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($opc_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($opc_id),
        ]);
    }

    /**
     * Creates a new Opcion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Opcion();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'opc_id' => $model->opc_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Opcion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $opc_id Opc ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($opc_id)
    {
        $model = $this->findModel($opc_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'opc_id' => $model->opc_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Opcion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $opc_id Opc ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

     /*
    public function actionDelete($opc_id)
    {
        $this->findModel($opc_id)->delete();

        return $this->redirect(['index']);
    }
    */

    /**
     * Finds the Opcion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $opc_id Opc ID
     * @return Opcion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($opc_id)
    {
        if (($model = Opcion::findOne(['opc_id' => $opc_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPublicar($id, $valor)
    {
        // Asegúrate de que solo acepte GET o POST si es necesario
        // Puedes agregarlo en el VerbFilter si quieres más seguridad

        // Validar valor recibido
        if ($valor === 'yes') {
            $nuevoValor = 'no';
        } elseif ($valor === 'no') {
            $nuevoValor = 'yes';
        } else {
            throw new \yii\web\BadRequestHttpException('Valor de sitio_online inválido');
        }

        // Buscar la opción en la tabla opciones
        $opcion = Opcion::findOne($id);
        if ($opcion && $opcion->opc_nombre === 'sitio_online') {
            $opcion->opc_valor = $nuevoValor;
            if ($opcion->save(false)) { // Si quieres omitir validación, usa save(false)
                Yii::$app->session->setFlash('success', 'Estado del sitio actualizado correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo actualizar el estado del sitio: ' . json_encode($opcion->getErrors()));
            }
        } else {
            throw new \yii\web\NotFoundHttpException('Opción sitio_online no encontrada.');
        }

        // Redirigir a la página de origen
        return $this->redirect(Yii::$app->request->referrer);
    }



    public function actionDebug($id, $valor)
    {
        if (!in_array($valor, ['yes', 'no'])) {
            throw new \yii\web\BadRequestHttpException('Valor de debug inválido');
        }

        $opcion = Opcion::findOne($id);
        if ($opcion && $opcion->opc_nombre === 'debug') {
            $opcion->opc_valor = $valor;
            if ($opcion->save(false)) {
                Yii::$app->session->setFlash('success', 'Modo debug actualizado correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo actualizar el modo debug: ' . json_encode($opcion->getErrors()));
            }
        } else {
            throw new \yii\web\NotFoundHttpException('Opción debug no encontrada.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionEditarValor()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $valor = Yii::$app->request->post('valor');

        $model = Opcion::findOne($id);
        if ($model) {
            $model->opc_valor = $valor;
            if ($model->save(false)) {
                return ['success' => true];
            }
        }
        return ['success' => false];
    }


    public function actionCms()
    {
        // Ahora buscamos opciones relacionadas con colores CMS (opc_cat_id = 4)
        $coloresCms = Opcion::find()
            ->where(['opc_cat_id' => 4])
            ->orderBy(['opc_nombre' => SORT_ASC])
            ->all();


        // Retorna a la vista
        return $this->render('cms', [
            'coloresCms' => $coloresCms,
        ]);
    }


    public function actionUpdateColor($opc_id = null, $opc_valor = null)
    {
        Yii::info("opc_id: " . $opc_id);
        Yii::info("opc_valor: " . $opc_valor);

        if ($opc_id !== null && $opc_valor !== null) {
            // Buscamos la opción por su PK
            $opcion = Opcion::findOne($opc_id);

            if ($opcion !== null) {
                // Asignamos el nuevo valor
                $opcion->opc_valor = $opc_valor;
                // Salvamos sin validación (asumimos que ya es un hex válido)
                if ($opcion->save(false)) {
                    Yii::info("Opción #{$opc_id} actualizada correctamente.");
                } else {
                    Yii::error("Error al guardar Opción #{$opc_id}: " . json_encode($opcion->errors));
                }
            } else {
                Yii::warning("No se encontró ninguna Opción con id = {$opc_id}");
            }
        } else {
            Yii::info("actionUpdate invocada sin todos los parámetros necesarios.");
        }

        // Al finalizar, volvemos a la página que llamó a este update
        return $this->redirect(Yii::$app->request->referrer);
    }

}
