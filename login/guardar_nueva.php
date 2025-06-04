<?php
include 'db.php';
$token = $_POST['token'];
$nueva = password_hash($_POST['nueva_contraseña'], PASSWORD_DEFAULT);

$result = $conn->query("SELECT * FROM usuario WHERE token='$token' AND token_expira > NOW()");
if ($result->num_rows === 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire('Error', 'Token inválido o expirado.', 'error').then(()=>{ window.location.href='login.php'; });
    </script>";
    exit;
}

$conn->query("UPDATE usuario SET contraseña='$nueva', token=NULL, token_expira=NULL WHERE token='$token'");
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
Swal.fire('¡Listo!', 'Contraseña actualizada. Ya puedes iniciar sesión.', 'success').then(()=>{ window.location.href='login.php'; });
</script>";
?>
