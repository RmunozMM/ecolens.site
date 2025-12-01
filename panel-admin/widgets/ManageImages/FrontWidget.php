<?php


namespace app\widgets\ManageImages;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidConfigException;

class FrontWidget extends Widget
{
    public $model;
    public $atributo;
    public $htmlOptions = [];
    public $enableModal = true;
    private $exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function run()
    {
        if (!$this->model || !$this->atributo) {
            throw new InvalidConfigException('Debe especificar "model" y "atributo".');
        }

        // *** Rutas base ***
        $uploadsUrl  = Yii::$app->request->hostInfo . '/recursos/uploads';
        $uploadsPath = dirname(dirname(Yii::getAlias('@webroot'))) . '/recursos/uploads';

        $entidad = strtolower((new \ReflectionClass($this->model))->getShortName());
        $pk      = $this->model->getPrimaryKey();
        $personal = null;

        // Imagen por PK (ej: users/5.jpg)
        if ($pk) {
            foreach ($this->exts as $ext) {
                $localPath = "$uploadsPath/$entidad/$pk.$ext";
                if (is_file($localPath)) {
                    $personal = "$uploadsUrl/$entidad/$pk.$ext";
                    break;
                }
            }
        }

        // Ruta guardada en BD (campo usu_imagen)
        $rutaDb = ltrim($this->model->{$this->atributo}, '/');
        $dbUrl  = $rutaDb ? "$uploadsUrl/$rutaDb" : null;

        // Imagen genérica por entidad
        $sinEntidad = null;
        foreach ($this->exts as $ext) {
            $localPath = "$uploadsPath/default/entidad/$entidad/sin_imagen.$ext";
            if (is_file($localPath)) {
                $sinEntidad = "$uploadsUrl/default/entidad/$entidad/sin_imagen.$ext";
                break;
            }
        }

        // Fallback global
        $fallback = "$uploadsUrl/default/no_disponible.png";

        // Orden de prioridad:
        // 1. ruta BD (usu_imagen)
        // 2. por PK
        // 3. genérica entidad
        // 4. fallback global
        $finalUrl = $dbUrl ?: ($personal ?: ($sinEntidad ?: $fallback));
        $nombreArchivo = basename($finalUrl);

        // Atributos HTML
        $defaultOptions = [
            'alt'       => $entidad,
            'class'     => 'img-thumbnail image-modal-trigger',
            'style'     => 'max-width:120px; cursor:pointer;',
            'data-src'  => $finalUrl,
            'data-full' => $finalUrl,
            'data-name' => $nombreArchivo,
        ];
        $options = array_merge($defaultOptions, $this->htmlOptions);

        $this->registerAssets();

        return Html::tag('div',
            Html::img($finalUrl, $options) .
            Html::tag('div', Html::encode($nombreArchivo), ['class' => 'text-muted small mt-2 text-center']),
            ['class' => 'text-center']
        );
    }

    protected function registerAssets()
    {
        $view = Yii::$app->view;

        $css = <<<CSS
        #image-modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        #image-modal-overlay.active {
            display: flex;
            opacity: 1;
        }
        #image-modal-overlay img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }
        #image-modal-filename {
            margin-top: 15px;
            color: #eee;
            font-size: 13px;
            text-align: center;
            word-break: break-word;
            max-width: 90%;
        }
        CSS;
        $view->registerCss($css);

        $view->on(\yii\web\View::EVENT_END_BODY, function () {
            echo <<<HTML
<div id="image-modal-overlay">
    <img id="image-modal-img" src="" alt="Imagen ampliada">
    <div id="image-modal-filename"></div>
</div>
HTML;
        });

        $js = <<<JS
$(document).on('click', '.image-modal-trigger', function() {
    var src = $(this).data('src');
    var full = $(this).data('full');
    if (src) {
        $('#image-modal-img').attr('src', src);
        $('#image-modal-filename').text(full);
        $('#image-modal-overlay').addClass('active');
    }
});
$(document).on('click', '#image-modal-overlay', function(e) {
    if (e.target.id === 'image-modal-overlay') {
        $(this).removeClass('active');
        $('#image-modal-img').attr('src', '');
        $('#image-modal-filename').text('');
    }
});
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        $('#image-modal-overlay').removeClass('active');
        $('#image-modal-img').attr('src', '');
        $('#image-modal-filename').text('');
    }
});
JS;
        $view->registerJs($js);
    }
}