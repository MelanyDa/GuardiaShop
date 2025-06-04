<?php
session_start();
include('./login/conexion.php');

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_SESSION['usuario_id'];
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $fecha_de_cumpleaños = $_POST['fecha_de_cumpleaños'];

    // Manejo de la foto de perfil
    $foto_perfil = null;
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = 'uploads/perfil_' . $id . '_' . time() . '.' . $ext;
        $ruta_destino = __DIR__ . '/' . $nombre_archivo;
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_destino)) {
            $foto_perfil = $nombre_archivo;
        }
    }

    // Construye la consulta SQL dinámicamente
    $sql = "UPDATE usuario SET primer_nombre=?, segundo_nombre=?, primer_apellido=?, segundo_apellido=?, fecha_de_cumpleaños=?";
    $params = [$primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $fecha_de_cumpleaños];
    $types = "sssss";

    if ($foto_perfil) {
        $sql .= ", foto_perfil=?";
        $params[] = $foto_perfil;
        $types .= "s";
    }

    $sql .= " WHERE id=?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['primer_nombre'] = $primer_nombre;
        $_SESSION['segundo_nombre'] = $segundo_nombre;
        $_SESSION['primer_apellido'] = $primer_apellido;
        $_SESSION['segundo_apellido'] = $segundo_apellido;
        $_SESSION['success'] = "¡Perfil actualizado correctamente!";
    } else {
        $_SESSION['error_update'] = "Error al actualizar: " . $stmt->error;
    }

    header("Location: modificar_perfil.php");
    exit();
}
?>
