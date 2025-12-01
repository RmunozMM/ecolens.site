<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "experiencias".
 *
 * @property int $exp_id
 * @property string $exp_cargo
 * @property string|null $exp_empresa
 * @property string|null $exp_fecha_inicio
 * @property string|null $exp_fecha_fin
 * @property string|null $exp_descripcion
 * @property string|null $exp_logros
 * @property string $exp_publicada
 * @property int $exp_mod_id
 *
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Experiencia extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'experiencias';
    }

    /**
     * Before validation, normalize dates in YYYY-MM to YYYY-MM-01
     */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        foreach (['exp_fecha_inicio', 'exp_fecha_fin'] as $attr) {
            if (!empty($this->$attr) && preg_match('/^\d{4}-\d{2}$/', $this->$attr)) {
                $this->$attr .= '-01';
            }
        }

        return true;
    }

    public function rules()
    {
        return [
            [['exp_cargo', 'exp_publicada'], 'required'],
            [['exp_mod_id', 'created_by', 'updated_by'], 'integer'],
            // Ensure valid dates in Y-m-d
            [['exp_fecha_inicio', 'exp_fecha_fin'], 'date', 'format' => 'php:Y-m-d'],
            [['exp_descripcion', 'exp_logros', 'exp_publicada'], 'string'],
            [['exp_cargo', 'exp_empresa'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'exp_id'           => 'ID de la experiencia',
            'exp_cargo'        => 'Cargo de la experiencia',
            'exp_empresa'      => 'Empresa de la experiencia',
            'exp_fecha_inicio' => 'Fecha de inicio de la experiencia',
            'exp_fecha_fin'    => 'Fecha de fin de la experiencia',
            'exp_descripcion'  => 'Descripción de la experiencia',
            'exp_logros'       => 'Logros alcanzados',
            'exp_publicada'    => 'Experiencia publicada',
            'exp_mod_id'       => 'Modalidad de experiencia',
            'created_at'       => 'Fecha de creación',
            'updated_at'       => 'Fecha de modificación',
            'created_by'       => 'Creado por',
            'updated_by'       => 'Modificado por',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function() {
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
        return $this->hasOne(User::class, ['usu_id' => 'created_by']);
    }

    public function getModalidad()
    {
        return $this->hasOne(Modalidad::class, ['mod_id' => 'exp_mod_id']);
    }

    public function getCreatedByUser()
    {
        return $this->hasOne(User::class, ['usu_id' => 'created_by']);
    }

    public function getUpdatedByUser()
    {
        return $this->hasOne(User::class, ['usu_id' => 'updated_by']);
    }
}
