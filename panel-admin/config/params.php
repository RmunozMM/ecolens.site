<?php
include("../../recursos/db_resources.php");

function obtenerDatos($arreglo){
    // Obtener los valores individuales del array
    $servidor = explode(';', $arreglo['dsn'])[0];
    $servidor = explode ('=', $servidor);
    $servidor = $servidor[1];
    $basedatos = explode('=', explode(';', $arreglo['dsn'])[1])[1];
    $usuario = $arreglo['username'];
    $password = $arreglo['password'];

    $mysql = array(
        "host"=>$servidor,
        "name"=>$basedatos,
        "user"=>$usuario,
        "pass"=>$password,
  );

 return $mysql;

}

$mysql = obtenerDatos($arreglo); 

// Función para obtener las configuraciones
function obtenerConfiguraciones() {
  global $mysql;

  $conn = mysqli_connect($mysql['host'], $mysql['user'], $mysql['pass'] , $mysql['name']);

  // Verificar la conexión
  if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
  }
 // Establecer la codificación de caracteres UTF-8
 mysqli_set_charset($conn, 'utf8');
 


  $sql = "
        SELECT 
          opc_nombre COLLATE utf8mb4_unicode_ci AS con_nombre, 
          opc_valor COLLATE utf8mb4_unicode_ci AS con_valor 
        FROM opciones

        UNION ALL

        SELECT 
          col_nombre COLLATE utf8mb4_unicode_ci AS con_nombre, 
          col_valor COLLATE utf8mb4_unicode_ci AS con_valor 
        FROM colores
        WHERE col_layout_id IS NULL

  ";

  $result = mysqli_query($conn, $sql);

  // Verificar si se obtuvo algún resultado
  if (mysqli_num_rows($result) > 0) {
    // Obtener los resultados como un arreglo asociativo
    $configuraciones = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
      $configuraciones[$row["con_nombre"]] = $row["con_valor"];
    }
    
    // Cerrar la conexión
    mysqli_close($conn);
    // Devolver el arreglo de configuraciones
    return $configuraciones;
  } else {
    // Cerrar la conexión
    mysqli_close($conn);
    // Devolver un arreglo vacío si no se encontraron resultados
    return array();

  }
}
// Ejemplo de uso
$configuraciones = obtenerConfiguraciones();
return $configuraciones;



?>
