<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");  // Redirige al login si no está autenticado
    exit();
}

echo "Bienvenido al panel de usuario.";
?>
