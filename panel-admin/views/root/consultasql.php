<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Consulta SQL';
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Url;
$url_tables = Url::to(['/root/get-tables'], true);

// Asegurar un string para Ace Editor
$consultaJs = json_encode($model->consulta ?? '');
?>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h2><?= Html::encode($this->title) ?></h2>

            <div class="consulta-form">
                <?php $form = ActiveForm::begin(); ?>

                <label for="sql-editor"><strong>Escribe tu consulta SQL (solo SELECT):</strong></label>
                <div id="sql-editor" style="height: 300px; width: 100%;"></div>

                <!-- Campo hidden con la consulta -->
                <?= $form->field($model, 'consulta')->hiddenInput(['id' => 'txt-sql'])->label(false) ?>

                <div class="form-group" style="margin-top:10px;">
                    <?= Html::submitButton(
                        '<i class="fas fa-play"></i> Ejecutar',
                        [
                            'class' => 'btn btn-success btn-lg',
                            'id'    => 'btn-ejecutar',
                        ]
                    ) ?>

                    <?= Html::submitButton(
                        '<i class="fas fa-file-csv"></i> Exportar CSV',
                        [
                            'class' => 'btn btn-primary btn-lg',
                            'name'  => 'btn-exportar-csv',
                            'value' => '1',
                        ]
                    ) ?>

                    <span id="cargando" style="display:none; margin-left: 10px;">
                        <i class="fas fa-spinner fa-spin"></i> Ejecutando...
                    </span>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

            <?php if (!empty($resultado)): ?>
                <h2>Resultado de la consulta:</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <?php foreach (array_keys($resultado[0]) as $columna): ?>
                                    <th><?= Html::encode($columna) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultado as $fila): ?>
                                <tr>
                                    <?php foreach ($fila as $valor): ?>
                                        <td><?= Html::encode($valor) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Ace Editor + ext-language_tools -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-language_tools.js" charset="utf-8"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var editor = ace.edit("sql-editor");
    editor.session.setMode("ace/mode/sql");
    editor.setTheme("ace/theme/sqlserver");

    var initialQuery = <?= $consultaJs ?> || "";
    editor.setValue(initialQuery, -1);

    editor.setOptions({
        fontSize: "16px",
        showLineNumbers: true,
        wrap: true,
        showPrintMargin: true,
        enableBasicAutocompletion: true,
        enableLiveAutocompletion: true,
        enableSnippets: true
    });

    var textarea = document.getElementById("txt-sql");
    editor.session.on("change", function () {
        textarea.value = editor.getValue();
    });

    document.getElementById("btn-ejecutar").addEventListener("click", function () {
        document.getElementById("cargando").style.display = "inline-block";
    });

    if (!editor.completers) {
        editor.completers = [];
    }

    fetch("<?= htmlspecialchars($url_tables, ENT_QUOTES, 'UTF-8') ?>")
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error en la API: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("📥 Datos recibidos de la API:", data);

            if (!data || Object.keys(data).length === 0) {
                console.warn("⚠️ No se recibieron tablas desde la API.");
                return;
            }

            let tableSuggestions = [];

            Object.keys(data).forEach(table => {
                tableSuggestions.push({ value: table, meta: "🗂 Tabla" });
                data[table].forEach(column => {
                    tableSuggestions.push({ value: column, meta: "📌 Columna" });
                });
            });

            var customCompleter = {
                getCompletions: function (editor, session, pos, prefix, callback) {
                    if (prefix.length === 0) return;
                    console.log("🔹 Autocompletado activado: ", prefix);
                    callback(null, tableSuggestions);
                }
            };

            editor.completers.push(customCompleter);

            editor.commands.on("afterExec", function (e) {
                if (
                    e.command.name === "insertstring" &&
                    /^[a-zA-Z0-9_.]$/.test(e.args)
                ) {
                    editor.execCommand("startAutocomplete");
                }
            });

            console.log("✅ Autocompletado de SQL cargado con éxito.");
        })
        .catch(error => console.error("❌ Error al obtener tablas:", error));
});
</script>