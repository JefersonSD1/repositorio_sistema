<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
require_once 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Compra</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:
#f8fafc; padding: 20px; }

.container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-
radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: bold; color: #334155; }
select, input { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 5px;
box-sizing: border-box; }
button { width: 100%; padding: 12px; background-color: #3b82f6; color: white; border:
none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top:
10px; }
button:hover { background-color: #2563eb; }
.btn-volver { display: inline-block; margin-bottom: 20px; color: #64748b; text-decoration:
none; font-weight: bold; }
</style>
</head>
<body>

<div class="container">
<a href="dashboard.php" class="btn-volver">← Volver al Dashboard</a>
<h2 style="color: #0f172a; margin-top: 0;">Ingresar Nueva Mercadería</h2>

<form action="guardar_compra.php" method="POST">

<div class="form-group">
<label>Proveedor:</label>
<select name="proveedor_id" required>
<option value="">-- Seleccione un proveedor --</option>
<?php
$res_prov = $conn->query("SELECT id, nombre_empresa FROM proveedores ORDER
BY nombre_empresa ASC");
while($prov = $res_prov->fetch_assoc()) {
echo "<option value='" . $prov['id'] . "'>" . $prov['nombre_empresa'] . "</option>";
}
?>
</select>
</div>

<div class="form-group">
    <label>Producto a Ingresar:</label>
<select name="producto_id" required>
<option value="">-- Seleccione el producto --</option>
<?php
$res_prod = $conn->query("SELECT id, nombre_producto FROM productos ORDER BY
nombre_producto ASC");
while($prod = $res_prod->fetch_assoc()) {
echo "<option value='" . $prod['id'] . "'>" . $prod['nombre_producto'] . "</option>";
}
?>
</select>
</div>

<div class="form-group">
<label>Cantidad de Unidades:</label>
<input type="number" name="cantidad" min="1" required>
</div>

<div class="form-group">
<label>Precio de Compra Unitario ($):</label>
<input type="number" name="precio_compra" step="0.01" min="0.01" required>
</div>

<button type="submit">Procesar y Guardar Compra</button>
</form>
</div>

</body>
</html>