<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "articulos".
 *
 * @property int $art_id ID único del artículo
 * @property string $art_titulo Título del artículo
 * @property string|null $art_contenido Contenido HTML del artículo
 * @property string|null $art_resumen Resumen o descripción del artículo
 * @property string|null $art_etiquetas Palabras clave o etiquetas del artículo
 * @property string|null $art_fecha_publicacion Fecha de publicación del artículo
 * @property string|null $art_destacado Indicador para marcar el artículo como destacado
 * @property int|null $art_vistas Número de veces que se ha visto el artículo
 * @property int|null $art_likes Número de "me gusta" del artículo
 * @property string|null $art_comentarios_habilitados Indicador para permitir comentarios en el artículo
 * @property string|null $art_palabras_clave Palabras clave relacionadas con el contenido del artículo
 * @property string|null $art_meta_descripcion Meta descripción del artículo
 * @property string|null $art_slug Slug del artículo (único)
 * @property string $art_estado Estado del artículo
 * @property int|null $art_categoria_id ID de la categoría del artículo
 * @property string|null $art_notificacion ¿Notificar a suscriptores?
 * @property string|null $art_imagen URL de la imagen principal del artículo
 *
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Articulo extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'articulos';
    }

    public function rules()
    {
        return [
            [['art_titulo', 'art_categoria_id'], 'required'],
            [['art_contenido', 'art_resumen', 'art_destacado', 'art_comentarios_habilitados', 'art_palabras_clave', 'art_meta_descripcion', 'art_estado', 'art_notificacion'], 'string'],
            [['art_fecha_publicacion'], 'safe'],
            [['art_vistas', 'art_likes', 'art_categoria_id'], 'integer'],
            [['art_titulo', 'art_etiquetas', 'art_slug'], 'string', 'max' => 255],
            [['art_imagen'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'], // ✅ agregado
        ];
    }

    public function attributeLabels()
    {
        return [
            'art_id' => 'ID único del artículo',
            'art_titulo' => 'Título del artículo',
            'art_contenido' => 'Contenido HTML del artículo',
            'art_resumen' => 'Resumen o descripción del artículo',
            'art_etiquetas' => 'Palabras clave o etiquetas del artículo',
            'art_fecha_publicacion' => 'Fecha de publicación del artículo',
            'art_destacado' => 'Indicador para marcar el artículo como destacado',
            'art_vistas' => 'Número de veces que se ha visto el artículo',
            'art_likes' => 'Número de "me gusta" del artículo',
            'art_comentarios_habilitados' => 'Indicador para permitir comentarios en el artículo',
            'art_palabras_clave' => 'Palabras clave relacionadas con el contenido del artículo',
            'art_meta_descripcion' => 'Meta descripción del artículo',
            'art_slug' => 'Slug del artículo (único)',
            'art_estado' => 'Estado del artículo',
            'art_categoria_id' => 'ID de la categoría del artículo',
            'art_notificacion' => '¿Notificar a suscriptores?',
            'art_imagen' => 'URL de la imagen principal del artículo',
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

    public function getCategoriaArticulo()
    {
        return $this->hasOne(CategoriaArticulo::class, ['caa_id' => 'art_categoria_id']);
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
