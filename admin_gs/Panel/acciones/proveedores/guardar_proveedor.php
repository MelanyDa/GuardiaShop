<?php
$conn = new mysqli("localhost", "root", "", "guardiashop");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre_empresa = $_POST['nombre_empresa'];
$nombre_contacto = $_POST['nombre_contacto'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$ciudad = $_POST['ciudad'];
$pais = $_POST['pais'];
$nit_o_ruc = $_POST['nit_o_ruc'];
$fecha_registro = date('Y-m-d');
$activo = 1;

$sql = "INSERT INTO proveedores (nombre_empresa, nombre_contacto, correo, telefono, direccion, ciudad, pais, nit_o_ruc, fecha_registro, activo) 
        VALUES ('$nombre_empresa', '$nombre_contacto', '$correo', '$telefono', '$direccion', '$ciudad', '$pais', '$nit_o_ruc', '$fecha_registro', '$activo')";

if ($conn->query($sql) === TRUE) {
    header("Location: /guardiashop/admin_gs/panel/g_proveedores.php?success=1");
    exit();
} else {
    header("Location: /guardiashop/admin_gs/panel/g_proveedores.php?error=1");
    exit();
}

$conn->close();
?>
