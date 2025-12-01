<?php

namespace app\controllers;

use app\models\Colores;
use app\models\ColorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\User;


/**
 * ColorController implements the CRUD actions for Colores model.
 */
class ColorController extends Controller
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
     * Lists all Colores models.
     *
     * @return string
     */

    // ...
    

    public function actionCms()
    {
        $colLayoutId = Yii::$app->request->get('col_layout_id');
    
        if ($colLayoutId) {
            $colores = Colores::find()
                ->where(['col_layout_id' => $colLayoutId])
                ->orderBy(['col_layout_id' => SORT_ASC])
                ->all();
        } else {
            // Solo colores donde col_layout_id sea NULL
            $colores = Colores::find()
                ->where(['col_layout_id' => null])
                ->orderBy(['col_layout_id' => SORT_ASC])
                ->all();
        }
    
        // Formatear nombres
        foreach ($colores as $color) {
            $color->col_nombre = $this->formatOptionName($color->col_nombre);
        }
    
        return $this->render('index', [
            'colores' => $colores,
        ]);
    }

public function actionIndex()
{
    // Trae SOLO la opción sitio_layout para saber el layout activo
    $layoutActivo = (new \yii\db\Query())
        ->select(['opc_valor'])
        ->from('opciones')
        ->where(['opc_nombre' => 'sitio_layout'])
        ->scalar();

    // Si no hay layout activo definido, puedes poner un valor por defecto
    if (!$layoutActivo) {
        $layoutActivo = 'Personal';
    }

    // Busca los colores SOLO del layout activo
    $colores = Colores::find()
        ->joinWith('layout')
        ->where(['layouts.lay_nombre' => $layoutActivo])
        ->orderBy(['col_layout_id' => SORT_ASC])
        ->all();

    foreach ($colores as $color) {
        $color->col_nombre = $this->formatOptionName($color->col_nombre);
    }

    return $this->render('index', [
        'colores' => $colores,
    ]);
}
     
    

    /**
     * Displays a single Colores model.
     * @param int $col_id Col ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($col_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($col_id),
        ]);
    }

    /**
     * Creates a new Colores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Colores();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'col_id' => $model->col_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Colores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $col_id Col ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($col_id = null, $col_valor = null)
    {
        Yii::info("col_id: " . $col_id);
        Yii::info("col_valor: " . $col_valor);

        if ($col_id !== null && $col_valor !== null) {
            $color = Colores::findOne($col_id);
            if ($color !== null) {
                $color->col_valor = $col_valor;
                $color->save();
            }
        }else{
            Yii::info("No se recibieron todos los valores");
        }
    
        // Redirigir a la página de origen
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Colores model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $col_id Col ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($col_id)
    {
        $this->findModel($col_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Colores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $col_id Col ID
     * @return Colores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($col_id)
    {
        if (($model = Colores::findOne(['col_id' => $col_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    private function formatOptionName($name)
    {
        $words = explode('_', $name);
        $formattedName = ucwords(implode(' ', $words));
        return $formattedName;
    }
}
