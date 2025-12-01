<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "categorias_opciones".
 *
 * @property int $cat_id Identificador único de la categoría de opción
 * @property string $cat_nombre Nombre único de la categoría (ej: visual, sistema, api)
 * @property string|null $cat_descripcion Descripción breve del propósito de la categoría
 * @property string|null $cat_icono Clase CSS o nombre de ícono para UI (opcional)
 * @property int|null $cat_orden Orden de visualización en el panel
 * @property string $created_at Fecha de creación del registro
 * @property string $updated_at Fecha de última modificación
 * @property int|null $created_by ID del usuario que creó la categoría
 * @property int|null $updated_by ID del usuario que modificó por última vez la categoría
 *
 * @property Users $createdByUser
 * @property Users $updatedByUser
 */
class CategoriaOpcion extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'categorias_opciones';
    }

    public function rules()
    {
        return [
            [['cat_descripcion', 'cat_icono', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['cat_orden'], 'default', 'value' => 1],
            [['cat_nombre'], 'required'],
            [['cat_orden', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['cat_nombre', 'cat_icono'], 'string', 'max' => 50],
            [['cat_descripcion'], 'string', 'max' => 255],
            [['cat_nombre'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cat_id' => 'ID',
            'cat_nombre' => 'Nombre',
            'cat_descripcion' => 'Descripción',
            'cat_icono' => 'Ícono',
            'cat_orden' => 'Orden',
            'created_at' => 'Fecha creación',
            'updated_at' => 'Fecha modificación',
            'created_by' => 'Creado por',
            'updated_by' => 'Modificado por',
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
}