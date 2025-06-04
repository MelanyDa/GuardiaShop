<?php
if (!isset($_GET['mes'])) {
    echo json_encode(['total' => 0]);
    exit;
}
$mes = (int)$_GET['mes'];
$mysqli = new mysqli("localhost", "root", "", "guardiashop");
$sql = "SELECT SUM(total_factura) AS total FROM factura_venta_f WHERE MONTH(fecha_creacion_registro) = $mes AND YEAR(fecha_creacion_registro) = YEAR(CURDATE())";
$res = $mysqli->query($sql)->fetch_assoc();
echo json_encode(['total' => $res['total'] ?? 0]);
?>