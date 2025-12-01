<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Modelo $model */

$this->title = $model->mod_id;
$this->params['breadcrumbs'][] = ['label' => 'Modelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="modelo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'mod_id' => $model->mod_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'mod_id' => $model->mod_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mod_id',
            'mod_nombre',
            'mod_version',
            'mod_archivo',
            'mod_dataset',
            'mod_precision_val',
            'mod_fecha_entrenamiento',
            'mod_estado',
            'mod_notas:ntext',
            'mod_tipo',
        ],
    ]) ?>

</div>
