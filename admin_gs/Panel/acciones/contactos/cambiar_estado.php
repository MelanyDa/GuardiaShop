<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["estado"])) {
    $contactoId = intval($_POST["id"]);
    $nuevoEstado = $_POST["estado"];

    // Lista de estados válidos
    $estadosValidos = ["Nuevo", "Leído", "Respondido", "Cerrado"];

    if (!in_array($nuevoEstado, $estadosValidos)) {
        echo "Estado no válido.";
        exit();
    }

    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "guardiashop");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Escapar estado por seguridad
    $nuevoEstado = $conn->real_escape_string($nuevoEstado);

    // Actualizar estado
    $sql = "UPDATE contactanos SET estado = '$nuevoEstado' WHERE id_contacto = $contactoId";

    if ($conn->query($sql) === TRUE) {
        header("Location: /guardiashop/admin_gs/panel/g_contactos.php?estado_cambiado=1");
        exit;
    } else {
        echo "Error al actualizar el estado: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Solicitud no válida.";
}
?>
