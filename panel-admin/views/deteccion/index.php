<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\widgets\ManageImages\FrontWidget;
use app\helpers\AuditoriaGridColumns;
use app\models\Deteccion;

$this->title = 'Detecciones';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="deteccion-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between mb-3">
        <?php /*
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Detección', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        */ ?>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Deteccion',
                'exportUrl'  => ['export/index'],
            ]) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 🆔 ID visible con enlace al detalle público
            [
                'attribute' => 'det_id',
                'label' => 'ID',
                'format' => 'raw',
                'value' => fn($model) =>
                    Html::a(
                        "#{$model->det_id}",
                        "https://ecolens.site/sitio/web/detalle-deteccion/{$model->det_id}",
                        [
                            'target' => '_blank',
                            'title'  => 'Ver detalle público',
                            'data-pjax' => '0',
                        ]
                    ),
                'contentOptions' => ['style' => 'white-space:nowrap;'],
            ],

            // 🖼️ Imagen procesada
            [
                'attribute' => 'det_imagen',
                'label' => 'Imagen',
                'format' => 'raw',
                'value' => fn($model) => FrontWidget::widget([
                    'model' => $model,
                    'atributo' => 'det_imagen',
                    'htmlOptions' => [
                        'style'   => 'max-width: 80px; border-radius:4px;',
                        'loading' => 'lazy',
                    ],
                ]),
            ],

            // 🔢 Confianza IA
            [
                'attribute' => 'det_confianza_router',
                'label' => 'Conf. Router',
                'format' => ['decimal', 4],
                'contentOptions' => ['class' => 'text-end'],
            ],
            [
                'attribute' => 'det_confianza_experto',
                'label' => 'Conf. Experto',
                'format' => ['decimal', 4],
                'contentOptions' => ['class' => 'text-end'],
            ],

            // 🧬 Taxonomía con enlace + filtro por texto
            [
                'attribute' => 'tax_nombre',
                'label' => 'Taxonomía',
                'format' => 'raw',
                'value' => fn($model) =>
                    $model->taxonomia
                        ? Html::a(
                            Html::encode($model->taxonomia->tax_nombre),
                            ['taxonomia/view', 'tax_id' => $model->taxonomia->tax_id],
                            [
                                'title' => 'Ver grupo taxonómico',
                                'data-pjax' => '0',
                            ]
                        )
                        : Html::tag('span', '(no asignada)', ['class' => 'text-muted']),
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Buscar grupo...',
                ],
            ],

            // 🐾 Especie con enlace + filtro por texto
            [
                'attribute' => 'esp_nombre_cientifico',
                'label' => 'Especie',
                'format' => 'raw',
                'value' => fn($model) =>
                    $model->especie
                        ? Html::a(
                            Html::encode($model->especie->esp_nombre_cientifico),
                            ['especie/view', 'esp_id' => $model->especie->esp_id],
                            [
                                'title' => 'Ver especie',
                                'data-pjax' => '0',
                            ]
                        )
                        : Html::tag('span', '(no asignada)', ['class' => 'text-muted']),
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Buscar especie...',
                ],
            ],

            // 🔖 Estado general
            [
                'attribute' => 'det_estado',
                'label' => 'Estado',
                'value' => fn($model) => $model->displayEstado(),
                'filter' => Deteccion::optsDetEstado(),
                'contentOptions' => function ($model) {
                    $color = match ($model->det_estado) {
                        'validada'  => '#d4edda',
                        'rechazada' => '#f8d7da',
                        default     => '#fff3cd',
                    };
                    return ['style' => "background-color: {$color};"];
                },
            ],

            // 🧾 Estado de revisión
            [
                'attribute' => 'det_revision_estado',
                'label' => 'Revisión',
                'value' => fn($model) => $model->displayRevision(),
                'filter' => Deteccion::optsDetRevision(),
            ],

            // ⭐ Feedback del observador (like / dislike / sin respuesta)
            [
                'attribute' => 'det_feedback_usuario',
                'label'     => 'Feedback usuario',
                'format'    => 'raw',
                'value'     => function ($model) {
                    $v = $model->det_feedback_usuario;

                    if ($v === 'like') {
                        return '<span class="label label-success">'
                             . '<i class="fa fa-thumbs-up"></i> Coincide'
                             . '</span>';
                    }

                    if ($v === 'dislike') {
                        return '<span class="label label-danger">'
                             . '<i class="fa fa-thumbs-down"></i> No coincide'
                             . '</span>';
                    }

                    return '<span class="label label-default">Sin respuesta</span>';
                },
                'filter' => [
                    'like'    => 'Coincide',
                    'dislike' => 'No coincide',
                ],
                'contentOptions' => [
                    'style' => 'white-space:nowrap; text-align:center;',
                ],
            ],

            // 👤 Observador con filtro por texto
            [
                'attribute' => 'obs_nombre',
                'label' => 'Observador',
                'value' => fn($model) =>
                    $model->observador
                        ? $model->observador->obs_nombre
                        : '(anónimo)',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Buscar observador...',
                ],
            ],

            // 📅 Fechas
            [
                'attribute' => 'det_fecha',
                'label' => 'Fecha detección',
                'format' => ['datetime', 'php:d-m-Y H:i'],
                'contentOptions' => ['style' => 'white-space:nowrap;'],
            ],

            AuditoriaGridColumns::createdAt(),

            // ⚙️ Acciones
            CrudActionButtons::column([
                'actions' => ['revisar', 'delete'],
                'idAttribute' => 'det_id',
                'nombreRegistro' => 'detección',
                'customActions' => [
                    'revisar' => function ($model, $idAttr) {
                        $id = $model->{$idAttr};
                        return Html::a(
                            '',
                            ['revisar', $idAttr => $id],
                            [
                                'class' => 'btn-action btn-update fa-solid fa-clipboard-check text-success',
                                'title' => 'Revisar detección',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
            ]),
        ],
    ]); ?>
</div>
