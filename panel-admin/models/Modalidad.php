<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Yii;

/**
 * This is the model class for table "modalidad".
 *
 * @property int $mod_id Identificador único de modalidad
 * @property string $mod_nombre Nombre de la modalidad de trabajo
 * @property string $mod_publicado Indica si la modalidad está publicada
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de última modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Modalidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modalidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mod_nombre', 'mod_publicado'], 'required'],
            [['mod_publicado'], 'string'],
            [[ 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['mod_nombre'], 'string', 'max' => 100],
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
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mod_id' => 'Identificador único de modalidad',
            'mod_nombre' => 'Nombre de la modalidad de trabajo',
            'mod_publicado' => 'Indica si la modalidad está publicada',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de última modificación',
            'created_by' => 'Usuario que creó el registro',
            'updated_by' => 'Usuario que actualizó el registro',
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
