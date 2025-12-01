<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Paginas $model */

$this->title = 'Crear Páginas';
$this->params['breadcrumbs'][] = ['label' => 'Paginas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagina-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
