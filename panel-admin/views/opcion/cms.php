<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Opcion[] $coloresCms */

$this->title = 'Colores del CMS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="opcion-cms">
    <h2><?= Html::encode($this->title) ?></h2>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="small-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Propiedad</th>
                                <th>Descripción</th>
                                <th>Picker</th>
                                <th>Hex</th>
                                <th style="padding-left: 30px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($coloresCms as $opcion): ?>
                            <tr>
                                <td><?= Html::encode($opcion->opc_nombre) ?></td>
                                <td><?= Html::encode($opcion->opc_descripcion) ?></td>
                                <td>
                                    <input
                                        type="color"
                                        id="picker_<?= $opcion->opc_id ?>"
                                        value="<?= Html::encode($opcion->opc_valor) ?>">
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        id="input_<?= $opcion->opc_id ?>"
                                        value="<?= Html::encode($opcion->opc_valor) ?>"
                                        style="width:85px; padding-top:5px; border:none;">
                                    <div
                                        id="preview_<?= $opcion->opc_id ?>"
                                        class="color-preview"
                                        style="display:none; width:20px; height:20px; vertical-align:middle; margin-left:5px; background-color:<?= Html::encode($opcion->opc_valor) ?>;">
                                    </div>
                                </td>
                                <td>
                                    <a href="#"
                                       id="guardar_<?= $opcion->opc_id ?>"
                                       class="btn btn-success"
                                       style="float:right;">
                                        Guardar
                                    </a>
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

<?php $home = Yii::$app->homeUrl; ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    <?php foreach ($coloresCms as $opcion): ?>
    agregarColor(
        "picker_<?= $opcion->opc_id ?>",
        "input_<?= $opcion->opc_id ?>",
        "preview_<?= $opcion->opc_id ?>",
        "guardar_<?= $opcion->opc_id ?>",
        <?= $opcion->opc_id ?>,
        "<?= $home ?>opcion/update-color"
    );
    <?php endforeach; ?>
});

function agregarColor(pickerId, inputId, previewId, botonId, opcId, updateUrl) {
    const picker  = document.getElementById(pickerId);
    const input   = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const boton   = document.getElementById(botonId);

    // Inicializa el input y el preview
    input.value = picker.value;
    preview.style.backgroundColor = picker.value;

    // Cuando cambie el color
    picker.addEventListener("input", () => {
        input.value = picker.value;
        preview.style.backgroundColor = picker.value;
    });

    // Al hacer clic en Guardar
    boton.addEventListener("click", function(e) {
        e.preventDefault();
        const valor = encodeURIComponent(input.value);
        // Ej: /opcion/update?opc_id=4&opc_valor=%23ff0000
        const url = `${updateUrl}?opc_id=${opcId}&opc_valor=${valor}`;
        window.location.href = url;
    });
}
</script>
