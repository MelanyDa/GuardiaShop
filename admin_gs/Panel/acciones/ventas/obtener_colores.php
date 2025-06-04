<?php
$conn = new mysqli("localhost", "root", "", "guardiashop");

$id_producto = $_POST['id_producto'];
$id_talla = $_POST['talla']; // este valor debe ser id_tallas, no el nombre de la talla

// Consulta con JOIN a color_productos para obtener los nombres de los colores
$stmt = $conn->prepare("
    SELECT DISTINCT cp.id_color, cp.nombre 
    FROM detalles_productos dp
    INNER JOIN color_productos cp ON dp.id_color = cp.id_color
    WHERE dp.id_producto = ? AND dp.id_tallas = ?
");
$stmt->bind_param("ii", $id_producto, $id_talla);
$stmt->execute();
$result = $stmt->get_result();

echo '<option value="">Seleccione un color</option>';
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['id_color'] . '">' . $row['nombre'] . '</option>';
}

$stmt->close();
$conn->close();
?>
