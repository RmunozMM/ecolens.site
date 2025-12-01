<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\ManageImages\BackWidget;

/** @var yii\web\View      $this */
/** @var app\models\Media  $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="media-form">

    <?php if ($msg ?? false): ?>
        <div class="alert alert-success" role="alert"><?= $msg ?></div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <!-- Botón guardar -->
        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'med_ruta')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'med_ruta',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- Columna derecha: datos -->
        <div class="col-md-8 border">
            <div style="padding:10px 0">

                <?= $form->field($model,'med_tipo')->dropDownList(
                        ['site'=>'Sitio','entidad'=>'Entidad'],
                        ['prompt'=>'Selecciona el tipo de medio','id'=>'media-med_tipo']
                ) ?>

                <?= $form->field($model,'med_entidad')->textInput([
                        'maxlength'=>true,'id'=>'media-med_entidad'
                ]) ?>

                <?= $form->field($model,'med_nombre')->textInput([
                        'maxlength'=>true,'id'=>'media-med_nombre'
                ]) ?>

                <?= $form->field($model,'med_descripcion')->textInput(['maxlength'=>true]) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
/* --- JS --- */
$this->registerJs(<<<'JS'
//---------------------------------- utilidades
function slug(str){
  return str.toLowerCase()
            .replace(/[^a-z0-9]+/g,'_')
            .replace(/^_|_$/g,'');
}

function refreshNombreAuto(){
  var tipo    = $('#media-med_tipo').val();
  var raw     = $('#media-med_entidad').val();
  var entidad = slug(raw);

  // Sólo reemplazar si está vacío
  if(tipo === 'entidad' && $('#media-med_nombre').val().trim() === '') {
      var nombre = entidad ? 'imagen_'+entidad : '';
      $('#media-med_nombre').val(nombre);
  }
}

function toggleCampos(){
  var tipo = $('#media-med_tipo').val();
  var $entidad = $('#media-med_entidad').closest('.form-group');
  var $nombre  = $('#media-med_nombre');

  if(tipo==='entidad'){
      $entidad.show();
      $('#media-med_entidad').prop('required',true);
      $nombre.prop('readonly',true).prop('required',false);
      refreshNombreAuto();
  } else if(tipo==='site'){
      $entidad.hide();
      $('#media-med_entidad').prop('required',false);
      // No borrar valor para respetar dato existente
      $nombre.prop('readonly',false).prop('required',true);
  } else {
      $entidad.hide();
      $('#media-med_entidad').prop('required',false).val('');
      $nombre.prop('readonly',true).prop('required',false).val('');
  }
}

//---------------------------------- eventos
toggleCampos();                                   // inicial
$('#media-med_tipo').on('change', toggleCampos);
$('#media-med_entidad').on('keyup blur', refreshNombreAuto);
JS);
?>
