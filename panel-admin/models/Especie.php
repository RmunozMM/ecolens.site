<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "especies".
 *
 * @property int $esp_id
 * @property string $esp_nombre_cientifico
 * @property string|null $esp_slug
 * @property string|null $esp_nombre_comun
 * @property int|null $esp_tax_id
 * @property string|null $esp_descripcion
 * @property string|null $esp_imagen
 * @property string|null $esp_estado
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Taxonomia $taxonomia
 * @property Users $createdByUser
 * @property Users $updatedByUser
 * @property Deteccion[] $detecciones
 */
class Especie extends \yii\db\ActiveRecord
{
    const ESP_ESTADO_ACTIVO = 'activo';
    const ESP_ESTADO_INACTIVO = 'inactivo';

    public static function tableName()
    {
        return 'especies';
    }

    public function rules()
    {
        return [
            [['esp_nombre_cientifico'], 'required'],
            [['esp_tax_id', 'created_by', 'updated_by'], 'integer'],
            [['esp_descripcion', 'esp_estado'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['esp_nombre_cientifico', 'esp_slug', 'esp_nombre_comun', 'esp_imagen'], 'string', 'max' => 255],
            [['esp_estado'], 'in', 'range' => array_keys(self::optsEspEstado())],
            [['esp_slug'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'esp_id' => 'ID',
            'esp_nombre_cientifico' => 'Nombre científico',
            'esp_slug' => 'Slug',
            'esp_nombre_comun' => 'Nombre común',
            'esp_tax_id' => 'Clase taxonómica',
            'esp_descripcion' => 'Descripción',
            'esp_imagen' => 'Imagen',
            'esp_estado' => 'Estado',
            'created_at' => 'Creado el',
            'updated_at' => 'Actualizado el',
            'created_by' => 'Creado por',
            'updated_by' => 'Actualizado por',
        ];
    }

    public static function optsEspEstado()
    {
        return [
            self::ESP_ESTADO_ACTIVO => 'activo',
            self::ESP_ESTADO_INACTIVO => 'inactivo',
        ];
    }

    public function displayEspEstado()
    {
        return self::optsEspEstado()[$this->esp_estado] ?? 'desconocido';
    }

    public function isEspEstadoActivo()
    {
        return $this->esp_estado === self::ESP_ESTADO_ACTIVO;
    }

    public function setEspEstadoToActivo()
    {
        $this->esp_estado = self::ESP_ESTADO_ACTIVO;
    }

    public function isEspEstadoInactivo()
    {
        return $this->esp_estado === self::ESP_ESTADO_INACTIVO;
    }

    public function setEspEstadoToInactivo()
    {
        $this->esp_estado = self::ESP_ESTADO_INACTIVO;
    }

    /**
     * Relaciones
     */
    public function getTaxonomia()
    {
        return $this->hasOne(Taxonomia::class, ['tax_id' => 'esp_tax_id']);
    }

    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }

    public function getDetecciones()
    {
        return $this->hasMany(Deteccion::class, ['det_esp_id' => 'esp_id']);
    }

    /**
     * Comportamientos de auditoría
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

    /**
     * Genera automáticamente el slug si no se define manualmente
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->esp_slug) && !empty($this->esp_nombre_cientifico)) {
                $this->esp_slug = $this->generateSlug($this->esp_nombre_cientifico);
            }
            return true;
        }
        return false;
    }

    private function generateSlug($text)
    {
        $text = strtolower(trim($text));
        $text = str_replace([' ', '_'], '-', $text);
        $text = preg_replace('/[^a-z0-9\-]/', '', $text);
        return $text;
    }
}