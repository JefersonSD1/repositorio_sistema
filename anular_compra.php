<?php
session_start();
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
require_once 'conexion.php';
// Validar que se reciba el parametro ID por GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
header("Location: historial_compras.php");
exit();
}

$compra_id = intval($_GET['id']);
// INICIO DEL PROTOCOLO TRANSACCIONAL ACID
$conn->begin_transaction();
try {
// 1. Validar existencia y estado actual de la compra
$sql_check = "SELECT id, estado FROM compras WHERE id = ? FOR UPDATE";
$stmt_check = $conn->prepare($sql_check);
if (!$stmt_check) {
throw new Exception("Fallo en preparacion de consulta: " . $conn->error);
}
$stmt_check->bind_param("i", $compra_id);
$stmt_check->execute();
$res_check = $stmt_check->get_result();
if ($res_check->num_rows === 0) {
throw new Exception("La compra solicitada no existe en el sistema.");
}
$datos_compra = $res_check->fetch_assoc();
$stmt_check->close();
// Regla de Negocio: No permitir anular una compra ya anulada
if ($datos_compra['estado'] === 'ANULADA') {
throw new Exception("Esta compra ya se encuentra anulada. No es posible
revertirla nuevamente.");
}
// 2. Obtener los articulos y cantidades que deben deducirse del inventario
$sql_items = "SELECT producto_id, cantidad FROM detalle_compras WHERE compra_id
= ?";
$stmt_items = $conn->prepare($sql_items);
if (!$stmt_items) {
throw new Exception("Fallo al preparar consulta de detalle: " . $conn->error);
}
$stmt_items->bind_param("i", $compra_id);
$stmt_items->execute();
$res_items = $stmt_items->get_result();
// 3. Preparar la sentencia de deduccion matematica de existencias
$sql_revertir = "UPDATE productos SET stock = stock - ? WHERE id = ?";
$stmt_revertir = $conn->prepare($sql_revertir);
if (!$stmt_revertir) {
throw new Exception("Fallo al preparar reversor de stock: " . $conn->error);
}
while ($fila = $res_items->fetch_assoc()) {
$cantidad = intval($fila['cantidad']);
$producto_id = intval($fila['producto_id']);
$stmt_revertir->bind_param("ii", $cantidad, $producto_id);
if (!$stmt_revertir->execute()) {
throw new Exception("Fallo al revertir stock del producto ID " .
$producto_id);
}
}
$stmt_items->close();

$stmt_revertir->close();
// 4. Cambiar el estado de la compra a ANULADA
$sql_anular = "UPDATE compras SET estado = 'ANULADA' WHERE id = ?";
$stmt_anular = $conn->prepare($sql_anular);
if (!$stmt_anular) {
throw new Exception("Fallo al preparar anulacion de factura: " . $conn->error);
}
$stmt_anular->bind_param("i", $compra_id);
if (!$stmt_anular->execute()) {
throw new Exception("Fallo al actualizar el estado de la compra.");
}
$stmt_anular->close();
// 5. SI TODAS LAS SENTENCIAS SE EJECUTARON CON EXITO, CONSOLIDAR EN DISCO
$conn->commit();
header("Location: detalle_compra.php?id=" . $compra_id . "&msg=anulada_ok");
exit();
} catch (Exception $e) {
// SI OCURRE CUALQUIER ANOMALIA, DESHACER ABSOLUTAMENTE TODOS LOS CAMBIOS
$conn->rollback();
header("Location: detalle_compra.php?id=" . $compra_id . "&error=" . urlencode($e-
>getMessage()));
exit();
}
?>