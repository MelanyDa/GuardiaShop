<?php
/*
session_start();
if (!isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'super_admin', 'vendedor'])) {
    header('Location: ../login.php');
    exit();
}
*/
?>
<?php

$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener categorías
$categorias = [];
$cat_result = $conexion->query("SELECT id_categoria, nombre FROM categoria");
while ($row = $cat_result->fetch_assoc()) {
    $categorias[] = $row;
}

// Obtener sesiones
$sesiones = [];
$ses_result = $conexion->query("SELECT id_sesion, nombre FROM sesiones");
while ($row = $ses_result->fetch_assoc()) {
    $sesiones[] = $row;
}
// Obtener colores
$colores = [];
$color_result = $conexion->query("SELECT id_color, nombre FROM color_productos");
while ($row = $color_result->fetch_assoc()) {
    $colores[] = $row;
}
// Obtener tallas
$tallas = [];
$tallas_result = $conexion->query("SELECT id_talla, nombre_talla FROM talla_productos");
while ($row = $tallas_result->fetch_assoc()) {
    $tallas[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Gestionar Productos</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .productos-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            margin-top: 2rem;
        }
        .productos-header h1 {
            color: #2c4926;
            font-weight: bold;
            margin-bottom: 0;
        }
        .btn-agregar-producto {
            font-weight: bold;
            background: #2c4926;
            color: #fff;
            border-radius: 2rem;
            box-shadow: 0 2px 8px rgba(44, 73, 38, 0.13);
            transition: background 0.2s;
        }
        .btn-agregar-producto:hover {
            background: #25601d;
            color: #fff;
        }
        .card-producto {
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(44, 73, 38, 0.08);
            transition: transform 0.15s;
            border: none;
            background: #fff;
            margin-bottom: 2rem;
        }
        .card-producto:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 24px rgba(44, 73, 38, 0.13);
        }
        .card-img-top {
            border-radius: 1rem 1rem 0 0;
            height: 220px;
            object-fit: cover;
        }
        .badge-custom {
            font-size: 0.85rem;
            margin-right: 0.3rem;
        }
        .card-title {
            color: #2c4926;
            font-weight: bold;
        }
        .card-text {
            font-size: 0.97rem;
        }
        .modal-header, .modal-footer {
            border-radius: 1rem 1rem 0 0;
        }
        .modal-content {
            border-radius: 1rem;
        }
        .detalle-lista {
            font-size: 0.97rem;
            padding-left: 1.2rem;
        }
        .btn-outline-success:hover, .btn-outline-success:focus {
            background-color: #2c4926 !important;
            color: #fff !important;
            border-color: #2c4926 !important;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/menu.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/nav.php'); ?>

                <div class="container-fluid">
                    <div class="productos-header">
                        <h1 class="h3"><i class="fas fa-tshirt me-2"></i>Gestionar productos</h1>
                        <button type="button" class="btn btn-agregar-producto shadow" data-bs-toggle="modal" data-bs-target="#agregarProductoModal">
                            <i class="fas fa-plus me-1"></i>Agregar Producto
                        </button>
                    </div>
                 <style>
                   #agregarProductoModal .modal-dialog {
    margin-left: 25vw; /* Ajusta el valor según lo que necesites */
}
                 </style>
                    <!-- Modal para agregar producto -->
                    <div class="modal fade" id="agregarProductoModal" tabindex="-1" aria-labelledby="agregarProductoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <form action="acciones/productos/agregar_producto.php" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="agregarProductoLabel">Agregar Producto</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Código</label>
                                            <input type="text" name="codigo" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Descripción</label>
                                            <textarea name="descripcion" class="form-control" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label>Categoría</label>
                                            <select name="id_categoria" class="form-select" required>
                                                <?php foreach ($categorias as $cat): ?>
                                                    <option value="<?php echo $cat['id_categoria']; ?>">
                                                        <?php echo $cat['nombre']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Sesión</label>
                                            <select name="id_sesion" class="form-select" required>
                                                <?php foreach ($sesiones as $ses): ?>
                                                    <option value="<?php echo $ses['id_sesion']; ?>">
                                                        <?php echo $ses['nombre']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <!-- Combinaciones de Talla, Color, Precio y Stock -->
                                        <div id="combinaciones-container">
                                            <div class="row combinacion-item mb-3">
                                                <div class="col">
                                                    <label>Talla</label>
                                                    <select name="talla[]" class="form-select" required>
                                                        <?php foreach ($tallas as $t): ?>
                                                            <option value="<?php echo $t['id_talla']; ?>"><?php echo $t['nombre_talla']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label>Color</label>
                                                    <select name="color[]" class="form-select" required>
                                                        <?php foreach ($colores as $c): ?>
                                                            <option value="<?php echo $c['id_color']; ?>"><?php echo $c['nombre']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label>Precio</label>
                                                    <input type="number" name="precio[]" class="form-control" required>
                                                </div>
                                                <div class="col">
                                                    <label>Stock</label>
                                                    <input type="number" name="stock[]" class="form-control" required>
                                                </div>
                                                <div class="col-auto d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger remove-combinacion">X</button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" id="add-combinacion" class="btn btn-secondary mb-3">Agregar Combinación</button>
                                        <div class="mb-3">
                                            <label>Imagen del Producto</label>
                                            <input type="file" name="imagen" class="form-control" accept="image/*" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Color asociado a la imagen</label>
                                            <select name="color_imagen" class="form-select" required>
                                                <option value="">Selecciona un color</option>
                                                <?php foreach ($colores as $c): ?>
                                                    <option value="<?php echo $c['id_color']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Agregar Producto</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.getElementById("add-combinacion").addEventListener("click", function () {
                            const container = document.getElementById("combinaciones-container");
                            const item = container.querySelector(".combinacion-item").cloneNode(true);
                            item.querySelectorAll("input").forEach(i => i.value = "");
                            container.appendChild(item);
                        });
                        document.addEventListener("click", function (e) {
                            if (e.target.classList.contains("remove-combinacion")) {
                                const items = document.querySelectorAll(".combinacion-item");
                                if (items.length > 1) {
                                    e.target.closest(".combinacion-item").remove();
                                }
                            }
                        });
                    </script>

                    <!-- Productos en tarjetas -->
                    <div class="row">
                        <?php
                        $sql = "SELECT 
                            p.id_producto AS id,
                            p.codigo,
                            pi.imagen,
                            p.nombre,
                            p.descripcion,
                            s.id_sesion AS id_sesion,
                            s.nombre AS nombre_sesion,
                            cat.id_categoria AS id_categoria,
                            cat.nombre AS nombre_categoria,
                            (
                                SELECT GROUP_CONCAT(DISTINCT cp.nombre SEPARATOR ', ')
                                FROM detalles_productos dp
                                JOIN color_productos cp ON dp.id_color = cp.id_color
                                WHERE dp.id_producto = p.id_producto
                            ) AS colores,
                            (
                                SELECT GROUP_CONCAT(DISTINCT t.nombre_talla SEPARATOR ', ')
                                FROM detalles_productos dp
                                JOIN talla_productos t ON dp.id_tallas = t.id_talla
                                WHERE dp.id_producto = p.id_producto
                            ) AS tallas
                        FROM productos p
                        JOIN sesiones s ON p.id_sesion = s.id_sesion
                        JOIN categoria cat ON p.id_categoria = cat.id_categoria
                        JOIN (
                            SELECT id_producto, MIN(id_imagen) AS imagen
                            FROM producto_imagen
                            GROUP BY id_producto
                        ) img_principal ON img_principal.id_producto = p.id_producto
                        JOIN producto_imagen pi ON pi.id_imagen = img_principal.imagen
                        GROUP BY p.id_producto;";

                        $resultado = $conexion->query($sql);

       while ($row = $resultado->fetch_assoc()) {
                            $id = $row['id'];
                            $id_producto = $row['id'];
                            $detalles_sql = "
                                SELECT 
                                    t.nombre_talla, 
                                    cp.nombre AS color,
                                    dp.precio_producto, 
                                    dp.stock 
                                FROM detalles_productos dp
                                JOIN talla_productos t ON dp.id_tallas = t.id_talla
                                JOIN color_productos cp ON dp.id_color = cp.id_color
                                WHERE dp.id_producto = $id_producto
                            ";
                            $detalles_result = $conexion->query($detalles_sql);
                        ?>
                        
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4"> 
                            <div class="card card-producto h-100">
                                <img src="<?php echo htmlspecialchars($row['imagen']); ?>" class="card-img-top" alt="Imagen del producto">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                                    <p class="card-text mb-2" style="flex-grow: 1;"><?php echo htmlspecialchars($row['descripcion']); ?></p>
                                    <div class="badges-row mb-2">
                                    <!--    <span class="badge badge-custom" title="Categoría">
                                            <i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($row['nombre_categoria']); ?>
                                        </span>
                                        <span class="badge badge-custom" title="Sesión">
                                            <i class="fas fa-layer-group me-1"></i> <?php echo htmlspecialchars($row['nombre_sesion']); ?>
                                        </span>
                                        <span class="badge badge-custom" title="Tallas">
                                            <i class="fas fa-ruler me-1"></i> <?php echo htmlspecialchars($row['tallas']); ?>
                                        </span>
                                        <span class="badge badge-custom" title="Colores">
                                            <i class="fas fa-palette me-1"></i> <?php echo htmlspecialchars($row['colores']); ?>
                                        </span>-->
                                    </div>
                                    <button type="button" class="btn btn-ver-producto mt-auto" data-bs-toggle="modal" data-bs-target="#modalProducto<?php echo $id; ?>">
                                        <i class="fas fa-eye me-1"></i> Ver producto
                                    </button>
                                </div>
                            </div>
                        </div>

                       <style>

                        /* Estilos para el Panel de Gestión de Productos */

.productos-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    margin-top: 2rem;
}
.productos-header h1 {
    color: #2c4926;
    font-weight: bold;
    margin-bottom: 0;
    font-size: 1.75rem; /* Ajuste de tamaño para el título principal */
}
.btn-agregar-producto {
    font-weight: 500; /* Ligeramente menos bold */
    background: #2c4926;
    color: #fff;
    border-radius: 0.5rem; /* Menos redondeado para un look más moderno */
    padding: 0.6rem 1.2rem; /* Ajuste de padding */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: background 0.2s, box-shadow 0.2s;
}
.btn-agregar-producto:hover {
    background: #25601d;
    color: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
.btn-agregar-producto i {
    margin-right: 0.4rem;
}

.card-producto {
    border-radius: 0.75rem; /* Bordes de tarjeta más suaves */
    box-shadow: 0 2px 10px rgba(44, 73, 38, 0.07);
    transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
    border: none;
    background: #fff;
    /* margin-bottom ya está en la clase de columna con mb-4 */
}
.card-producto:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(44, 73, 38, 0.1);
}

.card-img-top {
    border-radius: 0.75rem 0.75rem 0 0;
    height: 190px; /* Altura ajustada para tarjetas más compactas */
    object-fit: cover;
}

.card-body {
    padding: 1rem; /* Espaciado interno consistente */
}

.card-title {
    color: #2c4926;
    font-weight: 600; /* Un poco menos bold que 'bold' */
    font-size: 1.05rem; /* Tamaño ajustado */
    margin-bottom: 0.3rem;
}

.card-text {
    font-size: 0.85rem;
    color: #5a6268; /* Un gris más estándar y suave */
    margin-bottom: 0.8rem;
    line-height: 1.5;
}

.badges-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem 0.6rem; /* Espacio vertical y horizontal entre badges */
    align-items: center;
    margin-bottom: 0.9rem; /* Espacio antes del botón "Ver producto" */
}

.badge-custom {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.75rem; /* Padding más fino */
    font-size: 0.78rem;   /* Tamaño de fuente más pequeño */
    font-weight: 500;
    line-height: 1.2;
    border-radius: 1rem; 
    background-color: #e9ecef; /* Fondo gris claro suave */
    color: #495057;       /* Texto oscuro para contraste */
    border: 1px solid #dee2e6; /* Borde sutil */
}

.badge-custom i {
    margin-right: 0.35em;
    font-size: 0.9em;
    color: #6c757d; /* Color del ícono */
}

/* Estilo para el botón "Ver producto" dentro de la tarjeta */
.btn-ver-producto {
    font-size: 0.85rem;
    padding: 0.5rem 0.9rem;
    border-radius: 0.5rem;
    font-weight: 500;
    background-color: #fff;
    color: #2c4926; /* Color principal de la marca para el texto */
    border: 1px solid #2c4926; /* Borde con color principal */
    transition: all 0.2s ease-in-out;
}
.btn-ver-producto:hover, 
.btn-ver-producto:focus {
    background-color: #2c4926;
    color: #fff;
    border-color: #2c4926;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.btn-ver-producto i {
    margin-right: 0.3rem;
}

/* Estilos para el Modal (puedes tenerlos en otro lado o aquí) */
.modal-header {
    background-color: #2c4926;
    color: white;
    border-bottom: none; /* Quitar borde si no se desea */
    border-radius: 0.75rem 0.75rem 0 0; /* Coincidir con tarjeta */
}
.modal-header .btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%); /* Para que la X sea bien visible */
}
.modal-content {
    border-radius: 0.75rem;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.modal-title {
    font-weight: 600;
}
.detalle-lista {
    font-size: 0.9rem;
    padding-left: 1.2rem;
    list-style-type: disc; /* O el que prefieras */
}
.detalle-lista li {
    margin-bottom: 0.3rem;
}
.modal-body p strong {
    color: #343a40;
}
.modal-footer {
    border-top: 1px solid #e9ecef; /* Borde superior sutil */
}
.modal-footer .btn {
    font-weight: 500;
    border-radius: 0.5rem;
}
    .badge-custom-unicolor {
    background-color: #2c4926 !important; /* Verde oscuro */
    color: #fff !important;
    border-radius: 20px;
    font-size: 0.95rem;
    padding: 0.5em 1.1em;
    margin-right: 0.5em;
    margin-bottom: 0.2em;
    box-shadow: 0 2px 8px rgba(44,73,38,0.08);
    font-weight: 500;
    letter-spacing: 0.02em;
    display: inline-flex;
    align-items: center;
    gap: 0.4em;
    transition: background 0.2s;
}
.badge-custom-unicolor i {
    color: #b6e2c2 !important; /* Un verde más claro para el icono */
}
.badges-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}
.badges-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
    align-items: center;
    min-height: 38px;
}
.badge-custom {
    display: flex;
    align-items: center;
    font-size: 0.93rem;
    border-radius: 1.5rem;
    font-weight: 500;
    padding: 0.45em 1em;
    margin-bottom: 0 !important;
    white-space: nowrap;
    line-height: 1.2;
}
.badge-custom i {
    margin-right: 0.5em;
    font-size: 1em;
    vertical-align: middle;
}/* --- Estilos para el Modal de "Ver Producto" Mejorado --- */
.product-details-modal-body {
    font-size: 0.95rem; /* Un poco más grande para legibilidad */
}

.product-modal-image {
    width: 100%;
    max-height: 400px; /* Ajusta según prefieras */
    object-fit: contain; /* Para que la imagen completa se vea */
    border: 1px solid #e9ecef;
    margin-bottom: 1rem; /* Espacio si los detalles se van abajo en móvil */
}

.product-modal-code {
    font-size: 0.9em;
    color: #6c757d;
    margin-bottom: 1rem;
}

.product-modal-subtitle {
    font-weight: 600;
    color: #343a40;
    margin-top: 0.8rem;
    margin-bottom: 0.5rem;
    font-size: 1.1em;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 0.3rem;
}

.product-modal-description {
    line-height: 1.6;
    color: #495057;
    margin-bottom: 1rem;
}

.product-modal-attributes p {
    margin-bottom: 0.5rem;
    font-size: 0.9em;
}
.product-modal-attributes p strong {
    color: #2c4926; /* Color principal */
}

.product-variants-table {
    margin-top: 0.5rem;
    font-size: 0.88rem; /* Un poco más pequeño para que quepa más info */
}

.product-variants-table th {
    background-color: #f8f9fa;
    font-weight: 500;
    color: #495057;
}

.product-variants-table td {
    vertical-align: middle;
}

.color-swatch-modal {
    display: inline-block;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 1px solid #ccc;
    margin-right: 5px;
    vertical-align: middle;
}

/* Ajustes para el footer del modal */
.modal-footer .btn {
    font-size: 0.9rem; /* Consistencia en tamaño de botones */
}
.modal-footer .btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}
.modal-footer .btn-outline-secondary:hover {
    background-color: #6c757d;
    color: #fff;
}

/* Responsividad del modal */
@media (max-width: 767px) { /* md breakpoint de Bootstrap */
    .product-details-modal-body .row > div[class^="col-md-"] {
        margin-bottom: 1.5rem; /* Espacio entre imagen y detalles en móvil */
    }
    .product-modal-image {
        max-height: 300px; /* Imagen más pequeña en móvil */
    }
}
</style>

                        <!-- Modal del producto -->
                       <!-- Modal del producto -->
<div class="modal fade" id="modalProducto<?php echo $id; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $id; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2c4926; color: white;">
                <h5 class="modal-title" id="modalLabel<?php echo $id; ?>"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body product-details-modal-body">
                <div class="row">
                    <!-- Columna de la Imagen Principal -->
                    <div class="col-md-5 mb-3 mb-md-0">
                        <img src="<?php echo htmlspecialchars($row['imagen']); ?>" class="img-fluid rounded product-modal-image" alt="Imagen de <?php echo htmlspecialchars($row['nombre']); ?>">
                        
                        <!-- Si tienes más imágenes para un carrusel aquí, este sería el lugar -->
                        <!-- Por ahora, solo la imagen principal -->
                    </div>

                    <!-- Columna de Detalles -->
                    <div class="col-md-7">
                        <p class="product-modal-code"><strong>Código:</strong> <?php echo htmlspecialchars($row['codigo']); ?></p>
                        
                        <h6 class="product-modal-subtitle">Descripción:</h6>
                        <p class="product-modal-description"><?php echo nl2br(htmlspecialchars($row['descripcion'])); ?></p>
                        
                        <div class="row product-modal-attributes">
                            <div class="col-sm-6">
                                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($row['nombre_categoria']); ?></p>
                            </div>
                            <div class="col-sm-6">
                                <p><strong>Sesión:</strong> <?php echo htmlspecialchars($row['nombre_sesion']); ?></p>
                            </div>
                        </div>

                        <h6 class="product-modal-subtitle mt-3">Variantes Disponibles:</h6>
                        <?php
                        // Re-ejecutar la consulta de detalles aquí o pasarla si es posible
                        // Por simplicidad, la re-ejecutamos. En un sistema más complejo, evitarías esto.
                        $detalles_variantes_sql = "
                            SELECT 
                                t.nombre_talla, 
                                cp.nombre AS nombre_color,
                                cp.codigo_hexadecimal,
                                dp.precio_producto, 
                                dp.stock 
                            FROM detalles_productos dp
                            JOIN talla_productos t ON dp.id_tallas = t.id_talla
                            JOIN color_productos cp ON dp.id_color = cp.id_color
                            WHERE dp.id_producto = " . intval($id_producto) . "
                            ORDER BY cp.nombre ASC, t.id_talla ASC 
                        "; // Ordenar por color y luego por talla
                        $detalles_variantes_result = $conexion->query($detalles_variantes_sql);
                        ?>
                        <?php if ($detalles_variantes_result && $detalles_variantes_result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover product-variants-table">
                                    <thead>
                                        <tr>
                                            <th>Color</th>
                                            <th>Talla</th>
                                            <th>Precio</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php while ($detalle_v = $detalles_variantes_result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <span class="color-swatch-modal" style="background-color: <?php echo htmlspecialchars($detalle_v['codigo_hexadecimal']); ?>;" title="<?php echo htmlspecialchars($detalle_v['nombre_color']); ?>"></span>
                                                <?php echo htmlspecialchars($detalle_v['nombre_color']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($detalle_v['nombre_talla']); ?></td>
                                            <td>$<?php echo number_format($detalle_v['precio_producto'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($detalle_v['stock']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>No hay detalles de variantes disponibles para este producto.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="editar_producto.php?id=<?php echo $id; ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <button type="button" class="btn btn-danger" onclick="eliminarProducto(<?php echo $id; ?>)">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

                        <!-- Modal para editar producto -->
                        <?php
                        $nombre = $row['nombre'];
                        $descripcion = $row['descripcion'];
                        $categoria_id = $row['id_categoria'];
                        $sesion_id = $row['id_sesion'];
                        ?>
                        
                        <?php } $conexion->close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
function agregarCombinacion(productoId) {
    const container = document.getElementById("editar-combinaciones-container" + productoId);
    const baseItem = container.querySelector(".combinacion-item").cloneNode(true);

    baseItem.querySelectorAll("input").forEach(input => {
        if (input.name === "id_detalle[]") input.remove();
        else input.value = "";
    });
    baseItem.querySelectorAll("select").forEach(select => select.selectedIndex = 0);
    container.appendChild(baseItem);
}
</script>

<script>
   function eliminarProducto(productoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Este producto será eliminado permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '¡Sí, eliminar!',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "acciones/productos/eliminar_producto.php?id=" + productoId + "&success=true";
        }
    });
}
function editarProducto(productoId) {
    window.location.href = "acciones/productos/editar_producto.php?id=" + productoId;
}
</script>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="js/demo/datatables-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<?php if (isset($_GET['edit']) && $_GET['edit'] === 'success'): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Producto actualizado!',
        showConfirmButton: false,
        timer: 1500
    });
</script>
<?php endif; ?>

<?php if (isset($_GET['add']) && $_GET['add'] == 'success'): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Producto agregado!',
        text: 'El producto se ha guardado correctamente.',
        confirmButtonColor: '#2c4926'
    });
</script>
<?php endif; ?>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Producto actualizado!',
            text: 'El producto se ha actualizado correctamente.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '/guardiashop/admin_gs/panel/g_productos.php';
        });
    </script>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al actualizar el producto.',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>



<?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Producto eliminado!',
            text: 'El producto se ha eliminado correctamente.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '/guardiashop/admin_gs/panel/g_productos.php';
        });
    </script>
<?php endif; ?>
</body>
</html>