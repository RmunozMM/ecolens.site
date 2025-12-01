<?php
namespace app\helpers;

use yii\helpers\Html;

/**
 * Wrapper para un array clave=>valor que escapa automáticamente
 * cada valor cuando se accede como propiedad.
 */
class Options
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Acceso a propiedades: retorna Html::encode(valor).
     */
    public function __get(string $name)
    {
        $val = $this->data[$name] ?? null;
        return $val !== null
            ? Html::encode($val)
            : null;
    }

    /**
     * Para isset($opciones->clave)
     */
    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }
}