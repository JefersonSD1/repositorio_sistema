<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
require_once 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Capturar datos del formulario (incluyendo el ID oculto)
$id_producto = $_POST['id'];
$nombre = trim($_POST['nombre']);
$categoria_id = $_POST['categoria'];
$stock = $_POST['stock'];
$precio = $_POST['precio'];

try {
// Sentencia UPDATE con marcadores (?) para prevenir Inyección SQL
$sql = "UPDATE productos SET nombre_producto = ?, categoria_id = ?, stock = ?, precio = ?
WHERE id = ?";
$stmt = $conn->prepare($sql);

// Vincular parámetros: string, int, int, double, int ("siidi")
$stmt->bind_param("siidi", $nombre, $categoria_id, $stock, $precio, $id_producto);

$stmt->execute();
$stmt->close();

header("Location: inventario.php");
exit();

} catch (mysqli_sql_exception $e) {
die("Error al actualizar el producto: " . $e->getMessage());
}
} else {
header("Location: inventario.php");
exit();
}
?>