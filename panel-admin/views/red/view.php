<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Redes $model */

$this->title = $model->red_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Redes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="redes-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'red_id' => $model->red_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'red_id' => $model->red_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estas seguro de querer eliminar este ítem?. Esta acción no se puede deshacer',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'red_id',
            'red_nombre',
            'red_enlace',
            'red_perfil',
            [
                'label' => 'Enlace',
                'format' => 'raw',
                'value' => function ($model) {
                    $url = "http://".$model->red_enlace."/".$model->red_perfil; // Reemplaza 'enlace' por el atributo que contiene la URL
    
                    return Html::a('Ir al sitio', $url,['target' => '_blank']);
                },
            ],
            'red_publicada',
        ],
    ]) ?>

</div>
