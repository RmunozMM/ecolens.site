<?php
namespace app\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use Yii;

class CodeEditorWidget extends InputWidget
{
    public $language = 'php';
    public $height   = '350px'; // puedes setearlo desde la vista

    public function run()
    {
        $id = $this->getId();
        $wrapperId = "editor-$id";

        // Input oculto pero PRESENTE en el DOM (así Yii2 lo procesa)
        $input = Html::activeTextarea($this->model, $this->attribute, [
            'id'    => $id,
            'style' => 'position:absolute;left:-9999px;top:auto;width:1px;height:1px;opacity:0;', // NUNCA display:none
        ]);

        // Contenedor Ace Editor, parte oculto
        $html = <<<HTML
<div id="{$wrapperId}" class="editor-ace-wrapper" style="display:none;width:100%;height:{$this->height};border-radius:8px;border:1px solid #d3d6df;margin-bottom:28px;font-size:1.1em;"></div>
{$input}
HTML;

        $js = <<<JS
function syncAceToTextarea(editor, textarea) {
    textarea.value = editor.getValue();
}

function startEditor_{$id}() {
    var textarea = document.getElementById('$id');
    var wrapper  = document.getElementById('{$wrapperId}');
    if (!wrapper || !textarea) return;

    // Solo crea el editor si aún no existe (previene duplicados)
    if (!wrapper.classList.contains('ace_editor')) {
        var editor = ace.edit(wrapper.id);
        editor.setTheme('ace/theme/twilight');
        editor.session.setMode('ace/mode/{$this->language}');
        editor.setValue(textarea.value || '', -1);

        // Guarda instancia global para manejo externo (resize/focus)
        window['aceEditor_{$id}'] = editor;

        // Sincronizar en cambios
        syncAceToTextarea(editor, textarea);
        editor.session.on('change', function() {
            syncAceToTextarea(editor, textarea);
        });

        // Antes de enviar el formulario, asegúrate de sincronizar
        var form = textarea.form;
        if(form) {
            form.addEventListener('submit', function() {
                syncAceToTextarea(editor, textarea);
            });
        }
    }
}

// Cargar ACE sólo si no está cargado
if (!window.aceLoaded) {
    var aceScript = document.createElement('script');
    aceScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.5/ace.js';
    aceScript.onload = function() {
        window.aceLoaded = true;
        startEditor_{$id}();
    };
    document.head.appendChild(aceScript);
} else {
    startEditor_{$id}();
}

// Ahora puedes acceder a window['aceEditor_{$id}'] en tu JS externo
// Ejemplo para forzar cursor/resize tras .show():
//   setTimeout(function() {
//     var ed = window['aceEditor_{$id}'];
//     if (ed) { ed.resize(); ed.focus(); }
//   }, 200);

JS;

        $this->getView()->registerJs($js);

        return $html;
    }
}