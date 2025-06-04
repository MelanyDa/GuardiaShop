<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id = $_POST['id_producto'];
$nombre = $conexion->real_escape_string($_POST['nombre']);
$descripcion = $conexion->real_escape_string($_POST['descripcion']);
$id_categoria = $_POST['id_categoria'];
$id_sesion = $_POST['id_sesion'];
$codigo = $_POST['codigo'];

// Actualizar datos generales del producto
$sql = "UPDATE productos 
        SET nombre = '$nombre',
            descripcion = '$descripcion', 
            id_categoria = $id_categoria, 
            id_sesion = $id_sesion,
            codigo = '$codigo'
        WHERE id_producto = $id";

if (!$conexion->query($sql)) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al actualizar el producto: ' . $conexion->error]);
    exit;
}

// Eliminar combinaciones anteriores
$conexion->query("DELETE FROM detalles_productos WHERE id_producto = $id");

// Insertar nuevas combinaciones
$tallas = $_POST['talla'];
$colores = $_POST['color'];
$precios = $_POST['precio'];
$stocks = $_POST['stock'];

for ($i = 0; $i < count($tallas); $i++) {
    $talla = $conexion->real_escape_string($tallas[$i]);
    $color = $conexion->real_escape_string($colores[$i]);
    $precio = floatval($precios[$i]);
    $stock = intval($stocks[$i]);

    if (!$conexion->query("INSERT INTO detalles_productos (id_producto, id_tallas, id_color, precio_producto, stock)
                           VALUES ($id, '$talla', '$color', $precio, $stock)")) {
        // Si hubo error en la inserción
        header("Location: /guardiashop/admin_gs/panel/g_productos.php?error=1");
        exit();
    }
}

// Obtener el color asociado a la imagen
$id_color_asociado = isset($_POST['color_imagen']) ? intval($_POST['color_imagen']) : null;

// Manejar la imagen si se subió una nueva
if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] == 0) {
    $nombreImagen = basename($_FILES["nueva_imagen"]["name"]);

    // Determinar carpeta según categoría
    $carpeta = "images/";
    switch ($id_categoria) {
        case 1: $carpeta .= "blusas/"; break;
        case 2: $carpeta .= "short_damas/"; break;
        case 3: $carpeta .= "gorras/"; break;
        case 4: $carpeta .= "camisa_hombre/"; break;
        // Agrega más según tus categorías
        default: $carpeta .= "otros/"; break;
    }

    // Crear carpeta si no existe
    $rutaCarpeta = $_SERVER['DOCUMENT_ROOT'] . "/guardiashop/admin_gs/Panel/" . $carpeta;
    if (!is_dir($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    $rutaFisica = $rutaCarpeta . $nombreImagen;
    $rutaBD = $carpeta . $nombreImagen;

    if (move_uploaded_file($_FILES["nueva_imagen"]["tmp_name"], $rutaFisica)) {
        // Actualizar la imagen y el color asociado
        $conexion->query("UPDATE producto_imagen SET imagen = '$rutaBD', id_color_asociado = $id_color_asociado WHERE id_producto = $id LIMIT 1");
    }
} else {
    // Si no se subió nueva imagen, solo actualiza el color asociado
    $conexion->query("UPDATE producto_imagen SET id_color_asociado = $id_color_asociado WHERE id_producto = $id LIMIT 1");
}

// Redirigir con mensaje de éxito después de completar el bucle
header("Location: /guardiashop/admin_gs/panel/g_productos.php?success=1");
$conexion->close();
?>
