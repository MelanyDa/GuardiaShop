<?php
$mysqli = new mysqli("localhost", "root", "", "guardiashop");
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
$sql = "SELECT SUM(total_factura) AS total FROM facturas_venta WHERE MONTH(fecha_creacion_registro) = $mes AND YEAR(fecha_creacion_registro) = YEAR(CURDATE())";
$res = $mysqli->query($sql)->fetch_assoc();
echo json_encode(['total' => $res['total'] ?? 0]);
?>