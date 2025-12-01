<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Asunto $model */

$this->title = 'Crear Asuntos de formulario de contacto';
$this->params['breadcrumbs'][] = ['label' => 'Asuntos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asunto-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
