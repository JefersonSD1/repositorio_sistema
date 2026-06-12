<?php
// 1. Iniciar sesión y aplicar el candado de seguridad (Guía 14)
session_start();
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}

// 2. Incluir el puente de conexión a la base de datos
require_once 'conexion.php';

// 3. Preparar la consulta SQL relacional (Guía 11)
// Usamos INNER JOIN para mostrar el nombre de la categoría, no su ID numérico
$sql = "SELECT p.id, p.nombre_producto, c.nombre_categoria, p.stock, p.precio
FROM productos p
INNER JOIN categorias c ON p.categoria_id = c.id
ORDER BY p.id ASC";

// 4. Ejecutar la consulta con MySQLi Orientado a Objetos
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventario - Sistema de Ventas</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:
#f8fafc; padding: 20px; }
.container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px;
border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
.header { display: flex; justify-content: space-between; align-items: center; border-bottom:
2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px; }
h2 { color: #0f172a; margin: 0; }
.btn-salir { background-color: #ef4444; color: white; text-decoration: none; padding: 8px
15px; border-radius: 5px; font-weight: bold; }
.btn-salir:hover { background-color: #dc2626; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
th { background-color: #f1f5f9; color: #334155; font-weight: bold; }
tr:hover { background-color: #f8fafc; }
.stock-bajo { color: #dc2626; font-weight: bold; }
</style>
</head>
<body>

<div class="container">
<div class="header">
    <h2>Catálogo de Inventario</h2>
<div>
<span>Usuario: <strong><?php echo $_SESSION['nombre']; ?></strong></span>
<a href="logout.php" class="btn-salir">Cerrar Sesión</a>
</div>
</div>

<table>
<thead>
<tr>
<th>Código</th>
<th>Nombre del Producto</th>
<th>Categoría</th>
<th>Stock</th>
<th>Precio Unitario</th>
</tr>
</thead>
<tbody>
<?php
// 5. Ciclo WHILE para imprimir las filas dinámicamente
// Si hay resultados en la base de datos, iteramos fila por fila
if ($resultado->num_rows > 0) {
while($fila = $resultado->fetch_assoc()) {

// Lógica de negocio: Si el stock es menor a 10, le ponemos una clase roja
$claseStock = ($fila['stock'] < 10) ? 'stock-bajo' : '';
?>
<!-- HTML que se repite por cada producto -->
<tr>
<td> <?php echo $fila['id']; ?> </td>
<td> <?php echo $fila['nombre_producto']; ?> </td>
<td> <?php echo $fila['nombre_categoria']; ?> </td>
<td class="<?php echo $claseStock; ?>"> <?php echo $fila['stock']; ?> unds. </td>
<td> $<?php echo number_format($fila['precio'], 2); ?> </td>
</tr>
<?php
} // Fin del bucle while
} else {
?>
<!-- Si la tabla está vacía, mostramos este mensaje -->
<tr>
<td colspan="5" style="text-align:center;">No hay productos registrados en el
sistema.</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

<?php
// 6. Liberar la memoria del resultado (Buena práctica profesional)
$resultado->free();
?>

</body>
</html> 