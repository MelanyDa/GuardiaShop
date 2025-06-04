<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_proveedor']);
    $nombre_empresa = $_POST['nombre_empresa'];
    $nombre_contacto = $_POST['nombre_contacto'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $pais = $_POST['pais'];
    $nit_o_ruc = $_POST['nit_o_ruc'];
    $activo = $_POST['activo'];

    $conn = new mysqli("localhost", "root", "", "guardiashop");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("UPDATE proveedores SET nombre_empresa=?, nombre_contacto=?, correo=?, telefono=?, direccion=?, ciudad=?, pais=?, nit_o_ruc=?, activo=? WHERE id_proveedor=?");
    $stmt->bind_param("ssssssssii", $nombre_empresa, $nombre_contacto, $correo, $telefono, $direccion, $ciudad, $pais, $nit_o_ruc, $activo, $id);

    if ($stmt->execute()) {
        header("Location: /guardiashop/admin_gs/panel/g_proveedores.php?success=2");
    } else {
        header("Location: /guardiashop/admin_gs/panel/g_proveedores.php?error=2");
    }
    $stmt->close();
    $conn->close();
    exit;
} else {
    header("Location: /guardiashop/admin_gs/panel/g_proveedores.php");
    exit;
}
?>