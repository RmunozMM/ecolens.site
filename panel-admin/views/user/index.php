<?php

use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-index">

    <h2>Usuarios</h2>
    <?php if($msg!= null){?>
        <div class="alert alert-success" role="alert">
            <?php echo $msg;?>
        </div>
    <?php } ?>

    <p>
        <?= Html::a('Crear Usuarios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//           'usu_id',
            'usu_username',
            'usu_email:email',
            [
                'label' => '¿Email verificado?',
                'value' => 'usu_email_verificado',
            ], 
//          'usu_password',
//          'usu_authKey',
//          'usu_accessToken',
//          'usu_activate:boolean',  
            [
                'label' => 'Rol del usuario',
                'value' => function ($model) {
                    return $model->rol->rol_nombre;
                },
            ], 
            [
                'label' => 'Estado',
                'value' => function($model){
                        return ($model->usu_activate === "SI")? 'Activo':'Inactivo';
                }
            ],
            [
                'attribute' => 'usu_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'usu_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            CrudActionButtons::column([
                'actions' => ['view', 'update', 'delete', 'setPassword'],
                'idAttribute' => 'usu_id',
                'nombreRegistro' => 'usuario',
                'customActions' => [
                    'setPassword' => function($model, $idAttr) {
                        if ($model->usu_rol_id >= Yii::$app->user->identity->usu_rol_id) {
                            return Html::a(
                                '',
                                ['setpassword', $idAttr => $model->{$idAttr}],
                                [
                                    'class' => 'btn-action btn-set-password fa-solid fa-key',
                                    'title' => 'Cambiar contraseña',
                                ]
                            );
                        }
                        return null;
                    },
                ],

            ])
            
        ],
    ]); ?>


</div>


