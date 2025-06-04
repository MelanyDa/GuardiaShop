<?php
$mysqli = new mysqli("localhost", "root", "", "guardiashop");
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y');
$sql = "SELECT MONTH(fecha_creacion_registro) as mes, SUM(total_factura) as total FROM facturas_venta WHERE YEAR(fecha_creacion_registro) = $anio GROUP BY mes";
$res = $mysqli->query($sql);
$data = [];
while ($row = $res->fetch_assoc()) {
    $data[(int)$row['mes']] = $row['total'];
}
echo json_encode($data);
?>