<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$id_categoria = $_POST['id_categoria'];
$id_sesion = $_POST['id_sesion'];
$codigo = $_POST['codigo'];

// Procesar imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    $nombreImagen = basename($_FILES["imagen"]["name"]);

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

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFisica)) {
        // Insertar producto
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, id_categoria, id_sesion, codigo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiis", $nombre, $descripcion, $id_categoria, $id_sesion, $codigo);
        $stmt->execute();

        $id_nuevo_producto = $conexion->insert_id;

        // Obtener el color asociado a la imagen
        $id_color_asociado = isset($_POST['color_imagen']) ? intval($_POST['color_imagen']) : null;

        // Insertar imagen con color asociado
        $stmt_img = $conexion->prepare("INSERT INTO producto_imagen (id_producto, id_color_asociado, imagen) VALUES (?, ?, ?)");
        $stmt_img->bind_param("iis", $id_nuevo_producto, $id_color_asociado, $rutaBD);
        $stmt_img->execute();

        // Insertar detalles de producto (tallas, colores, precios, stock)
        $tallas = $_POST['talla'];  // Recibe el array de tallas
        $colores = $_POST['color']; // Recibe el array de colores
        $precios = $_POST['precio']; // Recibe el array de precios
        $stocks = $_POST['stock'];  // Recibe el array de stock
        

        foreach ($tallas as $index => $id_talla) {
            $id_color = $colores[$index];
            $precio = $precios[$index];
            $stock = $stocks[$index];

            // Insertar detalle del producto (combinación de talla, color, precio y stock)
            $stmt_detalle = $conexion->prepare("INSERT INTO detalles_productos (id_producto, id_color, id_tallas, precio_producto, stock) VALUES (?, ?, ?, ?, ?)");
            $stmt_detalle->bind_param("iiidi", $id_nuevo_producto, $id_color, $id_talla, $precio, $stock);
            $stmt_detalle->execute();
        }

        header("Location: /guardiashop/admin_gs/panel/g_productos.php?add=success");
        exit();
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "No se recibió la imagen o hubo un error.";
}

?>
