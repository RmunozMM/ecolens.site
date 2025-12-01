<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Yii;

/**
 * This is the model class for table "paginas".
 *
 * @property int         $pag_id ID único de la página
 * @property string      $pag_titulo Título de la página (único)
 * @property string|null $pag_contenido_antes Contenido HTML de la página antes
 * @property string|null $pag_contenido_despues Contenido HTML de la página después
 * @property string|null $pag_contenido_programador Bloque de código editable por un programador
 * @property string|null $pag_css_programador CSS exclusivo para esta página
 * @property string|null $pag_plantilla Nombre de archivo de plantilla PHP (solo nombre, sin ruta)
 * @property string|null $pag_slug Slug de la página (único)
 * @property string      $pag_estado Estado de la página
 * @property int         $pag_autor_id ID del autor de la página
 * @property string      $pag_mostrar_menu ¿Enlace en menú principal?
 * @property string      $pag_mostrar_menu_secundario ¿Enlace en menú secundario?
 * @property int         $pag_posicion Posición de la página en el menú
 * @property string      $pag_label Label del hipervínculo de la Página
 * @property string      $pag_modo_contenido Modo de edición de la Página
 * @property string|null $pag_fuente_contenido Fuente de contenido: usar_plantilla o editar_directo
 * @property string|null $pag_icono Ícono de la Página
 * @property string|null $pag_acceso Nivel de visibilidad de la página (publica o privada)
 *
 * Auditoría:
 * @property string|null $created_at Fecha de creación
 * @property string|null $updated_at Fecha de última modificación
 * @property int|null    $created_by Usuario que creó el registro
 * @property int|null    $updated_by Usuario que modificó el registro
 */
class Pagina extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'paginas';
    }

    public function rules()
    {
        return [
            // Campos obligatorios siempre
            [['pag_titulo', 'pag_autor_id', 'pag_mostrar_menu'], 'required'],

            // Tipos básicos
            [['pag_contenido_antes', 'pag_contenido_despues', 'pag_estado', 'pag_label', 'pag_icono', 'pag_contenido_programador', 'pag_css_programador'], 'string'],
            [['pag_posicion', 'pag_autor_id', 'created_by', 'updated_by'], 'integer'],
            [['pag_mostrar_menu_secundario', 'pag_mostrar_menu'], 'string'],
            [['pag_mostrar_menu', 'pag_mostrar_menu_secundario'], 'default', 'value' => 'NO'],
            [['created_at', 'updated_at'], 'safe'],
            [['pag_titulo', 'pag_slug', 'pag_modo_contenido'], 'string', 'max' => 255],

            // --- Validaciones para pag_modo_contenido ---
            [['pag_modo_contenido'], 'default', 'value' => 'autoadministrable'],
            [['pag_modo_contenido'], 'in', 'range' => ['autoadministrable', 'administrado_programador']],

            // --- Validación para pag_fuente_contenido ---
            [['pag_fuente_contenido'], 'default', 'value' => 'usar_plantilla'],
            [['pag_fuente_contenido'], 'in', 'range' => ['usar_plantilla', 'editar_directo']],

            // --- Validación para pag_acceso ---
            [['pag_acceso'], 'default', 'value' => 'publica'],
            [['pag_acceso'], 'in', 'range' => ['publica', 'privada']],

            // --- Formato de pag_plantilla ---
            ['pag_plantilla', 'match',
                'pattern' => '/^[a-zA-Z0-9_\-]+\.php$/',
                'message' => 'El nombre de plantilla debe terminar en .php y no contener rutas.'
            ],
            ['pag_plantilla', 'string', 'max' => 255],

            // --- pag_plantilla es requerido cuando: modo=programador + fuente=plantilla
            ['pag_plantilla', 'required',
                'when' => function($model) {
                    return $model->pag_modo_contenido === 'administrado_programador'
                        && $model->pag_fuente_contenido === 'usar_plantilla';
                },
                'whenClient' => "function (attribute, value) {
                    return $('#pagina-pag_modo_contenido').val() === 'administrado_programador'
                        && $('#pagina-pag_fuente_contenido').val() === 'usar_plantilla';
                }",
                'message' => 'Cuando fuente es “Usar plantilla” en modo programador, debes ingresar un archivo de plantilla.'
            ],

            // --- pag_contenido_programador es requerido cuando: modo=programador + fuente=directo
            [['pag_contenido_programador'], 'required',
                'when' => function($model) {
                    return $model->pag_modo_contenido === 'administrado_programador'
                        && $model->pag_fuente_contenido === 'editar_directo';
                },
                'whenClient' => "function (attribute, value) {
                    return $('#pagina-pag_modo_contenido').val() === 'administrado_programador'
                        && $('#pagina-pag_fuente_contenido').val() === 'editar_directo';
                }",
                'message' => 'Cuando fuente es “Editar directamente” en modo programador, debes ingresar el código en Contenido Programador.'
            ],

            // Título único
            [['pag_titulo'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'pag_id'                      => 'ID único de la página',
            'pag_titulo'                  => 'Título de la página (único)',
            'pag_contenido_antes'         => 'Contenido HTML de la página antes',
            'pag_contenido_despues'       => 'Contenido HTML de la página después',
            'pag_contenido_programador'   => 'Bloque de código editable por un programador',
            'pag_css_programador'         => 'CSS exclusivo para esta página',
            'pag_plantilla'               => 'Archivo de Plantilla',
            'pag_fuente_contenido'        => 'Fuente de contenido',
            'pag_slug'                    => 'Slug de la página (único)',
            'pag_estado'                  => 'Estado de la página',
            'pag_autor_id'                => 'ID del autor de la página',
            'pag_mostrar_menu'            => '¿Enlace en menú principal?',
            'pag_mostrar_menu_secundario' => '¿Enlace en menú secundario?',
            'pag_posicion'                => 'Posición de la página en el menú',
            'pag_label'                   => 'Label del hipervínculo de la Página',
            'pag_modo_contenido'          => 'Modo de edición de la Página',
            'pag_icono'                   => 'Ícono de la Página',
            'pag_acceso'                  => 'Nivel de visibilidad de la página',
            'created_at'                  => 'Fecha de creación',
            'updated_at'                  => 'Última modificación',
            'created_by'                  => 'Creado por',
            'updated_by'                  => 'Modificado por',
        ];
    }

    public static function optsPagAcceso()
    {
        return [
            'publica' => 'Pública',
            'privada' => 'Privada',
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
        return $this->hasOne(Users::class, ['usu_id' => 'pag_autor_id']);
    }

    public function getModoContenidoLabel()
    {
        $modos = [
            'autoadministrable'        => 'Autoadministrable',
            'administrado_programador' => 'Administrado por Programador',
            'ambos'                    => 'Ambos',
        ];

        return $modos[$this->pag_modo_contenido] ?? null;
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