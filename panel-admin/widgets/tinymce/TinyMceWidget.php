<?php
namespace app\widgets\tinymce;

use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

class TinyMceWidget extends Widget
{
    public $selector      = 'textarea.tinymce';
    public $clientOptions = [];
    public $language;
    public $entidad;
    public $registro;
    protected $enabled = true;

    public function init()
    {
        parent::init();

        // 1) Detectar entidad/registro desde la URL si no están seteados explícitamente
        if (!$this->entidad) {
            $ctrl  = Yii::$app->controller->id;
            $param = strtolower(substr($ctrl, 0, 3)) . '_id';
            $this->entidad  = $ctrl;
            $this->registro = Yii::$app->request->get($param, null);
        }

        // 2) Siempre permitimos inicializar el editor (aunque no haya registro)
        $this->enabled = true;

        // 3) Selector CSS donde TinyMCE se aplicará
        $this->clientOptions['selector'] = $this->selector;

        // 4) Plugins: incluye editimageplugin para tu plugin de edición de imagen
        $this->clientOptions['plugins'] = [
            'advlist','autolink','lists','link','image','charmap',
            'preview','anchor','searchreplace','visualblocks','visualchars',
            'code','codesample','fullscreen','insertdatetime','media','table',
            'pagebreak','nonbreaking','save','autosave','autoresize',
            'importcss','quickbars','help','wordcount','emoticons','directionality',
            // Este es tu plugin extra para “editar imagen” (registerEditImage)
            'editimageplugin'
        ];

        // 5) Toolbar completo, idéntico al tuyo, con browsegallery y editimage al final
        $this->clientOptions['toolbar'] = [
            // Primera fila de botones
            'fullscreen undo redo | save | bold italic underline strikethrough | fontfamily fontsize blocks | forecolor backcolor',
            // Segunda fila de botones
            'alignleft aligncenter alignright alignjustify | numlist bullist indent outdent | link image media table pagebreak nonbreaking',
            // Tercera fila de botones: mantenemos browsegallery y editimage
            'importcss quickbars charmap insertdatetime visualchars | removeformat code preview help browsegallery emoticons editimage'
        ];

        // 6) Menú contextual (Click derecho dentro del editor)
        $this->clientOptions['contextmenu'] = 'link image editimage browsegallery table';

        // 7) Otras opciones fijas
        $this->clientOptions['menubar']     = true;
        $this->clientOptions['language']    = 'es';
        $this->clientOptions['license_key'] = 'gpl';

        // 8) Configurar upload personalizado **sin** usar images_upload_url
        if ($this->registro !== null) {
            $uploadUrl = Url::to([
                'media/upload',
                'entidad'  => $this->entidad,
                'registro' => $this->registro,
            ], true);

            // Habilitamos subidas automáticas (TinyMCE mostrará el botón en la pestaña “Cargar”)
            $this->clientOptions['automatic_uploads']     = true;
            // ------------------- Aquí quitamos images_upload_url -------------------
            // $this->clientOptions['images_upload_url'] = $uploadUrl;
            // En su lugar definimos solo nuestro handler personalizado:
            $this->clientOptions['images_upload_handler'] = new JsExpression('TinyMCELogic.images_upload_handler');
            // -----------------------------------------------------------------------
            $this->clientOptions['file_picker_types']     = 'image';

            // setup() registrará browsegallery y editimage
            $this->clientOptions['setup'] = new JsExpression(<<<'JS'
function(editor) {
    if (window.TinyMCELogic) {
        if (typeof window.TinyMCELogic.registerGallery === 'function') {
            window.TinyMCELogic.registerGallery(editor);
        }
        if (typeof window.TinyMCELogic.registerEditImage === 'function') {
            window.TinyMCELogic.registerEditImage(editor);
        }
    }
}
JS
            );
        }

        // 9) Desactivar branding y promoción de TinyMCE
        $this->clientOptions['branding']  = false;
        $this->clientOptions['promotion'] = false;
    }

    public function run()
    {
        $view   = $this->getView();
        $bundle = TinyMceAsset::register($view);
        $base   = $bundle->baseUrl;

        // 10) Si existe registro, inyectar las URLs en JS para usar en tinymce-logic.js
        if ($this->registro !== null) {
            $uploadUrl = Url::to([
                'media/upload',
                'entidad'  => $this->entidad,
                'registro' => $this->registro,
            ], true);
            $browseUrl = Url::to([
                'media/browse',
                'entidad'  => $this->entidad,
                'registro' => $this->registro,
            ], true);

            $view->registerJs(
                "window.MEDIA_UPLOAD_URL = " . Json::htmlEncode($uploadUrl) . ";",
                View::POS_HEAD
            );
            $view->registerJs(
                "window.MEDIA_BROWSE_URL = " . Json::htmlEncode($browseUrl) . ";",
                View::POS_HEAD
            );
        }

        // 11) Inyectar Cropper.js y su CSS (necesario para el plugin de editar imagen)
        $view->registerCssFile(
            'https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css',
            ['position' => View::POS_HEAD]
        );
        $view->registerJsFile(
            'https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js',
            ['position' => View::POS_HEAD]
        );

        // 12) Inicializar TinyMCE con todas las clientOptions ya armadas
        $this->clientOptions['language_url'] = "{$base}/langs/{$this->clientOptions['language']}.js";
        $opts = Json::htmlEncode($this->clientOptions);

$view->registerJs(<<<JS
tinymce.init({$opts});
JS
, View::POS_READY);

        return null;
    }
}