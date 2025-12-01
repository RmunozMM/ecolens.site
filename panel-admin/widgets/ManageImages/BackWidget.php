<?php
namespace app\widgets\ManageImages;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidConfigException;

class BackWidget extends Widget
{
    public $model;          // AR
    public $atributo;       // med_ruta | trab_imagen…
    public $htmlOptions = [];
    public $mostrarInput = false;   // en grid ‑ false; en forms ‑ true

    public function run()
    {
        if (!$this->model || !$this->atributo) {
            throw new InvalidConfigException('Falta model o atributo');
        }

        $uploadsUrl  = str_replace('/panel-admin/web', '', Yii::getAlias('@web'))
                     . '/recursos/uploads';

        $valorRuta = ltrim($this->model->{$this->atributo}, '/');

        // 1️⃣ Si el campo trae ruta válida ⇒ úsala tal‑cual
        if ($valorRuta) {
            $url = "$uploadsUrl/$valorRuta";
        } else {
            // 2️⃣ Si no hay nada, usa la genérica
            $url = "$uploadsUrl/default/no_disponible.jpg";
        }

        $html = '';

        if ($this->mostrarInput) {
            $html .= Html::activeFileInput($this->model, $this->atributo,
                       ['class' => 'form-control img-view img-thumbnail mt-4'])."\n";
        }

        return $html . Html::img($url, $this->htmlOptions);
    }
}
