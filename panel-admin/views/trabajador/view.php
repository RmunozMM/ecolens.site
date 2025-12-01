<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;
use app\helpers\LibreriaHelper;



/** @var yii\web\View $this */
/** @var app\models\Trabajador $model */

$this->title = $model->tra_nombre . " " . $model->tra_apellido;
$this->params['breadcrumbs'][] = ['label' => 'Trabajadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="trabajador-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $msg ?>

    <p>
        <?= Html::a('Actualizar', ['update', 'tra_id' => $model->tra_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'tra_id' => $model->tra_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'tra_nombre',
            'tra_apellido',
            'tra_cedula',
            'tra_fecha_nacimiento',
            'tra_genero',
            'tra_puesto',
            'tra_departamento',
            'tra_fecha_contratacion',
            'tra_salario',
            'tra_email:email',
            'tra_telefono',
            'tra_direccion:ntext',
            'tra_facebook',
            'tra_instagram',
            'tra_linkedin',
            'tra_tiktok',
            'tra_twitter',
            'tra_whatsapp',
            'tra_modalidad_contrato',
            'tra_publicado',
            'tra_estado',
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
            [
                'attribute' => 'tra_descripcion',
                'format' => 'html',
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>