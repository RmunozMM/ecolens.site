<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Yii;

/**
 * This is the model class for table "servicios".
 *
 * @property int $ser_id Identificador único de Servicios
 * @property string $ser_titulo Titulo del Servicio
 * @property string $ser_slug Slug del Servicio
 * @property string|null $ser_resumen Resumen del Servicio
 * @property string|null $ser_cuerpo Cuerpo del Servicio
 * @property string $ser_publicado Estado de publicación del Servicio
 * @property string $ser_destacado ¿ServicioDestacado?
 * @property string|null $ser_imagen Imagen del Servicio
 * @property string|null $ser_icono Ícono del Servicio
 * @property int $ser_cat_id Categoría del Servicio
 */
class Servicio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ser_titulo','ser_publicado', 'ser_destacado','ser_cat_id'], 'required'],
            [['ser_id', ], 'integer'],
            [['ser_cuerpo', 'ser_publicado', 'ser_destacado', 'ser_icono'], 'string'],
            [['ser_titulo', 'ser_resumen'], 'string', 'max' => 100],
            [['ser_slug'], 'string', 'max' => 255],
            [['ser_imagen'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ser_id' => 'Identificador único de Servicios',
            'ser_titulo' => 'Titulo del Servicio',
            'ser_slug' => 'Slug del Servicio',
            'ser_resumen' => 'Resumen del Servicio',
            'ser_cuerpo' => 'Cuerpo del Servicio',
            'ser_publicado' => 'Estado de publicación del Servicio',
            'ser_destacado' => '¿Servicio Destacado?',
            'ser_creacion' => 'Fecha de creación del Servicio',
            'ser_modificacion' => 'Fecha de última modificación del Servicio',
            'ser_imagen' => 'Imagen del Servicio',
            'ser_icono' => 'Ícono del Servicio',
            'ser_cat_id' => 'Categoría del Servicio',
        ];
    }
    public function getCategoriaServicio()
    {
        // Si tu columna foránea es ser_cat_id y en la tabla categoria_servicios la PK es cas_id
        return $this->hasOne(CategoriaServicio::class, ['cas_id' => 'ser_cat_id']);
    }
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
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
}
