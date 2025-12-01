<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Yii;

/**
 * This is the model class for table "trabajadores".
 *
 * @property int    $tra_id
 * @property string $tra_nombre
 * @property string $tra_apellido
 * @property string $tra_cedula
 * @property string|null $tra_fecha_nacimiento
 * @property string|null $tra_genero
 * @property string|null $tra_puesto
 * @property string|null $tra_departamento
 * @property string|null $tra_fecha_contratacion
 * @property float|null  $tra_salario
 * @property string|null $tra_email
 * @property string|null $tra_telefono
 * @property string|null $tra_direccion
 * @property string|null $tra_foto_perfil
 * @property string|null $tra_descripcion
 * @property string|null $tra_facebook
 * @property string|null $tra_instagram
 * @property string|null $tra_linkedin
 * @property string|null $tra_tiktok
 * @property string|null $tra_twitter
 * @property string|null $tra_whatsapp
 * @property string      $tra_modalidad_contrato
 * @property string      $tra_publicado
 * @property string      $tra_estado
 */
class Trabajador extends \yii\db\ActiveRecord
{
    /** {@inheritdoc} */
    public static function tableName()
    {
        return 'trabajadores';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            // ── Obligatorios ────────────────────────────────
            [['tra_nombre', 'tra_apellido', 'tra_cedula',
              'tra_modalidad_contrato', 'tra_publicado', 'tra_estado'], 'required'],

            // ── Validaciones de tipo ────────────────────────
            [['tra_fecha_nacimiento', 'tra_fecha_contratacion'], 'safe'],
            [['tra_salario'], 'number'],
            [['tra_genero', 'tra_direccion', 'tra_descripcion',
              'tra_modalidad_contrato', 'tra_publicado', 'tra_estado'], 'string'],

            // ── ENUMs ───────────────────────────────────────
            [['tra_modalidad_contrato'], 'in',
                'range' => ['Plazo Fijo', 'Indefinido', 'A Demanda']],
            [['tra_publicado'], 'in', 'range' => ['SI', 'NO']],
            [['tra_estado'], 'in', 'range' => ['Activo', 'Inactivo']],

            // ── Longitudes ─────────────────────────────────
            [['tra_nombre', 'tra_apellido', 'tra_email', 'tra_facebook',
              'tra_instagram', 'tra_linkedin', 'tra_tiktok',
              'tra_twitter', 'tra_whatsapp'], 'string', 'max' => 255],
            [['tra_cedula', 'tra_telefono'], 'string', 'max' => 20],
            [['tra_puesto', 'tra_departamento'], 'string', 'max' => 100],

            // ── Unicidad ───────────────────────────────────
            [['tra_cedula'], 'unique'],

            // ── Archivo de imagen ──────────────────────────
            [['tra_foto_perfil'], 'file', 'skipOnEmpty' => true,
                                    'extensions' => 'jpg, jpeg, png'],
        ];
    }

    /**
     * Convierte strings vacíos a NULL en atributos opcionales
     */
    public function beforeValidate()
    {
        foreach (['tra_genero'] as $attr) {
            if ($this->$attr === '') {
                $this->$attr = null;
            }
        }
        return parent::beforeValidate();
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return [
            'tra_id'                 => 'Identificador único del trabajador',
            'tra_nombre'             => 'Nombre del trabajador',
            'tra_apellido'           => 'Apellido del trabajador',
            'tra_cedula'             => 'Número de cédula o documento de identidad',
            'tra_fecha_nacimiento'   => 'Fecha de nacimiento del trabajador',
            'tra_genero'             => 'Género del trabajador',
            'tra_puesto'             => 'Puesto de trabajo del trabajador',
            'tra_departamento'       => 'Departamento o área en la que trabaja el trabajador',
            'tra_fecha_contratacion' => 'Fecha de contratación del trabajador',
            'tra_salario'            => 'Salario del trabajador',
            'tra_email'              => 'Correo electrónico del trabajador',
            'tra_telefono'           => 'Número de teléfono del trabajador',
            'tra_direccion'          => 'Dirección del trabajador',
            'tra_foto_perfil'        => 'Ruta de la foto de perfil del trabajador',
            'tra_descripcion'        => 'Descripción o perfil del trabajador',
            'tra_facebook'           => 'Enlace al perfil de Facebook del trabajador',
            'tra_instagram'          => 'Enlace al perfil de Instagram del trabajador',
            'tra_linkedin'           => 'Enlace al perfil de LinkedIn del trabajador',
            'tra_tiktok'             => 'Enlace al perfil de TikTok del trabajador',
            'tra_twitter'            => 'Enlace al perfil de Twitter del trabajador',
            'tra_whatsapp'           => 'Número de WhatsApp del trabajador',
            'tra_modalidad_contrato' => 'Modalidad de contrato del trabajador',
            'tra_publicado'          => 'Indica si el trabajador está publicado',
            'tra_estado'             => 'Estado del trabajador (activo o inactivo)',
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
