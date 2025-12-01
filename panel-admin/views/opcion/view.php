<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CategoriaOpcion;
use app\models\Rol;
use app\helpers\AuditoriaGridColumns; // Si usas el helper de auditoría

/** @var yii\web\View $this */
/** @var app\models\Opcion $model */

$this->title = $model->opc_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Opciones del Sistema', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="opcion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'opc_id' => $model->opc_id], ['class' => 'btn btn-primary']) ?>
        <?php /*
        <?= Html::a('Eliminar', ['delete', 'opc_id' => $model->opc_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar esta opción?',
                'method' => 'post',
            ],
        ]) ?>
         */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            //'opc_id',
            'opc_nombre',
[
    'attribute' => 'opc_valor',
    'format' => 'raw',
    'value' => function($model) {
        if ($model->opc_tipo == 'color') {
            return "<span style='display:inline-block;width:30px;height:30px;border-radius:4px;border:1px solid #ccc;background:{$model->opc_valor}'></span> {$model->opc_valor}";
        }
        if ($model->opc_tipo == 'bool') {
            return $model->opc_valor === 'yes'
                ? "<span class='badge bg-success'>Sí</span>"
                : "<span class='badge bg-danger'>No</span>";
        }
        // Para otros tipos, muestra como texto plano/JSON
        return nl2br(Html::encode($model->opc_valor));
    },
],
            [
                'attribute' => 'opc_tipo',
                'value' => function($model) {
                    // Puedes poner un array con los labels si quieres
                    $labels = [
                        'string' => 'Texto',
                        'int' => 'Número',
                        'bool' => 'Booleano',
                        'float' => 'Decimal',
                        'json' => 'JSON',
                        'enum' => 'Enum',
                        'color' => 'Color',
                    ];
                    return $labels[$model->opc_tipo] ?? $model->opc_tipo;
                },
            ],
            [
                'attribute' => 'opc_cat_id',
                'label' => 'Categoría',
                'value' => function($model) {
                    return $model->categoria ? $model->categoria->cat_nombre : "(Sin categoría)";
                },
            ],
            [
                'attribute' => 'opc_rol_id',
                'label' => 'Rol mínimo',
                'value' => function($model) {
                    return $model->rol ? $model->rol->rol_nombre : "(Sin rol)";
                },
            ],
            'opc_descripcion',
        ], method_exists(AuditoriaGridColumns::class, 'getAuditoriaAttributes') ? AuditoriaGridColumns::getAuditoriaAttributes($model) : []),
    ]) ?>

</div>