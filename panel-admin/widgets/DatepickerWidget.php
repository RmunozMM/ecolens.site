<?php

namespace app\widgets;

use yii\base\Widget;

class DatepickerWidget extends Widget
{
    /**
     * @var string Selector del input (por ejemplo, "#datepicker")
     */
    public $selector = '#datepicker';

    /**
     * @var string Formato de la fecha (Y-m-d, Y-m, etc.)
     */
    public $dateFormat = 'Y-m-d';

    /**
     * @var bool Desactivar Flatpickr en móviles (false = permitir)
     */
    public $disableMobile = false;

    /**
     * @var string Fecha mínima permitida
     */
    public $minDate;

    /**
     * Inicializa el widget antes de run().
     */
    public function init()
    {
        parent::init();
        // Valor por defecto para minDate si no se especifica
        if (empty($this->minDate)) {
            $this->minDate = '2000-01-01';
        }
    }

    /**
     * Renderiza el widget (registra CSS/JS y genera el script de inicialización).
     */
    public function run()
    {
        // 1. Registrar Flatpickr (CSS+JS). 
        //    Puedes moverlo a un AssetBundle si prefieres.
        $this->view->registerCssFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
        $this->view->registerJsFile(
            'https://cdn.jsdelivr.net/npm/flatpickr',
            ['depends' => [\yii\web\JqueryAsset::class]]
        );

        // 2. Convertir la propiedad disableMobile a un literal JS ('true' o 'false')
        $disableMobileJs = $this->disableMobile ? 'true' : 'false';

        // 3. Construir el script de inicialización:
        $js = <<<JS
flatpickr('{$this->selector}', {
    dateFormat: '{$this->dateFormat}',
    minDate: '{$this->minDate}',
    disableMobile: {$disableMobileJs},
    onChange: function (selectedDates, dateStr, instance) {
        // Personaliza aquí tu lógica: 
        // Ej. ajustar el primer día del mes, etc.
    }
});
JS;

        // 4. Registrar el JS en la vista
        $this->view->registerJs($js);

        // 5. El widget no retorna HTML específico; 
        //    solo registramos el script de inicialización.
        return "<!-- DatepickerWidget Initialized -->";
    }
}