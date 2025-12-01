<?php

namespace app\helpers;

use Yii;
use app\helpers\LibreriaHelper;
use app\models\User;

class UsuarioHelper
{
    /**
     * Devuelve la URL de la imagen del usuario (o la imagen por defecto).
     *
     * @param User $usuario
     * @return string
     */
    public static function obtenerImagen($usuario)
    {
        

        return $usuario->usu_imagen
            ? LibreriaHelper::getRecursos(). 'uploads/' . $usuario->usu_imagen
            : LibreriaHelper::getRecursos(). 'uploads/users/sin_imagen.png';
    }
    public static function obtenerTamanioFuente($usuario)
    {
        return $usuario ? ($usuario->usu_letra ?: 15) : 15;
    }
    
}
