<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * Esta es la clase modelo para la tabla "proyectos".
 *
 * @property int $pro_id
 * @property string $pro_titulo
 * @property string|null $pro_descripcion
 * @property string|null $pro_resumen
 * @property string $pro_slug
 * @property string $pro_estado
 * @property string $pro_destacado
 * @property string|null $pro_imagen
 * @property int|null $pro_ser_id
 * @property string|null $pro_url
 * @property int|null $pro_cli_id
 * @property string|null $pro_fecha_inicio
 * @property string|null $pro_fecha_fin
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Proyecto extends ActiveRecord
{
    public static function tableName()
    {
        return 'proyectos';
    }

    public function rules()
    {
        return [
            [['pro_titulo', 'pro_slug', 'pro_estado', 'pro_destacado'], 'required'],
            [['pro_descripcion'], 'string'],
            [['pro_ser_id', 'pro_cli_id', 'created_by', 'updated_by'], 'integer'],
            [['pro_fecha_inicio', 'pro_fecha_fin', 'created_at', 'updated_at'], 'safe'],
            [['pro_titulo', 'pro_resumen', 'pro_slug', 'pro_imagen', 'pro_url'], 'string', 'max' => 255],
            [['pro_estado'], 'in', 'range' => ['PUBLICADO', 'BORRADOR']],
            [['pro_destacado'], 'in', 'range' => ['SI', 'NO']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'pro_id' => 'ID del proyecto',
            'pro_titulo' => 'Título del proyecto',
            'pro_descripcion' => 'Descripción',
            'pro_resumen' => 'Resumen',
            'pro_slug' => 'Slug',
            'pro_estado' => 'Estado',
            'pro_destacado' => '¿Destacado?',
            'pro_imagen' => 'Imagen',
            'pro_ser_id' => 'Servicio asociado',
            'pro_url' => 'URL del proyecto',
            'pro_cli_id' => 'Cliente',
            'pro_fecha_inicio' => 'Inicio',
            'pro_fecha_fin' => 'Fin',
            'created_at' => 'Creado el',
            'updated_at' => 'Modificado el',
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

    // Relaciones con Users
    public function getCreatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
    }
    public function getCliente()
    {
        return $this->hasOne(Cliente::class, ['cli_id' => 'cli_nombre']);
    }
    public function getServicio()
    {
        // pro_ser_id es la FK en proyectos, ser_id la PK en servicios
        return $this->hasOne(Servicio::class, ['ser_id' => 'pro_ser_id']);
    }

    public function getCategoria()
    {
        return $this->hasOne(CategoriaServicio::class, ['cas_id' => 'cas_nombre']);
    }
}