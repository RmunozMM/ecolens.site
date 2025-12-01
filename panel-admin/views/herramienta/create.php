<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Herramienta $model */

$this->title = 'Crear Herramientas';
$this->params['breadcrumbs'][] = ['label' => 'Herramientas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="herramienta-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
