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
// ... (tu código de sesión y conexión) ...
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
// --- CONSULTA PARA LA TABLA DE RESUMEN DE PEDIDOS ---
$sql_resumen_pedidos = "SELECT
    p.id_pedido,
    p.fecha_orden,
    p.total,
    p.estado,
    u.primer_nombre AS nombre_usuario,
    u.primer_apellido AS apellido_usuario,
    u.correo AS correo_usuario,
    (SELECT GROUP_CONCAT(pr.nombre SEPARATOR ', ') 
     FROM detalles_pedido dp_inner
     JOIN detalles_productos dpr_inner ON dp_inner.id_detalles_productos = dpr_inner.id_detalles_productos
     JOIN productos pr ON dpr_inner.id_producto = pr.id_producto
     WHERE dp_inner.id_pedido = p.id_pedido
    ) AS productos_resumen
FROM pedido p
INNER JOIN usuario u ON p.usuario_id = u.id
ORDER BY p.fecha_orden DESC";

$resultado_resumen = $conexion->query($sql_resumen_pedidos);
if (!$resultado_resumen) {
    die("Error en la consulta SQL de resumen de pedidos: " . $conexion->error);
}
$pedidos_para_tabla = [];
while ($row_resumen = $resultado_resumen->fetch_assoc()) {
    $pedidos_para_tabla[] = $row_resumen;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestionar Pedidos</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Nunito', Arial, sans-serif;
        }
        .modal-content {
            font-family: 'Nunito', Arial, sans-serif;
        }
        .modal-header, .modal-footer {
            border: none;
        }
        .modal-title i {
            color: #fff;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
        .badge-status {
            padding: 0.4em 0.7em;
            font-size: 0.85em;
            border-radius: 0.25rem;
        }
        .badge-status.status-pendiente { background-color: #ffc107; color: #212529; }
        .badge-status.status-confirmado { background-color: #198754; color: white; }
        .badge-status.status-preparando { background-color: #0dcaf0; color: #000; }
        .badge-status.status-enviado { background-color: #0d6efd; color: white; }
        .badge-status.status-en_camino { background-color: #fd7e14; color: white; }
        .badge-status.status-entregado { background-color: #17a2b8; color: white; }
        .badge-status.status-cancelado, .badge-status.status-fallido { background-color: #dc3545; color: white; }
        .badge-status.status-devuelto { background-color: #198754; color: white; }
        .modal-footer .btn {
            min-width: 110px;
        }
        .form-select {
            font-size: 1em;
            padding: 0.4em 1.5em 0.4em 0.75em;
        }
        .productos-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; margin-top: 1.5rem; }
        .productos-header h1 { color: #198754; font-weight: bold; margin-bottom: 0; font-size: 1.75rem; }
        .table thead th { background-color:rgb(23, 100, 43); color: #fff; border-color: #bfa14a; }
        .table tbody tr:hover { background-color: #f8f9fa; }
        .btn-info-custom { background-color: #bfa14a; border-color: #bfa14a; color:white; }
        .btn-info-custom:hover { background-color: #a88d3c; border-color: #a88d3c; color:white; }
        .btn-warning { background-color: #198754 !important; border-color: #198754 !important; color: #fff !important; }
        .btn-warning:hover { background-color: #146c43 !important; border-color: #146c43 !important; color: #fff !important; }
        .modal-header-custom { background-color: #198754; color: white; }
        .modal-header-custom .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
        .table-sm th, .table-sm td {
            font-size: 0.92em !important;
        }
        .modal-body {
            font-size: 0.97em;
        }
        .table-articulos th, .table-articulos td {
            font-size: 0.93em !important;
        }
        .table-historial th, .table-historial td {
            font-size: 0.93em !important;
        }
        /* Quitar resaltadores de color y fondo en variantes */
        .no-highlight {
            background: none !important;
            color: #222 !important;
            font-weight: normal !important;
            border: none !important;
            padding: 0 !important;
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
                        <h1 class="h3"><i class="fas fa-fw fa-box me-2"></i><b>Pedidos</b></h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #bfa14a;">Listado de Pedidos</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID Pedido</th>
                                            <th>Fecha</th>
                                            <th>Cliente</th>
                                            <th>Correo Cliente</th>
                                            <th>Productos (Resumen)</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($pedidos_para_tabla as $pedido_fila): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($pedido_fila['id_pedido']); ?></td>
                                            <td><?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($pedido_fila['fecha_orden']))); ?></td>
                                            <td><?php echo htmlspecialchars($pedido_fila['nombre_usuario'] . ' ' . $pedido_fila['apellido_usuario']); ?></td>
                                            <td><?php echo htmlspecialchars($pedido_fila['correo_usuario']); ?></td>
                                            <td><small><?php echo htmlspecialchars($pedido_fila['productos_resumen']); ?></small></td>
                                            <td>$<?php echo number_format($pedido_fila['total'], 0, ',', '.'); ?></td>
                                            <td>
                                                <span class="badge-status status-<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($pedido_fila['estado']))); ?>">
                                                    <?php echo ucfirst(htmlspecialchars($pedido_fila['estado'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info-custom w-100 mb-1" data-bs-toggle="modal" data-bs-target="#detalleModal<?php echo $pedido_fila['id_pedido']; ?>">
                                                    <i class="fas fa-eye"></i> Ver
                                                </button>
                                                <button class="btn btn-sm btn-warning w-100 btn-abrir-estado" 
                                                    data-id="<?php echo $pedido_fila['id_pedido']; ?>" 
                                                    data-estado="<?php echo htmlspecialchars($pedido_fila['estado']); ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEstadoPedido">
                                                    <i class="fas fa-edit"></i> Estado
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modales para Detalles de Pedido -->
                    <?php foreach ($pedidos_para_tabla as $pedido_fila_modal):
                        $id_pedido_actual_modal = $pedido_fila_modal['id_pedido'];
                        $sql_detalles_modal = "SELECT
                            dp.cantidad,
                            dp.precio_unitario,
                            pr.nombre AS nombre_producto,
                            c.nombre AS color_producto,
                            t.nombre_talla AS talla_producto,
                            d_envio.direccion AS direccion_envio,
                            d_envio.ciudad AS ciudad_envio,
                            d_envio.departamento AS departamento_envio,
                            d_envio.telefono AS telefono_envio
                        FROM detalles_pedido dp
                        INNER JOIN detalles_productos dpr ON dp.id_detalles_productos = dpr.id_detalles_productos
                        INNER JOIN productos pr ON dpr.id_producto = pr.id_producto
                        INNER JOIN color_productos c ON dpr.id_color = c.id_color
                        INNER JOIN talla_productos t ON dpr.id_tallas = t.id_talla
                        INNER JOIN pedido p_main ON dp.id_pedido = p_main.id_pedido
                        LEFT JOIN direccion d_envio ON p_main.usuario_id = d_envio.usuario_id
                        WHERE dp.id_pedido = ?";
                        $stmt_detalles_modal = $conexion->prepare($sql_detalles_modal);
                        $stmt_detalles_modal->bind_param("i", $id_pedido_actual_modal);
                        $stmt_detalles_modal->execute();
                        $resultado_detalles_modal = $stmt_detalles_modal->get_result();
                        $items_del_pedido_modal = [];
                        $direccion_envio_modal = null;
                        while($detalle_item_modal = $resultado_detalles_modal->fetch_assoc()){
                            $items_del_pedido_modal[] = $detalle_item_modal;
                            if ($direccion_envio_modal === null && !empty($detalle_item_modal['direccion_envio'])) {
                                $direccion_envio_modal = [
                                    'direccion' => $detalle_item_modal['direccion_envio'],
                                    'ciudad' => $detalle_item_modal['ciudad_envio'],
                                    'departamento' => $detalle_item_modal['departamento_envio'],
                                    'telefono' => $detalle_item_modal['telefono_envio']
                                ];
                            }
                        }
                        $stmt_detalles_modal->close();
                    ?>
                    <!-- Modal Detalles del Pedido Mejorado y Compacto -->
                    <div class="modal fade" id="detalleModal<?php echo $id_pedido_actual_modal; ?>" tabindex="-1" aria-labelledby="detalleModalLabel<?php echo $id_pedido_actual_modal; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content shadow border-0 rounded-4">
                                <div class="modal-header py-2 px-4" style="background: linear-gradient(90deg, #bfa14a 60%, #198754 100%); border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                    <h5 class="modal-title fw-bold text-white" id="detalleModalLabel<?php echo $id_pedido_actual_modal; ?>">
                                        <i class="fas fa-receipt me-2"></i>Detalles del Pedido #<?php echo htmlspecialchars($id_pedido_actual_modal); ?>
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body px-4 py-3" style="background-color: #f8f9fa;">
                                    <div class="row g-3 mb-2">
                                        <div class="col-md-6">
                                            <div class="p-2 bg-white rounded-3 shadow-sm h-100">
                                                <h6 class="fw-bold mb-2" style="color:#198754;"><i class="fas fa-user me-1"></i> Cliente</h6>
                                                <div class="mb-1"><span class="fw-semibold">Nombre:</span> <?php echo htmlspecialchars($pedido_fila_modal['nombre_usuario'] . ' ' . $pedido_fila_modal['apellido_usuario']); ?></div>
                                                <div class="mb-1"><span class="fw-semibold">Correo:</span> <?php echo htmlspecialchars($pedido_fila_modal['correo_usuario']); ?></div>
                                                <div class="mb-1"><span class="fw-semibold">Fecha:</span> <?php echo htmlspecialchars(date("d/m/Y H:i:s", strtotime($pedido_fila_modal['fecha_orden']))); ?></div>
                                                <div class="mb-1">
                                                    <span class="fw-semibold">Total:</span>
                                                    $<?php echo number_format($pedido_fila_modal['total'], 0, ',', '.'); ?>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold">Estado:</span>
                                                    <span class="badge-status status-<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($pedido_fila_modal['estado']))); ?> fs-6">
                                                        <?php echo ucfirst(htmlspecialchars($pedido_fila_modal['estado'])); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-2 bg-white rounded-3 shadow-sm h-100">
                                                <h6 class="fw-bold mb-2" style="color:#198754;"><i class="fas fa-truck me-1"></i> Envío</h6>
                                                <?php if ($direccion_envio_modal): ?>
                                                    <div class="mb-1"><i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($direccion_envio_modal['direccion']); ?></div>
                                                    <div class="mb-1"><i class="fas fa-city me-1"></i> <?php echo htmlspecialchars($direccion_envio_modal['ciudad']); ?>, <?php echo htmlspecialchars($direccion_envio_modal['departamento']); ?></div>
                                                    <div><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($direccion_envio_modal['telefono']); ?></div>
                                                <?php else: ?>
                                                    <div class="text-muted mb-0">No se encontró información de envío principal.</div>
                                                    <small class="text-muted">(Considera guardar la dirección de envío específica en la tabla <code>pedido</code> o una tabla <code>pedido_direccion</code>)</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <h6 class="fw-bold mb-2" style="color:#bfa14a;"><i class="fas fa-boxes me-1"></i> Artículos del Pedido</h6>
                                    <?php if (!empty($items_del_pedido_modal)): ?>
                                        <div class="table-responsive mb-3">
                                            <table class="table table-sm table-bordered align-middle bg-white rounded-3 shadow-sm table-articulos">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Color</th>
                                                        <th>Talla</th>
                                                        <th class="text-center">Cantidad</th>
                                                        <th>Precio Unit.</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($items_del_pedido_modal as $item_m): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($item_m['nombre_producto']); ?></td>
                                                        <td class="no-highlight"><?php echo htmlspecialchars($item_m['color_producto']); ?></td>
                                                        <td class="no-highlight"><?php echo htmlspecialchars($item_m['talla_producto']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($item_m['cantidad']); ?></td>
                                                        <td>$<?php echo number_format($item_m['precio_unitario'], 0, ',', '.'); ?></td>
                                                        <td class="fw-bold">$<?php echo number_format($item_m['cantidad'] * $item_m['precio_unitario'], 0, ',', '.'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No se encontraron artículos para este pedido.</p>
                                    <?php endif; ?>

                                    <hr class="my-3">
                                    <h6 class="fw-bold mb-2" style="color:#198754;"><i class="fas fa-history me-1"></i> Historial de Estados</h6>
                                    <?php
                                    $sql_historial_p = "SELECT fecha_cambio, estado_anterior, estado_nuevo, notas, id_admin_cambio FROM pedido_historial WHERE id_pedido = ? ORDER BY fecha_cambio ASC";
                                    $stmt_hist_p = $conexion->prepare($sql_historial_p);
                                    $stmt_hist_p->bind_param("i", $id_pedido_actual_modal);
                                    $stmt_hist_p->execute();
                                    $res_hist_p = $stmt_hist_p->get_result();
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered align-middle bg-white rounded-3 shadow-sm table-historial">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>De</th>
                                                    <th>A</th>
                                                    <th>Notas</th>
                                                    <th>Admin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php while($h_row = $res_hist_p->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo date("d/m/Y H:i", strtotime($h_row['fecha_cambio'])); ?></td>
                                                    <td><span class="badge-status status-<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($h_row['estado_anterior'] ?? 'Inicio'))); ?>"><?php echo htmlspecialchars($h_row['estado_anterior'] ?? 'Inicio'); ?></span></td>
                                                    <td><span class="badge-status status-<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($h_row['estado_nuevo']))); ?>"><?php echo htmlspecialchars($h_row['estado_nuevo']); ?></span></td>
                                                    <td><?php echo !empty($h_row['notas']) ? '<span class="text-muted">'.htmlspecialchars($h_row['notas']).'</span>' : '-'; ?></td>
                                                    <td><?php echo !empty($h_row['id_admin_cambio']) ? htmlspecialchars($h_row['id_admin_cambio']) : '-'; ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                            <?php $stmt_hist_p->close(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <!-- Modal para Cambiar Estado del Pedido (Estilizado) -->
                    <div class="modal fade" id="modalEstadoPedido" tabindex="-1" aria-labelledby="modalEstadoPedidoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow border-0">
                                <form id="formCambiarEstadoPedido">
                                    <div class="modal-header py-2 px-4" style="background: linear-gradient(90deg, #bfa14a 60%, #198754 100%); border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                        <h5 class="modal-title fw-bold text-white" id="modalEstadoPedidoLabel">
                                            <i class="fas fa-edit me-2"></i> Cambiar Estado del Pedido
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body px-4 py-3" style="background-color: #f8f9fa;">
                                        <input type="hidden" name="id_pedido" id="modal_id_pedido">
                                        <div class="mb-3">
                                            <label for="modal_nuevo_estado" class="form-label fw-semibold" style="color:#198754;">Nuevo estado:</label>
                                            <select name="nuevo_estado" id="modal_nuevo_estado" class="form-select rounded-pill border-2" style="max-width: 300px;">
                                                <?php
                                                $estados = ['pendiente','confirmado','preparando','enviado','en camino','entregado','cancelado','devuelto','fallido'];
                                                foreach ($estados as $estado) {
                                                    echo "<option value=\"$estado\">" . ucfirst($estado) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i> Cerrar
                                        </button>
                                        <button type="submit" class="btn" style="background:#198754; color:#fff; min-width:120px;">
                                            <i class="fas fa-save"></i> Guardar Estado
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php $conexion->close(); ?>
                </div>
            </div>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <!-- Logout Modal, etc. -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.querySelectorAll('.btn-abrir-estado').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('modal_id_pedido').value = this.dataset.id;
        document.getElementById('modal_nuevo_estado').value = this.dataset.estado;
    });
});

document.getElementById('formCambiarEstadoPedido').addEventListener('submit', function(e) {
    e.preventDefault();
    const id_pedido = document.getElementById('modal_id_pedido').value;
    const nuevo_estado = document.getElementById('modal_nuevo_estado').value;
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    fetch('acciones/pedido/actualizar_estado_pedido.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id_pedido=' + encodeURIComponent(id_pedido) + '&nuevo_estado=' + encodeURIComponent(nuevo_estado)
    })
    .then(resp => resp.text())
    .then(data => {
        if (data.trim() === 'ok') {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-secondary');
            btn.innerHTML = '<i class="fas fa-check"></i> Guardado';
            setTimeout(() => {
                location.reload();
            }, 900);
        } else {
            alert('Error: ' + data);
            btn.disabled = false;
            btn.classList.add('btn-danger');
        }
    })
    .catch(err => {
        alert('Error de red');
        btn.disabled = false;
    });
});
    </script>
</body>
</html>
