<?php
include('conexion.php');  // Incluimos la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $primer_nombre = $_POST['nombre'];
    $segundo_nombre = $_POST['nombre2'];
    $primer_apellido = $_POST['apellido'];
    $segundo_apellido = $_POST['apellido2'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $confirmar = $_POST['confirmar'];

    // Validar que la contraseña y la confirmación coincidan
    if ($contraseña !== $confirmar) {
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
                title: 'Las contraseñas no coinciden',
                text: 'Por favor, verifica que ambas contraseñas sean iguales.',
                confirmButtonColor: '#c0392b'
            }).then(() => {
                window.location.href = 'registro.php';
            });
        </script>
        </body>
        </html>
        ";
        exit();
    }

    // Verificar si el correo ya está registrado
    $stmt = $conn->prepare("SELECT id FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
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
                title: 'Correo ya registrado',
                text: 'Este correo electrónico ya está registrado.',
                confirmButtonColor: '#c0392b'
            }).then(() => {
                window.location.href = 'registro.php';
            });
        </script>
        </body>
        </html>
        ";
        exit();
    }

    // Encriptar la contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertar los datos del usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuario (primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, contraseña) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $correo, $contraseña_hash);

    if ($stmt->execute()) {
    session_start();
    $nuevo_id = $stmt->insert_id;
    $_SESSION['usuario_nombre'] = $primer_nombre . ' ' . $primer_apellido;
    $_SESSION['usuario_id'] = $nuevo_id;
    $_SESSION['id_usuario'] = $nuevo_id; // <-- AGREGA ESTA LÍNEA
    // Si redirect existe y no tiene barra inicial, úsalo. Si tiene barra inicial, quítala.
    $redirect = '../index.php';
    if (!empty($_POST['redirect'])) {
        $redirect = '../' . ltrim($_POST['redirect'], '/');
    }

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
            icon: 'success',
            title: '¡Registro exitoso!',
            text: 'Usuario registrado exitosamente.',
            confirmButtonColor: '#2c4926'
        }).then(() => {
            window.location.href = '$redirect';
        });
    </script>
    </body>
    </html>
    ";
    } else {
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
                title: 'Error',
                text: 'Error al registrar el usuario: " . addslashes($stmt->error) . "',
                confirmButtonColor: '#c0392b'
            }).then(() => {
                window.location.href = 'registro.php';
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