<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "asuntos".
 *
 * @property int $asu_id ID del asunto
 * @property string $asu_nombre Nombre del asunto
 * @property string $asu_publicado Indicador de si el asunto está publicado
 *
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Asunto extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'asuntos';
    }

    public function rules()
    {
        return [
            [['asu_nombre', 'asu_publicado'], 'required'],
            [['asu_publicado'], 'string'],
            [['asu_nombre'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'asu_id' => 'ID del asunto',
            'asu_nombre' => 'Nombre del asunto',
            'asu_publicado' => 'Indicador de si el asunto está publicado',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
            'created_by' => 'Creado por',
            'updated_by' => 'Modificado por',
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
