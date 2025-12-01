<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Formacion $model */

$this->title = 'Crear Formación';
$this->params['breadcrumbs'][] = ['label' => 'Formación Académica', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="formacion-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
