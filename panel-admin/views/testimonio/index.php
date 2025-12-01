<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\helpers\LibreriaHelper;
use app\widgets\CrudActionButtons;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\TestimonioSearch $searchModel */



$this->title = 'Testimonios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testimonio-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between mb-3">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Testimonio', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\\models\\Testimonio',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\\models\\Testimonio',
                'modelLabel' => 'Testimonios',
                'fieldsMap'  => [
                    'Nombre'         => 'tes_nombre',
                    'Cargo'          => 'tes_cargo',
                    'Empresa'        => 'tes_empresa',
                    'Testimonio'     => 'tes_testimonio',
                    'Imagen'         => 'tes_imagen',
                    'Orden'          => 'tes_orden',
                    'Estado'         => 'tes_estado',
                    'Slug'           => 'tes_slug',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\\grid\\SerialColumn'],
            'tes_nombre',
            'tes_empresa',
            'tes_cargo',
            'tes_slug',
            [
                'attribute' => 'tes_estado',
                'label' => 'Estado',
            ],
            [
                'attribute' => 'tes_testimonio',
                'format' => 'html',
                'contentOptions' => ['style' => 'max-width: 400px; white-space: normal;'],
            ],
            [
                'attribute' => 'tes_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'tes_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            [
                'label' => 'Orden',
                'format' => 'raw',
                'value' => function($model) {
                    return '<i class="fa-solid fa-arrows-alt move-handle" data-id="' . $model->tes_id . '"></i>';
                },
                'contentOptions' => ['class' => 'orden-cell text-center'],
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions' => ['view', 'update', 'delete'],
                'idAttribute' => 'tes_id',
                'nombreRegistro' => 'testimonio',
            ]),
        ],
    ]); ?>
</div>

<?php 
$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
?>

<script>
$(function () {
    const container = $("tbody");
    container.sortable({
        handle: ".move-handle",
        items: "tr",
        update: function () {
            const orden = [];
            container.find("tr").each(function (index) {
                const id = $(this).find(".move-handle").data("id");
                if (id) {
                    orden.push({ id: id, orden: index + 1 });
                }
            });

            $.ajax({
                url: "<?= Url::to(['testimonio/update-order']) ?>",
                type: "POST",
                data: { orden: orden },
                success: function (response) {
                    console.log("Orden actualizado", response);
                },
                error: function () {
                    alert("Error al guardar el nuevo orden.");
                }
            });
        }
    }).disableSelection();
});
</script>

<style>
.orden-cell {
    width: 50px;
    text-align: center;
}
.move-handle {
    cursor: grab;
}
.move-handle:active {
    cursor: grabbing;
}
</style>
