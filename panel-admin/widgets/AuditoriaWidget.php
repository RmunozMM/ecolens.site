<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use app\models\Users;

class AuditoriaWidget extends Widget
{
    public $model;
    public $modo = 'compacto'; // 'compacto' o 'detallado'

    public function run()
    {
        if (!$this->model) return '';

        $creador = $this->formatUsuario($this->model->created_by);
        $modificador = $this->formatUsuario($this->model->updated_by);

        $fechaCreacion = $this->formatFecha($this->model->created_at);
        $fechaModificacion = $this->formatFecha($this->model->updated_at);

        switch ($this->modo) {
            case 'creado':
                return "<small><strong>$creador</strong><br><span>{$fechaCreacion}</span></small>";
            case 'modificado':
                return "<small><strong>$modificador</strong><br><span>{$fechaModificacion}</span></small>";
            case 'extendido':
                return "<p><strong>Creado por:</strong> $creador el $fechaCreacion</p><p><strong>Modificado por:</strong> $modificador el $fechaModificacion</p>";
            default: // compacto
                return "<small><strong>C:</strong> $creador ($fechaCreacion)<br><strong>M:</strong> $modificador ($fechaModificacion)</small>";
        }
    }


    protected function renderCompacto()
    {
        $creado = $this->formatUsuario($this->model->created_by);
        $modificado = $this->formatUsuario($this->model->updated_by);

        $fechaCreacion = $this->formatFecha($this->model->created_at);
        $fechaModificacion = $this->formatFecha($this->model->updated_at);

        return "<div class='auditoria-widget'>
            <small><strong>C:</strong> {$creado} ({$fechaCreacion})<br>
            <strong>M:</strong> {$modificado} ({$fechaModificacion})</small>
        </div>";
    }

    protected function renderDetallado()
    {
        $creado = $this->formatUsuario($this->model->created_by);
        $modificado = $this->formatUsuario($this->model->updated_by);

        $fechaCreacion = $this->formatFecha($this->model->created_at);
        $fechaModificacion = $this->formatFecha($this->model->updated_at);

        return "<div class='auditoria-widget'>
            <p><strong>Creado por:</strong> {$creado} el <em>{$fechaCreacion}</em></p>
            <p><strong>Última modificación por:</strong> {$modificado} el <em>{$fechaModificacion}</em></p>
        </div>";
    }

    protected function formatUsuario($userId)
    {
        $usuario = Users::findOne($userId);
        return $usuario ? Html::encode($usuario->usu_username) : '<span class="text-muted">Desconocido</span>';
    }

    protected function formatFecha2($fecha)
    {
        return $fecha ? date('d-m-Y H:i', strtotime($fecha)) : '<span class="text-muted">No registrada</span>';
    }


    protected function formatFecha($fecha)
{
    if (!$fecha) {
        return '<span class="text-muted">No registrada</span>';
    }

    // Convertimos la fecha al formato requerido por <input type="datetime-local">
    $valorInput = date('Y-m-d\TH:i', strtotime($fecha));

    return Html::input('datetime-local', null, $valorInput, [
        'class' => 'form-control form-control-sm',
        'readonly' => true, // para que no se pueda editar
        'style' => 'max-width: 250px;',
    ]);
}
}
