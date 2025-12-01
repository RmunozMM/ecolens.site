<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modelos".
 *
 * @property int $mod_id Identificador único del modelo IA
 * @property string $mod_nombre Nombre del modelo (ej. EfficientNet-B5)
 * @property string|null $mod_version Versión o checkpoint interno del modelo
 * @property string|null $mod_archivo Ruta o nombre del archivo .pth del modelo
 * @property string|null $mod_dataset Dataset utilizado para el entrenamiento
 * @property float|null $mod_precision_val Precisión de validación en porcentaje
 * @property string|null $mod_fecha_entrenamiento Fecha de entrenamiento del modelo
 * @property string|null $mod_estado Estado operativo del modelo
 * @property string|null $mod_notas Observaciones o comentarios técnicos
 * @property string|null $mod_tipo
 */
class Modelos extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const MOD_ESTADO_ACTIVO = 'activo';
    const MOD_ESTADO_DEPRECADO = 'deprecado';
    const MOD_ESTADO_EN_ENTRENAMIENTO = 'en_entrenamiento';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modelos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mod_version', 'mod_archivo', 'mod_dataset', 'mod_precision_val', 'mod_notas', 'mod_tipo'], 'default', 'value' => null],
            [['mod_estado'], 'default', 'value' => 'activo'],
            [['mod_nombre'], 'required'],
            [['mod_precision_val'], 'number'],
            [['mod_fecha_entrenamiento'], 'safe'],
            [['mod_estado', 'mod_notas'], 'string'],
            [['mod_nombre'], 'string', 'max' => 100],
            [['mod_version'], 'string', 'max' => 50],
            [['mod_archivo', 'mod_dataset'], 'string', 'max' => 255],
            [['mod_tipo'], 'string', 'max' => 20],
            ['mod_estado', 'in', 'range' => array_keys(self::optsModEstado())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mod_id' => 'Mod ID',
            'mod_nombre' => 'Mod Nombre',
            'mod_version' => 'Mod Version',
            'mod_archivo' => 'Mod Archivo',
            'mod_dataset' => 'Mod Dataset',
            'mod_precision_val' => 'Mod Precision Val',
            'mod_fecha_entrenamiento' => 'Mod Fecha Entrenamiento',
            'mod_estado' => 'Mod Estado',
            'mod_notas' => 'Mod Notas',
            'mod_tipo' => 'Mod Tipo',
        ];
    }


    /**
     * column mod_estado ENUM value labels
     * @return string[]
     */
    public static function optsModEstado()
    {
        return [
            self::MOD_ESTADO_ACTIVO => 'activo',
            self::MOD_ESTADO_DEPRECADO => 'deprecado',
            self::MOD_ESTADO_EN_ENTRENAMIENTO => 'en_entrenamiento',
        ];
    }

    /**
     * @return string
     */
    public function displayModEstado()
    {
        return self::optsModEstado()[$this->mod_estado];
    }

    /**
     * @return bool
     */
    public function isModEstadoActivo()
    {
        return $this->mod_estado === self::MOD_ESTADO_ACTIVO;
    }

    public function setModEstadoToActivo()
    {
        $this->mod_estado = self::MOD_ESTADO_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isModEstadoDeprecado()
    {
        return $this->mod_estado === self::MOD_ESTADO_DEPRECADO;
    }

    public function setModEstadoToDeprecado()
    {
        $this->mod_estado = self::MOD_ESTADO_DEPRECADO;
    }

    /**
     * @return bool
     */
    public function isModEstadoEnentrenamiento()
    {
        return $this->mod_estado === self::MOD_ESTADO_EN_ENTRENAMIENTO;
    }

    public function setModEstadoToEnentrenamiento()
    {
        $this->mod_estado = self::MOD_ESTADO_EN_ENTRENAMIENTO;
    }
}
