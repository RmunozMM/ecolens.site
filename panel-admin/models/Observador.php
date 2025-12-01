<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * Modelo para la tabla "observadores".
 *
 * @property int         $obs_id
 * @property string      $obs_nombre
 * @property string      $obs_email
 * @property string|null $obs_usuario
 * @property string|null $obs_institucion
 * @property string|null $obs_experiencia
 * @property string|null $obs_pais
 * @property string|null $obs_ciudad
 * @property string|null $obs_estado
 * @property string|null $obs_fecha_registro
 * @property string|null $obs_foto
 * @property string|null $obs_token                 Hash de contraseña
 * @property string|null $obs_act_token_hash       Hash de token de activación
 * @property string|null $obs_act_expires          Expiración del token de activación (DATETIME)
 * @property string|null $obs_email_verificado_at  Fecha/hora de verificación de correo (DATETIME)
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Deteccion[] $detecciones
 */
class Observador extends \yii\db\ActiveRecord
{
    // 📘 Enumeraciones de experiencia
    const OBS_EXPERIENCIA_PRINCIPIANTE   = 'principiante';
    const OBS_EXPERIENCIA_AFICIONADO     = 'aficionado';
    const OBS_EXPERIENCIA_EXPERTO        = 'experto';
    const OBS_EXPERIENCIA_INSTITUCIONAL  = 'institucional';

    // 📗 Enumeraciones de estado
    const OBS_ESTADO_ACTIVO    = 'activo';
    const OBS_ESTADO_INACTIVO  = 'inactivo';
    const OBS_ESTADO_PENDIENTE = 'pendiente';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'observadores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['obs_nombre', 'obs_email'], 'required'],

            [['obs_experiencia', 'obs_estado'], 'string'],
            [['obs_fecha_registro', 'created_at', 'updated_at'], 'safe'],

            // Nuevos campos tipo fecha/hora
            [['obs_act_expires', 'obs_email_verificado_at'], 'safe'],

            // Longitudes
            [['obs_nombre', 'obs_email', 'obs_institucion', 'obs_usuario'], 'string', 'max' => 150],
            [['obs_pais', 'obs_ciudad'], 'string', 'max' => 100],
            [['obs_foto', 'obs_token'], 'string', 'max' => 255],
            // Nuevo: hash del token de activación (hex de SHA-256 => 64)
            [['obs_act_token_hash'], 'string', 'max' => 64],

            // 📧 Validación de email
            ['obs_email', 'email'],
            ['obs_email', 'unique', 'message' => 'Este correo ya está registrado.'],

            [['obs_experiencia'], 'in', 'range' => array_keys(self::optsObsExperiencia())],
            [['obs_estado'], 'in', 'range' => array_keys(self::optsObsEstado())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'obs_id'                   => 'ID Observador',
            'obs_nombre'               => 'Nombre completo',
            'obs_usuario'              => 'Nombre de usuario',
            'obs_email'                => 'Correo electrónico',
            'obs_institucion'          => 'Institución o afiliación',
            'obs_experiencia'          => 'Nivel de experiencia',
            'obs_pais'                 => 'País',
            'obs_ciudad'               => 'Ciudad o región',
            'obs_estado'               => 'Estado de la cuenta',
            'obs_fecha_registro'       => 'Fecha de registro',
            'obs_foto'                 => 'Foto o avatar',
            'obs_token'                => 'Hash de contraseña',
            'obs_act_token_hash'       => 'Hash token de activación',
            'obs_act_expires'          => 'Expira token de activación',
            'obs_email_verificado_at'  => 'Correo verificado el',
            'created_at'               => 'Creado el',
            'updated_at'               => 'Actualizado el',
        ];
    }

    /**
     * Opciones de experiencia
     */
    public static function optsObsExperiencia()
    {
        return [
            self::OBS_EXPERIENCIA_PRINCIPIANTE  => 'principiante',
            self::OBS_EXPERIENCIA_AFICIONADO    => 'aficionado',
            self::OBS_EXPERIENCIA_EXPERTO       => 'experto',
            self::OBS_EXPERIENCIA_INSTITUCIONAL => 'institucional',
        ];
    }

    /**
     * Opciones de estado
     */
    public static function optsObsEstado()
    {
        return [
            self::OBS_ESTADO_ACTIVO    => 'activo',
            self::OBS_ESTADO_INACTIVO  => 'inactivo',
            self::OBS_ESTADO_PENDIENTE => 'pendiente',
        ];
    }

    /**
     * Muestra legible de experiencia
     */
    public function displayObsExperiencia()
    {
        return self::optsObsExperiencia()[$this->obs_experiencia] ?? 'desconocido';
    }

    /**
     * Muestra legible de estado
     */
    public function displayObsEstado()
    {
        return self::optsObsEstado()[$this->obs_estado] ?? 'desconocido';
    }

    /**
     * Relación con Detección (1:N)
     */
    public function getDetecciones()
    {
        return $this->hasMany(Deteccion::class, ['det_observador_id' => 'obs_id']);
        // Si ya renombraste la FK en toda la app a det_obs_id, cambia la línea por:
        // return $this->hasMany(Deteccion::class, ['det_obs_id' => 'obs_id']);
    }

    /**
     * Comportamientos automáticos (timestamp)
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
        ];
    }

    /**
     * Asigna valores por defecto antes de guardar
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->obs_estado = $this->obs_estado ?? self::OBS_ESTADO_PENDIENTE;
                $this->obs_fecha_registro = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }

    /**
     * Asigna la contraseña en forma segura (genera hash)
     */
    public function setPassword($password)
    {
        $this->obs_token = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Valida una contraseña con el hash guardado
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->obs_token);
    }
}