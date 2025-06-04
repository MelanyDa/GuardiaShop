<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    http_response_code(500);
    exit;
}
$id_producto = intval($_POST['id_producto']);
$id_tallas = intval($_POST['id_tallas']);
$id_color = intval($_POST['id_color']);

$stmt = $conexion->prepare("SELECT id_detalles_productos FROM detalles_productos WHERE id_producto = ? AND id_tallas = ? AND id_color = ?");
$stmt->bind_param("iii", $id_producto, $id_tallas, $id_color);
$stmt->execute();
$stmt->bind_result($id_detalles_productos);
$stmt->fetch();
$stmt->close();

echo json_encode(['id_detalles_productos' => $id_detalles_productos]);