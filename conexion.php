<?php
// Configuración de las credenciales de la base de datos
$host = "localhost";
$db_name = "sistema_inventario";
$username = "root";
$password = ""; // Vacío por defecto en XAMPP

// Habilitar el reporte de errores de mysqli para usar excepciones
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // 1. Instanciar el objeto mysqli
    $conn = new mysqli($host, $username, $password, $db_name);

    // 2. Configurar UTF-8 para admitir tildes y eñes
    $conn->set_charset("utf8");

    // echo "Conexión exitosa y segura al sistema de inventario.";

} catch (mysqli_sql_exception $e) {
    die("Error crítico: No se pudo establecer la conexión segura con el servidor de datos.");
}
?>