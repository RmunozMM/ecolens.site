<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Modalidad $model */

$this->title = 'Crear Modalidad';
$this->params['breadcrumbs'][] = ['label' => 'Modalidad de Experiencia', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modalidad-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
