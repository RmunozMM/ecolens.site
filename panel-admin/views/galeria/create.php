<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Galerias $model */
/** @var string|null $galTipoRegistro */
/** @var int|null $galIdRegistro */


$this->title = 'Create Galerias';
$this->params['breadcrumbs'][] = ['label' => 'Galerias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$tipo_registro = $_GET["tipo_registro"];
$id_registro = $_GET["id"];

?>
<div class="galerias-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tipo_registro' => $tipo_registro,
        'id_registro' => $id_registro,
    ]) ?>

</div>
