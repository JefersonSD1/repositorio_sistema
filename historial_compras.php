<?php
session_start();
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
require_once 'conexion.php';
$sql = "SELECT c.id AS numero_factura, c.fecha, p.nombre_empresa AS proveedor,
u.nombre_completo AS cajero, c.total
FROM compras c
INNER JOIN proveedores p ON c.proveedor_id = p.id
INNER JOIN usuarios u ON c.usuario_id = u.id
ORDER BY c.id DESC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial de Compras</title>
<style>
body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 20px; }
.contenedor { background: #ffffff; padding: 25px; border-radius: 6px; box-shadow: 0
2px 4px rgba(0,0,0,0.1); }
.encabezado { display: flex; justify-content: space-between; align-items: center;
margin-bottom: 20px; }

.btn-regresar { background: #64748b; color: #ffffff; padding: 8px 12px; text-
decoration: none; border-radius: 4px; }

table { width: 100%; border-collapse: collapse; }
th, td { padding: 10px; border-bottom: 1px solid #cbd5e1; text-align: left; }
th { background-color: #1e293b; color: #ffffff; }
.monto { color: #059669; font-weight: bold; }
</style>
</head>
<body>
<div class="contenedor">
<div class="encabezado">
<h2>Historial de Ingresos de Mercaderia</h2>
<a href="dashboard.php" class="btn-regresar">Regresar al Panel</a>
</div>
<table>
<thead>
<tr>
<th>Numero</th>
<th>Fecha y Hora</th>
<th>Proveedor</th>
<th>Usuario Responsable</th>
<th>Total Invertido</th>
</tr>
</thead>
<tbody>
<?php
if ($resultado->num_rows > 0) {
while($fila = $resultado->fetch_assoc()) {
echo "<tr>";
echo "<td>" . $fila['numero_factura'] . "</td>";
echo "<td>" . $fila['fecha'] . "</td>";
echo "<td>" . $fila['proveedor'] . "</td>";
echo "<td>" . $fila['cajero'] . "</td>";
echo "<td class='monto'>$" . number_format($fila['total'], 2) . "</td>";
echo "</tr>";
}
} else {
echo "<tr><td colspan='5'>Sin registros de compras disponibles.</td></tr>";
}
?>
<td>
<a href="detalle_compra.php?id=<?php echo $fila['numero_factura']; ?>" style="background:
#2563eb; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size:
13px; font-weight: bold;">Ver Detalle</a>
</td>
</tbody>
</table>
</div>
</body>
</html>