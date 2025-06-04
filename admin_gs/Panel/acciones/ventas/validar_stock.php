<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    http_response_code(500);
    exit;
}
$carrito = json_decode($_POST['carrito'], true);
$sin_stock = [];

foreach ($carrito as $item) {
    $id_producto = intval($item['id_producto']);
    $id_color = intval($item['id_color']);
    $id_tallas = intval($item['id_tallas']);
    $cantidad = intval($item['cantidad']);

    $stmt = $conexion->prepare("SELECT stock FROM detalles_productos WHERE id_producto = ? AND id_tallas = ? AND id_color = ?");
    $stmt->bind_param("iii", $id_producto, $id_tallas, $id_color);
    $stmt->execute();
    $stmt->bind_result($stock_actual);
    $stmt->fetch();
    $stmt->close();

    if ($stock_actual < $cantidad) {
        $sin_stock[] = [
            'stock_disponible' => $stock_actual
        ];
    }
}
header('Content-Type: application/json');
echo json_encode($sin_stock);
?>