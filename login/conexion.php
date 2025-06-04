<?php
$servername = "localhost";
$username = "root";  // Ajusta según tus credenciales
$password = "";      // Ajusta según tus credenciales
$dbname = "guardiashop";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
