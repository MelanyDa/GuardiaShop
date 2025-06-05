<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Restringir acceso solo a admin, super_admin o vendedor
if (!isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'super_admin', 'vendedor'])) {
    header('Location: /guardiashop/login/login.php');
    exit();
}
?>
<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Procesar compra AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'comprar_variante') {
    $id_detalles_productos = intval($_POST['id_detalles_productos']);
    $cantidad = intval($_POST['cantidad']);
    $costo_unitario = floatval($_POST['costo_unitario']);

    // Obtener info de la variante y producto
    $stmt = $conexion->prepare("SELECT dp.stock, dp.id_producto, p.nombre, p.id_categoria FROM detalles_productos dp JOIN productos p ON dp.id_producto = p.id_producto WHERE dp.id_detalles_productos = ?");
    $stmt->bind_param("i", $id_detalles_productos);
    $stmt->execute();
    $stmt->bind_result($stock_actual, $id_producto, $nombre_producto, $id_categoria);
    $stmt->fetch();
    $stmt->close();

    // Buscar proveedor según categoría (ajusta si quieres por producto)
    $proveedor = $conexion->query("SELECT id_proveedor FROM proveedores WHERE LOWER(nombre_contacto) LIKE '%" . strtolower($nombre_producto) . "%' OR id_proveedor = $id_categoria LIMIT 1")->fetch_assoc();
    if (!$proveedor) {
        // Si no encuentra por nombre, asigna uno por defecto (el primero)
        $proveedor = $conexion->query("SELECT id_proveedor FROM proveedores LIMIT 1")->fetch_assoc();
    }
    $id_proveedor = $proveedor['id_proveedor'];

    // Crear número de factura único
    $numero_factura = 'COMPRA-' . strtoupper(uniqid());
    $fecha_compra = date('Y-m-d H:i:s');
    $total_compra = $cantidad * $costo_unitario;
    $estado_compra = 'recibida_completa';

    // Insertar en compras
    $stmt = $conexion->prepare("INSERT INTO compras (id_proveedor, fecha_compra, Número_de_factura, total_compra, estado_compra) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $id_proveedor, $fecha_compra, $numero_factura, $total_compra, $estado_compra);
    $stmt->execute();
    $id_compra = $stmt->insert_id;
    $stmt->close();

    // Insertar en detalles_compra
    $stmt = $conexion->prepare("INSERT INTO detalles_compra (id_compra, id_detalles_productos, cantidad_comprada, costo_unitario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $id_compra, $id_detalles_productos, $cantidad, $costo_unitario);
    $stmt->execute();
    $stmt->close();

    // Actualizar stock
    $nuevo_stock = $stock_actual + $cantidad;
    $stmt = $conexion->prepare("UPDATE detalles_productos SET stock = ? WHERE id_detalles_productos = ?");
    $stmt->bind_param("ii", $nuevo_stock, $id_detalles_productos);
    $stmt->execute();
    $stmt->close();

    // Registrar movimiento en inventario
    $stmt = $conexion->prepare("INSERT INTO movimientos_inventario 
        (id_detalles_productos, stock_inicial, tipo_movimiento, cantidad_cambio, stock_resultante, fecha_hora, referencia_origen, costo_unitario_movimiento) 
        VALUES (?, ?, 'Compra', ?, ?, NOW(), ?, ?)");
    $referencia_origen = $numero_factura;
    $stmt->bind_param("iiiisd", $id_detalles_productos, $stock_actual, $cantidad, $nuevo_stock, $referencia_origen, $costo_unitario);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true, 'mensaje' => '¡Compra registrada y stock actualizado!']);
    exit;
}

// Obtener productos y sus imágenes principales
$sql = "SELECT 
    p.id_producto AS id,
    p.codigo,
    pi.imagen,
    p.nombre,
    p.descripcion,
    s.id_sesion AS id_sesion,
    s.nombre AS nombre_sesion,
    cat.id_categoria AS id_categoria,
    cat.nombre AS nombre_categoria
FROM productos p
JOIN sesiones s ON p.id_sesion = s.id_sesion
JOIN categoria cat ON p.id_categoria = cat.id_categoria
JOIN (
    SELECT id_producto, MIN(id_imagen) AS imagen
    FROM producto_imagen
    GROUP BY id_producto
) img_principal ON img_principal.id_producto = p.id_producto
JOIN producto_imagen pi ON pi.id_imagen = img_principal.imagen
GROUP BY p.id_producto";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprar Inventario</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .card-title {
            color: #2c4926;
            font-weight: bold;
        }
        .btn-comprar {
            font-weight: bold;
            background: #2c4926;
            color: #fff;
            border-radius: 2rem;
            box-shadow: 0 2px 8px rgba(44, 73, 38, 0.13);
            transition: background 0.2s;
        }
        .btn-comprar:hover {
            background: #25601d;
            color: #fff;
        }
        .modal-header {
            background-color: #2c4926;
            color: white;
        }
        .modal-content {
            border-radius: 1rem;
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
                        <h1 class="h3"><i class="fas fa-cart-plus me-2"></i>Comprar inventario</h1>
                    </div>
                    <div class="row">
                        <?php while ($row = $resultado->fetch_assoc()): 
                            $id = $row['id'];
                        ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4"> 
                            <div class="card card-producto h-100">
                                <img src="<?php echo htmlspecialchars($row['imagen']); ?>" class="card-img-top" alt="Imagen del producto">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                                    <p class="card-text mb-2" style="flex-grow: 1;"><?php echo htmlspecialchars($row['descripcion']); ?></p>
                                    <button type="button" class="btn btn-comprar mt-auto" data-bs-toggle="modal" data-bs-target="#modalProducto<?php echo $id; ?>">
                                        <i class="fas fa-shopping-cart me-1"></i> Ver más
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de variantes para comprar -->
                        <div class="modal fade" id="modalProducto<?php echo $id; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $id; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"> 
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel<?php echo $id; ?>"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover product-variants-table">
                                                <thead>
                                                    <tr>
                                                        <th>Color</th>
                                                        <th>Talla</th>
                                                        <th>Precio</th>
                                                        <th>Stock actual</th>
                                                        <th>Costo compra</th>
                                                        <th>Cantidad</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $detalles_sql = "
                                                    SELECT 
                                                        dp.id_detalles_productos,
                                                        t.nombre_talla, 
                                                        cp.nombre AS nombre_color,
                                                        cp.codigo_hexadecimal,
                                                        dp.precio_producto, 
                                                        dp.stock 
                                                    FROM detalles_productos dp
                                                    JOIN talla_productos t ON dp.id_tallas = t.id_talla
                                                    JOIN color_productos cp ON dp.id_color = cp.id_color
                                                    WHERE dp.id_producto = $id
                                                    ORDER BY cp.nombre ASC, t.id_talla ASC
                                                ";
                                                $detalles_result = $conexion->query($detalles_sql);
                                                while ($detalle = $detalles_result->fetch_assoc()):
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <span class="color-swatch-modal" style="background-color: <?php echo htmlspecialchars($detalle['codigo_hexadecimal']); ?>;" title="<?php echo htmlspecialchars($detalle['nombre_color']); ?>"></span>
                                                            <?php echo htmlspecialchars($detalle['nombre_color']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($detalle['nombre_talla']); ?></td>
                                                        <td>$<?php echo number_format($detalle['precio_producto'], 0, ',', '.'); ?></td>
                                                        <td id="stock_actual_<?php echo $detalle['id_detalles_productos']; ?>"><?php echo htmlspecialchars($detalle['stock']); ?></td>
                                                        <td>
                                                            <input type="number" 
                                                                class="form-control form-control-sm" 
                                                                min="1" step="0.01" 
                                                                id="costo_<?php echo $detalle['id_detalles_productos']; ?>" 
                                                                value="<?php echo htmlspecialchars($detalle['precio_producto']); ?>" 
                                                                readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm" min="1" id="cantidad_<?php echo $detalle['id_detalles_productos']; ?>" placeholder="Cantidad">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-success btn-sm" onclick="comprarVariante(<?php echo $detalle['id_detalles_productos']; ?>)">Comprar</button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
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
<script>
function comprarVariante(id_detalles_productos) {
    const cantidad = parseInt(document.getElementById('cantidad_' + id_detalles_productos).value);
    const costo = parseFloat(document.getElementById('costo_' + id_detalles_productos).value);
    if (!cantidad || cantidad < 1 || !costo || costo < 1) {
        Swal.fire('Error', 'Ingrese cantidad y costo unitario válidos.', 'warning');
        return;
    }
    $.post('comprar.php', {
        accion: 'comprar_variante',
        id_detalles_productos: id_detalles_productos,
        cantidad: cantidad,
        costo_unitario: costo
    }, function(resp) {
        try {
            const data = JSON.parse(resp);
            if (data.success) {
                Swal.fire('¡Compra exitosa!', data.mensaje, 'success');
                // Actualizar stock en la tabla del modal
                const stockCell = document.getElementById('stock_actual_' + id_detalles_productos);
                if (stockCell) {
                    stockCell.textContent = (parseInt(stockCell.textContent) + cantidad);
                }
            } else {
                Swal.fire('Error', 'No se pudo registrar la compra.', 'error');
            }
        } catch(e) {
            Swal.fire('Error', 'Ocurrió un error inesperado.', 'error');
        }
    });
}
</script>
</body>
</html>
