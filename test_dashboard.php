<?php
// Iniciar sesión para leer las variables globales
session_start();

// Validar si el usuario NO ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
// Si no está registrado, redirigir inmediatamente al login
header("Location: index.php");
exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Control - Acceso Autorizado</title>
<style>
body { font-family: Arial, sans-serif; padding: 50px; background-color: #f0fdf4; }
.welcome-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px
10px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto; text-align: center; }
h1 { color: #16a34a; }
.badge { background-color: #1e3a8a; color: white; padding: 5px 10px; border-radius: 4px;
font-size: 14px; }

a { color: #dc2626; text-decoration: none; font-weight: bold; display: inline-block; margin-
top: 20px; }

</style>
</head>
<body>
<div class="welcome-box">
<h1>¡Acceso Autorizado con Éxito!</h1>
<p>Bienvenido al Sistema de Inventario y Ventas, <strong><?php echo
$_SESSION['nombre']; ?></strong>.</p>
<p>Tu rol asignado en el sistema es: <span class="badge"><?php echo
$_SESSION['rol']; ?></span></p>

<!-- Enlace temporal para cerrar la sesión -->
<a href="logout.php">Cerrar Sesión Segura</a>
</div>
</body>
</html>