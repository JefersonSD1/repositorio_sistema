<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso al Sistema - Instituto Nacional de Ciudad Barrios</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:
#f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
.login-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px
15px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
h2 { text-align: center; color: #1e3a8a; margin-bottom: 20px; }
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: bold; color: #4b5563; }

input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px; box-
sizing: border-box; }

button { width: 100%; padding: 10px; background-color: #1e3a8a; color: white; border:
none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px; }
button:hover { background-color: #1d4ed8; }
.error { color: #dc2626; text-align: center; font-size: 14px; margin-bottom: 10px; }
</style>
</head>
<body>
<div class="login-card">
<h2>Control de Inventario</h2>

<?php if (isset($_GET['error'])): ?>
<div class="error">Usuario o contraseña incorrectos.</div>
<?php endif; ?>

<form action="procesar_login.php" method="POST">
<div class="form-group">
<label for="usuario">Nombre de Usuario:</label>
<input type="text" name="usuario" id="usuario" required autocomplete="off">
</div>
<div class="form-group">
<label for="password">Contraseña:</label>
<input type="password" name="password" id="password" required>
</div>
<button type="submit">Iniciar Sesión</button>
</form>
</div>
</body>
</html>