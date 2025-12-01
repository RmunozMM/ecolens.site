<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "modulos".
 *
 * @property int $mod_id ID único del módulo
 * @property string $mod_titulo Título del módulo
 * @property string|null $mod_descripcion Descripción del módulo
 * @property int|null $mod_orden Orden del módulo dentro del curso
 * @property string $mod_estado Estado del módulo
 * @property string $mod_slug Slug del módulo
 * @property string|null $mod_imagen Imagen de portada del módulo
 * @property string|null $mod_icono Ícono representativo del módulo
 * @property int $mod_cur_id ID del curso al que pertenece el módulo
 * @property string $created_at Fecha de creación
 * @property string $updated_at Fecha de modificación
 * @property int $created_by ID del usuario que creó
 * @property int $updated_by ID del usuario que modificó
 */
class Modulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modulos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mod_titulo', 'mod_slug', 'mod_cur_id'], 'required'],
            [['mod_orden',  'mod_cur_id', 'created_by', 'updated_by'], 'integer'],
            [['mod_estado', 'mod_slug', 'mod_imagen', 'mod_descripcion', 'mod_icono'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['mod_titulo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mod_id' => 'ID único del módulo',
            'mod_titulo' => 'Título del módulo',
            'mod_descripcion' => 'Descripción del módulo',
            'mod_orden' => 'Orden del módulo dentro del curso',
            'mod_estado' => 'Estado del módulo',
            'mod_slug' => 'Slug del módulo',
            'mod_imagen' => 'Imagen de portada del módulo',
            'mod_icono' => 'Ícono representativo del módulo',
            'mod_cur_id' => 'ID del curso al que pertenece el módulo',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de última modificación',
            'created_by' => 'Creado por',
            'updated_by' => 'Modificado por',
        ];
    }

    /**
     * Relaciones
     */
    public function getCurso()
    {
        return $this->hasOne(Curso::class, ['cur_id' => 'mod_cur_id']);
    }

    /**
     * Comportamientos: auditoría automática
     */
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
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }
}
