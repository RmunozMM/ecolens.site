<?php

use app\models\Formacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Users;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\LibreriaHelper;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;



/** @var yii\web\View $this */
/** @var app\models\FormacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Formación Académica';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="formacion-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Formación', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Formacion',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Formacion',
                'modelLabel' => 'Formación Académica',
                'fieldsMap'  => [
                    'Institución'         => 'for_institucion',
                    'Grado/Título'        => 'for_grado_titulo',
                    'Tipo de Logro'       => 'for_tipo_logro',
                    'Categoría'           => 'for_categoria',
                    'Publicada'           => 'for_publicada',
                    'Mostrar Certificado' => 'for_mostrar_certificado',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'for_institucion',
                'label'     => 'Institución',
            ],
            [
                'attribute' => 'for_grado_titulo',
                'label'     => 'Grado o Título',
            ],
            [
                'attribute' => 'for_mostrar_certificado',
                'label'     => '¿Muestrar?',
            ],
            [
                'attribute' => 'for_fecha_inicio',
                'label'     => 'Inicio',
                'format'    => ['date', 'MM-yyyy'], // Formato MM-yyyy
            ],
            [
                'attribute' => 'for_fecha_fin',
                'label'     => 'Fin',
                'format'    => ['date', 'MM-yyyy'], // Formato MM-yyyy
            ],
            [
                'attribute' => 'for_tipo_logro',
                'filter'    => [
                    'Curso'         => 'Curso',
                    'Certificación' => 'Certificación',
                    'Doctorado'     => 'Doctorado',
                    'Enseñanza'     => 'Enseñanza',
                    'Diplomado'     => 'Diplomado',
                    'Maestría'      => 'Maestría',
                    'Licenciatura'  => 'Licenciatura',
                ],
            ],
            [
                'attribute' => 'for_categoria',
                'label'     => 'Categoría',
                'filter'    => ['Curso' => 'Curso', 'Certificación' => 'Certificación', 'Formación' => 'Formación'],
            ],
            [
                'attribute' => 'for_publicada',
                'label'     => '¿Publicada?',
                'value'     => function ($model) {
                    return $model->for_publicada;
                },
                'filter'    => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'for_id',
                'nombreRegistro' => 'formación',                
            ])
        ],
    ]); ?>

</div>