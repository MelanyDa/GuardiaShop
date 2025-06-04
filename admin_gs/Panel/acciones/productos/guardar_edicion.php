<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "guardiashop");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtén los datos del formulario
    $detalleId = intval($_POST['id_detalles_productos']);
    $nuevoPrecio = $conn->real_escape_string($_POST['precio_producto']);
    $nuevoStock = $conn->real_escape_string($_POST['stock']);
    $nuevaImagen = $conn->real_escape_string($_POST['imagen']);  // Asumiendo que 'imagen' es editable
    $nuevoNombre = $conn->real_escape_string($_POST['nombre']);    // Asumiendo que 'nombre' es editable
    $nuevaDescripcion = $conn->real_escape_string($_POST['descripcion']); // Asumiendo que 'descripcion' es editable

    // Actualiza el precio y stock en la tabla detalles_productos
    $sqlDetalles = "UPDATE detalles_productos 
                    SET precio_producto = '$nuevoPrecio', 
                        stock = '$nuevoStock' 
                    WHERE id_detalles_productos = $detalleId";

    // Actualiza la imagen en la tabla producto_imagen
    $sqlImagen = "UPDATE producto_imagen
                  SET imagen = '$nuevaImagen'
                  WHERE id_producto = (SELECT id_producto FROM detalles_productos WHERE id_detalles_productos = $detalleId)";

    // Actualiza el nombre y la descripción en la tabla productos
    $sqlProducto = "UPDATE productos
                    SET nombre = '$nuevoNombre', 
                        descripcion = '$nuevaDescripcion'
                    WHERE id_producto = (SELECT id_producto FROM detalles_productos WHERE id_detalles_productos = $detalleId)";

    // Ejecuta las consultas en la base de datos
    $conn->begin_transaction();

    try {
        if ($conn->query($sqlDetalles) === TRUE &&
            $conn->query($sqlImagen) === TRUE &&
            $conn->query($sqlProducto) === TRUE) {
            // Si todas las consultas se ejecutan correctamente, se confirma la transacción
            $conn->commit();
            echo "<script>alert('Producto actualizado correctamente'); window.location = '/guardiashop/admin_gs/Panel/g_productos.php';</script>";
        } else {
            // Si alguna consulta falla, se revierte la transacción
            $conn->rollback();
            echo "Error al actualizar el producto: " . $conn->error;
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error en la transacción: " . $e->getMessage();
    }

    // Cierra la conexión
    $conn->close();
} else {
    echo "Solicitud no válida";
}
?>
