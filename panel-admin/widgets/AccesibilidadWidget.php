<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class AccesibilidadWidget extends Widget
{
    public function run()
    {
        $letra = Yii::$app->user->identity->usu_letra ?? 15;

        return Html::tag('div',
            implode("\n", [
                // Disminuir letra
                Html::a('<i class="bi bi-dash-circle"></i>', 'javascript:void(0);', [
                    'id' => 'disminuirLetra',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'top',
                    'title' => "Disminuir a " . ($letra - 1) . "px",
                    'data-fontsize' => $letra
                ]),

                // Aumentar letra
                Html::a('<i class="bi bi-plus-circle"></i>', 'javascript:void(0);', [
                    'id' => 'aumentarLetra',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'top',
                    'title' => "Aumentar a " . ($letra + 1) . "px",
                    'data-fontsize' => $letra
                ]),

                // Alto contraste
                Html::a('<i class="bi bi-eye"></i>', 'javascript:void(0);', [
                    'id' => 'modoAltoContraste',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'top',
                    'title' => 'Modo Alto Contraste'
                ]),

                // Dislexia
                Html::a('<i class="bi bi-type"></i>', 'javascript:void(0);', [
                    'id' => 'modoDislexia',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'top',
                    'title' => 'Activar Fuente para Dislexia'
                ])
            ]),
            [
                'class' => 'sidebar-footer',
                'style' => 'background: ' . Yii::$app->params['color_navbar_cms'] . ';'
            ]
        );
    }
}
