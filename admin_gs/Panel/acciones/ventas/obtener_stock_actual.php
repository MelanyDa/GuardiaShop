<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
$id_producto = intval($_POST['id_producto']);
$id_tallas = intval($_POST['id_tallas']);
$id_color = intval($_POST['id_color']);
$stmt = $conexion->prepare("SELECT stock FROM detalles_productos WHERE id_producto = ? AND id_tallas = ? AND id_color = ?");
$stmt->bind_param("iii", $id_producto, $id_tallas, $id_color);
$stmt->execute();
$stmt->bind_result($stock);
$stmt->fetch();
$stmt->close();
echo json_encode(['stock' => intval($stock)]);
?>