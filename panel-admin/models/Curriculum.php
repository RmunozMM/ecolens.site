<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "curriculum".
 *
 * @property int $cur_id Llave primaria del curriculum
 * @property int $cur_per_id Referencia al perfil (per_id)
 * @property string $cur_titulo Título del Curriculum (ej. "Currículum Vitae")
 * @property string|null $cur_subtitulo Subtítulo o tagline opcional
 * @property string $cur_casa_estudio Casa de estudios indicada en el curriculum
 * @property string|null $cur_resumen_profesional Resumen profesional extendido para el CV
 * @property string|null $cur_estilos Configuración adicional (por ejemplo, JSON o CSS para personalizar el CV)
 *
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Curriculum extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'curriculum';
    }

    public function rules()
    {
        return [
            [['cur_per_id', 'cur_titulo'], 'required'],
            [['cur_per_id', 'created_by', 'updated_by'], 'integer'],
            [['cur_resumen_profesional', 'cur_estilos'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['cur_titulo', 'cur_subtitulo', 'cur_casa_estudio'], 'string', 'max' => 255],
            [['cur_per_id'], 'exist', 'skipOnError' => true, 'targetClass' => Perfil::class, 'targetAttribute' => ['cur_per_id' => 'per_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cur_id' => 'Llave primaria del curriculum',
            'cur_per_id' => 'Referencia al perfil (per_id)',
            'cur_titulo' => 'Título del Curriculum',
            'cur_subtitulo' => 'Subtítulo o tagline opcional',
            'cur_casa_estudio' => 'Casa de estudios',
            'cur_resumen_profesional' => 'Resumen profesional',
            'cur_estilos' => 'Estilos personalizados',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
            'created_by' => 'Creado por',
            'updated_by' => 'Modificado por',
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

    public function getCurPer()
    {
        return $this->hasOne(Perfil::class, ['per_id' => 'cur_per_id']);
    }

    public static function obtenerExperiencias()
    {
        return Yii::$app->db->createCommand("SELECT * FROM experiencias WHERE exp_publicada = 'si' ORDER BY exp_fecha_inicio DESC")->queryAll();
    }

    public static function obtenerFormaciones()
    {
        return Yii::$app->db->createCommand("SELECT * FROM formacion WHERE for_publicada = 'si' AND for_tipo_logro NOT IN ('curso', 'certificación') ORDER BY for_fecha_fin DESC")->queryAll();
    }

    public static function obtenerCursos()
    {
        return Yii::$app->db->createCommand("SELECT * FROM formacion WHERE for_publicada = 'si' AND for_tipo_logro = 'Curso' ORDER BY for_fecha_fin DESC")->queryAll();
    }

    public static function obtenerCertificaciones()
    {
        return Yii::$app->db->createCommand("SELECT * FROM formacion WHERE for_publicada = 'si' AND for_tipo_logro = 'Certificación' ORDER BY for_fecha_fin DESC")->queryAll();
    }

    public static function obtenerHabilidades()
    {
        return Yii::$app->db->createCommand("SELECT hab_nombre FROM habilidades WHERE hab_publicada = 'si'")->queryColumn();
    }

    public static function obtenerHerramientas()
    {
        return Yii::$app->db->createCommand("SELECT her_nombre FROM herramientas WHERE her_publicada = 'si'")->queryColumn();
    }

    public static function obtenerDatosCurriculum()
    {
        return self::find()->where(['cur_id' => 1])->asArray()->one();
    }

    public static function obtenerDatosPerfil()
    {
        return Yii::$app->db->createCommand("SELECT * FROM perfil WHERE per_id = 1")->queryOne();
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
