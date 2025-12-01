<?php

use app\models\Redes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\RedSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Redes Sociales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between mb-3">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Red Social', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <!-- Si necesitas ExportRecordsWidget o MassUploadWidget, ponlos aquí -->
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'         => 'Ícono',
                'format'        => 'raw',
                'headerOptions' => ['class' => 'text-center'],      // centra el header
                'contentOptions'=> [
                    'class' => 'text-center align-middle',          // centra horizontal + vertical
                    // o, si prefieres usar flex:
                    // 'style' => 'display:flex; justify-content:center; align-items:center;'
                ],
                'value'         => function ($model) {
                    list($iconClass, $iconColor) = explode('|', $model->red_icono);
                    return Html::tag('i', '', [
                        'class' => $iconClass,
                        'style' => "color:{$iconColor};",
                    ]);
                },
            ],
            [
                'label'     => 'Nombre',
                'attribute' => 'red_nombre',
            ],
            [
                'label'     => 'Enlace',
                'attribute' => 'red_enlace',
                'contentOptions'=> [
                    'class' => 'text-center align-middle',       
                ],   // centra horizontal + vertical

            ],
            'red_perfil',
            'red_categoria',
            [
                'attribute' => 'red_publicada',
                'label'     => '¿Publicada?',
                'value'     => function ($model) {
                    return $model->red_publicada;
                },
                'filter'    => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            [
                'label'  => 'Enlace Completo',
                'format' => 'raw',
                                'contentOptions'=> [
                    'class' => 'text-center align-middle',       
                ],
                'value'  => function ($model) {
                    $url = "http://" . $model->red_enlace . "/" . $model->red_perfil;
                    return Html::a('Ir', $url, ['target' => '_blank']);
                },
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '<div class="gridview__actions">{update} {publicar}</div>',
                'headerOptions' => ['style' => 'max-width: 110px;'],
                'contentOptions' => [
                    'style' => 'max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                ],
                'buttons' => [
                    'update' => function($url, $model, $key) {
                        return Html::a(
                            '',
                            ['update', 'red_id' => $model->red_id],
                            [
                                'class' => 'fa-solid fa-pen btn-action btn-update',
                                'title' => 'Actualizar Red Social',
                            ]
                        );
                    },
                    'publicar' => function ($url, $model, $key) {
                        // Usamos un ícono check (publicado) o x (no publicado) en blanco sobre fondo verde o rojo
                        if ($model->red_publicada === 'SI') {
                            $iconClass  = 'fa-solid fa-circle-check';
                            $extraClass = 'btn-publish--on';  // Fondo verde
                        } else {
                            $iconClass  = 'fa-solid fa-circle-xmark';
                            $extraClass = 'btn-publish--off'; // Fondo rojo
                        }
                        return Html::a(
                            "<i class='$iconClass'></i>",
                            ['publicar', 'red_id' => $model->red_id, 'red_publicada' => $model->red_publicada],
                            [
                                'class' => "btn-action btn-publish $extraClass",
                                'title' => 'Publicar / Despublicar',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div>

<!-- Estilos basados en "Servicios", con fondo de color para el publicar -->
<style>
    .btn-action {
        margin-right: 5px;
        padding: 6px;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s ease;
        color: #fff; /* Ícono en blanco */
    }
    .btn-action:hover {
        transform: scale(1.1);
    }
    /* Botón Publicar base */
    .btn-publish {
        /* color blanco ya definido en .btn-action */
    }
    /* Publicado: fondo verde */
    .btn-publish--on {
        background-color: #186a3b;
    }
    /* No publicado: fondo rojo */
    .btn-publish--off {
        background-color: #c0392b;
    }
</style>

<!-- FontAwesome (si no está ya cargado en el layout) -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      referrerpolicy="no-referrer" />