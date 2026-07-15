<?php
session_start();
// 1. Candado de seguridad: Solo usuarios logueados
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}

// 2. Conexión a la base de datos
require_once 'conexion.php';

// --- MÉTRICA 1: Total de Productos en Catálogo ---
$res_total = $conn->query("SELECT COUNT(id) AS cantidad FROM productos");
$fila_total = $res_total->fetch_assoc();
$total_productos = $fila_total['cantidad'];

// --- MÉTRICA 2: Valor Total del Inventario (Capital Invertido) ---
$res_valor = $conn->query("SELECT SUM(precio * stock) AS capital FROM productos");
$fila_valor = $res_valor->fetch_assoc();
// Prevenimos el error por si la base está vacía (NULL a 0)
$capital_inventario = $fila_valor['capital'] ? $fila_valor['capital'] : 0;

// --- MÉTRICA 3: Producto más caro ---
$res_caro = $conn->query("SELECT MAX(precio) AS max_precio FROM productos");
$fila_caro = $res_caro->fetch_assoc();
$precio_maximo = $fila_caro['max_precio'] ? $fila_caro['max_precio'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Control - Sistema de Ventas</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:
#f1f5f9; margin: 0; padding: 20px; }
.navbar { background: #1e293b; color: white; padding: 15px 25px; border-radius: 8px;

display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; box-
shadow: 0 4px 6px rgba(0,0,0,0.1); }

.navbar h1 { margin: 0; font-size: 22px; }

.btn-salir { background: #ef4444; color: white; padding: 8px 15px; border-radius: 5px; text-
decoration: none; font-weight: bold; }

/* Contenedor Flexbox para las Tarjetas */

.tarjetas-container { display: flex; gap: 20px; justify-content: space-between; margin-
bottom: 30px; }
/* Diseño de cada Métrica */
.tarjeta { background: white; flex: 1; padding: 25px; border-radius: 8px; box-shadow: 0 4px
6px rgba(0,0,0,0.05); text-align: center; border-top: 5px solid #3b82f6; }
.tarjeta.verde { border-top-color: #10b981; }
.tarjeta.naranja { border-top-color: #f59e0b; }

.tarjeta h3 { color: #64748b; margin: 0 0 10px 0; font-size: 16px; text-transform:
uppercase; }
.tarjeta .numero { font-size: 32px; font-weight: bold; color: #0f172a; margin: 0; }

/* Menú de accesos rápidos */
.menu-modulos { display: flex; gap: 20px; }
.modulo { background: #3b82f6; color: white; flex: 1; padding: 20px; text-align: center;
border-radius: 8px; text-decoration: none; font-size: 18px; font-weight: bold; transition:
background 0.3s; }
.modulo:hover { background: #2563eb; }
</style>
</head>
<body>

<!-- Barra Superior -->
<div class="navbar">
<h1>Bienvenido, <?php echo $_SESSION['nombre']; ?>
<span style="font-size: 14px; color: #94a3b8;">(Rol: <?php echo
$_SESSION['rol']; ?>)</span>
</h1>
<a href="logout.php" class="btn-salir">Cerrar Sesión</a>
</div>

<!-- Panel de Métricas Dinámicas -->
 <div class="tarjetas-container">

<div class="tarjeta">
<h3>Total de Productos</h3>
<!-- Inyección del conteo de PHP -->
<p class="numero"><?php echo $total_productos; ?> unds</p>
</div>

<div class="tarjeta verde">
<h3>Capital Invertido</h3>
<!-- Inyección de la suma, formateada con comas y decimales -->
<p class="numero">$<?php echo number_format($capital_inventario, 2); ?></p>
</div>

<div class="tarjeta naranja">
<h3>Producto de Mayor Precio</h3>
<!-- Inyección del precio máximo -->
<p class="numero">$<?php echo number_format($precio_maximo, 2); ?></p>
</div>

</div>

<!-- Accesos Rápidos del Sistema -->
<h2 style="color: #334155;">Módulos del Sistema</h2>
<div class="menu-modulos">
<a href="inventario.php" class="modulo">📦Ir al Catálogo de Inventario</a>
<!-- Este enlace lo programaremos en el siguiente bloque del año -->
<a href="#" class="modulo" style="background:#64748b;">🛒Punto de Venta
(Próximamente)</a>
</div>
</body>
</html>