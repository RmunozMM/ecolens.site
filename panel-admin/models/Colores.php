<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "colores".
 *
 * @property int $col_id
 * @property string $col_nombre
 * @property string|null $col_valor
 * @property string|null $col_descripcion
 *
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Colores extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'colores';
    }

    public function rules()
    {
        return [
            [['col_nombre'], 'required'],
            [['col_valor', 'col_descripcion'], 'string'],
            [['col_nombre'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'col_id' => 'Col ID',
            'col_nombre' => 'Col Nombre',
            'col_valor' => 'Col Valor',
            'col_descripcion' => 'Col Descripcion',
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

    public static function getLayoutName($col_layout_id)
    {
        $layout = Layouts::find()->select('lay_nombre')->where(['lay_id' => $col_layout_id])->scalar();
        return $layout ?? "CMS";
    }

    public function getLayout()
    {
        return $this->hasOne(Layouts::class, ['lay_id' => 'col_layout_id']);
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
