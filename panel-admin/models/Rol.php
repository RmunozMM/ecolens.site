<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

use Yii;
use yii\db\ActiveRecord;

/**
 * Este es el modelo para la tabla "roles".
 *
 * @property int $rol_id ID único del rol
 * @property string $rol_nombre Nombre del rol
 * @property string|null $rol_descripcion Descripción del rol
 */
class Rol extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rol_nombre'], 'required'],
            [['rol_nombre'], 'string', 'max' => 45],
            [['rol_descripcion'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rol_id' => 'ID del Rol',
            'rol_nombre' => 'Nombre del Rol',
            'rol_descripcion' => 'Descripción del Rol',
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

    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }

    // Puedes agregar relaciones aquí si alguna tabla hace referencia a roles
}