<?php
// Claves de Wompi para entorno de pruebas (sandbox)
define('WOMPI_PUBLIC_KEY', 'pub_test_gEx2F8byS00eSPNgtBQafYvuHhT0jdYE');
define('WOMPI_PRIVATE_KEY', 'prv_test_OCx32iWiFVScjJXuuDQPYPUhnKpBNHtv'); 
define('WOMPI_INTEGRITY_SECRET', 'test_integrity_exRvAZi8HzkWOXh85phhJiIC0zHxtMaE'); // SECRETO DE INTEGRIDAD// solo si consultas transacciones
define('WOMPI_REDIRECT_URL', 'http://localhost/guardiashop/envio/compra_exitosa.php');
define('WOMPI_CURRENCY', 'COP');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'guardiashop');

// Conexión a MySQL
$host = 'localhost';
$user = 'root';
$pass = ''; // o tu contraseña
$db = 'guardiashop'; // nombre de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

?>
