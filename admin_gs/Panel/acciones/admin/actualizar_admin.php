<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $correo = $_POST['correo'];
    $fecha_de_cumpleaños = $_POST['fecha_de_cumpleaños'];
    $rol = $_POST['rol'];

    $conn = new mysqli("localhost", "root", "", "guardiashop");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuario SET primer_nombre=?, segundo_nombre=?, primer_apellido=?, segundo_apellido=?, correo=?, fecha_de_cumpleaños=?, password=?, rol=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $correo, $fecha_de_cumpleaños, $password, $rol, $id);
    } else {
        $stmt = $conn->prepare("UPDATE usuario SET primer_nombre=?, segundo_nombre=?, primer_apellido=?, segundo_apellido=?, correo=?, fecha_de_cumpleaños=?, rol=? WHERE id=?");
        $stmt->bind_param("sssssssi", $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $correo, $fecha_de_cumpleaños, $rol, $id);
    }

    if ($stmt->execute()) {
        header("Location: /guardiashop/admin_gs/panel/g_admins.php?success=2");
    } else {
        header("Location: /guardiashop/admin_gs/panel/g_admins.php?error=2");
    }
    $stmt->close();
    $conn->close();
    exit;
} else {
    header("Location: /guardiashop/admin_gs/panel/g_admins.php");
    exit;
}
?>