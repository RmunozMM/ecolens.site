<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\ManageImages\FrontWidget;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var string $msg */

$this->title = "Mi Perfil";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-create">
    <h2>Mi Perfil</h2>

    <?php if (!empty($msg)) : ?>
        <div class="alert alert-success" role="alert">
            <?= Html::encode($msg) ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'method'  => 'post',
        'options' => ['enctype' => 'multipart/form-data'], // IMPORTANTE
    ]); ?>

    <div class="form-group btn_save mb-4">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="row">
        <!-- Columna izquierda: imagen -->
        <div class="col-md-4">

            <?= $form->field($model, 'usu_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ])->label('Subir nueva imagen') ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= FrontWidget::widget([
                    'model'       => $model,
                    'atributo'    => 'usu_imagen',
                    'htmlOptions' => [
                        'style'    => 'max-width:100%; cursor:pointer;',
                        'loading'  => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- Columna derecha: datos -->
        <div class="col-md-8 border rounded p-3">
            <?= $form->field($model, 'usu_nombres')->textInput()->label('Nombres') ?>

            <?= $form->field($model, 'usu_apellidos')->textInput()->label('Apellidos') ?>

            <?= $form->field($model, 'usu_telefono')->textInput()->label('Teléfono') ?>

            <?= $form->field($model, 'usu_ubicacion')->dropDownList([
                'Arica, CL'         => 'Arica',
                'Iquique, CL'       => 'Iquique',
                'Antofagasta, CL'   => 'Antofagasta',
                'Calama, CL'        => 'Calama',
                'Copiapó, CL'       => 'Copiapó',
                'La Serena, CL'     => 'La Serena',
                'Valparaíso, CL'    => 'Valparaíso',
                'Rancagua, CL'      => 'Rancagua',
                'Talca, CL'         => 'Talca',
                'Concepción, CL'    => 'Concepción',
                'Temuco, CL'        => 'Temuco',
                'Valdivia, CL'      => 'Valdivia',
                'Puerto Montt, CL'  => 'Puerto Montt',
                'Coyhaique, CL'     => 'Coyhaique',
                'Punta Arenas, CL'  => 'Punta Arenas',
                'Santiago, CL'      => 'Santiago',
                'Osorno, CL'        => 'Osorno',
            ], ['prompt' => 'Selecciona tu ciudad'])
            ->label('Ubicación por defecto del usuario') ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>