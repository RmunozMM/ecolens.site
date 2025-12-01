<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "menu".
 *
 * @property int $men_id Identificador único del menú
 * @property string $men_nombre Nombre del menú
 * @property string|null $men_url URL del menú
 * @property string $men_etiqueta Etiqueta del menú
 * @property string|null $men_mostrar Indica si el menú debe mostrarse o no 
 * @property string|null $men_nivel Nivel del menú (nivel_1 para el menú principal, nivel_2 para submenús)
 * @property string|null $men_link_options Opciones adicionales del enlace del menú (por ejemplo, estilos)
 * @property string|null $men_target Atributo target del enlace del menú
 * @property int $men_rol_id ID del rol asociado al menú (clave externa que referencia la tabla de roles)
 * @property int|null $men_padre_id
 * @property string|null $created_at Fecha y hora de creación del registro
 * @property string|null $updated_at Fecha y hora de última modificación del registro
 * @property int|null $created_by ID del usuario que creó el registro
 * @property int|null $updated_by ID del usuario que actualizó el registro
 * @property string|null $men_icono Ícono Bootstrap + color (ej: bi-house|#223142)
 */
class Menu extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'menu';
    }

    public function rules()
    {
        return [
            [['men_nombre', 'men_rol_id'], 'required'],
            [['men_rol_id', 'men_padre_id', 'created_by', 'updated_by'], 'integer'],
            [['men_nivel', 'men_target', 'men_mostrar'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['men_nombre', 'men_url', 'men_etiqueta'], 'string', 'max' => 255],
            [['men_link_options'], 'string', 'max' => 1000],
            [['men_url'], 'required', 'when' => function($model) {
                    return $model->men_nivel == 'nivel_2';
                }, 'whenClient' => "function (attribute, value) {
                    return $('#menu_men_nivel').val() === 'nivel_2';
                }"
            ],
            ['men_icono', 'string', 'max' => 100],
            ['men_icono', 'default', 'value' => 'bi-house|#223142'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'men_id' => 'Identificador único del menú',
            'men_nombre' => 'Nombre del menú',
            'men_url' => 'URL del menú',
            'men_etiqueta' => 'Etiqueta del menú',
            'men_mostrar' => 'Indica si el menú debe mostrarse o no',
            'men_nivel' => 'Nivel del menú (nivel_1 para el menú principal, nivel_2 para submenús)',
            'men_link_options' => 'Opciones adicionales del enlace del menú (por ejemplo, estilos)',
            'men_target' => 'Atributo target del enlace del menú',
            'men_rol_id' => 'ID del rol asociado al menú (clave externa que referencia la tabla de roles)',
            'men_padre_id' => 'Men Padre ID',
            'created_at' => 'Fecha y hora de creación del registro',
            'updated_at' => 'Fecha y hora de última modificación del registro',
            'created_by' => 'ID del usuario que creó el registro',
            'updated_by' => 'ID del usuario que actualizó el registro',
            'men_icono' => 'Ícono (Bootstrap + color)',
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


    public function getOpcionesMenu()
    {
        if (Yii::$app->user->isGuest) {
            return [];
        }

        $rolId = Yii::$app->user->identity->usu_rol_id;
        $currentRoute = Yii::$app->controller->route;

        $accionesDetalle = ['view', 'update', 'create'];
        $routeParts = explode('/', $currentRoute);

        if (in_array($routeParts[1] ?? '', $accionesDetalle)) {
            $currentRoute = $routeParts[0] . '/index';
        }

        $query = Menu::find()->where(['men_nivel' => 'nivel_1', 'men_mostrar' => 'Si']);

        if ($rolId == 1) {
            // superadmin: ver todo
        } elseif ($rolId == 2) {
            $query->andWhere(['in', 'men_rol_id', [2, 3]]);
        } else {
            $query->andWhere(['men_rol_id' => 3]);
        }

        $query->orderBy(['men_posicion' => SORT_ASC]);
        $menuItems = $query->all();

        $menuOptions = [];

        foreach ($menuItems as $menuItem) {
            if ($menuItem->men_mostrar == 'Si') {
                $label = !empty($menuItem->men_etiqueta) ? $menuItem->men_etiqueta : $menuItem->men_nombre;
                $url = $this->formatearUrl($menuItem->men_url);

                $menuUrl = is_array($url) ? $url[0] : $url;
                $isActive = is_string($menuUrl) && strpos($currentRoute, $menuUrl) === 0;

                // Icono para menú principal
                $iconData = explode('|', $menuItem->men_icono ?? 'bi-house|#223142');
                $iconClass = $iconData[0] ?? 'bi-house';
                $iconColor = $iconData[1] ?? '#223142';

                $menuItemOptions = [
                    'label' => $label,
                    'url' => $url,
                    'linkOptions' => [
                        'style' => 'color: ' . Yii::$app->params['color_links_navbar_nivel_1'] . ' !important; font-size: ' . Yii::$app->user->identity->usu_letra . 'px;',
                        'target' => $menuItem->men_target,
                    ],
                    'icon' => $iconClass,       // Icono nivel 1
                    'iconColor' => $iconColor,  // Color nivel 1
                    'active' => $isActive,
                ];

                // SUBMENÚS
                $subMenuItems = Menu::find()
                    ->where(['men_padre_id' => $menuItem->men_id, 'men_mostrar' => 'Si'])
                    ->orderBy(['men_posicion' => SORT_ASC])
                    ->all();

                $sortedSubMenuItems = [];

                foreach ($subMenuItems as $subMenuItem) {
                    $subItemLabel = !empty($subMenuItem->men_etiqueta) ? $subMenuItem->men_etiqueta : $subMenuItem->men_nombre;
                    $subItemUrl = $this->formatearUrl($subMenuItem->men_url);
                    $subUrl = is_array($subItemUrl) ? $subItemUrl[0] : $subItemUrl;
                    $subActive = is_string($subUrl) && strpos($currentRoute, $subUrl) === 0;

                    // Icono para submenú
                    $subIconData = explode('|', $subMenuItem->men_icono ?? 'bi-list|#223142');
                    $subIconClass = $subIconData[0] ?? 'bi-list';
                    $subIconColor = $subIconData[1] ?? '#223142';

                    $sortedSubMenuItems[] = [
                        'label' => $subItemLabel,
                        'url' => $subItemUrl,
                        'linkOptions' => [
                            'style' => 'color: ' . Yii::$app->params['color_links_navbar_nivel_2'] . ' !important; font-size: ' . Yii::$app->user->identity->usu_letra . 'px;',
                            'target' => $subMenuItem->men_target,
                        ],
                        'icon' => $subIconClass,       // Icono nivel 2
                        'iconColor' => $subIconColor,  // Color nivel 2
                        'active' => $subActive,
                    ];

                    if ($subActive) {
                        $menuItemOptions['active'] = true;
                    }
                }

                $menuItemOptions['items'] = $sortedSubMenuItems;
                $menuOptions[] = $menuItemOptions;
            }
        }

        // DEBUG: Si quieres ver la estructura final
        // echo '<pre>'; print_r($menuOptions); exit;

        return $menuOptions;
    }


    private function formatearUrl($url)
    {
        if (!empty($url)) {
            return preg_match('/^(https?:\/\/|www\.)/i', $url) ? $url : [$url];
        }
        return '#';
    }

    public function getRol()
    {
        return $this->hasOne(Rol::class, ['rol_id' => 'men_rol_id']);
    }

    public function getUsuarioCreador()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'created_by']);
    }

    public function getUsuarioEditor()
    {
        return $this->hasOne(Users::class, ['usu_id' => 'updated_by']);
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
