<?php
session_start();
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
require_once 'conexion.php';

// Consultar todos los proveedores
$sql = "SELECT * FROM proveedores ORDER BY id ASC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Proveedores - Sistema de Ventas</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:
#f8fafc; padding: 20px; }

.container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-
radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

.header { display: flex; justify-content: space-between; align-items: center; border-bottom:
2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px; }
.btn-volver { background-color: #64748b; color: white; padding: 8px 15px; text-decoration:
none; border-radius: 4px; font-weight: bold; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
th { background-color: #f1f5f9; color: #334155; }
tr:hover { background-color: #f8fafc; }
</style>
</head>
<body>
<div class="container">
<div class="header">
<h2 style="margin:0;">Catálogo de Proveedores</h2>
<div>
<a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
</div>
</div>

<!-- Botón preparado para la próxima clase -->
<a href="#" style="background: #3b82f6; color: white; padding: 10px; text-decoration: none;
border-radius: 5px; font-weight: bold;">+ Nuevo Proveedor</a>

<table>
<thead>
<tr>
<th>ID</th>
<th>Empresa</th>
<th>Contacto</th>
<th>Teléfono</th>
<th>Dirección</th>
</tr>
</thead>
<tbody>
<?php
if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
?>
<tr>
<td> <?php echo $fila['id']; ?> </td>
<td> <?php echo $fila['nombre_empresa']; ?> </td>
<td> <?php echo $fila['contacto']; ?> </td>
<td> <?php echo $fila['telefono']; ?> </td>
<td> <?php echo $fila['direccion']; ?> </td>
</tr>
<?php
}
} else {
echo "<tr><td colspan='5' style='text-align:center;'>No hay proveedores
registrados.</td></tr>";
}
?>
</tbody>
</table>
</div>

</body>
</html>
