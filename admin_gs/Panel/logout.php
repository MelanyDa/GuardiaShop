<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrando sesión...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Sesión cerrada!',
    text: 'Has cerrado sesión correctamente.',
    confirmButtonColor: '#2c4926',
    timer: 1000,
    showConfirmButton: false
});
setTimeout(function() {
    window.location.href = '/guardiashop/login/login.php';
}, 1000);
</script>
</body>
</html> 