<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * Modelo ActiveRecord para la tabla `detecciones`.
 * Representa un registro de inferencia (router + experto) generado por el sistema IA de EcoLens.
 */
class Deteccion extends \yii\db\ActiveRecord
{
    // === ENUMS ===

    const DET_FUENTE_WEB     = 'web';
    const DET_FUENTE_API     = 'api';
    const DET_FUENTE_MOVIL   = 'movil';
    const DET_FUENTE_SISTEMA = 'sistema';

    const DET_ESTADO_PENDIENTE = 'pendiente';
    const DET_ESTADO_VALIDADA  = 'validada';
    const DET_ESTADO_RECHAZADA = 'rechazada';

    const DET_REVISION_SIN  = 'sin_revisar';
    const DET_REVISION_EN   = 'en_revision';
    const DET_REVISION_OK   = 'revisada';

    const DET_DISPOSITIVO_DESKTOP = 'desktop';
    const DET_DISPOSITIVO_MOBILE  = 'mobile';
    const DET_DISPOSITIVO_TABLET  = 'tablet';
    const DET_DISPOSITIVO_OTROS   = 'otros';

    const DET_SO_WINDOWS = 'Windows';
    const DET_SO_MACOS   = 'macOS';
    const DET_SO_LINUX   = 'Linux';
    const DET_SO_ANDROID = 'Android';
    const DET_SO_IOS     = 'iOS';
    const DET_SO_OTRO    = 'Otro';

    const DET_NAV_CHROME  = 'Chrome';
    const DET_NAV_FIREFOX = 'Firefox';
    const DET_NAV_SAFARI  = 'Safari';
    const DET_NAV_EDGE    = 'Edge';
    const DET_NAV_OTRO    = 'Otro';

    // === BASE ===

    public static function tableName()
    {
        return 'detecciones';
    }

    public function rules()
    {
        return [
            [['det_confianza_router', 'det_confianza_experto', 'det_latitud', 'det_longitud'], 'default', 'value' => null],
            [['det_fuente'], 'default', 'value' => self::DET_FUENTE_WEB],
            [['det_estado'], 'default', 'value' => self::DET_ESTADO_PENDIENTE],
            [['det_revision_estado'], 'default', 'value' => self::DET_REVISION_SIN],
            [['det_dispositivo_tipo'], 'default', 'value' => self::DET_DISPOSITIVO_OTROS],
            [['det_sistema_operativo'], 'default', 'value' => self::DET_SO_OTRO],
            [['det_navegador'], 'default', 'value' => self::DET_NAV_OTRO],

            [['det_confianza_router', 'det_confianza_experto', 'det_latitud', 'det_longitud'], 'number'],
            [['det_modelo_router_id', 'det_modelo_experto_id', 'det_tax_id', 'det_esp_id', 'det_obs_id', 'det_validado_por'], 'integer'],
            [['det_tiempo_router_ms', 'det_tiempo_experto_ms'], 'integer'],
            [['det_fecha', 'created_at', 'updated_at', 'det_validacion_fecha'], 'safe'],

            [['det_imagen', 'det_origen_archivo', 'det_ip_cliente', 'det_ubicacion_textual'], 'string', 'max' => 255],
            [['det_fuente', 'det_estado', 'det_revision_estado', 'det_dispositivo_tipo', 'det_sistema_operativo', 'det_navegador'], 'string'],
            [['det_observaciones'], 'string'],

            ['det_fuente', 'in', 'range' => array_keys(self::optsDetFuente())],
            ['det_estado', 'in', 'range' => array_keys(self::optsDetEstado())],
            ['det_revision_estado', 'in', 'range' => array_keys(self::optsDetRevision())],
            ['det_dispositivo_tipo', 'in', 'range' => array_keys(self::optsDetDispositivo())],
            ['det_sistema_operativo', 'in', 'range' => array_keys(self::optsDetSO())],
            ['det_navegador', 'in', 'range' => array_keys(self::optsDetNavegador())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'det_id'                => 'ID de detección',
            'det_imagen'            => 'Imagen procesada',
            'det_origen_archivo'    => 'Archivo original',
            'det_confianza_router'  => 'Confianza (Router)',
            'det_confianza_experto' => 'Confianza (Experto)',
            'det_modelo_router_id'  => 'Modelo Router',
            'det_modelo_experto_id' => 'Modelo Experto',
            'det_tax_id'            => 'Clase taxonómica',
            'det_esp_id'            => 'Especie predicha',
            'det_tiempo_router_ms'  => 'Tiempo Router (ms)',
            'det_tiempo_experto_ms' => 'Tiempo Experto (ms)',
            'det_latitud'           => 'Latitud',
            'det_longitud'          => 'Longitud',
            'det_ubicacion_textual' => 'Ubicación textual',
            'det_obs_id'            => 'Usuario',
            'det_ip_cliente'        => 'IP Cliente',
            'det_dispositivo_tipo'  => 'Tipo de dispositivo',
            'det_sistema_operativo' => 'Sistema operativo',
            'det_navegador'         => 'Navegador',
            'det_fuente'            => 'Fuente',
            'det_estado'            => 'Estado',
            'det_revision_estado'   => 'Estado de revisión',
            'det_observaciones'     => 'Observaciones',
            'det_validado_por'      => 'Validado por',
            'det_validacion_fecha'  => 'Fecha de validación',
            'det_fecha'             => 'Fecha de detección',
            'created_at'            => 'Fecha de creación',
            'updated_at'            => 'Última modificación',
        ];
    }

    /**
     * Escenarios personalizados.
     * IMPORTANTE: aquí definimos el escenario 'revisar' para que load() sí cargue esos campos.
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['revisar'] = [
            'det_estado',
            'det_revision_estado',
            'det_observaciones',
            'det_validado_por',
            'det_validacion_fecha',
            'updated_at',
        ];

        return $scenarios;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => fn() => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'det_obs_id',
                'updatedByAttribute' => 'det_validado_por',
                'value' => function () {
                    // Siempre usamos el usu_id del modelo Users
                    return Yii::$app->user->identity->usu_id ?? null;
                },
            ],
        ];
    }


    // === Relaciones ===

    public function getEspecie()
    {
        return $this->hasOne(Especie::class, ['esp_id' => 'det_esp_id']);
    }

    public function getTaxonomia()
    {
        return $this->hasOne(Taxonomia::class, ['tax_id' => 'det_tax_id']);
    }

    public function getModeloRouter()
    {
        return $this->hasOne(Modelo::class, ['mod_id' => 'det_modelo_router_id']);
    }

    public function getModeloExperto()
    {
        return $this->hasOne(Modelo::class, ['mod_id' => 'det_modelo_experto_id']);
    }

    public function getUsuario()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'det_obs_id']);
    }

    public function getValidador()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'det_validado_por']);
    }

    public function getObservador()
    {
        return $this->hasOne(Observador::class, ['obs_id' => 'det_obs_id']);
    }

    // === ENUM helpers ===

    public static function optsDetFuente()
    {
        return [
            self::DET_FUENTE_WEB     => 'Web',
            self::DET_FUENTE_API     => 'API',
            self::DET_FUENTE_MOVIL   => 'Móvil',
            self::DET_FUENTE_SISTEMA => 'Sistema',
        ];
    }

    public static function optsDetEstado()
    {
        return [
            self::DET_ESTADO_PENDIENTE => 'Pendiente',
            self::DET_ESTADO_VALIDADA  => 'Validada',
            self::DET_ESTADO_RECHAZADA => 'Rechazada',
        ];
    }

    public static function optsDetRevision()
    {
        return [
            self::DET_REVISION_SIN => 'Sin revisar',
            self::DET_REVISION_EN  => 'En revisión',
            self::DET_REVISION_OK  => 'Revisada',
        ];
    }

    public static function optsDetDispositivo()
    {
        return [
            self::DET_DISPOSITIVO_DESKTOP => 'Desktop',
            self::DET_DISPOSITIVO_MOBILE  => 'Móvil',
            self::DET_DISPOSITIVO_TABLET  => 'Tablet',
            self::DET_DISPOSITIVO_OTROS   => 'Otros',
        ];
    }

    public static function optsDetSO()
    {
        return [
            self::DET_SO_WINDOWS => 'Windows',
            self::DET_SO_MACOS   => 'macOS',
            self::DET_SO_LINUX   => 'Linux',
            self::DET_SO_ANDROID => 'Android',
            self::DET_SO_IOS     => 'iOS',
            self::DET_SO_OTRO    => 'Otro',
        ];
    }

    public static function optsDetNavegador()
    {
        return [
            self::DET_NAV_CHROME  => 'Chrome',
            self::DET_NAV_FIREFOX => 'Firefox',
            self::DET_NAV_SAFARI  => 'Safari',
            self::DET_NAV_EDGE    => 'Edge',
            self::DET_NAV_OTRO    => 'Otro',
        ];
    }

    // === Display helpers ===

    public function displayFuente()
    {
        return self::optsDetFuente()[$this->det_fuente] ?? '-';
    }

    public function displayEstado()
    {
        return self::optsDetEstado()[$this->det_estado] ?? '-';
    }

    public function displayRevision()
    {
        return self::optsDetRevision()[$this->det_revision_estado] ?? '-';
    }

    public function displayDispositivo()
    {
        return self::optsDetDispositivo()[$this->det_dispositivo_tipo] ?? '-';
    }

    public function displaySO()
    {
        return self::optsDetSO()[$this->det_sistema_operativo] ?? '-';
    }

    public function displayNavegador()
    {
        return self::optsDetNavegador()[$this->det_navegador] ?? '-';
    }
}
