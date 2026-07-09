<?php
session_start();
// 1. Validar seguridad: Solo usuarios logueados pueden eliminar
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
require_once 'conexion.php';
// 2. Validar que la variable 'id' venga en la URL (método GET)
if (isset($_GET['id'])) {
// Capturar el ID
$id_producto = $_GET['id'];
try {
// 3. Crear la consulta SQL segura para DELETE con un marcador (?)
$sql = "DELETE FROM productos WHERE id = ?";
// 4. Preparar la sentencia en el servidor MySQL
$stmt = $conn->prepare($sql);
// 5. Vincular el parámetro indicando que es un número entero ("i")
$stmt->bind_param("i", $id_producto);
// 6. Ejecutar la eliminación
$stmt->execute();
// 7. Cerrar la sentencia
$stmt->close();
// 8. Redirigir de regreso al inventario
header("Location: inventario.php");
exit();
} catch (mysqli_sql_exception $e) {
    // Manejo de errores: Por ejemplo, si intentas borrar un producto que
// tiene ventas asociadas en otra tabla (restricción de llave foránea).
die("Error crítico al intentar eliminar el registro: " . $e->getMessage());
}
} else {
// Si alguien entra al archivo sin enviar un ID en la URL, lo devolvemos
header("Location: inventario.php");
exit();
}
?>