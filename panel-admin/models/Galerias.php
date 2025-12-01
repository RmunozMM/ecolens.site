<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "galerias".
 *
 * @property int $gal_id Identificador único de la galería
 * @property string|null $gal_tipo_registro Tipo de registro asociado a la galería
 * @property int|null $gal_id_registro ID del registro asociado a la galería
 * @property string|null $gal_descripcion Descripción de la galería
 * @property string|null $gal_estado Estado de la galería (publicado/borrador)
 * @property string|null $gal_titulo Título de la galería
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de última modificación
 * @property int|null $created_by ID del usuario que creó la galería
 * @property int|null $updated_by ID del usuario que modificó la galería
 */
class Galerias extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'galerias';
    }

    public function rules()
    {
        return [
            [['gal_id_registro', 'created_by', 'updated_by'], 'integer'],
            [['gal_descripcion', 'gal_estado'], 'string'],
            [['gal_tipo_registro', 'gal_titulo'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'gal_id' => 'Identificador único de la galería',
            'gal_tipo_registro' => 'Tipo de registro asociado a la galería',
            'gal_id_registro' => 'ID del registro asociado a la galería',
            'gal_descripcion' => 'Descripción de la galería',
            'gal_estado' => 'Estado de la galería (publicado/borrador)',
            'gal_titulo' => 'Título de la galería',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de última modificación',
            'created_by' => 'Usuario que creó la galería',
            'updated_by' => 'Usuario que modificó la galería',
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

    public function getImagenesGaleria()
    {
        return $this->hasMany(ImagenesGaleria::class, ['img_gal_id' => 'gal_id']);
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
