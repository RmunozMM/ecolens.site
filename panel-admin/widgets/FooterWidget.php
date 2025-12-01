<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class FooterWidget extends Widget
{
    public function run()
    {
        $panelName = Yii::$app->params['panel_admin_name'] ?? '';
        $siteName = Yii::$app->params['cliente_nombre'] ?? '';
        $authorUrl = Yii::$app->params['sitio_autor'] ?? '#';
        $authorName = Yii::$app->params['meta_author'] ?? '';
        $year = date("Y");

        $colLeft = Html::tag('div',
            "&copy; {$panelName} de <b>{$siteName}</b>",
            ['class' => 'col-md-8 text-center text-md-start']
        );

        $colRight = Html::tag('div',
            Html::a($authorName, $authorUrl, ['target' => '_blank']) . " - {$year} Todos los derechos reservados",
            ['class' => 'col-md-4 text-center text-md-end']
        );

        $row = Html::tag('div', $colLeft . $colRight, ['class' => 'row text-muted align-items-center']);
        $container = Html::tag('div', $row, ['class' => 'container']);

        return Html::tag('footer', $container, [
            'id' => 'footer',
            'class' => 'bg-light py-3'
        ]);
    }
}
