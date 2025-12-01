<?php

include(__DIR__ . '/../../recursos/db_resources.php');

function obtenerDatos($arreglo) {
    $servidor = explode(';', $arreglo['dsn'])[0];
    $servidor = explode('=', $servidor);
    $servidor = $servidor[1];
    $basedatos = explode('=', explode(';', $arreglo['dsn'])[1])[1];
    $usuario = $arreglo['username'];
    $password = $arreglo['password'];

    return [
        'host' => $servidor,
        'name' => $basedatos,
        'user' => $usuario,
        'pass' => $password,
    ];
}

$mysql = obtenerDatos($arreglo);

$conn = new mysqli($mysql['host'], $mysql['user'], $mysql['pass'], $mysql['name']);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
