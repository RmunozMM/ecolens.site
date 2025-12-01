<?php

namespace app\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\web\View;

/**
 * CssEditorWidget renders a CodeMirror-based CSS editor for a model attribute.
 * It automatically registers CodeMirror assets and initializes the editor on DOM ready.
 *
 * Usage:
 * <?= $form->field($model, 'pag_css_programador')->widget(CssEditorWidget::class, [
 *     'height'        => '300px',   // override default height
 *     'clientOptions' => [           // CodeMirror options
 *         'mode'        => 'css',
 *         'theme'       => 'elegant',
 *         'lineNumbers' => true,
 *     ],
 *     'options' => [                // additional textarea HTML options
 *         'class' => 'css-editor',
 *     ],
 * ]) ?>
 */
class CssEditorWidget extends InputWidget
{
    /** @var string Height of the editor (e.g., '300px') */
    public $height = '300px';

    /** @var array CodeMirror client options */
    public $clientOptions = [
        'mode'             => 'css',
        'theme'            => 'elegant',
        'lineNumbers'      => true,
        'indentUnit'       => 4,
        'matchBrackets'    => true,
        'autoCloseBrackets'=> true,
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $view = $this->getView();
        // Register CodeMirror CSS & JS
        $view->registerCssFile(
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css',
            ['media' => 'all']
        );
        // Theme CSS
        if (!empty($this->clientOptions['theme']) && $this->clientOptions['theme'] !== 'default') {
            $theme = $this->clientOptions['theme'];
            $view->registerCssFile(
                "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/{$theme}.min.css",
                ['media' => 'all']
            );
        }
        // Core JS
        $view->registerJsFile(
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js',
            ['position' => View::POS_END]
        );
        // CSS mode
        $view->registerJsFile(
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js',
            ['position' => View::POS_END]
        );

        // Prepare textarea
        $id = $this->getId();
        $options = array_merge([
            'id'    => $id,
            'class' => 'css-editor form-control',
            'style' => "height:{$this->height};",
        ], $this->options);
        $textarea = Html::activeTextarea($this->model, $this->attribute, $options);

        // Build init script
        $jsOpts = json_encode($this->clientOptions);
        $js = <<<JS
jQuery(function(){
    // Try by ID
    var ta = document.getElementById('{$id}');
    // Fallback: selecciona cualquier textarea con clase 'css'
    if (!ta) { ta = document.querySelector('textarea.css'); }('{$id}');
    // Fallback: any textarea.css-editor
    if (!ta) { ta = document.querySelector('textarea.css-editor'); }
    if (!ta || typeof CodeMirror === 'undefined') return;
    var cm = CodeMirror.fromTextArea(ta, {$jsOpts});
    cm.setSize(null, '{$this->height}');
    window['cssEditor_{$id}'] = cm;
});
JS;
        $view->registerJs($js, View::POS_READY);

        return $textarea;
    }
}
