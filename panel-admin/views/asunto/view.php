<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Asunto $model */

$this->title = $model->asu_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Asuntos de formulario de Contacto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="asuntos-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'asu_id' => $model->asu_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'asu_id' => $model->asu_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estas seguro de querer eliminar este ítem? Esta acción no se puede deshacer',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'asu_id',
            'asu_nombre',
            'asu_publicado',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model))
    ]) ?>

</div>