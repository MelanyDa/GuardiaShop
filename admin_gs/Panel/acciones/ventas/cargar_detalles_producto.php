<?php
$conn = new mysqli("localhost", "root", "", "guardiashop");
if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

$id_producto = $_GET['id_producto'];
$query = $conn->query("SELECT talla, color, precio, stock FROM detalles_productos WHERE id_producto = '$id_producto'");

$resultado = [];
while ($row = $query->fetch_assoc()) {
    $resultado[] = $row;
}
echo json_encode($resultado);
?>
