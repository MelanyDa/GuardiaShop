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

// Consultas para ambas tiendas
$consultas = [];

if ($tipo_tienda == '' || $tipo_tienda == 'fisica') {
    $where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';
    $consultas[] = "
        SELECT total_factura AS total
        FROM factura_venta_f
        $where
    ";
}
if ($tipo_tienda == '' || $tipo_tienda == 'online') {
    $where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';
    $consultas[] = "
        SELECT total_factura AS total
        FROM facturas_venta
        $where
    ";
}

$sql = implode(" UNION ALL ", $consultas);
$resultado = $conexion->query($sql);

$total_vendido = 0;
$num_ventas = 0;

if ($resultado && $resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
        $total_vendido += (float)$row['total'];
        $num_ventas++;
    }
}

$ticket_promedio = $num_ventas > 0 ? ($total_vendido / $num_ventas) : 0;

echo json_encode([
    'total_vendido' => $total_vendido,
    'num_ventas' => $num_ventas,
    'ticket_promedio' => $ticket_promedio
]);