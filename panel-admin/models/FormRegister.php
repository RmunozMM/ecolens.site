<?php

namespace app\models;
use Yii;
use yii\base\model;
use app\models\Users;

class FormRegister extends model{
    public $usu_id;
    public $usu_username;
    public $usu_email;
    public $usu_password;
    public $usu_rol_id;
    
   // public $usu_password_repeat;
    
    public function rules()
    {
        return [
            [['usu_username', 'usu_email'], 'required', 'message' => 'Campo requerido'],
            ['usu_username', 'match', 'pattern' => "/^.{3,50}$/", 'message' => 'Mínimo 3 y máximo 50 caracteres'],
            ['usu_username', 'match', 'pattern' => "/^[0-9a-z]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['usu_username', 'username_existe'],
            ['usu_email', 'match', 'pattern' => "/^.{5,80}$/", 'message' => 'Mínimo 5 y máximo 80 caracteres'],
            ['usu_email', 'email', 'message' => 'Formato no válido'],
            ['usu_email', 'email_existe'],
            //['password', 'match', 'pattern' => "/^.{6,16}$/", 'message' => 'Mínimo 6 y máximo 16 caracteres'],
           // ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Los passwords no coinciden'],
        ];
    }
    
    public function email_existe($attribute, $params)
    {
  
  //Buscar el email en la tabla
  $table = Users::find()->where("usu_email=:usu_email", [":usu_email" => $this->usu_email]);
  
  //Si el email existe mostrar el error
  if ($table->count() == 1)
  {
                $this->addError($attribute, "El email seleccionado existe");
  }
    }
 
    public function username_existe($attribute, $params)
    {
  //Buscar el username en la tabla
  $table = Users::find()->where("usu_username=:usu_username", [":usu_username" => $this->usu_username]);
  
  //Si el username existe mostrar el error
  if ($table->count() == 1)
  {
                $this->addError($attribute, "El usuario seleccionado existe");
  }
    }
 
}