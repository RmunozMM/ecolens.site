<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "formacion".
 *
 * @property int $for_id ID de la formación
 * @property string $for_institucion Nombre de la institución
 * @property string $for_grado_titulo Grado o título obtenido
 * @property string|null $for_fecha_inicio Fecha de inicio
 * @property string|null $for_fecha_fin Fecha de finalización
 * @property string|null $for_logros_principales Logros principales
 * @property string $for_tipo_logro Tipo de logro
 * @property string $for_categoria Categoría de formación
 * @property string $for_publicada Indicador de publicación (SI/NO)
 * @property string|null $for_codigo_validacion Código de validación
 * @property string $for_certificado Ruta del archivo del certificado
 * @property string|null $for_mostrar_certificado Indica si se debe mostrar el certificado
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Formacion extends \yii\db\ActiveRecord
{
    public $for_categoria_logro;

    public static function tableName()
    {
        return 'formacion';
    }

    public function rules()
    {
        return [
            [['for_institucion', 'for_grado_titulo', 'for_tipo_logro'], 'required'],
            [['for_fecha_inicio', 'for_fecha_fin', 'created_at', 'updated_at'], 'safe'],
            [['for_logros_principales', 'for_tipo_logro', 'for_publicada', 'for_mostrar_certificado', 'for_categoria'], 'string'],
            [['created_by', 'updated_by'], 'integer'],
            [['for_institucion', 'for_grado_titulo'], 'string', 'max' => 100],
            [['for_codigo_validacion'], 'string', 'max' => 255],
            [['for_certificado'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'for_id' => 'ID de la formación',
            'for_institucion' => 'Nombre de la institución',
            'for_grado_titulo' => 'Grado o título obtenido',
            'for_fecha_inicio' => 'Fecha de inicio',
            'for_fecha_fin' => 'Fecha de finalización',
            'for_logros_principales' => 'Logros principales',
            'for_tipo_logro' => 'Tipo de logro',
            'for_categoria' => 'Categoría de Formación',
            'for_publicada' => 'Indicador de publicación (SI/NO)',
            'for_codigo_validacion' => 'Código de validación',
            'for_certificado' => 'Ruta del Certificado',
            'for_mostrar_certificado' => 'Indica si se debe mostrar el certificado',
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
}