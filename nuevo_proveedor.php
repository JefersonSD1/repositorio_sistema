<?php
session_start();
// Candado de seguridad
if (!isset($_SESSION['user_id'])) {
header("Location: index.php");
exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Proveedor - Sistema de Ventas</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:
#f8fafc; padding: 20px; }

.container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-
radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: bold; color: #334155; }
input, textarea { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius:
5px; box-sizing: border-box; font-family: inherit; }
button { width: 100%; padding: 10px; background-color: #3b82f6; color: white; border:
none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-top: 10px; font-weight: bold;}
button:hover { background-color: #2563eb; }
.btn-volver { display: inline-block; margin-bottom: 20px; color: #64748b; text-decoration:
none; font-weight: bold; }
</style>
</head>
<body>

<div class="container">
<a href="proveedores.php" class="btn-volver">← Volver al Catálogo</a>
<h2 style="color: #0f172a; margin-top: 0;">Registrar Proveedor</h2>

<!-- El formulario envía los datos por el método POST seguro -->
<form action="guardar_proveedor.php" method="POST">

<div class="form-group">
<label for="empresa">Nombre de la Empresa (*):</label>
<input type="text" id="empresa" name="empresa" required autocomplete="off"
placeholder="Ej. Tech Data S.A.">
</div>

<div class="form-group">
<label for="contacto">Nombre del Contacto:</label>
<input type="text" id="contacto" name="contacto" autocomplete="off" placeholder="Ej.
Juan Pérez (Ventas)">
</div>

<div class="form-group">
<label for="telefono">Teléfono:</label>
<input type="text" id="telefono" name="telefono" autocomplete="off" placeholder="Ej.
2222-3333">
</div>

<div class="form-group">
<label for="direccion">Dirección Física:</label>
<textarea id="direccion" name="direccion" rows="3" placeholder="Ubicación completa
de la bodega o empresa..."></textarea>
</div>

<button type="submit">Guardar Proveedor</button>
</form>
</div>

</body>
</html>