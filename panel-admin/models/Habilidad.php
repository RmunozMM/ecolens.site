<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\models\Users;

/**
 * This is the model class for table "habilidades".
 *
 * @property int $hab_id ID de la habilidad
 * @property string $hab_nombre Nombre de la habilidad
 * @property int $hab_nivel Nivel de la habilidad
 * @property string $hab_publicada Estado de Publicación
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de última modificación
 * @property int|null $created_by Usuario creador
 * @property int|null $updated_by Usuario modificador
 */
class Habilidad extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'habilidades';
    }

    public function rules()
    {
        return [
            [['hab_nombre', 'hab_nivel', 'hab_publicada'], 'required'],
            [['hab_nivel', 'created_by', 'updated_by'], 'integer'],
            [['hab_publicada'], 'string'],
            [['hab_nombre'], 'string', 'max' => 100],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'hab_id' => 'ID de la habilidad',
            'hab_nombre' => 'Nombre de la habilidad',
            'hab_nivel' => 'Nivel de la habilidad',
            'hab_publicada' => 'Estado de Publicación',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de última modificación',
            'created_by' => 'Usuario creador',
            'updated_by' => 'Usuario modificador',
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

    public function getUsuario()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
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
