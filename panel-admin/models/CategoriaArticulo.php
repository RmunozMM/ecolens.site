<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "categoria_articulo".
 *
 * @property int $caa_id ID único de la categoría
 * @property string $caa_nombre Nombre de la categoría
 * @property string $caa_slug Slug de la categoría
 * @property string $caa_estado Estado de la categoría (publicado/borrador)
 *
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de modificación
 * @property int|null $created_by Usuario que creó el registro
 * @property int|null $updated_by Usuario que actualizó el registro
 */
class CategoriaArticulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoria_articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caa_nombre', 'caa_estado'], 'required'],
            [['caa_nombre', 'caa_slug'], 'string', 'max' => 255],
            [['caa_estado'], 'in', 'range' => ['publicado', 'borrador']],
            [['caa_slug'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'caa_id' => 'ID único de la categoría',
            'caa_nombre' => 'Nombre de la categoría',
            'caa_slug' => 'Slug de la categoría',
            'caa_estado' => 'Estado de la categoría (publicado/borrador)',
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
