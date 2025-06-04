<?php
require_once '../vendor/autoload.php';
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$client = new Google_Client();
$client->setClientId('32837330900-ae47m83bdkc6dektln3du4v82cug1m56.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/guardiashop/login/google-callback.php');

$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        echo "Error al obtener token: " . $token['error_description'];
        exit;
    }

    $client->setAccessToken($token);
    $oauth = new Google_Service_Oauth2($client);
    $userData = $oauth->userinfo->get();

    // Obtener datos
    $nombreCompleto = $conexion->real_escape_string($userData->name);
    $correo = $conexion->real_escape_string($userData->email);
    $proveedorID = isset($userData->id) ? $conexion->real_escape_string($userData->id) : 0;

    // Separar nombre completo (simplemente por el primer espacio)
    $nombrePartes = explode(' ', $nombreCompleto);
    $primerNombre = $nombrePartes[0];
    $primerApellido = isset($nombrePartes[1]) ? $nombrePartes[1] : '';

    // Verificar si ya existe el usuario
    $consulta = "SELECT * FROM usuario WHERE correo = '$correo'";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows == 0) {
        // Insertar nuevo usuario con Google
        $insertar = "INSERT INTO usuario (
            primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
            correo, contraseña, fecha_de_cumpleaños,
            proveedor_registro, proveedor_id, rol
        ) VALUES (
            '$primerNombre', '', '$primerApellido', '',
            '$correo', '', '0000-00-00',
            'google', '$proveedorID', 'cliente'
        )";

        if (!$conexion->query($insertar)) {
            echo "Error al registrar usuario: " . $conexion->error;
            exit;
        }
    }

    // Guardar en sesión
    $_SESSION['usuario_nombre'] = $nombreCompleto;
    $_SESSION['usuario_email'] = $correo;

    // Si necesitas el id_usuario de tu tabla (opcional pero recomendado)
    // Buscar usuario por correo y proveedor
    $consultaId = "SELECT id FROM usuario WHERE correo = '$correo' AND proveedor_registro = 'google' LIMIT 1";
    $resId = $conexion->query($consultaId);
    if ($resId && $rowId = $resId->fetch_assoc()) {
        $_SESSION['usuario_id'] = $rowId['id'];
        $_SESSION['id_usuario'] = $rowId['id']; // <-- AÑADE ESTA LÍNEA
    }

    if (isset($_GET['redirect'])) {
        $_SESSION['redirect_after_login'] = $_GET['redirect'];
    }
    $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '/guardiashop/index.php';
    unset($_SESSION['redirect_after_login']);
    header('Location: ' . $redirect);
    exit;
} else {
    echo "Error: No se recibió el código de autorización.";
}
