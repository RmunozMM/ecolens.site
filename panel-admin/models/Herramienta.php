<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\models\Users;

/**
 * This is the model class for table "herramientas".
 *
 * @property int $her_id ID de la herramienta
 * @property string $her_nombre Nombre de la herramienta
 * @property int $her_nivel Nivel de la herramienta
 * @property string $her_publicada Estado de Publicación
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de última modificación
 * @property int|null $created_by Usuario creador
 * @property int|null $updated_by Usuario modificador
 */
class Herramienta extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'herramientas';
    }

    public function rules()
    {
        return [
            [['her_nombre', 'her_nivel', 'her_publicada'], 'required'],
            [['her_nivel', 'created_by', 'updated_by'], 'integer'],
            [['her_publicada'], 'string'],
            [['her_nombre'], 'string', 'max' => 100],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'her_id' => 'ID de la herramienta',
            'her_nombre' => 'Nombre de la herramienta',
            'her_nivel' => 'Nivel de la herramienta',
            'her_publicada' => 'Estado de Publicación',
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