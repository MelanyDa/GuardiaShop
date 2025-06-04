<?php
if (!isset($_GET['anio'])) {
    echo json_encode(['total' => 0]);
    exit;
}
$anio = (int)$_GET['anio'];
$mysqli = new mysqli("localhost", "root", "", "guardiashop");
$sql = "SELECT SUM(total_factura) AS total FROM factura_venta_f WHERE YEAR(fecha_creacion_registro) = $anio";
$res = $mysqli->query($sql)->fetch_assoc();
echo json_encode(['total' => $res['total'] ?? 0]);
?>