<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "media".
 *
 * @property int $med_id Identificador único del medio
 * @property string $med_nombre Nombre del medio
 * @property string $med_ruta Ruta del medio en el servidor
 * @property string $med_descripcion Descripción del medio
 * @property string|null $med_entidad Entidad asociada al medio
 * @property string $med_tipo Tipo de medio: 'entidad', 'site' o 'tinymce'
 * @property int|null $med_registro ID del registro asociado (p.ej. pag_id, art_id)
 * @property int $med_orden Orden correlativo dentro de la galería TinyMCE
 *
 * @property string|null $created_at Fecha y hora de creación del registro
 * @property string|null $updated_at Fecha y hora de última modificación del registro
 * @property int|null $created_by ID del usuario que creó el registro
 * @property int|null $updated_by ID del usuario que actualizó el registro
 */
class Media extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'media';
    }

    public function rules()
    {
        return [
            [['med_nombre', 'med_ruta', 'med_tipo'], 'required'],
            [['med_registro', 'med_orden', 'created_by', 'updated_by'], 'integer'],
            [['med_nombre', 'med_descripcion', 'med_entidad'], 'string', 'max' => 255],
            [['med_descripcion'], 'default', 'value' => ''],
            [['med_ruta'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, ico'],
            [['med_tipo'], 'in', 'range' => ['entidad', 'site', 'tinymce']],
            [['med_entidad'], 'match', 'pattern' => '/^[a-z0-9_]+$/',
                'message' => 'Solo letras minúsculas, números y “_”', 'skipOnEmpty' => true],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'med_id'          => 'Identificador único del medio',
            'med_nombre'      => 'Nombre del medio',
            'med_ruta'        => 'Ruta del medio en el servidor',
            'med_descripcion' => 'Descripción del medio',
            'med_entidad'     => 'Entidad asociada al medio',
            'med_tipo'        => 'Tipo de medio',
            'med_registro'    => 'ID del registro asociado',
            'med_orden'       => 'Orden correlativo',
            'created_at'      => 'Fecha y hora de creación del registro',
            'updated_at'      => 'Fecha y hora de última modificación del registro',
            'created_by'      => 'ID del usuario que creó el registro',
            'updated_by'      => 'ID del usuario que actualizó el registro',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
            [
                'class'              => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
                // defaultValue = null is fine, toma Yii::$app->user->id
            ],
        ];
    }
    

    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        // Normalizar med_entidad
        if ($this->med_tipo === 'entidad' && $this->med_entidad) {
            $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $this->med_entidad);
            $slug = preg_replace('/[^a-zA-Z0-9]+/', '_', $slug);
            $this->med_entidad = strtolower(trim($slug, '_'));
        }

        // Generar med_nombre si falta
        if ($this->med_tipo === 'tinymce' || $this->med_tipo === 'site') {
            if (empty($this->med_nombre) && $this->med_ruta instanceof \yii\web\UploadedFile) {
                $this->med_nombre = pathinfo($this->med_ruta->name, PATHINFO_FILENAME);
            }
        }

        return true;
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
