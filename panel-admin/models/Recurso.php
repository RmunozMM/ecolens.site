<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "recursos".
 *
 * @property int $rec_id
 * @property string $rec_titulo
 * @property string $rec_tipo
 * @property string|null $rec_url
 * @property string|null $rec_descripcion
 * @property string|null $rec_imagen
 * @property string|null $rec_icono
 * @property string $rec_estado
 * @property string $rec_slug
 * @property int|null $rec_lec_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Recurso extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'recursos';
    }

    public function rules()
    {
        return [
            [['rec_titulo', 'rec_slug'], 'required'],
            [['rec_tipo', 'rec_descripcion', 'rec_estado', 'rec_slug', 'rec_icono'], 'string'],
            [['created_by', 'updated_by', 'rec_lec_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['rec_url', 'rec_imagen', 'rec_titulo'], 'string', 'max' => 255],
            [['rec_slug'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'rec_id' => 'ID único del recurso',
            'rec_titulo' => 'Título del recurso',
            'rec_tipo' => 'Tipo de recurso (video, documento, imagen, enlace)',
            'rec_url' => 'URL o ruta del recurso',
            'rec_descripcion' => 'Descripción del recurso',
            'rec_imagen' => 'Imagen del recurso',
            'rec_icono' => 'Ícono representativo',
            'rec_estado' => 'Estado (activo/inactivo)',
            'rec_slug' => 'Slug del recurso',
            'rec_lec_id' => 'Lección asociada',
            'created_by' => 'Creado por',
            'updated_by' => 'Modificado por',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
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
                    return date('Y-m-d H:i:s'); // Formato correcto para MySQL
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

    // RELACIONES
    public function getLeccion()
    {
        return $this->hasOne(Leccion::class, ['lec_id' => 'rec_lec_id']);
    }

    public function getModulo()
    {
        return $this->hasOne(Modulo::class, ['mod_id' => 'lec_mod_id'])->via('leccion');
    }

    public function getCurso()
    {
        return $this->hasOne(Curso::class, ['cur_id' => 'mod_cur_id'])->via('modulo');
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