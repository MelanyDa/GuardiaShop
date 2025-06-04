<?php
// Actualiza un usuario en la base de datos
// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $correo = $_POST['correo'];
    $fecha_de_cumpleaños = $_POST['fecha_de_cumpleaños'];
    

    $conn = new mysqli("localhost", "root", "", "guardiashop");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE usuario SET primer_nombre=?, segundo_nombre=?, primer_apellido=?, segundo_apellido=?, correo=?, fecha_de_cumpleaños=? WHERE id=?");
    $stmt->bind_param("ssssssi", $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $correo, $fecha_de_cumpleaños,  $id);

    if ($stmt->execute()) {
        header("Location:  /guardiashop/admin_gs/panel/g_usuarios.php?success=2");
    } else {
        header("Location:  /guardiashop/admin_gs/panel/g_usuarios.php?error=3");
    }
    $stmt->close();
    $conn->close();
    exit;
} else {
    header("Location:  /guardiashop/admin_gs/panel/g_usuarios.php");
    exit;
}
?>