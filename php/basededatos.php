<?php
$servidor = 'localhost';
$usuario = 'root';
$contrasena = '';
$baseDatos = 'wiki';

$definicion = '';

//----------------------------------------------------------------

// Creo conexión 
$conexion = new mysqli($servidor, $usuario, $contrasena, $baseDatos);

// Verifico conexión
if ($conexion->connect_error) {
  die("Conexión fallida: " . $conexion->connect_error);
}

//----------------------------------------------------------------

// Obtener definición por palabra
if (isset($_POST["modo"]) && $_POST["modo"] == "get") {   // 

  $palabra = $_POST["palabra"];

  $sql = "SELECT definicion 
          FROM entradas 
          WHERE palabra = '$palabra'";

  $res = $conexion->query($sql);
  if ($res->num_rows > 0) {

    $row = $res->fetch_assoc();

    $definicion = $row["definicion"];
  }
}

//----------------------------------------------------------------

// Establecer definición por palabra
if (isset($_POST["modo"]) && $_POST["modo"] == "set") {   // 

  $palabra = $_POST["palabra"];
  $definicion = $_POST["definicion"];

  $sql = "SELECT id 
          FROM entradas 
          WHERE palabra = '$palabra'";

  $res = $conexion->query($sql);

  if ($res->num_rows > 0) {
    
    $sql = "UPDATE entradas 
            SET definicion = '$definicion' 
            WHERE palabra = '$palabra'";
  } else {

    $sql = "INSERT INTO entradas 
            (palabra, definicion) 
            VALUES('$palabra', '$definicion')";
  }
  $conexion->query($sql);
}

//----------------------------------------------------------------

$conexion->close();

$resultado = array(
  'definicion' => $definicion,
);

echo json_encode($resultado);
