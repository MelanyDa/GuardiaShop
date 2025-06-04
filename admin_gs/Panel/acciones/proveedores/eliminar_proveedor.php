<?php
$conn = new mysqli("localhost", "root", "", "guardiashop");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$sql = "DELETE FROM proveedores WHERE id_proveedor = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: /guardiashop/admin_gs/panel/g_proveedores.php?eliminado=1");
} else {
    header("Location: /guardiashop/admin_gs/panel/g_proveedores.php?error_eliminar=1");
}
$conn->close();
?>
