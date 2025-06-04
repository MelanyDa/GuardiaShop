<?php
session_start();
require_once 'login/conexion.php';

// --- Determinar página y categoría actual ---
$nombre_archivo_actual = basename($_SERVER['PHP_SELF']); // short.php
$id_categoria_actual_page = 2; // ID de la categoría "Shorts"
$nombre_pagina_actual = "Shorts";

// --- Leer parámetros GET para los filtros ---
$precio_seleccionado_filtro = null;
if (isset($_GET['precio_sel']) && is_numeric($_GET['precio_sel'])) {
    $precio_seleccionado_filtro = intval($_GET['precio_sel']);
}
$id_color_filtro = null;
if (isset($_GET['id_color_f']) && is_numeric($_GET['id_color_f']) && $_GET['id_color_f'] !== '') {
    $id_color_filtro = intval($_GET['id_color_f']);
}
$id_sesion_filtro = null; 
if (isset($_GET['sesion']) && is_numeric($_GET['sesion']) && $_GET['sesion'] !== '') {
    $id_sesion_filtro = intval($_GET['sesion']);
}


// --- Lógica AJAX para el modal ---
if (isset($_GET['accion']) && $_GET['accion'] == 'obtener_detalles_producto' && isset($_GET['id_producto'])) {
    header('Content-Type: application/json');
    $id_producto_solicitado = intval($_GET['id_producto']);
    $respuesta = ['error' => 'No se pudieron obtener los detalles del short.'];
    if ($id_producto_solicitado > 0 && isset($conn)) {
        try {
            $sql_base_modal = "SELECT p.id_producto, p.nombre, p.descripcion FROM productos p WHERE p.id_producto = ? AND p.id_categoria = ?";
            $stmt_base = $conn->prepare($sql_base_modal);
            $stmt_base->bind_param("ii", $id_producto_solicitado, $id_categoria_actual_page);
            $stmt_base->execute(); $result_base = $stmt_base->get_result(); $producto_db = $result_base->fetch_assoc(); $stmt_base->close();
            if ($producto_db) {
                $respuesta_producto = ['producto_base' => $producto_db, 'colores_disponibles' => [], 'variantes_sin_color_especifico' => []];
                $stmt_img_gen = $conn->prepare("SELECT imagen FROM producto_imagen WHERE id_producto = ? AND id_color_asociado IS NULL ORDER BY id_imagen ASC");
                $stmt_img_gen->bind_param("i", $id_producto_solicitado); $stmt_img_gen->execute(); $result_img_gen = $stmt_img_gen->get_result();
                while ($img_gen_row = $result_img_gen->fetch_assoc()) { $respuesta_producto['producto_base']['imagenes_generales'][] = $img_gen_row['imagen']; } $stmt_img_gen->close();
                $sql_colores_modal = "SELECT DISTINCT cp.id_color, cp.nombre AS nombre_color, cp.codigo_hexadecimal FROM detalles_productos dp JOIN color_productos cp ON dp.id_color = cp.id_color WHERE dp.id_producto = ? ORDER BY cp.nombre ASC";
                $stmt_colores_modal = $conn->prepare($sql_colores_modal); $stmt_colores_modal->bind_param("i", $id_producto_solicitado); $stmt_colores_modal->execute(); $result_colores_modal = $stmt_colores_modal->get_result();
                while ($color_row_modal = $result_colores_modal->fetch_assoc()) {
                    $id_color_actual_m = $color_row_modal['id_color'];
                    $color_data_m = ['id_color' => $id_color_actual_m, 'nombre_color' => $color_row_modal['nombre_color'], 'codigo_hex' => $color_row_modal['codigo_hexadecimal'], 'imagenes_del_color' => [], 'tallas_en_este_color' => []];
                    $stmt_imgs_c_m = $conn->prepare("SELECT imagen FROM producto_imagen WHERE id_producto = ? AND id_color_asociado = ? ORDER BY id_imagen ASC");
                    $stmt_imgs_c_m->bind_param("ii", $id_producto_solicitado, $id_color_actual_m); $stmt_imgs_c_m->execute(); $res_imgs_c_m = $stmt_imgs_c_m->get_result();
                    while ($img_c_m_row = $res_imgs_c_m->fetch_assoc()) { $color_data_m['imagenes_del_color'][] = $img_c_m_row['imagen']; } $stmt_imgs_c_m->close();
                    $sql_tallas_c_m = "SELECT dp.id_detalles_productos, dp.id_tallas, tp.nombre_talla, dp.precio_producto FROM detalles_productos dp JOIN talla_productos tp ON dp.id_tallas = tp.id_talla WHERE dp.id_producto = ? AND dp.id_color = ? ORDER BY tp.nombre_talla ASC";
                    $stmt_tallas_c_m = $conn->prepare($sql_tallas_c_m); $stmt_tallas_c_m->bind_param("ii", $id_producto_solicitado, $id_color_actual_m); $stmt_tallas_c_m->execute(); $res_tallas_c_m = $stmt_tallas_c_m->get_result();
                    while ($talla_row_m = $res_tallas_c_m->fetch_assoc()) { $color_data_m['tallas_en_este_color'][] = $talla_row_m; } $stmt_tallas_c_m->close();
                    $respuesta_producto['colores_disponibles'][] = $color_data_m;
                } $stmt_colores_modal->close();
                if (empty($respuesta_producto['colores_disponibles'])) {
                    $sql_sin_color_m = "SELECT dp.id_detalles_productos, dp.id_tallas, tp.nombre_talla, dp.precio_producto FROM detalles_productos dp JOIN talla_productos tp ON dp.id_tallas = tp.id_talla WHERE dp.id_producto = ? AND (dp.id_color IS NULL OR dp.id_color NOT IN (SELECT id_color FROM color_productos WHERE id_color = dp.id_color)) ORDER BY tp.nombre_talla ASC";
                    $stmt_sin_color_m = $conn->prepare($sql_sin_color_m); $stmt_sin_color_m->bind_param("i", $id_producto_solicitado); $stmt_sin_color_m->execute(); $res_sin_color_m = $stmt_sin_color_m->get_result();
                    while ($row_sc_m = $res_sin_color_m->fetch_assoc()) { $respuesta_producto['variantes_sin_color_especifico'][] = $row_sc_m;} $stmt_sin_color_m->close();
                } $respuesta = $respuesta_producto;
            } else { $respuesta = ['error' => 'Short no encontrado o no pertenece a esta categoría.']; }
        } catch (Exception $e) { $respuesta = ['error' => 'Error en el servidor: ' . $e->getMessage()]; }
    }
    echo json_encode($respuesta); if (isset($conn)) $conn->close(); exit;
}

// --- INICIO CARGA NORMAL DE LA PÁGINA ---
$productos_para_mostrar = [];
$colores_para_filtro_ui = [];
$precio_min_db_slider = 0;
$precio_max_db_slider = 200000;

if (isset($conn)) {
    $sql_colores_ui = "SELECT DISTINCT cp.id_color, cp.nombre, cp.codigo_hexadecimal FROM color_productos cp JOIN detalles_productos dp ON cp.id_color = dp.id_color JOIN productos p ON dp.id_producto = p.id_producto";
    $cond_col_ui = []; $params_col_ui = []; $types_col_ui = "";
    $cond_col_ui[] = "p.id_categoria = ?"; $params_col_ui[] = $id_categoria_actual_page; $types_col_ui .= "i";
    if ($id_sesion_filtro !== null) { $cond_col_ui[] = "p.id_sesion = ?"; $params_col_ui[] = $id_sesion_filtro; $types_col_ui .= "i"; }
    if (!empty($cond_col_ui)) { $sql_colores_ui .= " WHERE " . implode(" AND ", $cond_col_ui); }
    $sql_colores_ui .= " ORDER BY cp.nombre ASC";
    $stmt_col_ui = $conn->prepare($sql_colores_ui);
    if (!empty($params_col_ui)) { $stmt_col_ui->bind_param($types_col_ui, ...$params_col_ui); }
    $stmt_col_ui->execute(); $result_col_ui = $stmt_col_ui->get_result();
    if ($result_col_ui) { while ($row = $result_col_ui->fetch_assoc()) { $colores_para_filtro_ui[] = $row; } }
    $stmt_col_ui->close();

    $sql_rango_p = "SELECT MIN(dp.precio_producto) as min_p, MAX(dp.precio_producto) as max_p FROM detalles_productos dp JOIN productos p ON dp.id_producto = p.id_producto";
    $cond_rango_p = []; $params_rango_p = []; $types_rango_p = "";
    $cond_rango_p[] = "p.id_categoria = ?"; $params_rango_p[] = $id_categoria_actual_page; $types_rango_p .= "i";
    if ($id_sesion_filtro !== null) { $cond_rango_p[] = "p.id_sesion = ?"; $params_rango_p[] = $id_sesion_filtro; $types_rango_p .= "i"; }
    if (!empty($cond_rango_p)) { $sql_rango_p .= " WHERE " . implode(" AND ", $cond_rango_p); }
    $stmt_rango_p = $conn->prepare($sql_rango_p);
    if(!empty($params_rango_p)){ $stmt_rango_p->bind_param($types_rango_p, ...$params_rango_p); }
    $stmt_rango_p->execute(); $result_rango_db = $stmt_rango_p->get_result();
    if ($result_rango_db && $row_rango_db = $result_rango_db->fetch_assoc()) {
        $precio_min_db_slider = $row_rango_db['min_p'] ?? 0;
        $precio_max_db_slider = $row_rango_db['max_p'] ?? 200000;
    }
    $stmt_rango_p->close();
    if ($precio_seleccionado_filtro !== null) {
        if ($precio_seleccionado_filtro < $precio_min_db_slider) $precio_seleccionado_filtro = $precio_min_db_slider;
        if ($precio_seleccionado_filtro > $precio_max_db_slider) $precio_seleccionado_filtro = $precio_max_db_slider;
    }

    $sql_grid = "SELECT p.id_producto, p.nombre AS nombre_producto,
                    (SELECT MIN(dp_inner.precio_producto) FROM detalles_productos dp_inner WHERE dp_inner.id_producto = p.id_producto) AS precio_desde,
                    COALESCE( ";
    if ($id_color_filtro !== null) {
        $sql_grid .= " (SELECT pi_color.imagen FROM producto_imagen pi_color WHERE pi_color.id_producto = p.id_producto AND pi_color.id_color_asociado = " . intval($id_color_filtro) . " ORDER BY pi_color.id_imagen ASC LIMIT 1), ";
    }
    $sql_grid .= "     (SELECT pi_any_color.imagen FROM producto_imagen pi_any_color WHERE pi_any_color.id_producto = p.id_producto AND pi_any_color.id_color_asociado IS NOT NULL ORDER BY (pi_any_color.id_color_asociado % 3), pi_any_color.id_imagen ASC LIMIT 1),
                        (SELECT pi_general.imagen FROM producto_imagen pi_general WHERE pi_general.id_producto = p.id_producto AND pi_general.id_color_asociado IS NULL ORDER BY pi_general.id_imagen ASC LIMIT 1),
                        (SELECT pi_first.imagen FROM producto_imagen pi_first WHERE pi_first.id_producto = p.id_producto ORDER BY pi_first.id_imagen ASC LIMIT 1),
                        'assets/images/placeholder.png'
                    ) AS ruta_imagen_principal
                 FROM productos p ";
    $where_clauses = ["p.id_categoria = ?"]; $bind_params = [$id_categoria_actual_page]; $bind_types = "i";
    if ($id_sesion_filtro !== null) { $where_clauses[] = "p.id_sesion = ?"; $bind_params[] = $id_sesion_filtro; $bind_types .= "i"; }
    if ($precio_seleccionado_filtro !== null) {
        $precio_min_rango_filtro = floor($precio_seleccionado_filtro / 10000) * 10000;
        $precio_max_rango_filtro = $precio_min_rango_filtro + 9999.99;
        if ($precio_min_rango_filtro < $precio_min_db_slider && $precio_seleccionado_filtro >= $precio_min_db_slider) $precio_min_rango_filtro = $precio_min_db_slider;
        if ($precio_max_rango_filtro > $precio_max_db_slider) $precio_max_rango_filtro = $precio_max_db_slider;
        if( $precio_seleccionado_filtro < $precio_min_db_slider && $precio_min_rango_filtro > $precio_max_rango_filtro){ $precio_max_rango_filtro = $precio_min_rango_filtro; }
        $where_clauses[] = "EXISTS (SELECT 1 FROM detalles_productos dp_price WHERE dp_price.id_producto = p.id_producto AND dp_price.precio_producto BETWEEN ? AND ?)";
        $bind_params[] = $precio_min_rango_filtro; $bind_params[] = $precio_max_rango_filtro; $bind_types .= "dd";
    }
    if ($id_color_filtro !== null) {
        $where_clauses[] = "EXISTS (SELECT 1 FROM detalles_productos dp_color WHERE dp_color.id_producto = p.id_producto AND dp_color.id_color = ?)";
        $bind_params[] = $id_color_filtro; $bind_types .= "i";
    }
    if (!empty($where_clauses)) { $sql_grid .= " WHERE " . implode(" AND ", $where_clauses); }
    $sql_grid .= " GROUP BY p.id_producto ORDER BY p.nombre ASC";
    $stmt_grid_prod = $conn->prepare($sql_grid);
    if (!empty($bind_params)) { $stmt_grid_prod->bind_param($bind_types, ...$bind_params); }
    $stmt_grid_prod->execute(); $resultado_grid_prod = $stmt_grid_prod->get_result();
    if ($resultado_grid_prod) { while ($fila = $resultado_grid_prod->fetch_assoc()) { $productos_para_mostrar[] = $fila; } }
    $stmt_grid_prod->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Catálogo de <?php echo htmlspecialchars($nombre_pagina_actual); ?> - GuardiaShop">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GUARDIASHOP - <?php echo htmlspecialchars($nombre_pagina_actual); ?></title>
    <link rel="icon" href="img/core-img/logoguardiashop.ico">
    <link rel="stylesheet" href="css/core-styleff.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/carrito.css">
    <link rel="stylesheet" href="assets/css/filtro.css">
    <link rel="stylesheet" href="assets/css/filtros.css">
 <style>/* --- Estilos para el Modal de Producto --- */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.6); /* Black w/ opacity */
    padding-top: 40px; /* Location of the box */
    padding-bottom: 40px;
}

.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 25px;
    border: 1px solid #888;
    width: 85%; /* Default width */
    max-width: 800px; /* Max width */
    border-radius: 8px;
    position: relative;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    animation-name: animatetop;
    animation-duration: 0.4s
}
.productos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 32px;
    justify-items: center;
    align-items: start;
    max-width: 1100px;
    margin: 0 auto;
}

.producto-card {
    width: 100%;
    max-width: 260px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    padding: 18px 14px 20px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: box-shadow 0.2s;
}

.producto-card img {
    width: 100%;
    max-width: 180px;
    max-height: 180px;
    object-fit: contain;
    margin-bottom: 12px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@keyframes animatetop {
    from {top: -300px; opacity: 0}
    to {top: 0; opacity: 1}
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    line-height: 1;
}

.close-btn:hover,
.close-btn:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.modal-body {
    display: flex;
    gap: 25px;
    flex-wrap: wrap; /* Permitir que los elementos se envuelvan en pantallas pequeñas */
}

.modal-carousel {
    flex: 1 1 300px; /* Flex-grow, flex-shrink, flex-basis (ancho base) */
    min-width: 280px; /* Ancho mínimo antes de envolver */
    position: relative; /* Para los controles del carrusel */
}

.modal-img {
    width: 100%;
    height: auto;
    max-height: 450px; /* Altura máxima de la imagen */
    object-fit: contain; /* Mantiene la proporción, se ajusta dentro del contenedor */
    border-radius: 4px;
    border: 1px solid #eee;
}

.carousel-controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    padding: 0 10px;
}

.carousel-controls button {
    background-color: rgba(0, 0, 0, 0.4);
    color: white;
    border: none;
    padding: 8px 12px;
    font-size: 20px;
    cursor: pointer;
    border-radius: 50%;
    line-height: 1;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.carousel-controls button:hover {
    opacity: 1;
}

.modal-details {
    flex: 1 1 320px; /* Crecerá y se encogerá, con un ancho base */
    display: flex;
    flex-direction: column;
    gap: 12px; /* Espacio entre elementos de detalle */
}

.modal-details h2 {
    font-size: 1.8rem; /* Ajusta según tu diseño */
    margin-top: 0;
    margin-bottom: 5px;
    color: #333;
}

.modal-details p#modal-description {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #555;
    max-height: 120px; /* Limitar altura y permitir scroll si es mucho texto */
    overflow-y: auto;
    margin-bottom: 10px;
}

.modal-details .modal-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #87572b; /* Tu color de marca */
    margin-bottom: 10px;
}

.modal-details label {
    font-weight: bold;
    margin-bottom: 3px;
    display: block;
    font-size: 0.9rem;
    color: #444;
}

.modal-details select#modal-size {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
    width: 100%;
    max-width: 150px; /* Ocupar menos espacio si es necesario */
    font-size: 0.95rem;
}

.modal-colors .color-picks { /* Hereda de tu filtro, pero podemos ajustar */
    gap: 8px; /* Espacio entre swatches */
    margin-bottom: 10px;
}
.modal-colors .color-swatch { /* Hereda de tu filtro */
    width: 28px;
    height: 28px;
    cursor: pointer;
}
.modal-colors .color-swatch.active { /* Estado activo para el color seleccionado en el modal */
    border: 3px solid #555; /* Borde más grueso o diferente color */
    transform: scale(1.1);
}


.btn-add-cart { /* Hereda de .btn-primary o define estilos específicos */
    background-color:rgb(32, 102, 48); /* Verde */
    color: white;
    padding: 12px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: auto; /* Empuja el botón hacia abajo si hay espacio */
}

.btn-add-cart:hover {
    background-color:rgb(26, 88, 39);
}

body {
    font-size: 1.3em; /* Aumenta el tamaño base de todo el texto */
}

.profile-menu li a {
    padding: 16px 20px;
}
.h3 {
    font-size: 1em;
}
/* Media Query para pantallas más pequeñas (ajusta el breakpoint según necesites) */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        padding: 20px;
        margin-top: 20px; /* Menos padding superior en móviles */
        margin-bottom: 20px;
    }
    .modal-body {
        flex-direction: column; /* Apilar carrusel y detalles verticalmente */
    }
    .modal-carousel, .modal-details {
        flex-basis: auto; /* Dejar que tomen el ancho completo */
        min-width: 0; /* Resetear min-width */
    }
    .modal-details h2 {
        font-size: 1.5rem;
    }
    .modal-details .modal-price {
        font-size: 1.3rem;
    }
     .modal-details select#modal-size {
        max-width: 100%;
    }
}
@media (max-width: 600px) {
    .productos-grid {
        grid-template-columns: 2fr 2fr;
        gap: 18px;
    }
    .producto-card {
        max-width: 98vw;
        padding: 10px 4px 14px 4px;
    }
    .producto-card img {
        max-width: 200px;
        max-height: 260px;
    }
}</style>
</head>
<body>
    <?php include './arc/nav.php'; ?>
    
    <main class="main-content">
        <h5 class="page-title"><?php echo strtoupper(htmlspecialchars($nombre_pagina_actual)); ?></h5>

        <form id="filterForm" method="GET" action="<?php echo htmlspecialchars($nombre_archivo_actual); ?>">
            <?php 
                if ($id_color_filtro !== null) echo '<input type="hidden" name="id_color_f" value="'.htmlspecialchars($id_color_filtro).'">';
                // Si hay un filtro de sesión en la URL, lo mantenemos en el form para cuando el precio cambie
                if ($id_sesion_filtro !== null) echo '<input type="hidden" name="sesion" value="'.htmlspecialchars($id_sesion_filtro).'">';
            ?>
            <div class="filter-horizontal">
                <details id="detailsFiltroRopa" class="filtro-ropa">
    <summary>Ropa</summary>
    <div class="filtro-ropa-opciones">
        <a href="shop.php" class="filtro-ropa-btn <?php if ($nombre_archivo_actual == 'shop.php' && $id_categoria_actual_page === null ) echo 'active'; ?>">Todos</a>
        <a href="blusas.php" class="filtro-ropa-btn <?php if ($nombre_archivo_actual == 'blusas.php') echo 'active'; ?>">Blusas</a>
        <a href="camisetas.php" class="filtro-ropa-btn <?php if ($nombre_archivo_actual == 'camisetas.php') echo 'active'; ?>">Camisetas</a>
        <a href="short.php" class="filtro-ropa-btn <?php if ($nombre_archivo_actual == 'short.php') echo 'active'; ?>">Shorts</a>
        <a href="gorras.php" class="filtro-ropa-btn <?php if ($nombre_archivo_actual == 'gorras.php') echo 'active'; ?>">Gorras</a>
    </div>
</details>
                
               <details id="detailsFiltroGenero" class="filtro-ropa">
    <summary>Para Quién</summary>
    <div class="filtro-ropa-opciones">
        <?php 
            $params_no_sesion = $_GET; unset($params_no_sesion['sesion']);
            $url_todos_sesion = htmlspecialchars($nombre_archivo_actual) . (empty($params_no_sesion) ? '' : '?'.http_build_query($params_no_sesion));
        ?>
        <a href="<?php echo $url_todos_sesion; ?>" class="filtro-ropa-btn <?php echo ($id_sesion_filtro === null) ? 'active' : ''; ?>">Todos</a>
        <a href="<?php echo htmlspecialchars($nombre_archivo_actual) . '?' . http_build_query(array_merge($params_no_sesion, ['sesion' => '1']));?>" class="filtro-ropa-btn <?php echo ($id_sesion_filtro === 1) ? 'active' : ''; ?>">Hombre</a>
        <a href="<?php echo htmlspecialchars($nombre_archivo_actual) . '?' . http_build_query(array_merge($params_no_sesion, ['sesion' => '2']));?>" class="filtro-ropa-btn <?php echo ($id_sesion_filtro === 2) ? 'active' : ''; ?>">Mujer</a>
    </div>
</details>
<details id="detailsFiltroPrecio">
    <summary>Precio</summary>
    <div class="price-filter" style="display: flex; align-items: center; gap: 12px;">
        <?php
            $paramsSinPrecio = $_GET; unset($paramsSinPrecio['precio_sel']);
            $urlTodosPrecios = htmlspecialchars($nombre_archivo_actual) . '?' . http_build_query($paramsSinPrecio);
        ?>
<a href="<?php echo $urlTodosPrecios; ?>" class="filtro-ropa-btn <?php if($precio_seleccionado_filtro === null) echo 'active'; ?>" style="margin-right: 10px;">Ver Todos</a>        <input type="range" name="precio_sel" min="<?php echo $precio_min_db_slider; ?>" max="<?php echo $precio_max_db_slider; ?>" 
               value="<?php echo $precio_seleccionado_filtro ?? $precio_max_db_slider; ?>" step="1000" id="priceRange" style="flex:1;">
        <span style="margin-left:10px;">
            Rango: <span id="priceValueDisplay"><?php 
                if ($precio_seleccionado_filtro !== null) {
                    echo '$' . number_format(floor($precio_seleccionado_filtro / 10000) * 10000, 0, ',', '.') . " - $" . number_format( (floor($precio_seleccionado_filtro / 10000) * 10000) + 9999 , 0, ',', '.');
                } else {
                    echo "Todos"; 
                }
            ?></span>
        </span>
    </div>
</details>

<details id="detailsFiltroColor">
    <summary>Color</summary>
    <div class="filter-color-options color-picks" style="display: flex; align-items: center; gap: 12px;">
        <?php
            $paramsSinColor = $_GET; unset($paramsSinColor['id_color_f']);
            $urlTodosColores = htmlspecialchars($nombre_archivo_actual) . '?' . http_build_query($paramsSinColor);
        ?>
<a href="<?php echo $urlTodosColores; ?>" class="filtro-ropa-btn <?php if ($id_color_filtro === null) echo 'active'; ?>" style="margin-right: 10px;">Todos</a>        <?php foreach ($colores_para_filtro_ui as $color_f): ?>
            <?php
                $paramsColorActual = $_GET; $paramsColorActual['id_color_f'] = $color_f['id_color'];
                $urlColor = htmlspecialchars($nombre_archivo_actual) . '?' . http_build_query($paramsColorActual);
            ?>
            <a href="<?php echo $urlColor; ?>" 
               class="color-swatch <?php if ($id_color_filtro == $color_f['id_color']) echo 'active-filter-color'; ?>" 
               style="background-color: <?php echo htmlspecialchars($color_f['codigo_hexadecimal']); ?>;"
               title="<?php echo htmlspecialchars($color_f['nombre']); ?>"></a>
        <?php endforeach; ?>
    </div>
</details>
            </div>
        </form>

        <section class="productos-populares"> 
            <div class="container">
                <div class="productos-grid">
                    <?php if (!empty($productos_para_mostrar)): ?>
                        <?php foreach ($productos_para_mostrar as $producto_grid): ?>
                            <div class="producto-card">
                                <img src="<?php echo htmlspecialchars($producto_grid['ruta_imagen_principal']); ?>" alt="<?php echo htmlspecialchars($producto_grid['nombre_producto']); ?>">
                                <h3><?php echo htmlspecialchars($producto_grid['nombre_producto']); ?></h3>
                                <p class="precio">Desde: <?php echo !empty($producto_grid['precio_desde']) ? '$' . number_format($producto_grid['precio_desde'], 0, ',', '.') : 'Consultar'; ?></p>
                                <a href="javascript:void(0)" class="btn-primary"
                                   onclick="abrirModalProductoDinamico(<?php echo $producto_grid['id_producto']; ?>)">
                                    Ver más
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay <?php echo strtolower(htmlspecialchars($nombre_pagina_actual)); ?> que coincidan con los filtros seleccionados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
<div id="product-modal" class="modal"><div class="modal-content"><span class="close-btn" onclick="closeModal()">×</span><div class="modal-body"><div class="modal-carousel"><img id="carousel-img" src="assets/images/placeholder.png" alt="Producto" class="modal-img"><div class="carousel-controls"><button onclick="prevImageModal()">‹</button><button onclick="nextImageModal()">›</button></div></div><div class="modal-details"><h2 id="modal-title">Nombre del Producto</h2><p id="modal-description">Descripción del producto aquí.</p><p class="modal-price" id="modal-price">$0</p><div class="modal-colors"> <label>Color:</label> <div id="modal-color-options" class="color-picks"></div> </div><label for="modal-size">Talla:</label> <select id="modal-size"></select><button onclick="agregarAlCarritoDesdeModal()" class="btn-add-cart">Agregar al carrito</button></div></div></div></div>

    <script src="assets/js/carrito.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
// --- JavaScript del Modal Mejorado y Corregido ---
let datosProductoModalActual = null;
let todasLasImagenesDelProducto = [];
let imagenesCarruselActual = [];
let indiceImagenActualCarrusel = 0;

function formatearPrecio(numero) {
    return '$' + Number(numero).toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

async function abrirModalProductoDinamico(idProducto) {
    try {
        const nombreArchivoPhp = '<?php echo $nombre_archivo_actual; ?>';
        const respuesta = await fetch(`${nombreArchivoPhp}?accion=obtener_detalles_producto&id_producto=${idProducto}`);
        if (!respuesta.ok) {
            const errorText = await respuesta.text();
            console.error(`Error HTTP: ${respuesta.status} - ${errorText}`);
            alert(`Error al cargar detalles del producto.`);
            return;
        }
        datosProductoModalActual = await respuesta.json();
        if (datosProductoModalActual.error) {
            alert("Error del servidor al obtener detalles: " + datosProductoModalActual.error);
            return;
        }

        document.getElementById("modal-title").textContent = datosProductoModalActual.producto_base.nombre;
        document.getElementById("modal-description").textContent = datosProductoModalActual.producto_base.descripcion;
        
        const contenedorColores = document.getElementById("modal-color-options");
        contenedorColores.innerHTML = "";
        document.getElementById("modal-size").innerHTML = "";

        todasLasImagenesDelProducto = [];
        if (datosProductoModalActual.producto_base.imagenes_generales && datosProductoModalActual.producto_base.imagenes_generales.length > 0) {
            datosProductoModalActual.producto_base.imagenes_generales.forEach(dataImg => {
                todasLasImagenesDelProducto.push({ 
                    src: (typeof dataImg === 'string' ? dataImg : dataImg.src), 
                    idColorAsociado: (typeof dataImg === 'string' ? null : dataImg.idColorAsociado) 
                });
            });
        }

        if (datosProductoModalActual.colores_disponibles && datosProductoModalActual.colores_disponibles.length > 0) {
            datosProductoModalActual.colores_disponibles.forEach((colorInfo) => {
                const swatch = document.createElement("span");
                swatch.className = "color-swatch";
                swatch.style.backgroundColor = colorInfo.codigo_hex;
                swatch.dataset.idColor = colorInfo.id_color;
                swatch.title = colorInfo.nombre_color;
                swatch.onclick = () => seleccionarColorEnModal(colorInfo.id_color);
                contenedorColores.appendChild(swatch);

                if (colorInfo.imagenes_del_color && colorInfo.imagenes_del_color.length > 0) {
                    colorInfo.imagenes_del_color.forEach(dataImg => {
                        let imgSrc = typeof dataImg === 'string' ? dataImg : dataImg.src;
                        if (!todasLasImagenesDelProducto.find(img => img.src === imgSrc)) {
                            todasLasImagenesDelProducto.push({ 
                                src: imgSrc, 
                                idColorAsociado: (typeof dataImg === 'string' ? colorInfo.id_color : dataImg.idColorAsociado || colorInfo.id_color)
                            });
                        }
                    });
                }
            });
        }
        
        if (todasLasImagenesDelProducto.length === 0) {
            todasLasImagenesDelProducto.push({ src: 'assets/images/placeholder.png', idColorAsociado: null });
        }

        if (datosProductoModalActual.colores_disponibles && datosProductoModalActual.colores_disponibles.length > 0) {
            seleccionarColorEnModal(datosProductoModalActual.colores_disponibles[0].id_color);
        } else {
            imagenesCarruselActual = [...todasLasImagenesDelProducto];
            indiceImagenActualCarrusel = 0;
            actualizarImagenYControlesCarrusel();
            if (datosProductoModalActual.variantes_sin_color_especifico && datosProductoModalActual.variantes_sin_color_especifico.length > 0) {
                 poblarTallasModal(datosProductoModalActual.variantes_sin_color_especifico);
            } else {
                 poblarTallasModal([]);
                 contenedorColores.innerHTML = "<small>No hay opciones de color</small>";
                 document.getElementById("modal-price").textContent = "Consultar";
            }
        }
        document.getElementById("product-modal").style.display = "block";
    } catch (error) {
        console.error("Error JS en abrirModalProductoDinamico:", error);
        alert("No se pudieron cargar los detalles del producto.");
    }
}

function seleccionarColorEnModal(idColorSeleccionado) {
    if (!datosProductoModalActual) return;

    document.querySelectorAll('#modal-color-options .color-swatch').forEach(s => s.classList.remove('active'));
    const swatchActivo = document.querySelector(`#modal-color-options .color-swatch[data-id-color='${idColorSeleccionado}']`);
    if (swatchActivo) swatchActivo.classList.add('active');

    imagenesCarruselActual = todasLasImagenesDelProducto.filter(img => img.idColorAsociado == idColorSeleccionado);
    if (imagenesCarruselActual.length === 0) {
        imagenesCarruselActual = todasLasImagenesDelProducto.filter(img => img.idColorAsociado == null);
    }
    if (imagenesCarruselActual.length === 0 && todasLasImagenesDelProducto.length > 0) {
         imagenesCarruselActual = [todasLasImagenesDelProducto[0]]; // fallback a la primera imagen general si todo falla
    }
     if (imagenesCarruselActual.length === 0) {
        imagenesCarruselActual.push({ src: 'assets/images/placeholder.png', idColorAsociado: null });
    }

    indiceImagenActualCarrusel = 0;
    actualizarImagenYControlesCarrusel();

    const colorData = datosProductoModalActual.colores_disponibles ? datosProductoModalActual.colores_disponibles.find(c => c.id_color == idColorSeleccionado) : null;
    if (colorData) {
        poblarTallasModal(colorData.tallas_en_este_color);
    } else if (datosProductoModalActual.variantes_sin_color_especifico && datosProductoModalActual.variantes_sin_color_especifico.length > 0) {
        poblarTallasModal(datosProductoModalActual.variantes_sin_color_especifico);
    } else {
        poblarTallasModal([]);
    }
}

function actualizarImagenYControlesCarrusel() {
    const imgTag = document.getElementById("carousel-img");
    if (imagenesCarruselActual.length > 0 && imagenesCarruselActual[indiceImagenActualCarrusel]) {
        imgTag.src = imagenesCarruselActual[indiceImagenActualCarrusel].src;
        
        const idColorDeImagenActual = imagenesCarruselActual[indiceImagenActualCarrusel].idColorAsociado;
        document.querySelectorAll('#modal-color-options .color-swatch').forEach(s => s.classList.remove('active'));
        if (idColorDeImagenActual) {
            const swatchParaActivar = document.querySelector(`#modal-color-options .color-swatch[data-id-color='${idColorDeImagenActual}']`);
            if (swatchParaActivar) {
                 swatchParaActivar.classList.add('active');
                 const colorData = datosProductoModalActual.colores_disponibles.find(c => c.id_color == idColorDeImagenActual);
                 if (colorData) poblarTallasModal(colorData.tallas_en_este_color);
            }
        } else {
            // Si la imagen actual no tiene color asociado (es general),
            // podríamos deseleccionar todos los swatches o mantener el último seleccionado.
            // Por ahora, si no hay color asociado a la imagen, no se fuerza un swatch.
            // Si se quiere mantener el último swatch activo, se omite el desmarcado general y la re-activación.
            // O se podría buscar el primer color que use esta imagen general como fallback.
        }

    } else {
        imgTag.src = 'assets/images/placeholder.png';
    }
    const controles = document.querySelector('.modal-carousel .carousel-controls');
    if (controles) {
        controles.style.display = imagenesCarruselActual.length > 1 ? 'flex' : 'none';
    }
}

function prevImageModal() {
    if (imagenesCarruselActual.length <= 1) return;
    indiceImagenActualCarrusel = (indiceImagenActualCarrusel - 1 + imagenesCarruselActual.length) % imagenesCarruselActual.length;
    actualizarImagenYControlesCarrusel();
}

function nextImageModal() {
    if (imagenesCarruselActual.length <= 1) return;
    indiceImagenActualCarrusel = (indiceImagenActualCarrusel + 1) % imagenesCarruselActual.length;
    actualizarImagenYControlesCarrusel();
}

function poblarTallasModal(tallasDisponiblesArray) {
    const selectTalla = document.getElementById("modal-size");
    selectTalla.innerHTML = "";
    if (tallasDisponiblesArray && tallasDisponiblesArray.length > 0) {
        tallasDisponiblesArray.forEach(tallaInfo => {
            const option = document.createElement("option");
            option.value = tallaInfo.id_detalles_productos;
            option.textContent = tallaInfo.nombre_talla;
            option.dataset.precio = tallaInfo.precio_producto;
            selectTalla.appendChild(option);
        });
        if(selectTalla.options.length > 0 && selectTalla.options[0].dataset.precio){
             document.getElementById("modal-price").textContent = formatearPrecio(selectTalla.options[0].dataset.precio);
        } else {
             document.getElementById("modal-price").textContent = "Consultar";
        }
        selectTalla.disabled = false;
    } else {
        const option = document.createElement("option"); option.value = ""; option.textContent = "No hay tallas"; selectTalla.appendChild(option);
        document.getElementById("modal-price").textContent = "Consultar"; selectTalla.disabled = true;
    }
    selectTalla.onchange = function() {
        const opcionSeleccionada = this.options[this.selectedIndex];
        if (opcionSeleccionada && opcionSeleccionada.dataset.precio) {
            document.getElementById("modal-price").textContent = formatearPrecio(opcionSeleccionada.dataset.precio);
        }
    };
}

function closeModal() {
    document.getElementById("product-modal").style.display = "none";
    datosProductoModalActual = null;
}

window.addEventListener('click', function(event) {
    const modal = document.getElementById('product-modal');
    if (modal && modal.style.display === "block" && event.target == modal) {
        closeModal();
    }
});

function agregarAlCarritoDesdeModal() {
    if (!datosProductoModalActual) {alert("Error: No hay datos del producto para agregar."); return;}
    const selectTalla = document.getElementById("modal-size");
    const idDetalleProductoSeleccionado = selectTalla.value;
    if (!idDetalleProductoSeleccionado) {alert("Por favor, selecciona una talla."); return;}
    const opcionTallaSeleccionada = selectTalla.options[selectTalla.selectedIndex];
    const precioSeleccionado = parseFloat(opcionTallaSeleccionada.dataset.precio);
    const nombreTallaSeleccionada = opcionTallaSeleccionada.textContent;
    let nombreProductoCompleto = datosProductoModalActual.producto_base.nombre;
    const colorActivo = document.querySelector('#modal-color-options .color-swatch.active');
    let colorSeleccionadoNombre = '';
    if (colorActivo && colorActivo.title) {
        nombreProductoCompleto += ` - ${colorActivo.title}`;
        colorSeleccionadoNombre = colorActivo.title;
    }
    if (nombreTallaSeleccionada && nombreTallaSeleccionada !== "No hay tallas" && nombreTallaSeleccionada !== "Única") {
        nombreProductoCompleto += ` - Talla ${nombreTallaSeleccionada}`;
    } else if (nombreTallaSeleccionada === "Única") {
        nombreProductoCompleto += ` - Talla Única`;
    }
    const imageUrl = document.getElementById("carousel-img").src;
    if (typeof addToCart === "function") {
        addToCart(
            nombreProductoCompleto,
            precioSeleccionado,
            idDetalleProductoSeleccionado,
            imageUrl,
            nombreTallaSeleccionada,
            colorSeleccionadoNombre,
            datosProductoModalActual.producto_base.id_producto
        );
        closeModal();
    } else {
        console.error("La función addToCart no está definida globalmente o no se cargó desde carrito.js");
        alert("Error al agregar al carrito. La funcionalidad no está disponible.");
    }
}       // --- JavaScript para Filtros y estado de <details> ---
        const filterForm = document.getElementById('filterForm');
        const priceRangeInput = document.getElementById('priceRange');
        const priceValueSpan = document.getElementById('priceValueDisplay');
        
        const detailsPrecio = document.getElementById('detailsFiltroPrecio');
        const detailsColor = document.getElementById('detailsFiltroColor');
        const detailsGenero = document.getElementById('detailsFiltroGenero'); // Podría no existir en todas las páginas

        const pageKeyPart = '<?php echo $nombre_archivo_actual; ?>'; // e.g., 'shop.php'
        const precioOpenKey = `${pageKeyPart}_precio_open`;
        const colorOpenKey = `${pageKeyPart}_color_open`;
        const generoOpenKey = `${pageKeyPart}_genero_open`;

        document.addEventListener('DOMContentLoaded', function() {
            // Restaurar estado 'open' de los <details> desde sessionStorage
            if (sessionStorage.getItem(precioOpenKey) === 'true' && detailsPrecio) {
                detailsPrecio.open = true;
            }
            if (sessionStorage.getItem(colorOpenKey) === 'true' && detailsColor) {
                detailsColor.open = true;
            }
            if (detailsGenero && sessionStorage.getItem(generoOpenKey) === 'true') {
                detailsGenero.open = true;
            }

            // Guardar estado cuando se hace clic en <summary>
            if (detailsPrecio) {
                detailsPrecio.addEventListener('toggle', function() {
                    sessionStorage.setItem(precioOpenKey, detailsPrecio.open);
                });
            }
            if (detailsColor) {
                detailsColor.addEventListener('toggle', function() {
                    sessionStorage.setItem(colorOpenKey, detailsColor.open);
                });
            }
            if (detailsGenero) {
                detailsGenero.addEventListener('toggle', function() {
                    sessionStorage.setItem(generoOpenKey, detailsGenero.open);
                });
            }
            
            // Slider de precio
            if (priceRangeInput && priceValueSpan && filterForm) {
                priceRangeInput.addEventListener('input', function() {
                    let val = parseInt(this.value);
                    let minRange = Math.floor(val / 10000) * 10000;
                    let maxRange = minRange + 9999;
                    let minDb = parseInt(this.min);
                    let maxDb = parseInt(this.max);
                    if (maxRange > maxDb) maxRange = maxDb;
                    if (minRange < minDb && val >= minDb) minRange = minDb;
                    if (val < minDb) {
                        minRange = minDb;
                        maxRange = minDb + 9999;
                        if (maxRange > maxDb) maxRange = maxDb;
                    }
                    priceValueSpan.textContent = formatearPrecio(minRange) + " - " + formatearPrecio(maxRange);
                });
                priceRangeInput.addEventListener('change', function() {
                    // Antes de enviar, asegurarse de que los filtros de sesión y color (si están en URL) se incluyan
                    // Los inputs hidden ya lo hacen. Simplemente hacemos submit.
                    filterForm.submit(); 
                });
            }
        });
        
        function toggleUserMenu() { const dropdown = document.getElementById('user-dropdown'); if(dropdown) dropdown.classList.toggle('hidden'); }
        document.addEventListener('click', function(event) { const dropdown = document.getElementById('user-dropdown'); const menu = document.querySelector('.user-menu'); if (menu && dropdown && !menu.contains(event.target)) { dropdown.classList.add('hidden'); } });
    </script>

    <?php include './arc/footer.php';?>
</body>
</html>