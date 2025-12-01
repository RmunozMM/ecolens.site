<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "opciones".
 *
 * @property int $opc_id Identificador único de la opción
 * @property string $opc_nombre Nombre único y descriptivo de la opción (ej: visual_color_primario)
 * @property string|null $opc_valor Valor actual de la opción
 * @property string $opc_tipo Tipo de dato de la opción
 * @property int $opc_cat_id ID de la categoría funcional a la que pertenece (categorias_opciones.cat_id)
 * @property int $opc_rol_id ID mínimo de rol requerido para modificar la opción (roles.rol_id)
 * @property string|null $opc_descripcion Descripción breve del propósito y uso de la opción
 * @property string $created_at Fecha de creación del registro
 * @property string $updated_at Fecha de última modificación
 * @property int|null $created_by ID del usuario que creó la opción
 * @property int|null $updated_by ID del usuario que modificó por última vez la opción
 *
 * @property Users $createdByUser
 * @property Users $updatedByUser
 * @property CategoriaOpcion $categoria
 * @property Rol $rol
 */
class Opcion extends \yii\db\ActiveRecord
{
    // ENUM values
    const OPC_TIPO_STRING = 'string';
    const OPC_TIPO_INT = 'int';
    const OPC_TIPO_BOOL = 'bool';
    const OPC_TIPO_FLOAT = 'float';
    const OPC_TIPO_JSON = 'json';
    const OPC_TIPO_ENUM = 'enum';
    const OPC_TIPO_COLOR = 'color';

    public static function tableName()
    {
        return 'opciones';
    }

    public function rules()
    {
        return [
            [['opc_valor', 'opc_descripcion', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['opc_tipo'], 'default', 'value' => 'string'],
            [['opc_nombre', 'opc_cat_id', 'opc_rol_id'], 'required'],
            [['opc_valor', 'opc_tipo'], 'string'],
            [['opc_cat_id', 'opc_rol_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['opc_nombre'], 'string', 'max' => 80],
            [['opc_descripcion'], 'string', 'max' => 255],
            ['opc_tipo', 'in', 'range' => array_keys(self::optsOpcTipo())],
            [['opc_nombre'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'opc_id' => 'ID',
            'opc_nombre' => 'Nombre',
            'opc_valor' => 'Valor',
            'opc_tipo' => 'Tipo',
            'opc_cat_id' => 'Categoría',
            'opc_rol_id' => 'Rol mínimo',
            'opc_descripcion' => 'Descripción',
            'created_at' => 'Fecha creación',
            'updated_at' => 'Fecha modificación',
            'created_by' => 'Creado por',
            'updated_by' => 'Modificado por',
        ];
    }

    // ENUM labels
    public static function optsOpcTipo()
    {
        return [
            self::OPC_TIPO_STRING => 'string',
            self::OPC_TIPO_INT => 'int',
            self::OPC_TIPO_BOOL => 'bool',
            self::OPC_TIPO_FLOAT => 'float',
            self::OPC_TIPO_JSON => 'json',
            self::OPC_TIPO_ENUM => 'enum',
            self::OPC_TIPO_COLOR => 'color',
        ];
    }

    // Behaviors para auditoría
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

    // Relación: usuario que creó
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    // Relación: usuario que modificó
    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }

    // Relación: categoría de opción
    public function getCategoria()
    {
        return $this->hasOne(CategoriaOpcion::class, ['cat_id' => 'opc_cat_id']);
    }

    // Relación: rol mínimo
    public function getRol()
    {
        return $this->hasOne(Rol::class, ['rol_id' => 'opc_rol_id']);
    }
}