<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dispositivos".
 *
 * @property int $dis_id Identificador único del dispositivo registrado
 * @property string|null $dis_tipo Tipo de dispositivo utilizado
 * @property string|null $dis_sistema_operativo Sistema operativo detectado
 * @property string|null $dis_navegador Navegador o cliente usado
 * @property string|null $dis_user_agent Cadena completa del user agent
 * @property string|null $dis_ip_origen Dirección IP pública del dispositivo
 * @property int|null $dis_usuario_id Identificador lógico del usuario asociado
 * @property string|null $created_at Fecha de registro del dispositivo
 */
class Dispositivo extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const DIS_TIPO_DESKTOP = 'desktop';
    const DIS_TIPO_MOBILE = 'mobile';
    const DIS_TIPO_TABLET = 'tablet';
    const DIS_TIPO_CAMERA = 'camera';
    const DIS_TIPO_API = 'api';
    const DIS_SISTEMA_OPERATIVO_WINDOWS = 'Windows';
    const DIS_SISTEMA_OPERATIVO_MACOS = 'macOS';
    const DIS_SISTEMA_OPERATIVO_LINUX = 'Linux';
    const DIS_SISTEMA_OPERATIVO_ANDROID = 'Android';
    const DIS_SISTEMA_OPERATIVO_IOS = 'iOS';
    const DIS_SISTEMA_OPERATIVO_OTRO = 'Otro';
    const DIS_NAVEGADOR_CHROME = 'Chrome';
    const DIS_NAVEGADOR_SAFARI = 'Safari';
    const DIS_NAVEGADOR_FIREFOX = 'Firefox';
    const DIS_NAVEGADOR_EDGE = 'Edge';
    const DIS_NAVEGADOR_OTRO = 'Otro';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dispositivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dis_user_agent', 'dis_ip_origen', 'dis_usuario_id'], 'default', 'value' => null],
            [['dis_tipo'], 'default', 'value' => 'desktop'],
            [['dis_navegador'], 'default', 'value' => 'Otro'],
            [['dis_tipo', 'dis_sistema_operativo', 'dis_navegador'], 'string'],
            [['dis_usuario_id'], 'integer'],
            [['created_at'], 'safe'],
            [['dis_user_agent'], 'string', 'max' => 255],
            [['dis_ip_origen'], 'string', 'max' => 45],
            ['dis_tipo', 'in', 'range' => array_keys(self::optsDisTipo())],
            ['dis_sistema_operativo', 'in', 'range' => array_keys(self::optsDisSistemaOperativo())],
            ['dis_navegador', 'in', 'range' => array_keys(self::optsDisNavegador())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dis_id' => 'Identificador único del dispositivo registrado',
            'dis_tipo' => 'Tipo de dispositivo utilizado',
            'dis_sistema_operativo' => 'Sistema operativo detectado',
            'dis_navegador' => 'Navegador o cliente usado',
            'dis_user_agent' => 'Cadena completa del user agent',
            'dis_ip_origen' => 'Dirección IP pública del dispositivo',
            'dis_usuario_id' => 'Identificador lógico del usuario asociado',
            'created_at' => 'Fecha de registro del dispositivo',
        ];
    }


    /**
     * column dis_tipo ENUM value labels
     * @return string[]
     */
    public static function optsDisTipo()
    {
        return [
            self::DIS_TIPO_DESKTOP => 'desktop',
            self::DIS_TIPO_MOBILE => 'mobile',
            self::DIS_TIPO_TABLET => 'tablet',
            self::DIS_TIPO_CAMERA => 'camera',
            self::DIS_TIPO_API => 'api',
        ];
    }

    /**
     * column dis_sistema_operativo ENUM value labels
     * @return string[]
     */
    public static function optsDisSistemaOperativo()
    {
        return [
            self::DIS_SISTEMA_OPERATIVO_WINDOWS => 'Windows',
            self::DIS_SISTEMA_OPERATIVO_MACOS => 'macOS',
            self::DIS_SISTEMA_OPERATIVO_LINUX => 'Linux',
            self::DIS_SISTEMA_OPERATIVO_ANDROID => 'Android',
            self::DIS_SISTEMA_OPERATIVO_IOS => 'iOS',
            self::DIS_SISTEMA_OPERATIVO_OTRO => 'Otro',
        ];
    }

    /**
     * column dis_navegador ENUM value labels
     * @return string[]
     */
    public static function optsDisNavegador()
    {
        return [
            self::DIS_NAVEGADOR_CHROME => 'Chrome',
            self::DIS_NAVEGADOR_SAFARI => 'Safari',
            self::DIS_NAVEGADOR_FIREFOX => 'Firefox',
            self::DIS_NAVEGADOR_EDGE => 'Edge',
            self::DIS_NAVEGADOR_OTRO => 'Otro',
        ];
    }

    /**
     * @return string
     */
    public function displayDisTipo()
    {
        return self::optsDisTipo()[$this->dis_tipo];
    }

    /**
     * @return bool
     */
    public function isDisTipoDesktop()
    {
        return $this->dis_tipo === self::DIS_TIPO_DESKTOP;
    }

    public function setDisTipoToDesktop()
    {
        $this->dis_tipo = self::DIS_TIPO_DESKTOP;
    }

    /**
     * @return bool
     */
    public function isDisTipoMobile()
    {
        return $this->dis_tipo === self::DIS_TIPO_MOBILE;
    }

    public function setDisTipoToMobile()
    {
        $this->dis_tipo = self::DIS_TIPO_MOBILE;
    }

    /**
     * @return bool
     */
    public function isDisTipoTablet()
    {
        return $this->dis_tipo === self::DIS_TIPO_TABLET;
    }

    public function setDisTipoToTablet()
    {
        $this->dis_tipo = self::DIS_TIPO_TABLET;
    }

    /**
     * @return bool
     */
    public function isDisTipoCamera()
    {
        return $this->dis_tipo === self::DIS_TIPO_CAMERA;
    }

    public function setDisTipoToCamera()
    {
        $this->dis_tipo = self::DIS_TIPO_CAMERA;
    }

    /**
     * @return bool
     */
    public function isDisTipoApi()
    {
        return $this->dis_tipo === self::DIS_TIPO_API;
    }

    public function setDisTipoToApi()
    {
        $this->dis_tipo = self::DIS_TIPO_API;
    }

    /**
     * @return string
     */
    public function displayDisSistemaOperativo()
    {
        return self::optsDisSistemaOperativo()[$this->dis_sistema_operativo];
    }

    /**
     * @return bool
     */
    public function isDisSistemaOperativoWindows()
    {
        return $this->dis_sistema_operativo === self::DIS_SISTEMA_OPERATIVO_WINDOWS;
    }

    public function setDisSistemaOperativoToWindows()
    {
        $this->dis_sistema_operativo = self::DIS_SISTEMA_OPERATIVO_WINDOWS;
    }

    /**
     * @return bool
     */
    public function isDisSistemaOperativoMacos()
    {
        return $this->dis_sistema_operativo === self::DIS_SISTEMA_OPERATIVO_MACOS;
    }

    public function setDisSistemaOperativoToMacos()
    {
        $this->dis_sistema_operativo = self::DIS_SISTEMA_OPERATIVO_MACOS;
    }

    /**
     * @return bool
     */
    public function isDisSistemaOperativoLinux()
    {
        return $this->dis_sistema_operativo === self::DIS_SISTEMA_OPERATIVO_LINUX;
    }

    public function setDisSistemaOperativoToLinux()
    {
        $this->dis_sistema_operativo = self::DIS_SISTEMA_OPERATIVO_LINUX;
    }

    /**
     * @return bool
     */
    public function isDisSistemaOperativoAndroid()
    {
        return $this->dis_sistema_operativo === self::DIS_SISTEMA_OPERATIVO_ANDROID;
    }

    public function setDisSistemaOperativoToAndroid()
    {
        $this->dis_sistema_operativo = self::DIS_SISTEMA_OPERATIVO_ANDROID;
    }

    /**
     * @return bool
     */
    public function isDisSistemaOperativoIos()
    {
        return $this->dis_sistema_operativo === self::DIS_SISTEMA_OPERATIVO_IOS;
    }

    public function setDisSistemaOperativoToIos()
    {
        $this->dis_sistema_operativo = self::DIS_SISTEMA_OPERATIVO_IOS;
    }

    /**
     * @return bool
     */
    public function isDisSistemaOperativoOtro()
    {
        return $this->dis_sistema_operativo === self::DIS_SISTEMA_OPERATIVO_OTRO;
    }

    public function setDisSistemaOperativoToOtro()
    {
        $this->dis_sistema_operativo = self::DIS_SISTEMA_OPERATIVO_OTRO;
    }

    /**
     * @return string
     */
    public function displayDisNavegador()
    {
        return self::optsDisNavegador()[$this->dis_navegador];
    }

    /**
     * @return bool
     */
    public function isDisNavegadorChrome()
    {
        return $this->dis_navegador === self::DIS_NAVEGADOR_CHROME;
    }

    public function setDisNavegadorToChrome()
    {
        $this->dis_navegador = self::DIS_NAVEGADOR_CHROME;
    }

    /**
     * @return bool
     */
    public function isDisNavegadorSafari()
    {
        return $this->dis_navegador === self::DIS_NAVEGADOR_SAFARI;
    }

    public function setDisNavegadorToSafari()
    {
        $this->dis_navegador = self::DIS_NAVEGADOR_SAFARI;
    }

    /**
     * @return bool
     */
    public function isDisNavegadorFirefox()
    {
        return $this->dis_navegador === self::DIS_NAVEGADOR_FIREFOX;
    }

    public function setDisNavegadorToFirefox()
    {
        $this->dis_navegador = self::DIS_NAVEGADOR_FIREFOX;
    }

    /**
     * @return bool
     */
    public function isDisNavegadorEdge()
    {
        return $this->dis_navegador === self::DIS_NAVEGADOR_EDGE;
    }

    public function setDisNavegadorToEdge()
    {
        $this->dis_navegador = self::DIS_NAVEGADOR_EDGE;
    }

    /**
     * @return bool
     */
    public function isDisNavegadorOtro()
    {
        return $this->dis_navegador === self::DIS_NAVEGADOR_OTRO;
    }

    public function setDisNavegadorToOtro()
    {
        $this->dis_navegador = self::DIS_NAVEGADOR_OTRO;
    }
    public function getUsuario()
{
    return $this->hasOne(Users::class, ['usu_id' => 'dis_usuario_id']);
}

    public function getDetecciones()
    {
        return $this->hasMany(Deteccion::class, ['det_dispositivo_id' => 'dis_id']);
    }
}
