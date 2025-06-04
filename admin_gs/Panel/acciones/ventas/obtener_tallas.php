<?php
$conn = new mysqli("localhost", "root", "", "guardiashop");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verifica que se recibió el ID del producto
if (!isset($_POST['id_producto'])) {
    echo '<option value="">Error: producto no especificado</option>';
    exit;
}

$id_producto = $conn->real_escape_string($_POST['id_producto']);

// Consulta con JOIN para obtener nombre de talla
$sql = "
    SELECT DISTINCT tp.id_talla, tp.nombre_talla
    FROM detalles_productos dp
    INNER JOIN talla_productos tp ON dp.id_tallas = tp.id_talla
    WHERE dp.id_producto = '$id_producto'
";

$resultado = $conn->query($sql);

if (!$resultado) {
    echo '<option value="">Error en consulta: ' . $conn->error . '</option>';
    exit;
}

if ($resultado->num_rows > 0) {
    echo '<option value="">Seleccione una talla</option>';
    while ($row = $resultado->fetch_assoc()) {
        echo '<option value="' . $row['id_talla'] . '">' . $row['nombre_talla'] . '</option>';
    }
} else {
    echo '<option value="">No hay tallas disponibles</option>';
}
?>
