<?php
$conn = new mysqli("localhost", "root", "", "guardiashop");

$id_producto = $_POST['id_producto'];
$id_talla = $_POST['talla'];
$id_color = $_POST['color'];

$stmt = $conn->prepare("
    SELECT precio_producto 
    FROM detalles_productos 
    WHERE id_producto = ? AND id_tallas = ? AND id_color = ?
    LIMIT 1
");
$stmt->bind_param("iii", $id_producto, $id_talla, $id_color);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo $row['precio_producto'];
} else {
    echo "0"; // o puedes retornar mensaje de error si no se encuentra
}

$stmt->close();
$conn->close();
?>
