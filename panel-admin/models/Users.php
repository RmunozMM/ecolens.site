<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use app\models\Rol;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $usu_id
 * @property string $usu_username
 * @property string $usu_email
 * @property string $usu_email_verificado
 * @property string $usu_authKey
 * @property string $usu_accessToken
 * @property string $usu_password
 * @property int $usu_activate
 * @property string $usu_imagen
 * @property int $usu_rol_id
 * @property string $usu_nombres 
 * @property string $usu_apellidos
 * @property string $usu_telefono
 * @property string $usu_ubicacion
 * @property int $usu_letra
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usu_username', 'usu_email', 'usu_password', 'usu_authKey', 'usu_accessToken'], 'required'],
            [['usu_rol_id', 'usu_letra', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['usu_username', 'usu_ubicacion'], 'string', 'max' => 50],
            [['usu_email'], 'string', 'max' => 80],
            [['usu_nombres', 'usu_apellidos', 'usu_telefono'], 'string', 'max' => 100],
            [['usu_password', 'usu_authKey', 'usu_accessToken'], 'string', 'max' => 250],

                [['usu_imagen'], 'file', 'skipOnEmpty' => true,
                'extensions' => ['jpg','jpeg','png','gif','webp'],
                'maxSize' => 5 * 1024 * 1024, // 5MB, ajusta si quieres
                ]

        ];

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usu_id' => 'ID',
            'usu_username' => 'Username',
            'usu_email' => 'Email',
            'usu_password' => 'Password',
            'usu_authKey' => 'Auth Key',
            'usu_accessToken' => 'Access Token',
            'usu_activate' => 'Activate',
            'usu_imagen' => 'Imagen',
            'usu_rol_id' => 'Rol',
            'usu_nombres' => 'Nombres',
            'usu_apellidos' => 'Apellidos',
            'usu_telefono' => 'Teléfono',
            'usu_ubicacion' => 'Ubicación principal del usuario',
            'usu_letra' => 'Tamaño de letra del cms',
            'created_by' => 'Creado por',
            'updated_by' => 'Actualizado por',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
                'defaultValue' => Yii::$app->user->id ?? null,
            ],
        ];
    }

    public function getRol()
    {
        return $this->hasOne(Rol::class, ['rol_id' => 'usu_rol_id']);
    }
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }
}
