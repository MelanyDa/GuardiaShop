<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Obtener el ID del producto a eliminar
    $productoId = $_GET["id"];

    // Realiza la conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "guardiashop");

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Primero eliminamos las imágenes asociadas al producto
    $sql_imagenes = "DELETE FROM producto_imagen WHERE id_producto = $productoId";
    if (!$conn->query($sql_imagenes)) {
        echo "Error al eliminar las imágenes del producto: " . $conn->error;
        exit;
    }

    // Eliminar las referencias en la tabla detalles_productos si existen
    $sql_detalles = "DELETE FROM detalles_productos WHERE id_producto = $productoId";
    if (!$conn->query($sql_detalles)) {
        echo "Error al eliminar detalles del producto: " . $conn->error;
        exit;
    }

    // Ahora eliminamos el producto principal
    $sql_producto = "DELETE FROM productos WHERE id_producto = $productoId";
    if ($conn->query($sql_producto) === TRUE) {
        // Redirige de nuevo a la página de productos después de la eliminación
        header('Location: /guardiashop/admin_gs/panel/g_productos.php?success=2');
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }

    // Cierra la conexión
    $conn->close();
} else {
    echo "Solicitud no válida";
}
?>
