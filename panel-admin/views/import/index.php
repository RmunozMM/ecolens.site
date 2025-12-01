<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;

/** @var \app\models\forms\ImportForm $importForm */
/** @var string $modelClass */
/** @var array $fieldsMap */
/** @var string $modelLabel */
/** @var array $sqlStatements */
/** @var array $errors */
/** @var array $rowResults */

$this->title = 'Carga Masiva';

// Extraer nombre corto del modelo sin namespace
$shortModelName = (new \ReflectionClass($modelClass))->getShortName();
$routeId = Inflector::camel2id($shortModelName, '-');
$indexRoute = $routeId . '/index';
$modelLabel = Yii::$app->request->get('modelLabel', ucfirst($shortModelName));

$this->params['breadcrumbs'][] = ['label' => $modelLabel, 'url' => [$indexRoute]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Carga Masiva de <?= Html::encode($modelLabel) ?></h2>
    </div>
    <div class="card-body">
        <p class="text-muted">
            Selecciona un archivo CSV para importar registros en <strong><?= Html::encode($modelLabel) ?></strong>.
        </p>

        <div class="alert alert-info">
            <strong>Instrucciones:</strong>
            El archivo debe estar en formato <strong>CSV</strong> con delimitador <code>;</code>.
            Asegúrate de que la primera fila contenga los encabezados correctos.
        </div>

        <h5>📌 Campos Requeridos</h5>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Columna CSV</th>
                    <th>Atributo en el sistema</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fieldsMap as $csvColumn => $attribute): ?>
                    <tr>
                        <td><strong><?= Html::encode($csvColumn) ?></strong></td>
                        <td><?= Html::encode($attribute) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php $form = ActiveForm::begin([
            'id' => 'import-form',
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

        <div class="mb-3">
            <?= $form->field($importForm, 'uploadFile')->fileInput(['class' => 'form-control']) ?>
        </div>

        <div class="d-flex justify-content-between">
            <?= Html::a(
                '<i class="fa fa-arrow-left"></i> Volver a ' . Html::encode($modelLabel),
                [$indexRoute],
                ['class' => 'btn btn-secondary']
            ) ?>
            <?= Html::button('<i class="fa fa-upload"></i> Importar Archivo', [
                'class' => 'btn btn-success',
                'id' => 'import-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <!-- Resultado de la importación -->
        <div id="import-result" class="mt-4"></div>

        <!-- Tabla de errores -->
        <div id="import-errors" class="mt-4" style="<?= empty($errors) ? 'display: none;' : '' ?>">
            <h5 class="text-danger">Errores de Importación</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fila</th>
                        <th>Mensaje de Error</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($errors as $error): ?>
                        <tr>
                            <td><?= Html::encode($error['row']) ?></td>
                            <td><?= Html::encode($error['message']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SQL Debug -->
        <div id="sql-debug" class="mt-4" style="<?= empty($sqlStatements) ? 'display: none;' : '' ?>">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="text-warning mb-0">SQL Generado</h5>
                <button type="button" id="copy-sql" class="btn btn-info btn-sm">Copiar SQL</button>
            </div>
            <pre id="sql-content" class="bg-light p-3"><?= implode("\n", $sqlStatements) ?></pre>
        </div>

        <!-- Resultados por Fila -->
        <?php if (!empty($rowResults)): ?>
            <div id="row-results" class="mt-4">
                <h5>Resultados por Fila</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fila CSV</th>
                            <th>Estado</th>
                            <th>Mensaje</th>
                            <th>SQL Generado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rowResults as $res): ?>
                            <tr>
                                <td><?= Html::encode($res['row']) ?></td>
                                <td><?= Html::encode($res['status']) ?></td>
                                <td><?= Html::encode($res['message']) ?></td>
                                <td><?= Html::encode(isset($res['sql']) ? $res['sql'] : '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$importUrl = Url::to(['import/index', 'modelClass' => $modelClass, 'fieldsMap' => json_encode($fieldsMap)]);
$script = <<<JS
$(document).ready(function () {
    $('#import-button').click(function () {
        var formData = new FormData($('#import-form')[0]);
        $.ajax({
            url: '$importUrl',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $('#import-result').html('<div class="alert alert-info">Procesando archivo...</div>');
                $('#import-errors tbody').empty();
                $('#import-errors').hide();
                $('#sql-debug').hide();
                $('#row-results').hide();
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('#import-result').html('<div class="alert alert-success">' + response.message + '</div>');
                } else {
                    $('#import-result').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
                if (response.errors && response.errors.length > 0) {
                    $('#import-errors').show();
                    response.errors.forEach(function (error) {
                        $('#import-errors tbody').append('<tr><td>' + error.row + '</td><td>' + error.message + '</td></tr>');
                    });
                }
                if (response.sqlStatements && response.sqlStatements.length > 0) {
                    $('#sql-debug').show().find('pre').html(response.sqlStatements.join("\\n"));
                }
                if (response.rowResults && response.rowResults.length > 0) {
                    var html = '';
                    response.rowResults.forEach(function (res) {
                        html += '<tr><td>' + res.row + '</td><td>' + res.status + '</td><td>' + res.message + '</td><td>' + (res.sql ? res.sql : '') + '</td></tr>';
                    });
                    $('#row-results tbody').html(html);
                    $('#row-results').show();
                }
            },
            error: function () {
                $('#import-result').html('<div class="alert alert-danger">Error en la comunicación con el servidor.</div>');
            }
        });
    });
    
    // Copiar SQL al portapapeles
    $('#copy-sql').click(function(){
        var sqlContent = $('#sql-content').text();
        if (!sqlContent) {
            alert('No hay SQL para copiar.');
            return;
        }
        navigator.clipboard.writeText(sqlContent).then(function(){
            alert('SQL copiado al portapapeles.');
        }, function(){
            alert('Error al copiar el SQL.');
        });
    });
});
JS;
$this->registerJs($script);
?>