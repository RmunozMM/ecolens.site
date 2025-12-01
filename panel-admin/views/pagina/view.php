<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Pagina $model */

$this->title = $model->pag_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Páginas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pagina-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'pag_id' => $model->pag_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'pag_id' => $model->pag_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'pag_titulo',
            'pag_acceso',
            'pag_slug',
            'pag_estado',
            'pag_mostrar_menu',
            'pag_mostrar_menu_secundario',
            [
                'label' => 'Ícono',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<i id="icono-actual" style="font-size: 30px;" class="' . Html::encode($model->pag_icono) . '"></i>';
                },
            ],
            [
                'attribute' => 'pag_contenido_programador',
                'format' => 'raw',
                'value' => function ($model) {
                    $codigo = htmlspecialchars($model->pag_contenido_programador ?? '');
                    $id = 'ace-view-codigo-' . $model->pag_id;
                    return "<div id=\"$id\" style=\"width:100%; min-height:250px; border-radius:8px; border:1px solid #d3d6df; font-size:1.1em;\"></div>
                            <textarea id=\"{$id}-hidden\" style=\"display:none;\">$codigo</textarea>";
                },
                'contentOptions' => ['style' => 'padding:0; background:transparent;'],
            ],

[
    'attribute' => 'pag_css_programador',
    'label'     => 'CSS exclusivo para esta página',
    'format'    => 'raw',
    'value'     => function($model) {
        $css = htmlspecialchars($model->pag_css_programador);
        $id  = 'css-editor-' . $model->pag_id;
        // Sólo el textarea: CodeMirror lo ocultará y creará su div por ti
        return "<textarea id=\"{$id}\" class=\"css-editor form-control\" style=\"height:200px;\">{$css}</textarea>";
    },
],
            'pag_posicion',
            'pag_label',
            [
                'attribute' => 'pag_autor_id',
                'label' => 'Creado por',
                'value' => $model->usuario->usu_username ?? '—',
            ],
            [
                'attribute' => 'pag_contenido_antes',
                'format' => 'raw',
                'contentOptions' => ['style' => 'max-width: 100%; overflow: hidden;'],
            ],
            [
                'attribute' => 'pag_contenido_despues',
                'format' => 'raw',
                'contentOptions' => ['style' => 'max-width: 100%; overflow: hidden;'],
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>

<?php
// Carga Ace Editor desde CDN
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.5/ace.js', [
    'position' => \yii\web\View::POS_END,
]);

$this->registerJs(<<<'JS'
function hideCursorLayerWhenReady(editor) {
    var tries = 0;
    var maxTries = 20;
    var interval = setInterval(function() {
        if (editor.renderer && editor.renderer.$cursorLayer && editor.renderer.$cursorLayer.element) {
            editor.renderer.$cursorLayer.element.style.display = "none";
            clearInterval(interval);
        }
        tries++;
        if (tries > maxTries) clearInterval(interval);
    }, 50);
}
function initAceReadOnlyBlock(id) {
    var textarea = document.getElementById(id + "-hidden");
    var el = document.getElementById(id);
    if (!window.ace || !textarea || !el) return;
    var editor = ace.edit(id);
    editor.setTheme("ace/theme/twilight");
    editor.session.setMode("ace/mode/php");
    editor.setValue(textarea.value, -1);
    editor.setReadOnly(true);
    editor.setOptions({
        maxLines: 50,
        minLines: 10,
        highlightActiveLine: false,
        highlightGutterLine: false,
        showPrintMargin: false,
        useWorker: false,
        fontSize: "12px",
    });
    hideCursorLayerWhenReady(editor);
}
function aceReadyAndInitAll() {
    var bloques = document.querySelectorAll('div[id^="ace-view-codigo-"]');
    bloques.forEach(function(div) {
        var id = div.id;
        initAceReadOnlyBlock(id);
    });
}
if (window.ace) {
    aceReadyAndInitAll();
} else {
    var readyCheck = setInterval(function() {
        if (window.ace) {
            clearInterval(readyCheck);
            aceReadyAndInitAll();
        }
    }, 100);
}
JS, \yii\web\View::POS_READY);
?>


<?php
use yii\web\View;

/* 1) Registra assets de CodeMirror con tema “twilight” */
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

/* 2) Inicializa en modo “twilight” + solo lectura */
$this->registerJs(<<<'JS'
jQuery(function(){
    document.querySelectorAll('textarea.css-editor').forEach(function(ta){
        if (typeof CodeMirror === 'undefined') return;
        var cm = CodeMirror.fromTextArea(ta, {
            mode: 'css',
            theme: 'twilight',    // ← igual que Ace twilight
            lineNumbers: true,
            indentUnit: 2,
            matchBrackets: true,
            autoCloseBrackets: true,
            readOnly: true
        });
        cm.setSize(null, '200px');
    });
});
JS
, View::POS_READY);
?>