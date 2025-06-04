<?php
session_start();
include './login/conexion.php';

$mensaje_enviado = false;
$error_envio = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $mensaje = trim($_POST['mensaje']);
    $id_usuario = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

    if ($nombre && $correo && $mensaje) {
        // Puedes guardar el nombre en el mensaje si el usuario no está logueado
        $mensaje_final = $nombre . ":\n" . $mensaje;

        $sql = "INSERT INTO contactanos (id_usuario, mensaje, correo, estado) VALUES (?, ?, ?, 'Nuevo')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $id_usuario, $mensaje_final, $correo);

        if ($stmt->execute()) {
            $mensaje_enviado = true;
        } else {
            $error_envio = "Error al enviar el mensaje. Intenta de nuevo.";
        }
        $stmt->close();
    } else {
        $error_envio = "Todos los campos son obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title  -->
    <title>GUARDIASHOP</title>

    <!-- Favicon  -->
    <link rel="icon" href="./img/core-img/logoguardiashop.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="css/core-styleff.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/carrito.css">
    <link rel="stylesheet" href="assets/css/contacto.css">

    <style>
body {
    font-size: 0.95em;
}
.contact-title,
.contact-form h3,
.page-title h2 {
    font-size: 1em !important;
    margin-bottom: 0.5em;
}
.contact-description,
.contact-info p,
.contact-form input,
.contact-form textarea,
.contact-form button {
    font-size: 1em;
}
.page-title .subtitle {
    font-size: 0.35em !important;
}</style>
</head>

<body>
    <!-- ##### Header Area Start ##### -->
    <?php include './arc/nav.php'; ?>
    
    <div class="breadcumb_area breadcumb-style-two bg-img" style="background-image: url(img/bg-img/breadcumb2.jpg);">
        <div class="container-fluid h-100 m-0 p-0">
            <div class="row h-100 w-100 align-items-center">
                <div class="col-12">
                    <div class="page-title text-center">
                    <h2 style="color:#444242;">Contáctanos</h2>
                        <p class="subtitle">¿Tienes preguntas o comentarios? Estamos aquí para ayudarte. <br> Escríbenos o visítanos en nuestra sede.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-container">
        <h2 class="contact-title">¿Hablamos?</h2>
        <p class="contact-description">¿Tienes dudas, ideas o simplemente quieres saludarnos? <br>Estamos listos para escucharte.<br> Escríbenos o ven a conocernos en nuestra sede. ¡Será un gusto atenderte!
</p>

        <div class="contact-content">
            <div class="google-map">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.1555954198884!2d-76.66338592871985!3d5.690644317592581!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e488f718a8b6395%3A0x48da7c08bd8fa2ba!2zQ3JhLiA2ICMgMjYtMjcsIFF1aWJkw7MsIENob2PDsw!5e0!3m2!1ses!2sco!4v1745289179461!5m2!1ses!2sco" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <div class="contact-info">
                <p><i class="fas fa-map-marker-alt"></i> <strong>Dirección:</strong> CRA 6 #26-27, Quibdó, Chocó</p>
                <p><i class="fas fa-phone-alt"></i> <strong>Teléfono:</strong> <a href="tel:+5731043223454">+57 310 432 23454</a></p>
                <p><i class="fas fa-envelope"></i> <strong>Correo:</strong> <a href="mailto:Guardiashop@gmail.com">Guardiashop@gmail.com</a></p>
            </div>
        </div>
   <?php if ($mensaje_enviado): ?>
    <div class="alert alert-success" style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; padding:12px 18px; border-radius:6px; margin:18px 0; font-weight:500; text-align:center;">
        ¡Tu mensaje fue enviado correctamente!<br>
        Pronto nos contactaremos contigo.
    </div>
<?php elseif ($error_envio): ?>
    <div class="alert alert-danger" style="background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; padding:12px 18px; border-radius:6px; margin:18px 0; font-weight:500; text-align:center;">
        <?= htmlspecialchars($error_envio) ?>
    </div>
<?php endif; ?>
    </div>
        <!-- Formulario de contacto -->
        <div class="contact-form">
            <h3>ESCRIBENOS UN MENSAJE</h3>
            <form action="#" method="POST">
                <input type="text" name="nombre" placeholder="Tu nombre completo" required>
                <input type="email" name="correo" placeholder="Tu correo electrónico" required>
                <textarea name="mensaje" rows="6" placeholder="Escribe tu mensaje aquí..." required></textarea>
                <button type="submit">Enviar mensaje</button>
            </form>
        </div>

     


</div>

    <script>
            function toggleUserMenu() {
                const dropdown = document.getElementById('user-dropdown');
                dropdown.classList.toggle('hidden');
                }

                document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('user-dropdown');
                const toggle = document.querySelector('.menu-toggle');
                const menu = document.querySelector('.user-menu');

                if (!menu.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
                });
        </script>
    <script src="assets/js/carrito.js"></script>
    <!-- ##### Footer Area Start ##### -->
    <?php include './arc/footer.php';?>
    <!-- ##### Footer Area End ##### -->

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js"></script>
    <!-- Classy Nav js -->
    <script src="js/classy-nav.min.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>
    <!-- Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwuyLRa1uKNtbgx6xAJVmWy-zADgegA2s"></script>
    <script src="js/map-active.js"></script>

</body>

</html>