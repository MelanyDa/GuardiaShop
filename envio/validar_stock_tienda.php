<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    http_response_code(500);
    exit;
}
$carrito = json_decode(file_get_contents('php://input'), true);
$sin_stock = [];

foreach ($carrito as $item) {
    $id_detalles_productos = intval($item['id_detalles_productos']);
    $cantidad = intval($item['quantity']);

    $stmt = $conexion->prepare("SELECT stock FROM detalles_productos WHERE id_detalles_productos = ?");
    $stmt->bind_param("i", $id_detalles_productos);
    $stmt->execute();
    $stmt->bind_result($stock_actual);
    $stmt->fetch();
    $stmt->close();

    if ($stock_actual < $cantidad) {
        $sin_stock[] = [
            'producto' => $item['name'],
            'talla' => $item['talla'],
            'color' => $item['color'],
            'stock_disponible' => $stock_actual
        ];
    }
}
header('Content-Type: application/json');
echo json_encode(['sin_stock' => $sin_stock]);