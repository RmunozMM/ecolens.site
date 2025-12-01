<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

use app\models\Asunto;

/**
 * This is the model class for table "correos_electronicos".
 *
 * @property int $cor_id ID del correo electrónico
 * @property string $cor_nombre Nombre del remitente
 * @property string $cor_correo Correo electrónico del remitente
 * @property string $cor_asunto Asunto del correo
 * @property string $cor_mensaje Cuerpo del correo electrónico
 * @property string $cor_fecha_consulta Fecha de consulta del correo electrónico
 * @property string|null $cor_fecha_respuesta Fecha de respuesta del correo electrónico
 * @property string $cor_estado Estado del correo electrónico
 * @property string|null $cor_respuesta Campo para almacenar la respuesta al correo electrónico
 *
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Correo extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'correos_electronicos';
    }

    public function rules()
    {
        return [
            [['cor_nombre', 'cor_correo', 'cor_asunto', 'cor_mensaje', 'cor_fecha_consulta'], 'required'],
            [['cor_mensaje', 'cor_estado', 'cor_respuesta'], 'string'],
            [['cor_fecha_consulta', 'cor_fecha_respuesta', 'created_at', 'updated_at'], 'safe'],
            [['cor_nombre', 'cor_correo', 'cor_asunto'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cor_id' => 'ID del correo electrónico',
            'cor_nombre' => 'Nombre del remitente',
            'cor_correo' => 'Correo electrónico del remitente',
            'cor_asunto' => 'Asunto del correo',
            'cor_mensaje' => 'Cuerpo del correo electrónico',
            'cor_fecha_consulta' => 'Fecha de consulta del correo electrónico',
            'cor_fecha_respuesta' => 'Fecha de respuesta del correo electrónico',
            'cor_estado' => 'Estado del correo electrónico',
            'cor_respuesta' => 'Respuesta al correo electrónico',
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

    public function getUsuario()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }

        public function getAsunto()
    {
        // Si cor_asunto es el asu_id en la tabla “asuntos”, se mapea así:
        return $this->hasOne(Asunto::class, ['asu_id' => 'cor_asunto']);
    }
}
