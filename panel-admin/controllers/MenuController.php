<?php

namespace app\controllers;

use app\models\Menu;
use app\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
     * Lists all Menu models.
     *
     * @return string
     */
    
     public function actionIndex()
     {
         // Obtener todos los menús (tanto nivel 1 como nivel 2) con la relación de rol cargada
         $menus = Menu::find()
             ->innerJoinWith('rol') // INNER JOIN en lugar de LEFT JOIN
             ->orderBy(['men_nivel' => SORT_ASC, 'men_padre_id' => SORT_ASC, 'men_posicion' => SORT_ASC]) // Ordenamos por nivel y posición
             ->all();
     
         // Organizar los menús por nivel 1 y sus correspondientes nivel 2
         $menuItems = [];
     
         // Diccionario temporal para almacenar los niveles_2 sin nivel_1 asociado
         $tempNivel2 = [];
     
         foreach ($menus as $menu) {
             // Obtener el nombre del rol directamente desde la relación cargada
             $rolNombre = $menu->rol->rol_nombre ?? 'Sin Rol Asociado';
     
             if ($menu->men_nivel === 'nivel_1') {
                 // Si es nivel 1, añadirlo directamente a $menuItems
                 $menuItems[$menu->men_id] = [
                     'nivel_1' => $menu,
                     'nivel_2' => [],
                     'rol_nombre' => $rolNombre,
                 ];
     
                 // Si existen niveles 2 temporales, agregarlos aquí
                 if (isset($tempNivel2[$menu->men_id])) {
                     $menuItems[$menu->men_id]['nivel_2'] = $tempNivel2[$menu->men_id];
     
                     // Limpiar los menús nivel_2 temporales ya asignados
                     unset($tempNivel2[$menu->men_id]);
                 }
             } elseif ($menu->men_nivel === 'nivel_2' && $menu->men_padre_id !== null) {
                 // Si el nivel 1 existe, añadimos el nivel 2 ordenado por men_posicion
                 if (isset($menuItems[$menu->men_padre_id])) {
                     $menuItems[$menu->men_padre_id]['nivel_2'][] = $menu;
                 } else {
                     // Si el nivel 1 aún no ha sido procesado, guardamos el nivel 2 temporalmente
                     $tempNivel2[$menu->men_padre_id][] = $menu;
                 }
             }
         }
     
         // Asegurar que los submenús están ordenados por `men_posicion`
         foreach ($menuItems as &$menuItem) {
             usort($menuItem['nivel_2'], function ($a, $b) {
                 return $a->men_posicion - $b->men_posicion;
             });
         }
     
         // Pasar el número total de registros a la vista junto con $menuItems
         return $this->render('index', [
             'menuItems' => $menuItems,
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
     * If creation is successful, the browser will be redirected to the 'view' page.
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
     * If update is successful, the browser will be redirected to the 'view' page.
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
     * If deletion is successful, the browser will be redirected to the 'index' page.
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
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $men_id Identificador único del menú
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($men_id)
    {
        if (($model = Menu::findOne(['men_id' => $men_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionUpdateOrder()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $orden = Yii::$app->request->post('orden');

            foreach ($orden as $item) {
                $menu = Menu::findOne($item['id']);
                if ($menu) {
                    $menu->men_posicion = $item['orden']; // Guardamos el nuevo orden en la DB
                    $menu->save();
                }
            }

            return ['status' => 'success'];
        }

        return ['status' => 'error'];
    }
    /**
     * Cambia el estado de visibilidad del menú (men_mostrar) entre 'Si' y 'No'.
     * Retorna JSON si la petición es AJAX, o redirige al index si es una llamada normal.
     */
    public function actionToggleVisibility($men_id)
    {
        $model = $this->findModel($men_id);

        // Invertir el valor
        $model->men_mostrar = ($model->men_mostrar === 'Si') ? 'No' : 'Si';
        $model->updated_by = Yii::$app->user->id ?? null;
        $model->updated_at = date('Y-m-d H:i:s');

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
