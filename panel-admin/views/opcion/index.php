<?php

use app\models\Opcion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\OpcionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Opciones del Sistema';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="opcion-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if(Yii::$app->user->identity->usu_rol_id == 1): // Solo SuperAdmin puede crear opciones ?>
    <p>
        <?= Html::a('Crear Opción del Sistema', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'opc_id',
            'opc_nombre',
            [
                'attribute' => 'opc_valor',
                'format' => 'raw',
                'contentOptions' => function($model, $key, $index, $column) {
                    return [
                        'class' => 'inline-editable-td',
                        'data-id' => $model->opc_id,
                        'data-value' => $model->opc_valor,
                        'data-tipo' => $model->opc_tipo,
                        'style' => 'cursor:pointer; min-width: 100px; padding: 14px 18px;'
                    ];
                },
                'value' => function($model) {
                    // Puedes dejar solo el texto, sin span adicional
                    return Html::encode($model->opc_valor);
                },
            ],
            [
                'attribute' => 'opc_tipo',
                'filter' => [
                    'string' => 'String',
                    'int' => 'Entero',
                    'bool' => 'Booleano',
                    'float' => 'Decimal',
                    'json' => 'JSON',
                    'enum' => 'Enum',
                    'color' => 'Color',
                ],
            ],
            [
                'attribute' => 'opc_cat_id',
                'value' => function($model) {
                    return $model->categoria ? $model->categoria->cat_nombre : '(sin categoría)';
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    \app\models\CategoriaOpcion::find()->all(), 'cat_id', 'cat_nombre'
                ),
                'label' => 'Categoría',
            ],
            [
                'attribute' => 'opc_rol_id',
                'value' => function($model) {
                    return $model->rol ? $model->rol->rol_nombre : $model->opc_rol_id;
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    \app\models\Rol::find()->all(), 'rol_id', 'rol_nombre'
                ),
                'label' => 'Rol mínimo',
            ],
            [
                'attribute' => 'opc_descripcion',
                'format' => 'text',
                'contentOptions' => ['style' => 'max-width:200px; white-space:normal;'],
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),

            CrudActionButtons::column([
                'actions'         => ['view', 'update'],
                'idAttribute'     => 'opc_id',
                'nombreRegistro'  => 'opción del sistema',
            ]),
        ],
    ]); ?>


</div>

<?php
$this->registerJs("
    // Edición inline sobre el <td> completo (requiere 'inline-editable-td' como clase en contentOptions)
    $(document).on('click', '.inline-editable-td', function() {
        var td = $(this);
        var id = td.data('id');
        var oldValue = td.data('value');
        var tipo = td.data('tipo');

        // Previene múltiples inputs si ya está editando
        if(td.find('input, select, textarea').length > 0) return;

        var input;
        if (tipo === 'int') {
            input = $('<input type=\"number\" class=\"form-control form-control-sm\" style=\"max-width:100%; display:inline;\">').val(oldValue);
        } else {
            input = $('<input type=\"text\" class=\"form-control form-control-sm\" style=\"max-width:100%; display:inline;\">').val(oldValue);
        }

        td.html(input);
        input.focus();

        input.on('blur keydown', function(e) {
            if (e.type === 'blur' || (e.type === 'keydown' && e.key === 'Enter')) {
                var newValue = input.val();
                if (newValue !== oldValue) {
                    $.ajax({
                        url: '" . \yii\helpers\Url::to(['opcion/editar-valor']) . "',
                        type: 'POST',
                        data: {
                            id: id,
                            valor: newValue,
                            _csrf: yii.getCsrfToken()
                        },
                        success: function(response) {
                            td.data('value', newValue);
                            td.text(newValue);
                        },
                        error: function() {
                            td.text(oldValue);
                            alert('Error al guardar');
                        }
                    });
                } else {
                    td.text(oldValue);
                }
            }
        });
    });
");
?>

<style>
    .inline-editable-td { cursor: pointer; transition: background 0.2s; }
.inline-editable-td:hover { background: #f8f8f8; }  
</style>