<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "lecciones".
 *
 * @property int $lec_id ID único de la lección
 * @property string $lec_titulo Título de la lección
 * @property string|null $lec_contenido Contenido de la lección
 * @property string $lec_tipo Tipo de contenido
 * @property int|null $lec_orden Orden dentro del módulo
 * @property string $lec_estado Estado de la lección
 * @property string $lec_slug Slug de la lección
 * @property string|null $lec_imagen Imagen destacada de la lección
 * @property string|null $lec_icono Ícono representativo de la lección
 * @property int $lec_mod_id ID del módulo al que pertenece la lección
 *
 * @property string|null $created_at Fecha y hora de creación del registro
 * @property string|null $updated_at Fecha y hora de última modificación del registro
 * @property int|null $created_by ID del usuario que creó el registro
 * @property int|null $updated_by ID del usuario que actualizó el registro
 */
class Leccion extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'lecciones';
    }

    public function rules()
    {
        return [
            [['lec_titulo', 'lec_slug', 'lec_mod_id'], 'required'],
            [['lec_contenido', 'lec_tipo', 'lec_estado', 'lec_slug', 'lec_icono'], 'string'],
            [['lec_orden', 'lec_mod_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['lec_titulo', 'lec_imagen'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lec_id' => 'ID único de la lección',
            'lec_titulo' => 'Título de la lección',
            'lec_contenido' => 'Contenido de la lección',
            'lec_tipo' => 'Tipo de contenido',
            'lec_orden' => 'Orden dentro del módulo',
            'lec_estado' => 'Estado de la lección',
            'lec_slug' => 'Slug de la lección',
            'lec_imagen' => 'Imagen destacada de la lección',
            'lec_icono' => 'Ícono representativo de la lección',
            'lec_mod_id' => 'ID del módulo al que pertenece la lección',
            'created_at' => 'Fecha y hora de creación del registro',
            'updated_at' => 'Fecha y hora de última modificación del registro',
            'created_by' => 'ID del usuario que creó el registro',
            'updated_by' => 'ID del usuario que actualizó el registro',
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

    public function getModulo()
    {
        return $this->hasOne(Modulo::class, ['mod_id' => 'lec_mod_id']);
    }

    public function getCurso()
    {
        return $this->hasOne(Curso::class, ['cur_id' => 'mod_cur_id'])->via('modulo');
    }

    public function getUsuarioCreador()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUsuarioEditor()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
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