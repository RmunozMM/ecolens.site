<?php

namespace app\controllers;

use app\models\Pagina;
use app\models\PaginaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\LibreriaHelper;
use app\models\User;

use Yii;


/**
 * PaginaController implements the CRUD actions for Paginas model.
 */
class PaginaController extends Controller
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
     * Lists all Paginas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PaginaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['pag_posicion' => SORT_ASC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Paginas model.
     * @param int $pag_id ID único de la página
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($pag_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($pag_id),
        ]);
    }

    /**
     * Creates a new Paginas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    
    public function actionCreate()
    {
        $model = new Pagina();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->pag_slug = LibreriaHelper::generateSlug($model->pag_titulo);

                // Obtener el valor más alto de pag_posicion en la base de datos
                $maxPosition = Pagina::find()->max('pag_posicion');

                // Validar si el usuario tiene permisos para cambiar el modo de contenido
                if (Yii::$app->user->identity->usu_rol_id != 1) {
                    $model->pag_modo_contenido = 'autoadministrable';
                }

                // Establecer el nuevo valor de pag_posicion
                $model->pag_posicion = $maxPosition + 1;

                //Establecer el usuario
                $model->pag_autor_id = Yii::$app->user->identity->usu_id;
                

                if ($model->save()) {
                    return $this->redirect(['view', 'pag_id' => $model->pag_id]);
                } else {
                    // Mostramos errores en pantalla, manteniendo el formulario visible
                    Yii::$app->session->setFlash('error', print_r($model->getErrors(), true));
                    // Renderizamos el formulario de nuevo, mostrando los errores arriba
                    return $this->render('create', [
                        'model' => $model,
                    ]);
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
     * Updates an existing Paginas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $pag_id ID único de la página
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($pag_id)
    {
        $model = $this->findModel($pag_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            
            $model->pag_slug = LibreriaHelper::generateSlug($model->pag_titulo);

            if ($model->save()) {
                return $this->redirect(['view', 'pag_id' => $model->pag_id]);
            } else {
                Yii::$app->session->setFlash('error', print_r($model->getErrors(), true));
                // Renderizamos el formulario con errores
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }





    /**
     * Deletes an existing Paginas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $pag_id ID único de la página
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($pag_id)
    {
        $this->findModel($pag_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Paginas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $pag_id ID único de la página
     * @return Paginas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pag_id)
    {
        if (($model = Pagina::findOne(['pag_id' => $pag_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    public function actionUpdateOrder()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $order = Yii::$app->request->post('order');
    
        if ($order) {
            $allOk = true;
            $errors = [];
            foreach ($order as $item) {
                $pagina = \app\models\Pagina::findOne($item['id']);
                if ($pagina) {
                    $pagina->pag_posicion = $item['position'];
                    if (!$pagina->save(false, ['pag_posicion'])) {
                        $allOk = false;
                        $errors[] = [
                            'id' => $item['id'],
                            'errors' => $pagina->getErrors(),
                        ];
                        Yii::error("Error al guardar página con id {$item['id']}: " . json_encode($pagina->getErrors()));
                    }
                } else {
                    $allOk = false;
                    $errors[] = [
                        'id' => $item['id'],
                        'error' => 'Modelo no encontrado',
                    ];
                    Yii::error("No se encontró la página con id {$item['id']}.");
                }
            }
            if ($allOk) {
                return ['success' => true];
            } else {
                return ['success' => false, 'errors' => $errors];
            }
        }
    
        return ['success' => false, 'error' => 'No se recibió el orden.'];
    }
    
    /**
     * Acción para alternar el estado de la página (publicar/despublicar).
     * @param int $pag_id
     */
    public function actionPublish($pag_id)
    {
        $pagina = Pagina::findOne($pag_id);
        if ($pagina !== null) {
            // Alterna el estado según el valor actual
            if (strtolower($pagina->pag_estado) === 'borrador') {
                $pagina->pag_estado = 'publicado';
            } else {
                $pagina->pag_estado = 'borrador';
            }
            $pagina->save(false, ['pag_estado']);
        }

        // Redirige a la página anterior
        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionMenuPrincipal()
    {
        $paginas = Pagina::find()
            ->where(['pag_estado' => 'publicado', 'pag_mostrar_menu' => 'SI'])
            ->orderBy([
                new \yii\db\Expression("CASE 
                    WHEN pag_acceso = 'publica' THEN 0 
                    WHEN pag_acceso = 'privada' THEN 1 
                    ELSE 2 
                END"),
                'pag_posicion' => SORT_ASC
            ])
            ->all();

        return $this->renderPartial('_menu_principal', [
            'paginas' => $paginas,
        ]);
    }


    public function actionMenuPrincipalSecundario()
    {
        $paginas = Pagina::find()
            ->where(['pag_estado' => 'publicado', 'pag_mostrar_menu_secundario' => 'SI'])
            ->orderBy([
                new \yii\db\Expression("CASE 
                    WHEN pag_acceso = 'publica' THEN 0 
                    WHEN pag_acceso = 'privada' THEN 1 
                    ELSE 2 
                END"),
                'pag_posicion' => SORT_ASC
            ])
            ->all();

        return $this->renderPartial('_menu_secundario', [
            'paginas' => $paginas,
        ]);
    }
}
