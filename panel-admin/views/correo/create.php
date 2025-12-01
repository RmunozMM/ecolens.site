<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Correo $model */

$this->title = 'Create Correo';
$this->params['breadcrumbs'][] = ['label' => 'Correos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="correo-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
