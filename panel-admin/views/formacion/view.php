<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;
use app\helpers\LibreriaHelper;



/** @var yii\web\View $this */
/** @var app\models\Formacion $model */

$titulo = $model->for_grado_titulo . " - " . $model->for_institucion;

$this->title = $titulo;
$this->params['breadcrumbs'][] = ['label' => 'Formación Académica', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="formacion-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'for_id' => $model->for_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'for_id' => $model->for_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'for_id',
            'for_institucion',
            'for_grado_titulo',
            [
                'attribute' => 'for_fecha_inicio',
                'format' => 'raw',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->for_fecha_inicio, 'MM-yyyy');
                },
            ],
            [
                'attribute' => 'for_fecha_fin',
                'format' => 'raw',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->for_fecha_fin, 'MM-yyyy');
                },
            ],
            [
                'label' => 'Usuario creador',
                'value' => $model->usuario->usu_username ?? '—',
            ],
            [
                'attribute' => 'for_logros_principales',
                'format' => 'html',
            ],
            'for_tipo_logro',
            'for_categoria',
            'for_publicada',
            'for_codigo_validacion',
            'for_mostrar_certificado',
            [
                'attribute' => 'for_certificado',
                'label' => 'Certificado',
                'format' => 'raw',
                'value' => function ($model) use ($libreria) {
                    if ($model->for_certificado) {
                        $rutaArchivo = LibreriaHelper::getRecursos(). "uploads/" . $model->for_certificado;
                        return '<iframe src="' . $rutaArchivo . '" width="100%" height="500px"></iframe>';
                    } else {
                        return '<p>No has subido un certificado</p>';
                    }
                },
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>