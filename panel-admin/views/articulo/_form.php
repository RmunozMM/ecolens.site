<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\GaleriaButtonWidget;


use app\helpers\LibreriaHelper;


/** @var yii\web\View $this */
/** @var app\models\Articulos $model */
/** @var yii\widgets\ActiveForm $form */
?> 

<div class="articulo-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

    <div class="form-group btn_save">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
        <?= GaleriaButtonWidget::widget() ?>
    </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'art_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'art_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>
        
        <!-- Columna derecha -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">


                <?= $form->field($model, 'art_titulo')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'art_categoria_id')->dropDownList(\app\models\CategoriaArticulo::find()->select(['caa_nombre', 'caa_id'])->indexBy('caa_id')->column(), ['prompt' => 'Selecciona una categoría']) ?>

                <?= $form->field($model, 'art_estado')->dropDownList([ 'borrador' => 'Borrador', 'publicado' => 'Publicado', ], ['prompt' => '']) ?>

                <?= $form->field($model, 'art_destacado')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO', ], ['prompt' => '']) ?>

                <?= $form->field($model, 'art_notificacion')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO', ], ['prompt' => '']) ?>
                
                <?= $form->field($model, 'art_comentarios_habilitados')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO', ], ['prompt' => '']) ?>

                <?= $form->field($model, 'art_contenido')->textarea(['rows' => 10, 'id' => 'tinyMCE', 'class' => 'tinymce', 'style' => 'height: 1000px;']) ?>

                <?= $form->field($model, 'art_resumen')->textarea(['rows' => 4, 'id' => 'tinyMCE' ]) ?>

                <?= $form->field($model, 'art_etiquetas')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'art_meta_descripcion')->textarea(['rows' => 4, 'id' => 'tinyMCE', 'class' => 'tinymce']) ?>

                <?= $form->field($model, 'art_palabras_clave')->textarea(['rows' => 4, 'id' => 'tinyMCE' , 'class' => 'tinymce']) ?>

            </div>
                
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
