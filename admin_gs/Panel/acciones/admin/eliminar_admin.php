<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn = new mysqli("localhost", "root", "", "guardiashop");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("DELETE FROM usuario WHERE id=? AND rol='admin'");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: /guardiashop/admin_gs/panel/g_admins.php?success=3");
    } else {
        header("Location: /guardiashop/admin_gs/panel/g_admins.php?error=3");
    }
    $stmt->close();
    $conn->close();
    exit;
} else {
    header("Location: /guardiashop/admin_gs/panel/g_admins.php");
    exit;
}
?>