<?php

use app\models\Colores;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;


/** @var yii\web\View $this */
/** @var app\models\SitioSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Colores del Sitio';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sitio-index">
    <h2><?= Html::encode($this->title) ?></h2>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="small-container">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><b>Propiedad</b></td>
                                <td><b>Descripción</b></td>
                                <td>&nbsp;</td>
                                <td><b>Template</b></td>
                                <td><b>Color Actual</b></td>
                                <td><b>Color Hexadecimal</b></td>
                                <td style="padding-left: 30px;"><b>Acciones</b></td> <!-- Agregué el encabezado para la última columna -->
                            </tr>


                            <?php foreach ($colores as $color): ?>
                            <tr>
                                <td><?= Html::encode($color->col_nombre) ?></td>
                                <td><?= $color->col_descripcion ?><td>
                                <td><?= $color->getLayoutName($color->col_layout_id) ?></td>
                                <td>
                                    <input type="color" id="Picker_color_<?= Html::encode($color->col_id) ?>"
                                        value="<?= Html::encode($color->col_valor) ?>">
                                </td>
                                <td>
                                    <input type="text" id="color_<?= Html::encode($color->col_id) ?>"
                                        value="<?= Html::encode($color->col_valor) ?>" style="width:85px padding-top: 5px; border: none;">
                                    <div id="colorPreview<?= Html::encode($color->col_id) ?>"
                                        class="color-preview" style="background-color: <?= Html::encode($color->col_valor) ?>;"></div>
                                </td>
                                <td>
                                    <?php
                                    $url = Url::to(['color/index', 'col_id' => $color->col_id]);  
                                    ?>
                                    <a href="#" id="guardarEnlace<?= Html::encode($color->col_id) ?>" style="float:right;" class="btn btn-success">Guardar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        <?php foreach ($colores as $color): ?>
            agregarColor(
                "Picker_color_<?= Html::encode($color->col_id) ?>",
                "color_<?= Html::encode($color->col_id) ?>",
                "colorPreview<?= Html::encode($color->col_id) ?>",
                "<?= Html::encode($color->col_id) ?>" // Envuelto en comillas
            );
        <?php endforeach; ?>


    function agregarColor(colorPickerId, colorInputId, colorPreviewId, colorId) {
        var colorPicker = document.getElementById(colorPickerId);
        var colorInput = document.getElementById(colorInputId);
        var colorPreview = document.getElementById(colorPreviewId);
        var guardarEnlace = document.getElementById("guardarEnlace" + colorId); // Modificamos aquí

        colorInput.value = colorPicker.value;
        colorPreview.style.backgroundColor = colorInput.value;

        colorPicker.addEventListener("input", function () {
            colorInput.value = colorPicker.value;
            colorPreview.style.backgroundColor = colorInput.value;
        });

        // Función para guardar el color cuando se hace clic en el botón "Guardar"
        function guardarColor(event) {
            event.preventDefault();
            var enlace = "<?= Yii::$app->homeUrl ?>/color/update?col_id=" + colorId + "&col_valor=" + encodeURIComponent(colorInput.value);

            console.log("Guardando color con ID: " + colorId + " y valor: " + colorInput.value);
            console.log("Enlace de solicitud: " + enlace);

            // Realizar la solicitud para guardar el color en el backend
             window.location.href = enlace;
        }

        // Agregar evento de clic al botón "Guardar"
        guardarEnlace.addEventListener("click", guardarColor);
    }
});

</script>
