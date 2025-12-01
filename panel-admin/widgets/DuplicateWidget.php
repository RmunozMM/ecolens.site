<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class DuplicateWidget extends Widget
{
    public $modelClass;
    public $recordId;

    public function run()
    {
        if (!$this->recordId || !$this->modelClass) {
            return ''; // No renderiza nada si falta información
        }

        $url = Url::to(['duplicate/index', 'modelClass' => $this->modelClass, 'id' => $this->recordId]);

        return Html::a('<i class="fa fa-clone"></i>', $url, [
            'class' => 'btn btn-action btn-copy',
            'title' => 'Duplicar este registro',
            'data-confirm' => '¿Estás seguro de que quieres duplicar este registro?',
        ]);
    }
}