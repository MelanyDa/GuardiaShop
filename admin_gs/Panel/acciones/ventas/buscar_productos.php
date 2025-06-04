<?php
$conn = new mysqli("localhost", "root", "", "guardiashop");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$busqueda = $_GET['q'] ?? '';
$busqueda = $conn->real_escape_string($busqueda);

$sql = "
    SELECT id_producto, nombre 
    FROM productos 
    WHERE id_producto LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' 
    LIMIT 10
";
$result = $conn->query($sql);

$sugerencias = [];
while ($row = $result->fetch_assoc()) {
    $sugerencias[] = [
        'id' => $row['id_producto'],
        'text' => $row['id_producto'] . " - " . $row['nombre']
    ];
}

header('Content-Type: application/json');
echo json_encode($sugerencias);
?>
