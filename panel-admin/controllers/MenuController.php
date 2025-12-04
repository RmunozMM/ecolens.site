<?php

namespace app\controllers;

use app\models\Menu;
use app\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\User;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only'  => ['index', 'view', 'create', 'update', 'delete', 'update-order', 'toggle-visibility'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario, [1, 2, 3]);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete'           => ['POST'],
                    'update-order'     => ['POST'],
                    'toggle-visibility'=> ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Menu models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Obtener todos los menús (tanto nivel 1 como nivel 2) con la relación de rol cargada
        $menus = Menu::find()
            ->innerJoinWith('rol') // INNER JOIN en lugar de LEFT JOIN
            ->orderBy([
                'men_nivel'    => SORT_ASC,
                'men_padre_id' => SORT_ASC,
                'men_posicion' => SORT_ASC
            ])
            ->all();

        // Organizar los menús por nivel 1 y sus correspondientes nivel 2
        $menuItems  = [];
        $tempNivel2 = [];

        foreach ($menus as $menu) {
            $rolNombre = $menu->rol->rol_nombre ?? 'Sin Rol Asociado';

            if ($menu->men_nivel === 'nivel_1') {
                // Si es nivel 1, añadirlo directamente
                $menuItems[$menu->men_id] = [
                    'nivel_1'   => $menu,
                    'nivel_2'   => [],
                    'rol_nombre'=> $rolNombre,
                ];

                // Si existen niveles 2 temporales, agregarlos aquí
                if (isset($tempNivel2[$menu->men_id])) {
                    $menuItems[$menu->men_id]['nivel_2'] = $tempNivel2[$menu->men_id];
                    unset($tempNivel2[$menu->men_id]);
                }
            } elseif ($menu->men_nivel === 'nivel_2' && $menu->men_padre_id !== null) {
                // Submenús
                if (isset($menuItems[$menu->men_padre_id])) {
                    $menuItems[$menu->men_padre_id]['nivel_2'][] = $menu;
                } else {
                    // El padre aún no ha sido procesado
                    $tempNivel2[$menu->men_padre_id][] = $menu;
                }
            }
        }

        // Ordenar submenús por men_posicion dentro de cada padre
        foreach ($menuItems as &$menuItem) {
            usort($menuItem['nivel_2'], function ($a, $b) {
                return $a->men_posicion - $b->men_posicion;
            });
        }
        unset($menuItem);

        return $this->render('index', [
            'menuItems'  => $menuItems,
            'totalMenus' => count($menus),
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param int $men_id Identificador único del menú
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($men_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($men_id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Menu();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'men_id' => $model->men_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * @param int $men_id Identificador único del menú
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($men_id)
    {
        $model = $this->findModel($men_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'men_id' => $model->men_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * @param int $men_id Identificador único del menú
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($men_id)
    {
        $this->findModel($men_id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * @param int $men_id Identificador único del menú
     * @return Menu
     * @throws NotFoundHttpException
     */
    protected function findModel($men_id)
    {
        if (($model = Menu::findOne(['men_id' => $men_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Actualiza el orden de los menús.
     * Espera POST['orden'] = [
     *   ['id' => 1, 'orden' => 1, 'parent_id' => 0],
     *   ['id' => 2, 'orden' => 2, 'parent_id' => 1],
     *   ...
     * ]
     */
    public function actionUpdateOrder()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost) {
            return ['status' => 'error', 'message' => 'Método no permitido'];
        }

        $orden = Yii::$app->request->post('orden', []);

        if (!is_array($orden)) {
            return ['status' => 'error', 'message' => 'Formato de datos inválido'];
        }

        foreach ($orden as $item) {
            if (!isset($item['id'], $item['orden'])) {
                continue;
            }

            $menu = Menu::findOne((int)$item['id']);
            if (!$menu) {
                continue;
            }

            $menu->men_posicion = (int)$item['orden'];

            // Si viene parent_id y es nivel_2, opcionalmente actualizamos el padre
            if ($menu->men_nivel === 'nivel_2' && isset($item['parent_id'])) {
                $menu->men_padre_id = (int)$item['parent_id'];
            }

            // Guardamos sin validar todo el modelo, solo estos campos
            $menu->save(false, ['men_posicion', 'men_padre_id']);
        }

        return ['status' => 'success'];
    }

    /**
     * Cambia el estado de visibilidad del menú (men_mostrar) entre 'Si' y 'No'.
     * Retorna JSON si la petición es AJAX, o redirige al index si es una llamada normal.
     */
    public function actionToggleVisibility($men_id)
    {
        $model = $this->findModel($men_id);

        $model->men_mostrar = ($model->men_mostrar === 'Si') ? 'No' : 'Si';
        $model->updated_by  = Yii::$app->user->id ?? null;
        $model->updated_at  = date('Y-m-d H:i:s');

        if ($model->save(false, ['men_mostrar', 'updated_by', 'updated_at'])) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => true, 'new_state' => $model->men_mostrar];
            }
            Yii::$app->session->setFlash('success', 'Visibilidad del menú actualizada correctamente.');
        } else {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => false, 'message' => 'Error al actualizar la visibilidad.'];
            }
            Yii::$app->session->setFlash('error', 'Error al actualizar la visibilidad del menú.');
        }

        return $this->redirect(['index']);
    }
}
