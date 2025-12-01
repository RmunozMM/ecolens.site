<?php
// app\widgets\MainContentWidget.php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use app\widgets\DatepickerWidget;
use app\widgets\tinymce\TinyMceWidget;   // <- nombre correcto (Mce)
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;

class MainContentWidget extends Widget
{
    public $content;

    public function run()
    {
        $breadcrumbs = !empty(Yii::$app->view->params['breadcrumbs'])
            ? Breadcrumbs::widget(['links' => Yii::$app->view->params['breadcrumbs']])
            : '';

        $alert = Alert::widget();

        $datepicker = DatepickerWidget::widget([
            'selector'      => '#datepicker',
            'dateFormat'    => 'Y-m-d',
            'disableMobile' => false,
            'minDate'       => '2000-01-01',
        ]);

        // Llamada al widget con el nombre correcto
        $editor = TinyMceWidget::widget();

        $this->getView()->registerJs(<<<JS
            $(document).ready(function() {
                $('.fancybox').fancybox({ loop: true });
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
            });
        JS);

        return Html::tag('main',
            Html::tag('div',
                $breadcrumbs . $alert . $this->content . $datepicker . $editor,
                ['class' => 'container']
            ),
            ['id' => 'main-content', 'role' => 'main']
        );
    }
}
