<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "clientes".
 *
 * @property int $cli_id ID 
 * @property string $cli_nombre Nombre 
 * @property string $cli_email Correo Electrónico 
 * @property string|null $cli_telefono Teléfono 
 * @property string|null $cli_direccion Dirección 
 * @property string|null $cli_descripcion Descripción 
 * @property string $cli_estado Estado  (SI/NO)
 * @property string|null $cli_logo Ruta del Logotipo 
 * @property string $cli_publicado Publicado (SI/NO)
 * @property string $cli_destacado Destacado (SI/NO)
 *
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class Cliente extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'clientes';
    }

    public function rules()
    {
        return [
            [['cli_nombre', 'cli_slug', 'cli_estado'], 'required'],
            [['cli_descripcion', 'cli_estado', 'cli_publicado', 'cli_destacado'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['cli_nombre', 'cli_slug', 'cli_logo', 'cli_email', 'cli_telefono', 'cli_direccion'], 'string', 'max' => 255],
            [['cli_slug'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cli_id' => 'ID ',
            'cli_nombre' => 'Nombre ',
            'cli_slug' => 'Slug ',
            'cli_logo' => 'Logo ',
            'cli_email' => 'Correo Electrónico ',
            'cli_telefono' => 'Teléfono ',
            'cli_direccion' => 'Dirección ',
            'cli_descripcion' => 'Descripción ',
            'cli_estado' => 'Estado ',
            'cli_publicado' => 'Publicado',
            'cli_destacado' => 'Destacado',
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
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }
} 
