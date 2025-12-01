<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

use Yii;

/**
 * This is the model class for table "imagenes_galeria".
 *
 * @property int $img_id Identificador único de la imagen
 * @property int|null $img_gal_id ID de la galería a la que pertenece la imagen
 * @property string|null $img_ruta Ruta de la imagen en el sistema de archivos o URL
 * @property string|null $img_descripcion Descripción de la imagen
 * @property string|null $img_estado Estado de la imagen (publicado/borrador)
 * @property string|null $created_at Fecha y hora de creación del registro
 * @property string|null $updated_at Fecha y hora de última modificación del registro
 * @property int|null $created_by ID del usuario que creó el registro
 * @property int|null $updated_by ID del usuario que actualizó el registro
 */
class ImagenesGaleria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'imagenes_galeria';
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
    public function rules()
    {
        return [
            [['img_gal_id', 'created_by', 'updated_by'], 'integer'],
            [['img_descripcion', 'img_estado'], 'string'],
            [['img_ruta'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'img_id' => 'Identificador único de la imagen',
            'img_gal_id' => 'ID de la galería a la que pertenece la imagen',
            'img_ruta' => 'Ruta de la imagen en el sistema de archivos o URL',
            'img_descripcion' => 'Descripción de la imagen',
            'img_estado' => 'Estado de la imagen (publicado/borrador)',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
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
