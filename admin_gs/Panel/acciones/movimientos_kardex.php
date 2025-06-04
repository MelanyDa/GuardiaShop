<?php
header('Content-Type: application/json; charset=utf-8');
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida"]);
    exit;
}
// Filtros opcionales
$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : null;
$where = "";
if ($id_producto) {
    $where = "WHERE mi.id_detalles_productos IN (SELECT id_detalles_productos FROM detalles_productos WHERE id_producto = $id_producto)";
}
$sql = "SELECT mi.*, p.nombre AS nombre_producto, p.codigo, cp.nombre AS color, tp.nombre_talla, dp.precio_producto, a.primer_nombre, a.primer_apellido
FROM movimientos_inventario mi
JOIN detalles_productos dp ON mi.id_detalles_productos = dp.id_detalles_productos
JOIN productos p ON dp.id_producto = p.id_producto
JOIN color_productos cp ON dp.id_color = cp.id_color
JOIN talla_productos tp ON dp.id_tallas = tp.id_talla
LEFT JOIN usuario a ON mi.id_admin = a.id
$where
ORDER BY mi.fecha_hora DESC";
$res = $conexion->query($sql);
$movimientos = [];
while ($row = $res->fetch_assoc()) {
    $movimientos[] = $row;
}
echo json_encode($movimientos);
$conexion->close(); 