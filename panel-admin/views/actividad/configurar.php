<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var array $tablas */

$this->title = 'Configurar Actividad Reciente';
?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<h1><?= Html::encode($this->title) ?></h1>
<p class="text-muted">Define las tablas que se incluirán en la vista de actividad reciente. Puedes asociar un ícono visual a cada entidad.</p>

<?php $form = ActiveForm::begin([
    'action' => ['actividad/guardar-configuracion'],
    'method' => 'post'
]); ?>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle" id="tabla-config">
        <thead>
            <tr>
                <th>Nombre de Tabla</th>
                <th>Campo ID</th>
                <th>Campo Nombre/Título</th>
                <th>Ícono</th>
                <th class="text-center">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tablas as $i => $tabla): ?>
                <?php
                $iconData = explode('|', $tabla['icono'] ?? 'fa-database|#000000');
                $iconClass = $iconData[0] ?? 'fa-database';
                $iconColor = $iconData[1] ?? '#000000';
                $iconOptions = [
                    'fa-database', 'fa-file-lines', 'fa-newspaper', 'fa-briefcase',
                    'fa-envelope', 'fa-image', 'fa-user', 'fa-comment', 'fa-bolt', 'fa-star'
                ];
                ?>
                <tr>
                    <td><input type="text" name="Tablas[<?= $i ?>][nombre]" class="form-control" value="<?= Html::encode($tabla['nombre']) ?>"></td>
                    <td><input type="text" name="Tablas[<?= $i ?>][campo_id]" class="form-control" value="<?= Html::encode($tabla['campo_id']) ?>"></td>
                    <td><input type="text" name="Tablas[<?= $i ?>][campo_nombre]" class="form-control" value="<?= Html::encode($tabla['campo_nombre']) ?>"></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="form-control form-control-color" value="<?= Html::encode($iconColor) ?>" id="color-<?= $i ?>" onchange="actualizarIcono(<?= $i ?>)">
                            <select class="form-select" id="icono-<?= $i ?>" onchange="actualizarIcono(<?= $i ?>)">
                                <?php foreach ($iconOptions as $opt): ?>
                                    <option value="<?= $opt ?>" <?= $iconClass === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fa <?= $iconClass ?>" id="preview-icon-<?= $i ?>" style="color: <?= $iconColor ?>;"></i>
                        </div>
                        <input type="hidden" name="Tablas[<?= $i ?>][icono]" id="hidden-icono-<?= $i ?>" value="<?= Html::encode($tabla['icono'] ?? '') ?>">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Botón para añadir filas -->
<div class="mb-4">
    <button type="button" class="btn btn-secondary" onclick="agregarFila()">
        <i class="fa fa-plus"></i> Añadir fila
    </button>
</div>

<!-- Botón de guardar -->
<div class="form-group mb-5">
    <?= Html::submitButton('<i class="fa fa-save"></i> Guardar y Reconstruir Vista', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
function agregarFila() {
    const tbody = document.querySelector('#tabla-config tbody');
    const index = tbody.rows.length;
    const row = document.createElement('tr');

    const iconOptions = [
        'fa-database', 'fa-file-lines', 'fa-newspaper', 'fa-briefcase',
        'fa-envelope', 'fa-image', 'fa-user', 'fa-comment', 'fa-bolt', 'fa-star'
    ];

    const selectHtml = iconOptions.map(opt =>
        `<option value="${opt}">${opt}</option>`).join('');

    row.innerHTML = `
        <td><input type="text" name="Tablas[${index}][nombre]" class="form-control" placeholder="Nueva tabla"></td>
        <td><input type="text" name="Tablas[${index}][campo_id]" class="form-control" placeholder="ID ej. pag_id"></td>
        <td><input type="text" name="Tablas[${index}][campo_nombre]" class="form-control" placeholder="Campo ej. pag_titulo"></td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <input type="color" class="form-control form-control-color" value="#000000" id="color-${index}" onchange="actualizarIcono(${index})">
                <select class="form-select" id="icono-${index}" onchange="actualizarIcono(${index})">
                    ${selectHtml}
                </select>
                <i class="fa fa-database" id="preview-icon-${index}" style="color: #000000;"></i>
            </div>
            <input type="hidden" name="Tablas[${index}][icono]" id="hidden-icono-${index}" value="fa-database|#000000">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(this)">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
}

function eliminarFila(boton) {
    const fila = boton.closest('tr');
    if (fila) fila.remove();
}

function actualizarIcono(index) {
    const icon = document.getElementById(`icono-${index}`).value;
    const color = document.getElementById(`color-${index}`).value;
    document.getElementById(`hidden-icono-${index}`).value = icon + '|' + color;

    const preview = document.getElementById(`preview-icon-${index}`);
    if (preview) {
        preview.className = 'fa ' + icon;
        preview.style.color = color;
    }
}
</script>

<style>
/* Mejora la legibilidad del ícono de vista previa */
#tabla-config i.fa {
    font-size: 1.2em;
    min-width: 1.5em;
}
</style>
