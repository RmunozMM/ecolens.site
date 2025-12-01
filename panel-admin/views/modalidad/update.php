<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Modalidad $model */

$this->title = 'Actualizar Modalidad: ' . $model->mod_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Modalidad de Experiencia', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mod_nombre, 'url' => ['view', 'mod_id' => $model->mod_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="modalidad-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
