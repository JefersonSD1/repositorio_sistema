<?php
session_start();
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
require_once 'conexion.php';

// Validar que exista el parámetro id en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
header("Location: historial_compras.php");
exit();
}

$compra_id = intval($_GET['id']);

// 1. Consultar la cabecera de la compra (Maestro)
$sql_cabecera = "SELECT c.id, c.fecha, c.total, p.nombre_empresa, u.nombre_completo
FROM compras c
INNER JOIN proveedores p ON c.proveedor_id = p.id
INNER JOIN usuarios u ON c.usuario_id = u.id
WHERE c.id = ?";

$stmt_cab = $conn->prepare($sql_cabecera);
$stmt_cab->bind_param("i", $compra_id);
$stmt_cab->execute();
$res_cabecera = $stmt_cab->get_result();

if ($res_cabecera->num_rows === 0) {
header("Location: historial_compras.php");
exit();
}

$compra = $res_cabecera->fetch_assoc();
$stmt_cab->close();
// 2. Consultar el desglose de productos asociados (Detalle)
$sql_detalle = "SELECT p.nombre_producto, d.cantidad, d.precio_compra, (d.cantidad *
d.precio_compra) AS subtotal
FROM detalle_compras d
INNER JOIN productos p ON d.producto_id = p.id
WHERE d.compra_id = ?";

$stmt_det = $conn->prepare($sql_detalle);
$stmt_det->bind_param("i", $compra_id);
$stmt_det->execute();
$res_detalle = $stmt_det->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle de Compra N° <?php echo $compra['id']; ?></title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:
#f8fafc; padding: 20px; }
.contenedor { max-width: 900px; margin: 0 auto; background: white; padding: 30px;
border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
.btn-volver { display: inline-block; background: #64748b; color: white; padding: 8px 14px;
text-decoration: none; border-radius: 4px; font-weight: bold; margin-bottom: 20px; font-size:
14px; }
.btn-volver:hover { background: #475569; }
.datos-compra { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; background:
#f1f5f9; padding: 20px; border-radius: 6px; margin-bottom: 25px; border-left: 4px solid
#2563eb; }
.datos-compra p { margin: 6px 0; color: #334155; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
th { background-color: #1e293b; color: white; }
tr:hover { background-color: #f8fafc; }
.total-final { text-align: right; font-size: 20px; font-weight: bold; margin-top: 25px; color:
#059669; border-top: 2px solid #e2e8f0; padding-top: 15px; }
</style>
</head>
<body>

<div class="contenedor">
<a href="historial_compras.php" class="btn-volver">← Volver al Historial</a>

<h2 style="margin-top: 0; color: #0f172a;">Comprobante de Ingreso de Mercadería N° <?php
echo $compra['id']; ?></h2>

<div class="datos-compra">
<div>
<p><strong>Proveedor:</strong> <?php echo $compra['nombre_empresa']; ?></p>
<p><strong>Fecha de Registro:</strong> <?php echo $compra['fecha']; ?></p>
</div>
<div>
<p><strong>Registrado por:</strong> <?php echo $compra['nombre_completo']; ?></p>
<p><strong>Estado:</strong> Confirmado / Almacenado</p>
</div>
</div>

<h3 style="color: #334155;">Artículos Ingresados</h3>
<table>
<thead>
<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Costo Unitario</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>
<?php
if ($res_detalle->num_rows > 0) {
while($item = $res_detalle->fetch_assoc()) {
echo "<tr>";
echo "<td>" . $item['nombre_producto'] . "</td>";
echo "<td>" . $item['cantidad'] . " unds.</td>";
echo "<td>$" . number_format($item['precio_compra'], 2) . "</td>";
echo "<td><strong>$" . number_format($item['subtotal'], 2) . "</strong></td>";
echo "</tr>";
}
} else {
echo "<tr><td colspan='4' style='text-align: center;'>No se encontraron partidas para
esta compra.</td></tr>";
}
$stmt_det->close();
?>
</tbody>
</table>

<div class="total-final">
Total Liquidado: $<?php echo number_format($compra['total'], 2); ?>
</div>
</div>
</body>
</html>