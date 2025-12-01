<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Galerias;

class ManageGaleriaButton extends \yii\bootstrap5\Widget
{
    public $id;

    public function run()
    {
        $controllerId = Yii::$app->controller->id;
        $recordIdParam = $this->id;

        // ¿Existe galería para este registro?
        $galeriaExistente = Galerias::find()
            ->where(['gal_tipo_registro' => $controllerId, 'gal_id_registro' => $recordIdParam])
            ->one();

        // Definir la URL según exista o no
        $url = $galeriaExistente
            ? Url::to(['galeria/update', 'gal_id' => $galeriaExistente->gal_id])
            : Url::to(['galeria/create', 'tipo_registro' => $controllerId, 'id' => $recordIdParam]);

        // Ícono de carpeta abierta o cerrada
        $textoBoton = $galeriaExistente
            ? '<i class="fa-solid fa-folder-open"></i>'
            : '<i class="fa-regular fa-folder-closed"></i>';

        // Tooltip
        $tooltipText = $galeriaExistente ? 'Editar Galería' : 'Crear Galería';

        // Clases CSS para distinguir "existe/no existe" + la clase base .btn-action
        $btnClass = $galeriaExistente
            ? 'btn-action btn-manage-galeria btn-manage-galeria-exists'
            : 'btn-action btn-manage-galeria btn-manage-galeria-empty';

        return Html::a($textoBoton, $url, [
            'title' => $tooltipText,
            'role' => 'button',
            'class' => $btnClass,
        ]);
    }
}