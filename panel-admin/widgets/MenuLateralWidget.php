<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

class MenuLateralWidget extends Widget
{
    public $items = [];

    public function run()
    {

        $navbarBgColor = Yii::$app->params['color_navbar_cms'] ?? '#212529';

        return Html::tag('nav',
            Html::tag('ul', implode("\n", $this->renderItems()), ['class' => 'nav flex-column']),
            [ 
                'class' => 'sidebar-menu',
                'role' => 'navigation',
                'aria-label' => 'Menú lateral',
                'style' => "background-color: {$navbarBgColor};"
            ]
        );
    }

    
    private function renderItems()
{
    $output = [];
    foreach ($this->items as $item) {
        $hasChildren = !empty($item['items']);
        $classes = ['nav-item', 'level-1'];
        if ($hasChildren) $classes[] = 'expandable';
        if (!empty($item['active'])) $classes[] = 'active expanded';

        // ---- ICONO (ajusta aquí según como llega en tu estructura, puede ser $item['icon'] y $item['iconColor'])
        $iconHtml = '';
        if (!empty($item['icon'])) {
            $iconHtml = Html::tag('i', '', [
                'class' => $item['icon'],
                'style' => 'color:' . ($item['iconColor'] ?? '#223142') . '; font-size: 1.3em; margin-right:8px;',
            ]);
        }

        $labelHtml = $iconHtml . Html::tag('span', strtoupper($item['label']), ['class' => 'label-text']);
        $url = Url::to($item['url']);
        $linkOptions = [
            'class' => 'nav-link',
            'style' => $item['linkOptions']['style'] ?? '',
            'target' => $item['linkOptions']['target'] ?? '_self',
        ];

        $link = Html::a($labelHtml . ($hasChildren ? ' <span class="fa fa-chevron-down expand-icon"></span>' : ''), $url, $linkOptions);

        $submenu = '';
        if ($hasChildren) {
            $submenuItems = [];
            foreach ($item['items'] as $subitem) {
                $subIconHtml = '';
                if (!empty($subitem['icon'])) {
                    $subIconHtml = Html::tag('i', '', [
                        'class' => $subitem['icon'],
                        'style' => 'color:' . ($subitem['iconColor'] ?? '#223142') . '; font-size: 1.2em; margin-right:6px;',
                    ]);
                }
                $subLabelHtml = $subIconHtml . Html::tag('span', $subitem['label'], ['class' => 'label-text']);
                $subUrl = Url::to($subitem['url']);
                $subLinkOptions = [
                    'class' => 'nav-link',
                    'style' => $subitem['linkOptions']['style'] ?? '',
                    'target' => $subitem['linkOptions']['target'] ?? '_self',
                ];
                $subLink = Html::a($subLabelHtml, $subUrl, $subLinkOptions);
                $submenuItems[] = Html::tag('li', $subLink, [
                    'class' => 'nav-item level-2' . (!empty($subitem['active']) ? ' active' : ''),
                    'role' => 'menuitem'
                ]);
            }
            $submenu = Html::tag('ul', implode("\n", $submenuItems), ['class' => 'submenu', 'role' => 'menu']);
        }

        $output[] = Html::tag('li', $link . $submenu, ['class' => implode(' ', $classes)]);
    }
    return $output;
}
}
?>