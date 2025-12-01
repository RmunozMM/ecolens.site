<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Widget para agrandar y disminuir el tamaño de la fuente.
 * Incluye el HTML de los botones "+" y "-", así como el JS
 * necesario para la llamada AJAX que actualiza el tamaño.
 */
class FontSizeWidget extends Widget
{
    /**
     * @var string URL que procesará el cambio de tamaño (vía AJAX).
     *             Por ejemplo: Url::to(['user/letra'])
     */
    public $ajaxUrl;

    /**
     * @var int Tamaño actual de la fuente (en px).
     */
    public $currentSize = 15;

    /**
     * @var int Tamaño mínimo permitido.
     */
    public $minSize = 10;

    /**
     * @var int Tamaño máximo permitido.
     */
    public $maxSize = 19;

    /**
     * @var string Color de los íconos de los enlaces, por defecto #000.
     *             Puedes pasar un color desde la vista.
     */
    public $iconColor = '#ff0000';

    /**
     * Inicializa el widget antes de renderizarlo.
     * Aquí se pueden aplicar validaciones.
     */
    public function init()
    {
        parent::init();

        // Si no pasas la URL por parámetro, define una por defecto
        if (empty($this->ajaxUrl)) {
            $this->ajaxUrl = Url::to(['user/letra']);
        }
    }

    /**
     * Renderiza el contenido del widget (HTML + JS).
     */
    public function run()
    {
        // Generamos el HTML de los botones
        $html = $this->renderHtml();

        // Registramos el script JavaScript para que se ejecute al final del <body>
        $this->registerJsScript();

        return $html;
    }

    /**
     * Construye el HTML de los botones para agrandar y disminuir la letra.
     *
     * @return string
     */
    private function renderHtml()
    {
        // Puedes ajustar estilos o añadir atributos ARIA si lo deseas.
        // Se conservan las clases y estilos que tenías en tu código original.
        $html = <<<HTML
<div class="btn-group" style="padding-left: 5px;" role="group">
    <a id="disminuirLetra"
       href="javascript:void(0);"
       style="color: {$this->iconColor};"
       onclick="cambiarTamanioFuenteAjax(-1)"
       title="Disminuir fuente">
        <i class="fas fa-minus"></i>
    </a>
    <a id="aumentarLetra"
       href="javascript:void(0);"
       style="color: {$this->iconColor};"
       onclick="cambiarTamanioFuenteAjax(1)"
       title="Agrandar fuente">
        <i class="fas fa-plus"></i>
    </a>
</div>
HTML;
        return $html;
    }

    /**
     * Registra el JS necesario para manejar la llamada AJAX y actualizar la fuente.
     */
    private function registerJsScript()
    {
        // Sugerencia: Si no has cargado jQuery en la vista, asegúrate de hacerlo.
        // Por defecto Yii2 ya lo carga, pero puedes forzarlo con:
        // $this->view->registerAssetBundle(\yii\web\JqueryAsset::class);

        $js = <<<JS
var urlCambiarTamanioFuente = '{$this->ajaxUrl}';
var TamanioActual = {$this->currentSize};
var minFontSize = {$this->minSize};
var maxFontSize = {$this->maxSize};

function cambiarTamanioFuenteAjax(cantidad) {
    // Calculamos el nuevo tamaño
    var nuevoSize = TamanioActual + cantidad;
    console.log("Intentando cambiar a tamaño: " + nuevoSize);

    // Verificamos que esté dentro de los límites
    if (nuevoSize >= minFontSize && nuevoSize <= maxFontSize) {
        $.ajax({
            url: urlCambiarTamanioFuente,
            method: 'POST',
            data: { cantidad: cantidad },
            dataType: 'json',
            success: function (response) {
                if (!response.error) {
                    // Ajustamos la fuente en el body
                    document.body.style.fontSize = response.nuevoTamanio + 'px';
                    // Actualizamos el valor actual (importante)
                    TamanioActual = response.nuevoTamanio;
                    console.log("Nuevo Tamaño: " + response.nuevoTamanio);
                } else {
                    console.log("Error: " + response.error);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("Hubo un error en la solicitud AJAX: " + textStatus);
            }
        });
    } else {
        alert("No es posible seguir modificando el tamaño de letra");
    }
}
JS;

        // Se registra el script al final de la página (POS_END)
        $this->view->registerJs($js, \yii\web\View::POS_END);
    }
}