<?php

use app\models\Herramienta;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\HerramientaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Herramientas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="herramienta-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="d-flex justify-content-between mb-3">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Herramienta', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Herramienta',
                'exportUrl' => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Herramienta',
                'modelLabel' => 'Herramientas',
                'fieldsMap' => [
                    'Nombre' => 'her_nombre',
                    'Nivel' => 'her_nivel',
                    'Publicada' => 'her_publicada',
                    'Usuario' => 'her_usu_id',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'her_nombre',
            [
                'attribute' => 'her_nivel',
                'format' => 'raw',
                'value' => function ($model) {
                    $sliderValue = $model->her_nivel;
                    return "
                        <div style='display: flex; align-items: center;'>
                            <input type='range' min='0' max='100' step='10' value='{$sliderValue}' 
                                   class='slider-herramienta' data-id='{$model->her_id}' 
                                   style='width: 80%; margin-right: 10px;'>
                            <span class='slider-value' style='min-width: 40px; text-align: right;'>{$sliderValue}%</span>
                        </div>
                    ";
                },
            ],
            [
                'attribute' => 'her_publicada',
                'label' => '¿Publicada?',
                'filter' => ['SI' => 'SI', 'NO' => 'NO'],
                'value' => function ($model) {
                    return $model->her_publicada;
                },
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions' => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute' => 'her_id',
                'nombreRegistro' => 'herramienta',
            ])
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>

<?php
$updateUrl = Url::to(['herramienta/actualizar-nivel']);
$csrfToken = Yii::$app->request->getCsrfToken();

$js = <<<JS
function inicializarSlidersHerramienta() {
    $('.slider-herramienta').off('input change');

    $('.slider-herramienta').on('input', function() {
        var nuevoNivel = $(this).val();
        $(this).siblings('.slider-value').text(nuevoNivel + '%');
    });

    $('.slider-herramienta').on('change', function() {
        var herId = $(this).data('id');
        var nuevoNivel = $(this).val();

        $.ajax({
            url: '{$updateUrl}',
            type: 'POST',
            data: {
                id: herId,
                nivel: nuevoNivel,
                _csrf: '{$csrfToken}'
            },
            success: function(response) {
                alert('Nivel actualizado correctamente');
            },
            error: function() {
                alert('Error al actualizar el nivel de la herramienta.');
            }
        });
    });
}

$(document).ready(function() {
    inicializarSlidersHerramienta();
});

$(document).on('pjax:end', function() {
    inicializarSlidersHerramienta();
});
JS;

$this->registerJs($js);
?>