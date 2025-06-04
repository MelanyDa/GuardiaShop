<?php
header('Content-Type: application/json');
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$tipo_tienda = isset($_GET['tipo_tienda']) ? $_GET['tipo_tienda'] : '';

$condiciones = [];
if ($fecha_inicio) $condiciones[] = "fecha_emision >= '$fecha_inicio'";
if ($fecha_fin) $condiciones[] = "fecha_emision <= '$fecha_fin'";
$where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';

// FÍSICA
$ventasFisica = [];
if ($tipo_tienda == '' || $tipo_tienda == 'fisica') {
    $sqlFisica = "
        SELECT fecha_emision AS fecha, SUM(total_factura) AS total
        FROM factura_venta_f
        $where
        GROUP BY fecha_emision
        ORDER BY fecha_emision
    ";
    $resFisica = $conexion->query($sqlFisica);
    while ($row = $resFisica->fetch_assoc()) {
        $ventasFisica[$row['fecha']] = (float)$row['total'];
    }
}

// ONLINE
$ventasOnline = [];
if ($tipo_tienda == '' || $tipo_tienda == 'online') {
    $sqlOnline = "
        SELECT fecha_emision AS fecha, SUM(total_factura) AS total
        FROM facturas_venta
        $where
        GROUP BY fecha_emision
        ORDER BY fecha_emision
    ";
    $resOnline = $conexion->query($sqlOnline);
    while ($row = $resOnline->fetch_assoc()) {
        $ventasOnline[$row['fecha']] = (float)$row['total'];
    }
}

// Unir fechas
$fechas = array_unique(array_merge(array_keys($ventasFisica), array_keys($ventasOnline)));
sort($fechas);

$dataFisica = [];
$dataOnline = [];
foreach ($fechas as $fecha) {
    $dataFisica[] = isset($ventasFisica[$fecha]) ? $ventasFisica[$fecha] : 0;
    $dataOnline[] = isset($ventasOnline[$fecha]) ? $ventasOnline[$fecha] : 0;
}

echo json_encode([
    'fechas' => $fechas,
    'dataFisica' => $dataFisica,
    'dataOnline' => $dataOnline
]);