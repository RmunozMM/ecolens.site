<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\helpers\LibreriaHelper;


/** @var yii\web\View $this */
/** @var app\models\Clientes $model */

$this->title = $model->cli_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="cliente-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'cli_id' => $model->cli_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'cli_id' => $model->cli_id], [
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
            'cli_id',
            'cli_nombre',
            'cli_email:email',
            'cli_telefono',
            'cli_direccion',
            'cli_estado',
            'cli_publicado',
            'cli_destacado',
            [
                'attribute' => 'cli_logo',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'cli_logo',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            
        ],
    ]) ?>

</div>
