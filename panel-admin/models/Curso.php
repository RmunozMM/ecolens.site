<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "cursos".
 *
 * @property int $cur_id ID único del curso
 * @property string $cur_titulo Título del curso
 * @property string|null $cur_descripcion Descripción general del curso
 * @property string|null $cur_imagen Imagen de portada del curso
 * @property string|null $cur_icono Ícono representativo del curso
 * @property string $cur_estado Estado del curso
 * @property string $cur_slug Slug del curso para URL amigable
 *
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Curso extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'cursos';
    }

    public function rules()
    {
        return [
            [['cur_titulo', 'cur_slug'], 'required'],
            [['cur_descripcion', 'cur_estado', 'cur_slug', 'cur_icono'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['cur_titulo', 'cur_imagen'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cur_id' => 'ID único del curso',
            'cur_titulo' => 'Título del curso',
            'cur_descripcion' => 'Descripción general del curso',
            'cur_imagen' => 'Imagen de portada del curso',
            'cur_icono' => 'Ícono representativo del curso',
            'cur_estado' => 'Estado del curso',
            'cur_slug' => 'Slug del curso para URL amigable',
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