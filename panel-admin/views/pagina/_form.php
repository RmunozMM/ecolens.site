<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\GaleriaButtonWidget;
use app\widgets\IconPicker\IconPickerWidget;
use app\widgets\CodeEditorWidget;
use yii\web\View;

/** @var yii\web\View $this */
/** @var app\models\Pagina $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php 
    // Ajustar created_at / updated_at antes de renderizar el formulario
    $zonaHoraria = new DateTimeZone('America/Santiago');
    $fechaActual = new DateTime('now', $zonaHoraria);
    $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');

    if ($model->pag_id === null) { 
        $model->created_at = $fechaFormateada;
        $model->updated_at = $fechaFormateada;
    } else {
        $model->created_at = $model->created_at;
        $model->updated_at = $fechaFormateada;
    }
?>

<div class="pagina-form">
    <?php $form = ActiveForm::begin(); ?>

    <!-- Botones superiores -->
    <div class="form-group d-flex justify-content-between align-items-center mb-3" style="gap: 8px;">
        <div>
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>
        <!-- Botón fullscreen Ace (solo cuando modo programador + editar directamente) -->
        <button type="button" id="btn-modal-ace" class="btn btn-dark" style="display:none;" title="Pantalla completa">
            <i class="fa fa-expand"></i>
        </button>
    </div>

    <div class="row">
        <!-- ===== Columna IZQUIERDA ===== -->
        <div class="col-md-4">
            <?= $form->field($model, 'pag_titulo')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'pag_label')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'pag_estado')->dropDownList(
                ['borrador' => 'Borrador', 'publicado' => 'Publicado'],
                ['prompt' => '']
            ) ?>

            <!-- 🔒 Nivel de visibilidad -->
            <?= $form->field($model, 'pag_acceso')->dropDownList(
                \app\models\Pagina::optsPagAcceso(),
                ['prompt' => 'Selecciona tipo de acceso']
            )->label('Nivel de visibilidad') ?>

            <!-- Modo de edición de la Página -->
            <?= $form->field($model, 'pag_modo_contenido')->dropDownList(
                [
                    'autoadministrable'        => 'Autoadministrable',
                    'administrado_programador' => 'Administrado por Programador',
                ],
                [
                    'prompt'   => 'Seleccionar Modo de Contenido',
                    'id'       => 'pagina-pag_modo_contenido', // ← modificado (id generado por ActiveForm)
                    'disabled' => Yii::$app->user->identity->usu_rol_id != 1,
                ]
            ) ?>

            <!-- *** CAMBIO PRINCIPAL: reemplazo campo virtual por ActiveField de pag_fuente_contenido *** -->
            <div id="campo-fuente-contenido" style="display: none; margin-bottom: 1rem;">
                <?= $form->field($model, 'pag_fuente_contenido')->dropDownList(
                    [
                        'usar_plantilla' => 'Usar plantilla',
                        'editar_directo' => 'Editar directamente',
                    ],
                    [
                        'prompt' => 'Seleccionar fuente',
                        'id'     => 'pagina-pag_fuente_contenido', // ← modificado
                    ]
                )->label('Fuente de contenido') ?>
            </div>

            <!-- ↓ Campo pag_plantilla: solo si fuente = usar_plantilla -->
            <div id="campo-plantilla" style="display: none;">
                <?= $form->field($model, 'pag_plantilla')->textInput([
                        'maxlength'   => true,
                        'placeholder' => 'miPlantilla.php',
                    ])->label('Archivo de Plantilla')
                    ->hint('Ej: "miPlantilla.php". Debe existir en /views/plantillas/') 
                ?>
            </div>

            <?= IconPickerWidget::widget(['model' => $model, 'attribute' => 'pag_icono']) ?>

            <?= $form->field($model, 'pag_mostrar_menu')->dropDownList(
                ['SI' => 'SI', 'NO' => 'NO']
            ) ?>

            <?= $form->field($model, 'pag_mostrar_menu_secundario')->dropDownList(
                ['SI' => 'SI', 'NO' => 'NO']
            ) ?>
        </div>

        <!-- ===== Columna DERECHA ===== -->
        <div class="col-md-8 border" id="columna-derecha">
            <!-- Bloque AUTODMINISTRABLE: campos TinyMCE (visibles solo si modo = autoadministrable) -->
            <div id="bloque-autoadmin">
                <?= $form->field($model, 'pag_contenido_antes')->textarea([
                    'rows'  => 10,
                    'id'    => 'tinyMCE1',
                    'style' => 'height: 600px;',
                    'class' => 'tinymce',
                ]) ?>
                <?= $form->field($model, 'pag_contenido_despues')->textarea([
                    'rows'  => 10,
                    'id'    => 'tinyMCE2',
                    'style' => 'height: 600px;',
                    'class' => 'tinymce',
                ]) ?>
            </div>

            <!-- Bloque PROGRAMADOR: campos de PHP y CSS (solo si fuente = editar_directo) -->
            <div id="bloque-programador_php" style="display: none;">
                <?= $form->field($model, 'pag_contenido_programador')->widget(\app\widgets\CodeEditorWidget::class, [
                    'height' => '650px',
                ]) ?>
            </div>
            <div id="bloque-programador_css" style="display: false;">
                <?= $form->field($model, 'pag_css_programador')->widget(\app\widgets\CssEditorWidget::class, [
                    'height'        => '300px',
                    'clientOptions' => [
                        'mode'              => 'css',
                        'theme'             => 'twilight',
                        'lineNumbers'       => true,
                        'indentUnit'        => 2,
                        'matchBrackets'     => true,
                        'autoCloseBrackets' => true,
                    ],
                    'options' => [
                        'class' => 'css-editor form-control',
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- Modal fullscreen Ace -->
<div id="ace-modal-container" style="display:none;position:fixed;z-index:10010;top:0;left:0;width:100vw;height:100vh;background:rgba(24,29,43,0.97);">
    <div id="ace-modal-inner" style="position:absolute;top:5vh;left:6vw;width:88vw;height:90vh;background:#23272f;border-radius:12px;box-shadow:0 8px 30px #0009;overflow:hidden;">
        <button type="button" id="ace-modal-close" style="position:absolute;top:14px;right:28px;z-index:4;font-size:2em;color:#fff;background:transparent;border:none;cursor:pointer;">×</button>
        <div id="ace-modal-editor" style="width:100%;height:100%;border-radius:12px;"></div>
    </div>
</div>

<?php
// JavaScript para controlar visibilidad de bloques
$js = <<<JS
let aceModalEditor = null;

// Función que muestra/oculta secciones según Modo y Fuente de contenido
function toggleBloquesContenido() {
    var modo   = $('#pagina-pag_modo_contenido').val();       // ← modificado
    var fuente = $('#pagina-pag_fuente_contenido').val();     // ← modificado

    if (modo === 'autoadministrable' || modo === '') {
        // Modo autoadministrable: mostrar solo TinyMCE, ocultar programador + plantilla + selector fuente
        $('#bloque-autoadmin').show();
        $('#campo-fuente-contenido').hide();
        $('#campo-plantilla').hide();
        $('#bloque-programador_php').hide();
        $('#bloque-programador_css').hide();
        $('#editor-w2').hide();
        $('#btn-modal-ace').hide();
    }
    else if (modo === 'administrado_programador') {
        // Mostrar selector Fuente
        $('#bloque-autoadmin').hide();
        $('#campo-fuente-contenido').show();

        if (fuente === 'usar_plantilla') {
            // Si eligió “Usar plantilla”: ocultar editores, mostrar solo campo plantilla
            $('#campo-plantilla').show();
            $('#bloque-programador_php').hide();
            $('#bloque-programador_css').hide();
            $('#editor-w2').hide();
            $('#btn-modal-ace').hide();
        }
        else if (fuente === 'editar_directo') {
            // Si eligió “Editar directamente”: mostrar editores y ocultar campo plantilla
            $('#campo-plantilla').hide();
            $('#bloque-programador_php').show();
            $('#bloque-programador_css').show();
            $('#editor-w2').show();
            $('#btn-modal-ace').show();

            // Ajustar editor Ace tras mostrarse
            setTimeout(function() {
                var ed = window['aceEditor_w2'];
                if (ed) {
                    ed.resize();
                    ed.focus();
                } else if (typeof startEditor_w2 === 'function') {
                    startEditor_w2();
                    var ed2 = window['aceEditor_w2'];
                    if (ed2) {
                        ed2.resize();
                        ed2.focus();
                    }
                }
            }, 200);
        }
        else {
            // Si aún no ha seleccionado fuente: ocultar todo menos selector
            $('#campo-plantilla').hide();
            $('#bloque-programador_php').hide();
            $('#bloque-programador_css').hide();
            $('#editor-w2').hide();
            $('#btn-modal-ace').hide();
        }
    }
}

// Conectar eventos
$('#pagina-pag_modo_contenido').on('change', function() {   // ← modificado
    // Cuando cambie el modo, reseteo el combobox “Fuente”
    $('#pagina-pag_fuente_contenido').val('');             // ← modificado
    toggleBloquesContenido();
});
$('#pagina-pag_fuente_contenido').on('change', toggleBloquesContenido); // ← modificado

// Ejecutar al cargar la página
toggleBloquesContenido();

// --- Modal Ace ---
function getOriginalAce() {
    try {
        return ace.edit('editor-w2');
    } catch(e) {
        return null;
    }
}
$('#btn-modal-ace').off('click').on('click', function() {
    let origAce = getOriginalAce();
    if (!origAce) return;
    let code = origAce.getValue();
    $('#ace-modal-container').fadeIn(100, function() {
        if (aceModalEditor) aceModalEditor.destroy();
        aceModalEditor = ace.edit('ace-modal-editor');
        aceModalEditor.setTheme('ace/theme/twilight');
        aceModalEditor.session.setMode('ace/mode/php');
        aceModalEditor.setValue(code, -1);
        aceModalEditor.setOptions({fontSize: '1.2em'});
        aceModalEditor.focus();
    });
    $('body').css('overflow','hidden');
});
$('#ace-modal-close').off('click').on('click', cerrarModalAce);
$(document).on('keydown.ace-modal', function(e) {
    if (e.key === 'Escape' && $('#ace-modal-container').is(':visible')) {
        cerrarModalAce();
    }
});
function cerrarModalAce() {
    if (aceModalEditor) {
        let origAce = getOriginalAce();
        if (origAce) {
            origAce.setValue(aceModalEditor.getValue(), -1);
            setTimeout(function(){
                origAce.resize();
                origAce.focus();
            }, 200);
        }
        aceModalEditor.destroy();
        aceModalEditor = null;
    }
    $('#ace-modal-container').fadeOut(100);
    $('body').css('overflow','');
}
JS;

$this->registerJs($js, View::POS_READY);
?>

<style>
.ace-twilight .ace_cursor {
    border-left: 1.2px solid #fff !important;
    color: transparent !important;
    background: none !important;
    width: 0 !important;
}
</style>

<?php
// Registro de assets CodeMirror para el editor CSS
$this->registerCssFile(
    'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css',
    ['media' => 'all']
);
$this->registerCssFile(
    'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/twilight.min.css',
    ['media' => 'all']
);
$this->registerJsFile(
    'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js',
    ['position' => View::POS_END]
);
$this->registerJsFile(
    'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js',
    ['position' => View::POS_END]
);
?>