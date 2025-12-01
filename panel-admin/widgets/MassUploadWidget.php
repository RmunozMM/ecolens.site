<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

class MassUploadWidget extends Widget
{
    public $modelClass;   // Ejemplo: 'app\models\Articulo'
    public $fieldsMap = [];  // Ejemplo: ['Título' => 'art_titulo', ...]
    public $uploadUrl = ['import/index'];
    public $buttonLabel = '<i class="fa fa-upload"></i> Carga Masiva';
    public $buttonOptions = ['class' => 'btn btn-primary'];
    public $modelLabel;  // Nombre visible del modelo

    public function run()
    {
        if (!$this->modelLabel) {
            $this->modelLabel = ucfirst((new \ReflectionClass($this->modelClass))->getShortName());
        }

        $baseUrl = Url::to($this->uploadUrl);
        $sep = (strpos($baseUrl, '?') === false) ? '?' : '&';
        $params = [
            'modelClass' => $this->modelClass,
            'fieldsMap'  => Json::encode($this->fieldsMap),
            'modelLabel' => $this->modelLabel,
        ];
        $finalUrl = $baseUrl . $sep . http_build_query($params);

        return Html::a($this->buttonLabel, $finalUrl, $this->buttonOptions);
    }
}