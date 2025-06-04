<?php
// eliminar_usuario.php

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Realiza la conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "guardiashop");

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

   // Realiza la consulta para eliminar el usuario
$sql = "DELETE FROM usuario WHERE id = $userId";
if ($conn->query($sql) === TRUE) {
    // Redirige directamente a la página deseada
    header('Location: /guardiashop/admin_gs/panel/g_usuarios.php?success=1');

    exit; // Asegura que no se ejecute más código después de la redirección
} else {
    echo "Error al eliminar el usuario: " . $conn->error;
}

    // Cierra la conexión
    $conn->close();
} else {
    echo "ID de usuario no proporcionado";
}
?>
