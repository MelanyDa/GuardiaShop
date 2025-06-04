<?php
$mysqli = new mysqli("localhost", "root", "", "guardiashop");
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y');
$sql = "SELECT SUM(total_factura) AS total FROM facturas_venta WHERE YEAR(fecha_creacion_registro) = $anio";
$res = $mysqli->query($sql)->fetch_assoc();
echo json_encode(['total' => $res['total'] ?? 0]);
?>