<?php
session_start();
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
// Incluir el archivo de conexión
require_once 'conexion.php';

// Validar que realmente se recibió información por el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y limpiar los datos enviados por el usuario
$empresa = trim($_POST['empresa']);
$contacto = trim($_POST['contacto']);
$telefono = trim($_POST['telefono']);
$direccion = trim($_POST['direccion']);
try {
// 1. Estructurar la consulta SQL con marcadores de posición (?)
$sql = "INSERT INTO proveedores (nombre_empresa, contacto, telefono, direccion)
VALUES (?, ?, ?, ?)";

// 2. Preparar la consulta en el motor de base de datos
$stmt = $conn->prepare($sql);

// 3. Vincular los 4 parámetros indicando que todos son Strings ("ssss")
$stmt->bind_param("ssss", $empresa, $contacto, $telefono, $direccion);

// 4. Ejecutar la inserción
$stmt->execute();

// 5. Cerrar la sentencia de seguridad
$stmt->close();

// 6. Redirigir al usuario de vuelta a la lista de proveedores
header("Location: proveedores.php");
exit();
} catch (mysqli_sql_exception $e) {
die("Error crítico al registrar el proveedor: " . $e->getMessage());
}
} else {
// Expulsar a quien intente entrar por la URL directa
header("Location: proveedores.php");
exit();
}
?>