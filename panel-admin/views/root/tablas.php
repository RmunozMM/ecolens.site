<?php
use yii\db\Connection;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Ver estructura de Tablas';
$this->params['breadcrumbs'][] = $this->title;

// Incluye el archivo de configuración de la base de datos
$arreglo = require('../../recursos/db_resources.php');

// Quitar 'class' del array para no interferir con la conexión
unset($arreglo['class']);

// Crear la conexión manualmente con los parámetros del archivo
$db = new Connection($arreglo);

// Obtener todas las tablas
$tables = $db->schema->getTableNames();

// 1) Excluir la tabla "usuarios" del listado
$tables = array_filter($tables, function($tableName) {
    return strtolower($tableName) !== 'usuarios';
});

// Obtener valor 'tabla' desde el GET (p.ej. ?tabla=alumnos)
$tabla = Yii::$app->request->get('tabla');

// Genera el <form> con el dropdown
echo Html::beginForm(['tablas'], 'get'); 
// 'tablas' es la acción del controlador a la que apuntas, ajústalo si lo necesitas
echo Html::dropDownList(
    'tabla',
    $tabla,
    array_combine($tables, $tables), // clave=>valor con los mismos nombres
    [
        'class'    => 'form-select',
        'prompt'   => 'Selecciona una tabla',
        'onchange' => 'this.form.submit()'
    ]
);
echo Html::endForm();

// Verifica si se eligió una tabla
if (!empty($tabla)) {
    // 2) Chequear si "usuarios" fue forzado manualmente
    //    Aunque lo quitamos del combo, alguien puede poner ?tabla=usuarios
    if (strtolower($tabla) === 'usuarios') {
        echo "La tabla no existe.";
        return;
    }

    // Obtener el schema de la tabla
    $tableSchema = $db->getSchema()->getTableSchema($tabla);

    // Verifica si la tabla existe (getTableSchema devolverá null si no existe)
    if ($tableSchema !== null) {
        // Información de columnas y llaves
        $columns     = $tableSchema->columns;
        $primaryKey  = $tableSchema->primaryKey;
        $foreignKeys = $tableSchema->foreignKeys; // por si lo usas después

        // Mostrar la tabla de forma responsiva
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan="5">' . Html::encode($tabla) . '</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Columna</th>';
        echo '<th>Tipo</th>';
        echo '<th>Tamaño</th>';
        echo '<th>Es clave primaria</th>';
        echo '<th>Comentarios</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Recorre columnas
        foreach ($columns as $column) {
            $columnName         = $column->name;
            $columnType         = $column->type;
            $columnSize         = $column->size;
            $columnIsPrimaryKey = in_array($columnName, $primaryKey);

            // Si es columna ENUM
            if ($column->type === 'string' && $column->enumValues !== null) {
                $enumValues = $column->enumValues;
                echo '<tr>';
                echo '<td>' . Html::encode($columnName) . '</td>';
                echo '<td>ENUM</td>';
                echo '<td>' . implode(', ', $enumValues) . '</td>';
                echo '<td>' . ($columnIsPrimaryKey ? 'Sí' : 'No') . '</td>';
                echo '<td>' . Html::encode($column->comment) . '</td>';
                echo '</tr>';
            } else {
                // Columna normal
                echo '<tr>';
                echo '<td>' . Html::encode($columnName) . '</td>';
                echo '<td>' . Html::encode($columnType) . '</td>';
                echo '<td>' . Html::encode($columnSize) . '</td>';
                echo '<td>' . ($columnIsPrimaryKey ? 'Sí' : 'No') . '</td>';
                echo '<td>' . Html::encode($column->comment) . '</td>';
                echo '</tr>';
            }
        }

        // Cerrar la tabla HTML
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        // Si no existe (o se forzó un nombre inexistente)
        echo "La tabla no existe.";
    }
} else {
    // No se seleccionó nada todavía
    echo "No se proporcionó el parámetro 'tabla' en la URL.";
}
?>