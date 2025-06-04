<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $correo = $_POST['correo'];
    $fecha_de_cumpleaños = $_POST['fecha_de_cumpleaños'];
    $rol = $_POST['rol'];   
    $password = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);


    $conn = new mysqli("localhost", "root", "", "guardiashop");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO usuario (primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, fecha_de_cumpleaños, contraseña, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $correo, $fecha_de_cumpleaños, $password, $rol);

    if ($stmt->execute()) {
        header("Location: /guardiashop/admin_gs/panel/g_admins.php?success=1");
    } else {
        header("Location: /guardiashop/admin_gs/panel/g_admins.php?error=1");
    }
    $stmt->close();
    $conn->close();
    exit;
} else {
    header("Location: /guardiashop/admin_gs/panel/g_admins.php");
    exit;
}
?>