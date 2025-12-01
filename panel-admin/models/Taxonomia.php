<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "taxonomias".
 *
 * @property int $tax_id Identificador único de la clase taxonómica
 * @property string $tax_nombre Nombre científico o común del grupo (ej. Mammalia, Aves)
 * @property string|null $tax_nombre_comun Nombre común o local del grupo (ej. Mamíferos, Aves)
 * @property string|null $tax_descripcion Descripción general del grupo taxonómico
 * @property string|null $tax_imagen Imagen representativa o ícono del grupo
 * @property string|null $tax_estado Define si el grupo está disponible para clasificación
 * @property string|null $created_at Fecha de creación del registro
 * @property string|null $updated_at Fecha de última modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Taxonomia extends \yii\db\ActiveRecord
{
    const TAX_ESTADO_ACTIVO = 'activo';
    const TAX_ESTADO_INACTIVO = 'inactivo';

    public static function tableName()
    {
        return 'taxonomias';
    }

    public function rules()
    {
        return [
            [['tax_descripcion', 'tax_imagen', 'tax_nombre_comun', 'updated_at', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['tax_estado'], 'default', 'value' => self::TAX_ESTADO_ACTIVO],
            [['tax_nombre'], 'required'],
            [['tax_descripcion', 'tax_estado'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['tax_nombre', 'tax_nombre_comun'], 'string', 'max' => 150],
            [['tax_imagen'], 'string', 'max' => 255],
            ['tax_estado', 'in', 'range' => array_keys(self::optsTaxEstado())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'tax_id' => 'Identificador único de la clase taxonómica',
            'tax_nombre' => 'Nombre científico',
            'tax_nombre_comun' => 'Nombre común o local',
            'tax_slug' => 'URL de la taxonomía',
            'tax_descripcion' => 'Descripción general del grupo taxonómico',
            'tax_imagen' => 'Imagen representativa o ícono del grupo',
            'tax_estado' => 'Estado de la Taxonomía',
            'created_at' => 'Fecha de creación del registro',
            'updated_at' => 'Fecha de última modificación',
            'created_by' => 'Usuario que creó el registro',
            'updated_by' => 'Usuario que actualizó el registro',
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

    public static function optsTaxEstado()
    {
        return [
            self::TAX_ESTADO_ACTIVO => 'activo',
            self::TAX_ESTADO_INACTIVO => 'inactivo',
        ];
    }

    public function displayTaxEstado()
    {
        return self::optsTaxEstado()[$this->tax_estado];
    }

    public function isTaxEstadoActivo()
    {
        return $this->tax_estado === self::TAX_ESTADO_ACTIVO;
    }

    public function setTaxEstadoToActivo()
    {
        $this->tax_estado = self::TAX_ESTADO_ACTIVO;
    }

    public function isTaxEstadoInactivo()
    {
        return $this->tax_estado === self::TAX_ESTADO_INACTIVO;
    }

    public function setTaxEstadoToInactivo()
    {
        $this->tax_estado = self::TAX_ESTADO_INACTIVO;
    }

    /**
     * Relación con especies (1:N)
     */
    public function getEspecies()
    {
        return $this->hasMany(\app\models\Especie::class, ['esp_tax_id' => 'tax_id'])
            ->where(['esp_estado' => 'activo'])
            ->orderBy(['esp_nombre_cientifico' => SORT_ASC]);
    }

    /**
     * Relación con usuarios (creador / modificador)
     */
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }
}