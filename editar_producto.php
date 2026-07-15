<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
require_once 'conexion.php';

// Validar que se reciba un ID por GET
if (!isset($_GET['id'])) { header("Location: inventario.php"); exit(); }

$id_producto = $_GET['id'];

// 1. Consultar los datos actuales del producto
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

// Si el producto no existe, volver al inventario
if ($resultado->num_rows === 0) { header("Location: inventario.php"); exit(); }

$producto = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Producto</title>
<style>
body { font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; }

.container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-
radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

.form-group { margin-bottom: 15px; }
label { display: block; font-weight: bold; margin-bottom: 5px; }
input, select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 5px;
box-sizing: border-box; }
button { width: 100%; padding: 10px; background-color: #f59e0b; color: white; border:
none; border-radius: 5px; font-weight: bold; cursor: pointer; }
button:hover { background-color: #d97706; }
</style>
</head>
<body>
<div class="container">
<h2>Editar Producto #<?php echo $producto['id']; ?></h2>

<!-- El formulario envía los datos actualizados mediante POST -->
<form action="actualizar_producto.php" method="POST">

<!-- CAMPO OCULTO (HIDDEN): Vital para enviar el ID sin que el usuario lo modifique -->
<input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

<div class="form-group">
<label>Nombre del Producto:</label>
<!-- Llenamos el value con el dato extraído de la BD -->
<input type="text" name="nombre" value="<?php echo
$producto['nombre_producto']; ?>" required>
</div>

<div class="form-group">
<label>Categoría:</label>
<select name="categoria" required>
<?php
// Cargar categorías y marcar la correcta como "selected"
$sql_cat = "SELECT id, nombre_categoria FROM categorias";
$res_cat = $conn->query($sql_cat);
while($cat = $res_cat->fetch_assoc()) {
$seleccionado = ($cat['id'] == $producto['categoria_id']) ? "selected" : "";
echo "<option value='" . $cat['id'] . "' $seleccionado>" . $cat['nombre_categoria'] .
"</option>";
}
?>
</select>
</div>
<div class="form-group">
<label>Stock:</label>
<input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required>
</div>

<div class="form-group">
<label>Precio Unitario ($):</label>
<input type="number" name="precio" step="0.01" value="<?php echo
$producto['precio']; ?>" required>
</div>

<button type="submit">Guardar Cambios</button>
<a href="inventario.php" style="display:block; text-align:center; margin-top:15px;
color:#64748b; text-decoration:none;">Cancelar</a>
</form>
</div>

</body>
</html>