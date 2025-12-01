<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * Este es el modelo para la tabla "testimonios".
 *
 * @property int $tes_id
 * @property string $tes_nombre
 * @property string|null $tes_cargo
 * @property string|null $tes_empresa
 * @property string $tes_testimonio
 * @property string|null $tes_imagen
 * @property int $tes_orden
 * @property string $tes_estado
 * @property string|null $tes_slug
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Testimonio extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'testimonios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tes_nombre', 'tes_testimonio'], 'required'],
            [['tes_testimonio', 'tes_estado', 'tes_slug', 'tes_imagen'], 'string'],
            [['tes_orden', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['tes_nombre', 'tes_cargo', 'tes_empresa'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tes_id' => 'ID',
            'tes_nombre' => 'Nombre',
            'tes_cargo' => 'Cargo',
            'tes_empresa' => 'Empresa',
            'tes_testimonio' => 'Testimonio',
            'tes_imagen' => 'Imagen',
            'tes_orden' => 'Orden',
            'tes_estado' => 'Estado',
            'tes_slug' => 'Slug',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
            'created_by' => 'Creado por',
            'updated_by' => 'Modificado por',
        ];
    }

    /**
     * Relaciones para mostrar nombre de usuario
     */
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }

    /**
     * Comportamientos automáticos (timestamps)
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
}