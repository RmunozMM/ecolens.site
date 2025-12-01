<?php

use app\models\Habilidad;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\HabilidadSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Habilidades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="habilidad-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="d-flex justify-content-between mb-3">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Habilidad', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Habilidad',
                'exportUrl' => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Habilidad',
                'modelLabel' => 'Habilidades',
                'fieldsMap' => [
                    'Nombre' => 'hab_nombre',
                    'Nivel' => 'hab_nivel',
                    'Publicada' => 'hab_publicada',
                    'Usuario' => 'hab_usu_id',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'hab_nombre',
            [
                'attribute' => 'hab_nivel',
                'format'    => 'raw',
                'value'     => function ($model) {
                    $sliderValue = $model->hab_nivel;
                    return "
                        <div style='display: flex; align-items: center;'>
                            <input type='range' min='0' max='100' step='10' value='{$sliderValue}'
                                   class='slider-habilidad' data-id='{$model->hab_id}' 
                                   style='width: 80%; margin-right: 10px;'>
                            <span class='slider-value' style='min-width: 40px; text-align: right;'>{$sliderValue}%</span>
                        </div>
                    ";
                },
            ],
            [
                'attribute' => 'hab_publicada',
                'label'     => '¿Publicada?',
                'filter'    => ['SI' => 'SI', 'NO' => 'NO'],
                'value'     => function ($model) {
                    return $model->hab_publicada;
                },
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions' => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute' => 'hab_id',
                'nombreRegistro' => 'habilidad',
            ])
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>

<?php
$updateUrl = Url::to(['habilidad/actualizar-nivel']);
$csrfToken = Yii::$app->request->getCsrfToken();

$js = <<<JS
function inicializarSlidersHabilidad() {
    $('.slider-habilidad').off('input change');

    $('.slider-habilidad').on('input', function() {
        var nuevoNivel = $(this).val();
        $(this).siblings('.slider-value').text(nuevoNivel + '%');
    });

    $('.slider-habilidad').on('change', function() {
        var habId = $(this).data('id');
        var nuevoNivel = $(this).val();

        $.ajax({
            url: '{$updateUrl}',
            type: 'POST',
            data: {
                id: habId,
                nivel: nuevoNivel,
                _csrf: '{$csrfToken}'
            },
            success: function(response) {
                alert('Nivel actualizado correctamente');
            },
            error: function() {
                alert('Error al actualizar el nivel de la habilidad.');
            }
        });
    });
}

$(document).ready(function() {
    inicializarSlidersHabilidad();
});

$(document).on('pjax:end', function() {
    inicializarSlidersHabilidad();
});
JS;

$this->registerJs($js);
?>