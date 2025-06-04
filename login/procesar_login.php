<?php
session_start();
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $stmt = $conn->prepare("SELECT id, primer_nombre, primer_apellido, contraseña FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $primer_nombre, $primer_apellido, $contraseña_hash);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($contraseña, $contraseña_hash)) {      
            $_SESSION['usuario_nombre'] = $primer_nombre . ' ' . $primer_apellido;
            $_SESSION['usuario_id'] = $id;
            $_SESSION['id_usuario'] = $id; // <-- AGREGA ESTA LÍNEA
        // Redirección inteligente
        if (!empty($_POST['redirect'])) {
            header("Location: ../" . ltrim($_POST['redirect'], '/'));
        } else if (!empty($_SERVER['HTTP_REFERER'])) {
            // Si no hay redirect, vuelve a la página anterior (opcional)
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: ../index.php");
        }
        exit();  
            
        } else {
            // Contraseña incorrecta
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña incorrecta',
                    text: 'Verifica tu correo y contraseña.',
                    confirmButtonColor: '#c0392b'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            </script>
            </body>
            </html>
            ";
        }
    } else {
        // Usuario no encontrado
        echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Usuario no encontrado',
                text: 'No se encontró el usuario. Verifica tu correo y contraseña.',
                confirmButtonColor: '#c0392b'
            }).then(() => {
                window.location.href = 'login.php';
            });
        </script>
        </body>
        </html>
        ";
    }

    $stmt->close();
    $conn->close();
}
?>