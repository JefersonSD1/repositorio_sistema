<?php
session_start();
session_unset(); // Borrar variables de sesión
session_destroy(); // Destruir la sesión físicamente en el servidor
header("Location: index.php");
exit();
?>