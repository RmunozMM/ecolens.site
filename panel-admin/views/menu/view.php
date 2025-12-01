<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Menu $model */

$this->title = $model->men_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Menús', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'men_id' => $model->men_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'men_id' => $model->men_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge(
            [
                'men_id',
                'men_nombre',
                'men_url:url',
                'men_etiqueta',
                'men_mostrar',
                'men_nivel',
                'men_link_options',
                'men_target',
                [
                    'attribute' => 'men_rol_id',
                    'label' => 'Rol de seguridad',
                    'value' => $model->rol->rol_nombre ?? 'Sin Rol asignado',
                ],
                [
                    'attribute' => 'men_padre_id',
                    'label' => 'ID del Padre',
                    'value' => $model->men_padre_id ?? '-',
                ],
            ],
            AuditoriaGridColumns::getAuditoriaAttributes($model) // <-- aquí integramos automáticamente la auditoría
        ),
    ]) ?>

</div>