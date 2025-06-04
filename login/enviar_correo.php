<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $result = $conn->query("SELECT * FROM usuario WHERE correo='$correo'");

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $conn->query("UPDATE usuario SET token='$token', token_expira='$expira' WHERE correo='$correo'");

        // Configurar el correo
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gguardiashop@gmail.com'; // tu correo real
            $mail->Password = 'dkgucxevakslripl'; // contraseña de aplicación
            $mail->SMTPSecure = 'ssl'; // o tls
            $mail->Port = 465; // o 587


            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Contenido del correo
            $mail->setFrom('gguardiashop@gmail.com', 'GuardiaShop');
            $mail->addAddress($correo);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperar tu contrasena';
            $link = "http://localhost/guardiashop/login/reset.php?token=$token";
            $mail->Body = "Haz clic <a href='$link'>aquí</a> para cambiar tu contraseña. Este enlace expira en 1 hora.";

            $mail->send();
            echo "<!DOCTYPE html>
<html lang='es'>
<head>
  <meta charset='UTF-8'>
  <title>Recuperar contraseña</title>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Correo enviado!',
    text: 'Se ha enviado un enlace de recuperación a tu correo.',
    confirmButtonColor: '#b78732'
}).then(()=>{ window.location.href='login.php'; });
</script>
<noscript>
  <div style='text-align:center;margin-top:2em;color:#b78732;font-family:sans-serif;'>
    El enlace de recuperación fue enviado. Por favor revisa tu correo.<br>
    <a href='login.php'>Volver al inicio de sesión</a>
  </div>
</noscript>
</body>
</html>";
        } catch (Exception $e) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                text: '".addslashes($e->getMessage())."',
                confirmButtonColor: '#b78732'
            }).then(()=>{ window.location.href='recuperar.php'; });
            </script>";
        }
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Correo no registrado',
            text: 'Este correo no está registrado.',
            confirmButtonColor: '#b78732'
        }).then(()=>{ window.location.href='recuperar.php'; });
        </script>";
    }
}
?>
