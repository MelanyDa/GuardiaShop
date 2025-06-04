<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta para la tabla principal
$sql = "
SELECT 
    dc.id_detalle_compra,
    dc.id_compra,
    dc.id_detalles_productos,
    dc.cantidad_comprada,
    dc.costo_unitario,
    c.fecha_compra,
    c.Número_de_factura,
    c.estado_compra,
    p.nombre AS nombre_producto,
    pi.imagen AS imagen_variante,
    t.nombre_talla,
    col.nombre AS nombre_color,
    col.codigo_hexadecimal,
    pr.nombre_empresa AS nombre_proveedor
FROM detalles_compra dc
JOIN compras c ON dc.id_compra = c.id_compra
JOIN detalles_productos dp ON dc.id_detalles_productos = dp.id_detalles_productos
JOIN productos p ON dp.id_producto = p.id_producto
LEFT JOIN producto_imagen pi ON pi.id_producto = p.id_producto AND pi.id_color_asociado = dp.id_color
JOIN talla_productos t ON dp.id_tallas = t.id_talla
JOIN color_productos col ON dp.id_color = col.id_color
JOIN proveedores pr ON c.id_proveedor = pr.id_proveedor
ORDER BY c.fecha_compra DESC
";
$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error en la consulta SQL: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gestionar Compras</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .table thead th {
            background: #2c4926;
            color: #fff;
            vertical-align: middle;
        }
        .table td, .table th {
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
        .modal-header {
            background-color: #2c4926;
            color: white;
        }
        .modal-content {
            border-radius: 1rem;
        }
        .btn-ver {
            color: #2c4926;
            background: #e9ecef;
            border-radius: 50%;
            border: none;
            font-size: 1.2rem;
            padding: 0.4rem 0.6rem;
            transition: background 0.2s;
        }
        .btn-ver:hover {
            background: #2c4926;
            color: #fff;
        }
        .img-modal-compra {
            max-width: 140px;
            max-height: 140px;
            border-radius: 0.7rem;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(44, 73, 38, 0.13);
            margin-bottom: 1rem;
        }
        .modal-body .info-label {
            font-weight: 600;
            color: #2c4926;
        }
        .modal-body .info-value {
            font-weight: 400;
            color: #333;
        }
        .modal-body .badge-color {
            background: #f5f5f5;
            color: #222;
            border: 1px solid #ccc;
            font-size: 1rem;
            padding: 0.4em 0.8em;
            border-radius: 1em;
        }
        .modal-body .badge-talla {
            background: #e9ecef;
            color: #2c4926;
            font-size: 1rem;
            padding: 0.4em 0.8em;
            border-radius: 1em;
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
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                        <h1 class="h3"><i class="fas fa-clipboard-list me-2"></i>Gestionar compras</h1>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>N° Factura</th>
                                            <th>Proveedor</th>
                                            <th>Producto</th>
                                            <th>Color</th>
                                            <th>Talla</th>
                                            <th>Cantidad</th>
                                            <th>Costo unitario</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $i = 0; 
                                    $modales = [];
                                    while($row = $resultado->fetch_assoc()): 
                                        $i++; 
                                    ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_compra'])); ?></td>
                                            <td><?php echo htmlspecialchars($row['Número_de_factura']); ?></td>
                                            <td><?php echo htmlspecialchars($row['nombre_proveedor']); ?></td>
                                            <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                            <td>
                                                <span class="color-swatch-modal" style="background:<?php echo htmlspecialchars($row['codigo_hexadecimal']); ?>;" title="<?php echo htmlspecialchars($row['nombre_color']); ?>"></span>
                                                <?php echo htmlspecialchars($row['nombre_color']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['nombre_talla']); ?></td>
                                            <td><?php echo htmlspecialchars($row['cantidad_comprada']); ?></td>
                                            <td>$<?php echo number_format($row['costo_unitario'], 0, ',', '.'); ?></td>
                                            <td>
                                                <span class="badge bg-success"><?php echo htmlspecialchars($row['estado_compra']); ?></span>
                                            </td>
                                            <td class="acciones-btns" style="display:flex;gap:0.5rem;justify-content:center;">
                                                <button class="btn btn-ver" data-bs-toggle="modal" data-bs-target="#modalCompra<?php echo $i; ?>" title="Ver Detalle">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a class="btn btn-danger btn-sm" href="factura_c.php?factura=<?php echo urlencode($row['Número_de_factura']); ?>" target="_blank" title="Ver PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                        // Guarda los datos del modal para imprimirlos después
                                        $modales[] = ['i' => $i, 'row' => $row]; 
                                        ?>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                                <?php if (!empty($modales)): foreach($modales as $modal): $row = $modal['row']; $i = $modal['i']; ?>
                                <!-- Modal Detalle Compra -->
                                <div class="modal fade" id="modalCompra<?php echo $i; ?>" tabindex="-1" aria-labelledby="modalCompraLabel<?php echo $i; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCompraLabel<?php echo $i; ?>">Detalle de la compra</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row justify-content-center mb-3">
                                                    <div class="col-12 text-center">
                                                        <?php if ($row['imagen_variante']): ?>
                                                            <img src="<?php echo htmlspecialchars($row['imagen_variante']); ?>" class="img-modal-compra" alt="Imagen variante">
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin imagen</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-12">
                                                        <table class="table table-bordered table-striped mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="width:40%;">Producto</th>
                                                                    <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Proveedor</th>
                                                                    <td><?php echo htmlspecialchars($row['nombre_proveedor']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_compra'])); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>N° Factura</th>
                                                                    <td><?php echo htmlspecialchars($row['Número_de_factura']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Color</th>
                                                                    <td>
                                                                        <span class="color-swatch-modal" style="background:<?php echo htmlspecialchars($row['codigo_hexadecimal']); ?>;margin-right:6px;"></span>
                                                                        <?php echo htmlspecialchars($row['nombre_color']); ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Talla</th>
                                                                    <td><?php echo htmlspecialchars($row['nombre_talla']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Cantidad</th>
                                                                    <td><?php echo htmlspecialchars($row['cantidad_comprada']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Costo unitario</th>
                                                                    <td>$<?php echo number_format($row['costo_unitario'], 0, ',', '.'); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Estado</th>
                                                                    <td><span class="badge bg-success"><?php echo htmlspecialchars($row['estado_compra']); ?></span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>