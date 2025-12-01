<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use app\widgets\ManageGaleriaButton;
use app\widgets\DuplicateWidget;

/**
 * Widget reutilizable para botones CRUD con configuración personalizada.
 */
class CrudActionButtons extends Widget
{
    /** @var \yii\db\ActiveRecord */
    public $model;

    /** @var string[] Acciones a mostrar: view, update, delete, gallery, duplicate, preview */
    public $actions = ['view', 'update', 'delete'];

    /** @var string Atributo de clave primaria (por ejemplo: art_id, cli_id...) */
    public $idAttribute = 'id';

    /** @var string nombre para mostrar en el tooltip (por ejemplo: artículo, cliente...) */
    public $nombreRegistro;

    /** @var array Acciones personalizadas como setPassword, publish, etc. */
    public $customActions = [];

    public function run()
    {
        $id = $this->model->{$this->idAttribute} ?? null;

        if ($id === null) {
            return Html::tag('span', '⚠️ ID no encontrado', ['class' => 'text-danger']);
        }

        $buttons = [];

        $modelName = $this->nombreRegistro 
            ?? strtolower((new \ReflectionClass($this->model))->getShortName());

        foreach ($this->actions as $action) {
            switch ($action) {
                case 'view':
                    $buttons[] = Html::a('', [$action, $this->idAttribute => $id], [
                        'class' => 'btn-action btn-view fa-solid fa-eye',
                        'title' => 'Ver ' . $modelName,
                    ]);
                    break;
                case 'update':
                    $buttons[] = Html::a('', [$action, $this->idAttribute => $id], [
                        'class' => 'btn-action btn-update fa-solid fa-pen',
                        'title' => 'Actualizar ' . $modelName,
                    ]);
                    break;
                case 'delete':
                    $buttons[] = Html::a('', [$action, $this->idAttribute => $id], [
                        'class' => 'btn-action btn-delete fa-solid fa-trash',
                        'title' => 'Eliminar ' . $modelName,
                        'data-confirm' => '¿Estás seguro de querer eliminar este registro?',
                    ]);
                    break;
                case 'gallery':
                case 'manageGaleria':
                    $buttons[] = ManageGaleriaButton::widget([
                        'id' => $id,
                    ]);
                    break;
                case 'duplicate':
                    $buttons[] = DuplicateWidget::widget([
                        'modelClass' => get_class($this->model),
                        'recordId'   => $id,
                        'class'      => 'btn-action btn-copy fa-solid fa-clone',
                    ]);
                    break;
                case 'preview':
                    $buttons[] = Html::a('', [$action, $this->idAttribute => $id], [
                        'class' => 'btn-action btn-preview fa-solid fa-magnifying-glass',
                        'title' => 'Vista previa',
                    ]);
                    break;
                case 'publish':
                    $estadoActual = strtolower($this->model->pag_estado ?? '');
                    $esPublicado = $estadoActual === 'publicado';

                    $tooltip = $esPublicado ? 'Despublicar Página' : 'Publicar Página';
                    $confirmMsg = $esPublicado
                        ? '¿Estás seguro de querer despublicar esta Página?'
                        : '¿Estás seguro de querer publicar esta Página?';

                    $claseEstado = $esPublicado ? 'btn-unpublish' : 'btn-publish';

                    $buttons[] = Html::a('', ['publish', $this->idAttribute => $id], [
                        'class' => 'btn-action fa-solid fa-bullhorn ' . $claseEstado,
                        'title' => $tooltip,
                        'data-confirm' => $confirmMsg,
                    ]);
                    break;
                    
                    
                    
                    
                default:
                    if (isset($this->customActions[$action])) {
                        $custom = call_user_func($this->customActions[$action], $this->model, $this->idAttribute);
                        if ($custom !== null) {
                            $buttons[] = $custom;
                        }
                    } else {
                        $buttons[] = Html::tag('span', "⚠️ Acción '$action' no reconocida", ['class' => 'text-danger']);
                    }
            }
        }

        return Html::tag('div', implode(' ', $buttons), ['class' => 'btn-group']);
    }

    /**
     * Atajo para usar como columna completa en GridView
     */
    public static function column(array $config = [])
    {
        return [
            'format' => 'raw',
            'header' => 'Acciones',
            'value' => function($model) use ($config) {
                return self::widget([
                    'model'          => $model,
                    'actions'        => $config['actions']        ?? ['view', 'update', 'delete'],
                    'idAttribute'    => $config['idAttribute']    ?? 'id',
                    'nombreRegistro' => $config['nombreRegistro'] ?? null,
                    'customActions'  => $config['customActions']  ?? [],
                ]);
            },
            'headerOptions' => ['class' => 'actions'],
            'contentOptions' => [
                'class' => 'actions',
                'style' => 'white-space: nowrap; max-width: 250px; overflow: hidden; text-overflow: ellipsis;',
            ],
        ];
    }
}
