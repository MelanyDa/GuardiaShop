<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "guardiashop");
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y');
$datos = [];
for ($mes = 1; $mes <= 12; $mes++) {
    $sql = "SELECT SUM(total_factura) AS total FROM factura_venta_f WHERE MONTH(fecha_creacion_registro) = $mes AND YEAR(fecha_creacion_registro) = $anio";
    $res = $mysqli->query($sql)->fetch_assoc();
    $datos[$mes] = $res['total'] ? $res['total'] : 0;
}
echo json_encode($datos);