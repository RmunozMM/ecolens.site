<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pagina $model */

$this->title = 'Actualizar Página: ' . $model->pag_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Páginas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pag_titulo, 'url' => ['view', 'pag_id' => $model->pag_id]];
$this->params['breadcrumbs'][] = 'Actualizar Página';
?>
<div class="pagina-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
