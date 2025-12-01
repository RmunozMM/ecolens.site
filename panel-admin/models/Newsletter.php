<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "newsletter".
 *
 * @property int $new_id ID único del suscriptor
 * @property string $new_email Correo del suscriptor
 * @property string $new_estado Estado de la suscripción
 * @property string $new_verificado Indica si el email fue verificado
 * @property string $created_at Fecha de suscripción
 * @property string|null $updated_at Última modificación del registro
 * @property int $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que modificó el registro
 */
class Newsletter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newsletter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['new_email'], 'required'],
            [['new_estado', 'new_verificado'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['new_email'], 'string', 'max' => 255],
            [['new_email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'new_id' => 'ID único del suscriptor',
            'new_email' => 'Correo del suscriptor',
            'new_estado' => 'Estado de la suscripción',
            'new_verificado' => 'Indica si el email fue verificado',
            'created_at' => 'Fecha de suscripción',
            'updated_at' => 'Última modificación del registro',
            'created_by' => 'Usuario que creó el registro',
            'updated_by' => 'Usuario que modificó el registro',
        ];
    }

    /**
     * Comportamientos de auditoría: timestamps y usuario
     */
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

    public function getCreador()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getEditor()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
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
