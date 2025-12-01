<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ArticuloSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="articulo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'art_id') ?>

    <?= $form->field($model, 'art_titulo') ?>

    <?= $form->field($model, 'art_contenido') ?>

    <?= $form->field($model, 'art_resumen') ?>

    <?= $form->field($model, 'art_etiquetas') ?>

    <?php // echo $form->field($model, 'art_fecha_publicacion') ?>

    <?php // echo $form->field($model, 'art_destacado') ?>

    <?php // echo $form->field($model, 'art_vistas') ?>

    <?php // echo $form->field($model, 'art_likes') ?>

    <?php // echo $form->field($model, 'art_comentarios_habilitados') ?>

    <?php // echo $form->field($model, 'art_palabras_clave') ?>

    <?php // echo $form->field($model, 'art_meta_descripcion') ?>

    <?php // echo $form->field($model, 'art_slug') ?>

    <?php // echo $form->field($model, 'art_estado') ?>

    <?php // echo $form->field($model, 'art_creacion') ?>

    <?php // echo $form->field($model, 'art_modificacion') ?>

    <?php // echo $form->field($model, 'art_categoria_id') ?>

    <?php // echo $form->field($model, 'art_notificacion') ?>

    <?php // echo $form->field($model, 'art_imagen') ?>
    
    <?php echo $form->field($model, 'art_usu_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
