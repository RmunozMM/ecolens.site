<?php

use app\models\Trabajador;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\LibreriaHelper;
use app\widgets\CrudActionButtons;
use app\widgets\ManageImages\FrontWidget;
use app\helpers\AuditoriaGridColumns;



/** @var yii\web\View $this */
/** @var app\models\TrabajadorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Trabajadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trabajador-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Trabajador', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Trabajador',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Trabajador',
                'modelLabel' => 'Trabajadores',
                'fieldsMap'  => [
                    'Nombre'                => 'tra_nombre',
                    'Apellido'              => 'tra_apellido',
                    'Cédula'                => 'tra_cedula',
                    'Fecha de Nacimiento'   => 'tra_fecha_nacimiento',
                    'Género'                => 'tra_genero',
                    'Puesto'                => 'tra_puesto',
                    'Departamento'          => 'tra_departamento',
                    'Fecha de Contratación' => 'tra_fecha_contratacion',
                    'Salario'               => 'tra_salario',
                    'Email'                 => 'tra_email',
                    'Teléfono'              => 'tra_telefono',
                    'Dirección'             => 'tra_direccion',
                    'Foto de Perfil'        => 'tra_foto_perfil',
                    'Descripción'           => 'tra_descripcion',
                    'Facebook'              => 'tra_facebook',
                    'Instagram'             => 'tra_instagram',
                    'LinkedIn'              => 'tra_linkedin',
                    'TikTok'                => 'tra_tiktok',
                    'Twitter'               => 'tra_twitter',
                    'WhatsApp'              => 'tra_whatsapp',
                    'Modalidad de Contrato' => 'tra_modalidad_contrato',
                    'Publicado'             => 'tra_publicado',
                    'Estado'                => 'tra_estado',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label'     => 'Nombre',
                'attribute' => 'tra_nombre',
            ],
            [
                'label'     => 'Apellido',
                'attribute' => 'tra_apellido',
            ],
            [
                'label'     => 'Nacimiento',
                'attribute' => 'tra_fecha_nacimiento',
            ],
            [
                'label'     => 'Puesto',
                'attribute' => 'tra_puesto',
            ],
            [
                'label'     => 'Modalidad',
                'attribute' => 'tra_modalidad_contrato',
            ],
            [
                'attribute' => 'tra_publicado',
                'label'     => '¿Publicado?',
                'value'     => function ($model) {
                    return $model->tra_publicado;
                },
                'filter'    => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            [
                'attribute' => 'tra_estado',
                'label'     => 'Estado?',
                'value'     => function ($model) {
                    return $model->tra_estado;
                },
                'filter'    => ['Activo' => 'Activo', 'Inactivo' => 'Inactivo'],
            ],
            [
                'attribute' => 'tra_foto_perfil',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'tra_foto_perfil',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete' , 'duplicate'],
                'idAttribute'  => 'tra_id',
                'nombreRegistro' => 'trabajador',                
            ])
        ],
    ]); ?>

</div>