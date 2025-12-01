<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Dispositivo $model */

$this->title = 'Dispositivo #' . $model->dis_id;
$this->params['breadcrumbs'][] = ['label' => 'Dispositivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="dispositivo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'dis_id' => $model->dis_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'dis_id' => $model->dis_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'dis_id',
            'dis_tipo',
            'dis_sistema_operativo',
            'dis_navegador',
            'dis_user_agent',
            'dis_ip_origen',
            'dis_usuario_id',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>