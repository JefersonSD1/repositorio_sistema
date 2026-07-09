<?php
session_start();
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
require_once 'conexion.php';

// Validar que se hayan enviado datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// 1. Capturar los datos enviados desde el formulario
$nombre_producto = trim($_POST['nombre']);
$categoria_id = $_POST['categoria']; // Este es el ID numérico
$stock = $_POST['stock'];
$precio = $_POST['precio'];

try {
// 2. Crear la consulta SQL segura con marcadores de posición (?)
$sql = "INSERT INTO productos (nombre_producto, categoria_id, stock, precio) VALUES
(?, ?, ?, ?)";

// 3. Preparar la sentencia en el servidor MySQL
$stmt = $conn->prepare($sql);

// 4. Vincular los parámetros de forma estricta.
// "siid" significa: (s)tring, (i)nteger, (i)nteger, (d)ouble/decimal
$stmt->bind_param("siid", $nombre_producto, $categoria_id, $stock, $precio);

// 5. Ejecutar la inserción
$stmt->execute();

// 6. Cerrar la sentencia
$stmt->close();

// 7. Redirigir automáticamente a la pantalla de inventario
header("Location: inventario.php");
exit();

} catch (mysqli_sql_exception $e) {
// En caso de error, detener el script y mostrar seguridad
die("Error al registrar el producto: " . $e->getMessage());
}
} else {
// Si alguien intenta entrar directamente a este archivo, lo expulsamos
header("Location: inventario.php");
exit();
}
?>