<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class ExportRecordsWidget extends Widget
{
    public $modelClass;  // Ej. 'app\models\Curso'
    public $exportUrl = ['export/index'];
    public $buttonLabel = '<i class="fa fa-download"></i> Exportar';
    public $buttonOptions = ['class' => 'btn btn-secondary'];

    public function run()
    {
        // Construir la URL de exportación
        $finalUrl = Url::to(array_merge($this->exportUrl, ['modelClass' => $this->modelClass]));
        return Html::a($this->buttonLabel, $finalUrl, $this->buttonOptions);
    }
}