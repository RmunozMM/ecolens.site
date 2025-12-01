<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns; // 👈 IMPORTANTE agregarlo

/** @var yii\web\View $this */
/** @var app\models\Layouts $model */

$this->title = $model->lay_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Layouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="layouts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'lay_id' => $model->lay_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'lay_id' => $model->lay_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estas seguro de querer eliminar este ítem?. Esta acción no se puede deshacer',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
    'model' => $model,
    'attributes' => array_merge([
        'lay_id',
        'lay_nombre',
        'lay_ruta_imagenes',
        'lay_estado',
    ], AuditoriaGridColumns::getAuditoriaAttributes($model)), // <-- Acá hacemos el merge
    ]) ?>


    <?php
    // Obtener la lista de archivos en el directorio
    $rutaDirectorio = Yii::getAlias('@webroot') . '/' . 'temas/' . $model->lay_ruta_imagenes;
    $archivos = scandir($rutaDirectorio);
    ?>

    <h2>Imágenes del Layout</h2>
    <div class="row">
        <?php foreach ($archivos as $archivo) : ?>
            <?php
            // Obtener la extensión del archivo
            $extension = pathinfo($archivo, PATHINFO_EXTENSION);

            // Verificar si la extensión corresponde a una imagen
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array(strtolower($extension), $extensionesPermitidas)) {
                ?>
                <div class="col-md-3">
                    <img src="<?= Yii::getAlias('@web') . '/' . 'temas/' . $model->lay_ruta_imagenes . '/' . $archivo ?>" class="img-responsive img-thumbnail" alt="<?= $archivo ?>">
                </div>
            <?php
            }
            ?>
        <?php endforeach; ?>
    </div>

</div>