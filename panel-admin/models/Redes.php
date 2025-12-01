<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "redes_sociales".
 *
 * @property int $red_id ID de la red social
 * @property string $red_nombre Nombre de la red social
 * @property string $red_enlace Enlace asociado a la red social
 * @property string $red_perfil Perfil de la red social
 * @property string $red_publicada Indica si la red social está publicada
 * @property string $red_icono Icono de la red social
 * @property string $red_categoria Categoría de la Red Social
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Redes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'redes_sociales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['red_nombre', 'red_enlace'], 'required'],
            [['red_nombre'], 'unique'],
            [['red_publicada', 'red_perfil'], 'string'],
            [['created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['red_nombre', 'red_enlace', 'red_icono'], 'string', 'max' => 255],
            [['red_perfil'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'red_id' => 'ID de la red social',
            'red_nombre' => 'Nombre de la red social',
            'red_enlace' => 'Enlace asociado a la red social',
            'red_perfil' => 'Perfil de la red social',
            'red_publicada' => 'Indica si la red social está publicada',
            'red_icono' => 'Icono de la red social',
            'red_categoria' => 'Categoría de la red social',
            'created_by' => 'Creado por',
            'updated_by' => 'Actualizado por',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de última modificación',
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
}
