<?php

namespace app\models;
class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    
    public $usu_id;
    public $usu_username;
    public $usu_email;
    public $usu_email_verificado;
    public $usu_password;
    public $usu_authKey;
    public $usu_accessToken;
    public $usu_activate;
    public $usu_imagen;
    public $usu_rol_id;
    public $usu_nombres;
    public $usu_apellidos;
    public $usu_telefono;
    public $usu_ubicacion;
    public $usu_letra;


    // Campos agregados por auditoría (evita error al recibir estos campos desde Users)
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by;

    /**
     * @inheritdoc
     */
    
    /* busca la identidad del usuario a través de su $usu_id */

    public static function findIdentity($usu_id)
    {
        
        $user = Users::find()
                ->where("usu_activate=:usu_activate", [":usu_activate" => "SI"])
                ->andWhere("usu_id=:usu_id", ["usu_id" => $usu_id])
                ->andWhere("usu_email_verificado=:usu_email_verificado", ["usu_email_verificado" => "SI"])
                ->one();
        
        return isset($user) ? new static($user) : null;
    }

    /**
     * @inheritdoc
     */
    
    /* Busca la identidad del usuario a través de su token de acceso */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        
        $users = Users::find()
                ->where("usu_activate=:usu_activate", [":usu_activate" => 'SI'])
                ->andWhere("accessToken=:accessToken", [":accessToken" => $token])
                ->all();
        
        foreach ($users as $user) {
            if ($user->accessToken === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $usu_username
     * @return static|null
     */
    
    /* Busca la identidad del usuario a través del username */
    public static function findByUsername($usu_username)
    {
        $users = Users::find()
                ->where("usu_activate=:usu_activate", ["usu_activate" => 'SI'])
                ->andWhere("usu_username=:usu_username", [":usu_username" => $usu_username])
                ->all();
        

        foreach ($users as $user) {
            if (strcasecmp($user->usu_username, $usu_username) === 0 ) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    
    /* Regresa el id del usuario */
    public function getId()
    {
        return $this->usu_id;
    }

    /**
     * @inheritdoc
     */
    
    /* Regresa la clave de autenticación */
    public function getAuthKey()
    {
        return $this->usu_authKey;
    }
    
    /**
     * @inheritdoc
     */
    
    /* Valida la clave de autenticación */
    public function validateAuthKey($usu_authKey)
    {
        return $this->usu_authKey === $usu_authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $usu_password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($usu_password)
    {
        /* Valida el password */
        if (crypt($usu_password, $this->usu_password) == $this->usu_password)
        {
        return $usu_password === $usu_password;
        }
    }

    //le envio usuario y roles autorizados, si encuentra coincidencia permitirá acceder, caso contrario, retornará un error
    public static function checkRoleByUserId($usu_id, $usu_rol_id)
    {
    if (Users::findOne(['usu_id' => $usu_id, 'usu_activate' => 'SI', 'usu_rol_id' => $usu_rol_id])){
        return true;
    } else {
        return false;
       }
    } 


}