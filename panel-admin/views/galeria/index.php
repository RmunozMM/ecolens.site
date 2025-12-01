<?php
use app\models\Galerias;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\helpers\LibreriaHelper;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\GaleriaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Galerías';
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="galerias-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'gal_id',
            'gal_titulo',
            [
                'label' => 'Estado',
                'attribute' => 'gal_estado',  
            ],   
            [
                'label' => 'Registro asociado',
                'format' => 'raw',
                'value' => function ($model) {
                    $controller = strtolower($model->gal_tipo_registro);
                    $param = $model->gal_id_registro;
                    $url = Url::to([$controller . '/view', strtolower(substr($model->gal_tipo_registro, 0, 3)) . "_id" => $param]); 
                    return Html::a($controller . '-' . $param, $url);
                }
            ],
            [
                'label' => 'Imágenes',
                'format' => 'html',
                'value' => function ($model)  use ($libreria) {
                    $imagenesHtml = '<div class="imagenes-container">';
                    foreach ($model->imagenesGaleria as $imagen) {
                        $imagenesHtml .= Html::img(LibreriaHelper::getRecursos(). "uploads/" . $imagen->img_ruta, ['width' => '120px', 'height' => '120px', 'class' => 'img-thumbnail img-gridview']);
                    }
                    $imagenesHtml .= '</div>';
                    return $imagenesHtml;
                },
            ],
            
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete'],
                'idAttribute'  => 'gal_id',
                'nombreRegistro' => 'galería',                
            ])
        ],
    ]); ?>

</div>

<style>
    .imagenes-container {
        white-space: nowrap;
        overflow-x: auto;
    }

    .img-gridview {
        margin-right: 10px;
    }
</style>
