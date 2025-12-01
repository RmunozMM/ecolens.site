<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LibreriaHelper;

/** @var yii\web\View $this */
/** @var app\models\Proyecto $model */
/** @var yii\widgets\ActiveForm $form */


?>

<div class="proyecto-form">

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success" role="alert">
            <?= Html::encode($msg) ?>
        </div>
    <?php endif; ?>

    <?php
    // Importante: para subir archivos
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]);
    ?>

    <div class="row">

        <!-- Botones de acción -->
        <div class="form-group btn_save mb-3">
            <?= Html::submitButton('<i class="fa fa-save"></i> Guardar Cambios', [
                'class' => 'btn btn-success'
            ]) ?>
        </div>

        <!-- Columna izquierda: imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'pro_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mb-3',
            ])->label('Subir nueva imagen') ?>

            <label>Imagen actual</label>
            <div class="border p-3 text-center mb-3">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'       => $model,
                    'atributo'    => 'pro_imagen',
                    'htmlOptions' => [
                        'style'   => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- Columna derecha: campos -->
        <div class="col-md-8 border">
            <div class="p-3">

                <?= $form->field($model, 'pro_id')->hiddenInput()->label(false) ?>

                <?= $form->field($model, 'pro_titulo')
                    ->textInput(['maxlength' => true])
                    ->label('Título del proyecto') 
                ?>

                <?= $form->field($model, 'pro_cli_id')
                    ->dropDownList(
                        \yii\helpers\ArrayHelper::map(
                            \app\models\Cliente::find()->all(), 
                            'cli_id', 
                            'cli_nombre'
                        ),
                        ['prompt' => 'Seleccione un Cliente']
                    )
                    ->label('Cliente')
                ?>

                <?= $form->field($model, 'pro_ser_id')
                    ->dropDownList(
                        \app\models\Servicio::find()
                            ->select(['ser_titulo', 'ser_id'])
                            ->indexBy('ser_id')
                            ->column(),
                        ['prompt' => 'Seleccione un Servicio']
                    )
                    ->label('Servicio relacionado')
                ?>

                <?= $form->field($model, 'pro_url')
                    ->textInput(['maxlength' => true])
                    ->label('URL externa')
                ?>

                <?= $form->field($model, 'pro_resumen')
                    ->textInput(['maxlength' => true])
                    ->label('Resumen breve')
                ?>

                <?= $form->field($model, 'pro_estado')
                    ->dropDownList(
                        ['PUBLICADO' => 'Publicado', 'BORRADOR' => 'Borrador'],
                        ['prompt' => '']
                    )
                    ->label('Estado')
                ?>

                <?= $form->field($model, 'pro_destacado')
                    ->dropDownList(['SI' => 'Sí', 'NO' => 'No'], [
                        'prompt' => '',
                        'value'  => $model->isNewRecord ? 'NO' : $model->pro_destacado
                    ])
                    ->label('¿Destacado?')
                ?>

                <?= $form->field($model, 'pro_fecha_inicio')
                    ->textInput([
                        'class' => 'form-control',
                        'id'    => 'datepicker_inicio'
                    ])
                    ->label('Fecha de inicio')
                ?>

                <?= $form->field($model, 'pro_fecha_fin')
                    ->textInput([
                        'class' => 'form-control',
                        'id'    => 'datepicker_fin'
                    ])
                    ->label('Fecha de fin')
                ?>

                <?= $form->field($model, 'pro_descripcion')
                    ->textarea([
                        'rows'  => 6,
                        'id'    => 'tinyMCE',
                        'class' => 'tinymce'
                    ])
                    ->label('Descripción completa')
                ?>

            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>