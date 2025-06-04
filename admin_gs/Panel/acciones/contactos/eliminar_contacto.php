<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $contactoId = intval($_POST["id"]);

    $conn = new mysqli("localhost", "root", "", "guardiashop");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "DELETE FROM contactanos WHERE id_contacto = $contactoId";

    if ($conn->query($sql) === TRUE) {
        header('Location: /guardiashop/admin_gs/panel/g_contactos.php?success=4');
        exit();
    } else {
        echo "Error al eliminar el contacto: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Solicitud no válida.";
}
?>
