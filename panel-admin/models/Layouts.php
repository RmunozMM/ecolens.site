<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

use Yii;

/**
 * This is the model class for table "layouts".
 *
 * @property int $lay_id
 * @property string $lay_nombre Nombre del layout
 * @property string|null $lay_ruta_imagenes Ruta de las imágenes
 * @property string $lay_estado Estado del layout (activo/inactivo)
 * @property string|null $created_at Fecha y hora de creación del registro
 * @property string|null $updated_at Fecha y hora de última modificación del registro
 * @property int|null $created_by ID del usuario que creó el registro
 * @property int|null $updated_by ID del usuario que actualizó el registro
 */
class Layouts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'layouts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lay_nombre', 'lay_estado'], 'required'],
            [['lay_estado'], 'string'],
            [['lay_nombre'], 'string', 'max' => 100],
            [['lay_ruta_imagenes'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
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
            'lay_id' => 'Lay ID',
            'lay_nombre' => 'Nombre del layout',
            'lay_ruta_imagenes' => 'Ruta de las imágenes',
            'lay_estado' => 'Estado del layout (activo/inactivo)',
            'created_at' => 'Fecha y hora de creación del registro',
            'updated_at' => 'Fecha y hora de última modificación del registro',
            'created_by' => 'ID del usuario que creó el registro',
            'updated_by' => 'ID del usuario que actualizó el registro',
        ];
    }

    public function getSitioOpciones()
    {
        return $this->hasOne(Opcion::className(), ['opc_valor' => 'lay_nombre']);
    }

    public function getColores()
    {
        return $this->hasMany(Colores::class, ['col_layout_id' => 'lay_id']);
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
