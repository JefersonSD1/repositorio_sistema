<?php
session_start();
// Validar que solo usuarios logueados puedan ver esta página
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
require_once 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Nuevo Producto</title>
<style>
body { font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; }

.container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-
radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: bold; color: #334155; }
input, select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 5px;
box-sizing: border-box; }
button { width: 100%; padding: 10px; background-color: #10b981; color: white; border:
none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-top: 10px; font-weight: bold;}
button:hover { background-color: #059669; }
.btn-volver { display: inline-block; margin-bottom: 20px; color: #3b82f6; text-decoration:
none; font-weight: bold; }
</style>
</head>
<body>

<div class="container">
<a href="inventario.php" class="btn-volver">← Volver al Inventario</a>
<h2 style="color: #0f172a; margin-top: 0;">Registrar Nuevo Producto</h2>

<!-- El formulario enviará los datos al archivo guardar_producto.php -->
<form action="guardar_producto.php" method="POST">

<div class="form-group">
<label for="nombre">Nombre del Producto:</label>
<input type="text" id="nombre" name="nombre" required autocomplete="off">
</div>

<div class="form-group">
<label for="categoria">Categoría:</label>
<select id="categoria" name="categoria" required>
<option value="">-- Seleccione una categoría --</option>
<?php
// Consultar las categorías dinámicamente desde MySQLi
$sql_cat = "SELECT id, nombre_categoria FROM categorias ORDER BY
nombre_categoria ASC";
$res_cat = $conn->query($sql_cat);
// Dibujar las opciones
while($cat = $res_cat->fetch_assoc()) {
// El valor oculto (value) es el ID numérico, pero el usuario ve el texto
echo "<option value='" . $cat['id'] . "'>" . $cat['nombre_categoria'] . "</option>";
}
?>
</select>
</div>

<div class="form-group">
<label for="stock">Cantidad Inicial (Stock):</label>
<input type="number" id="stock" name="stock" min="0" required>
</div>

<div class="form-group">
<label for="precio">Precio Unitario ($):</label>
<input type="number" id="precio" name="precio" step="0.01" min="0.01" required>
</div>

<button type="submit">Guardar Producto</button>
</form>
</div>

</body>
</html>