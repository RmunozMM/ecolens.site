<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "perfil".
 */
class Perfil extends ActiveRecord
{
    public static function tableName()
    {
        return 'perfil';
    }

    public function rules()
    {
        return [
            [['per_tipo', 'per_nombre'], 'required'],
            [['per_fecha_nacimiento'], 'date', 'format' => 'php:Y-m-d'],
            [['per_idiomas'], 'string'],
            [['created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', ], 'safe'],
            [['per_tipo'], 'in', 'range' => ['persona', 'empresa']],
            [[
                'per_nombre', 'per_lugar_nacimiento_fundacion', 'per_ubicacion', 'per_nacionalidad',
                'per_correo', 'per_telefono', 'per_direccion', 'per_linkedin',
                'per_sitio_web', 'per_sector', 'per_imagen'
            ], 'string', 'max' => 255],
            [['singleton'], 'boolean'],
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

    public function attributeLabels()
    {
        return [
            'per_id' => 'ID del Perfil',
            'per_tipo' => 'Tipo de Perfil',
            'per_nombre' => 'Nombre',
            'per_fecha_nacimiento' => 'Fecha de Nacimiento / Fundación',
            'per_lugar_nacimiento_fundacion' => 'Lugar de Nacimiento / Fundación',
            'per_ubicacion' => 'Ubicación',
            'per_nacionalidad' => 'Nacionalidad',
            'per_correo' => 'Correo',
            'per_telefono' => 'Teléfono',
            'per_direccion' => 'Dirección',
            'per_linkedin' => 'LinkedIn',
            'per_sitio_web' => 'Sitio Web',
            'per_sector' => 'Sector',
            'per_idiomas' => 'Idiomas',
            'singleton' => 'Registro Único',
            'per_imagen' => 'Imagen de Perfil',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
            'created_by' => 'Creado por',
            'updated_by' => 'Modificado por',
        ];
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