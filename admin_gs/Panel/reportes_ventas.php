<?php
/*
session_start();
if (!isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'super_admin', 'vendedor'])) {
    header('Location: /guardiashop/admin_gs/login.php');
    exit();
}
*/
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Reportes de ventas</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .facturas-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            margin-top: 2rem;
        }
        .facturas-header h1 {
            color: #2c4926;
            font-weight: bold;
            margin-bottom: 0;
        }
        .table thead th {
        background: #2c4926;
        color: #fff !important; /* Encabezados blancos */
        vertical-align: middle;
    }
        .table tbody tr {
            background: #f8f9fc;
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background: #e2eaf1;
        }
        .table td, .table th {
            vertical-align: middle !important;
            text-align: center;
        }
        .badge-estado {
            font-size: 0.95rem;
            border-radius: 1.5rem;
            font-weight: 500;
            padding: 0.45em 1em;
            margin-bottom: 0 !important;
            white-space: nowrap;
            line-height: 1.2;
        }
          .table td, .table th {
        vertical-align: middle !important;
        text-align: center;
        color: #222 !important; /* Letras negras para toda la info */
    }
    .modal-body, .modal-body p, .modal-content {
        color: #222 !important; /* Letras negras en los modales */
    }
        .estado-pagada { background: #28a745; color: #fff; }
        .estado-pendiente { background: #ffc107; color: #222; }
        .estado-vencida { background: #dc3545; color: #fff; }
        .estado-anulada { background: #6c757d; color: #fff; }
        .card.shadow {
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(44, 73, 38, 0.08);
            border: none;
        }
        .acciones-btns {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
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
                    
                    <div class="container mt-4">
                        <!-- Encabezado decorativo -->
                        <div class="p-4 mb-4 rounded shadow" style="background: linear-gradient(90deg, #2c4926 60%, #6bbf59 100%); color: #fff;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-line fa-2x mr-3"></i>
                                <div>
                                    <h2 class="mb-0 font-weight-bold">Reportes de Ventas</h2>
                                    <small>Visualiza y compara ventas de tienda física y online</small>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <form class="row g-3 mb-4 p-3 rounded shadow-sm" style="background: #f4f8f6;" method="get">
                            <div class="col-md-3">
                                <label class="font-weight-bold">Desde</label>
                                <input type="date" class="form-control" name="fecha_inicio" value="<?= isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '' ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold">Hasta</label>
                                <input type="date" class="form-control" name="fecha_fin" value="<?= isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '' ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold">Tienda</label>
                                <select class="form-control" name="tipo_tienda">
                                    <option value="">Ambas</option>
                                    <option value="online">Online</option>
                                    <option value="fisica">Física</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn  btn-block shadow" style="font-weight: bold; font-size: 1.1em; background: #2c4926; color: #fff;">
                                    <i class="fas fa-search mr-2"></i>Filtrar
                                </button>
                            </div>
                        </form>

                        <!-- Tarjetas de totales -->
                        <div class="row mb-4 text-center">
                            <div class="col-md-4 mb-2">
                                <div class="card shadow border-0" style="background: linear-gradient(135deg, #6bbf59 60%, #2c4926 100%);">
                                    <div class="card-body text-white">
                                        <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                                        <h5>Total Vendido</h5>
                                        <h3 id="totalVendido" class="mb-0">$0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card shadow border-0" style="background: linear-gradient(135deg, #4e73df 60%, #224abe 100%);">
                                    <div class="card-body text-white">
                                        <i class="fas fa-receipt fa-2x mb-2"></i>
                                        <h5>Número de Ventas</h5>
                                        <h3 id="numVentas" class="mb-0">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card shadow border-0" style="background: linear-gradient(135deg, #f6c23e 60%, #e3a008 100%);">
                                    <div class="card-body text-white">
                                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                        <h5>Venta Promedio</h5>
                                        <h3 id="ticketPromedio" class="mb-0">$0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfica de ventas -->
                        <div class="card mb-4 shadow border-0" style="border-radius: 1.5rem;">
                            <div class="card-body">
                                <h5 class="mb-3 font-weight-bold " style="color: #2c4926;"><i class="fas fa-chart-bar mr-2"></i>Ventas por Día</h5>
                                <canvas id="ventasChart" style="min-height: 300px;"></canvas>
                            </div>
                        </div>

                        <!-- Tabla de ventas -->
                        <div class="card shadow border-0" style="border-radius: 1.5rem;">
                            <div class="card-body">
                                <h5 class="mb-3 font-weight-bold " style="color: #2c4926;"><i class="fas fa-table mr-2"></i>Detalle de Ventas</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Tienda</th>
                                                <th>Método de Pago</th>
                                                <th>Total</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaVentas">
                                            <?php
                                            // Conexión a la base de datos
                                            $conexion = new mysqli("localhost", "root", "", "guardiashop");
                                            if ($conexion->connect_error) {
                                                die("Error de conexión: " . $conexion->connect_error);
                                            }

                                            // Filtros
                                            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
                                            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
                                            $tipo_tienda = isset($_GET['tipo_tienda']) ? $_GET['tipo_tienda'] : '';

                                            // Construir consulta dinámica
                                            $condiciones = [];
                                            if ($fecha_inicio) $condiciones[] = "fecha_emision >= '$fecha_inicio'";
                                            if ($fecha_fin) $condiciones[] = "fecha_emision <= '$fecha_fin'";

                                            // Consultas para ambas tiendas
                                            $consultas = [];

                                            if ($tipo_tienda == '' || $tipo_tienda == 'fisica') {
                                                $where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';
                                                $consultas[] = "
                                                    SELECT 
                                                        fecha_emision AS fecha,
                                                        cliente_nombre_completo AS cliente,
                                                        'Física' AS tienda,
                                                        metodo_pago_registrado AS metodo_pago,
                                                        total_factura AS total,
                                                        estado_factura AS estado
                                                    FROM facturas_venta
                                                    $where
                                                ";
                                            }
                                            if ($tipo_tienda == '' || $tipo_tienda == 'online') {
                                                $where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';
                                                $consultas[] = "
                                                    SELECT 
                                                        fecha_emision AS fecha,
                                                        cliente_nombre_completo AS cliente,
                                                        'Online' AS tienda,
                                                        metodo_pago_registrado AS metodo_pago,
                                                        total_factura AS total,
                                                        estado_factura AS estado
                                                    FROM factura_venta_f
                                                    $where
                                                ";
                                            }

                                            // Unir consultas
                                            $sql = implode(" UNION ALL ", $consultas) . " ORDER BY fecha DESC";

                                            $resultado = $conexion->query($sql);

                                            if ($resultado && $resultado->num_rows > 0):
                                                while($row = $resultado->fetch_assoc()):
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                                                        <td><?= htmlspecialchars($row['cliente']) ?></td>
                                                        <td>
                                                            <span class="badge <?= $row['tienda'] == 'Online' ? 'badge-info' : 'badge-success' ?>">
                                                                <?= $row['tienda'] ?>
                                                            </span>
                                                        </td>
                                                        <td><?= htmlspecialchars($row['metodo_pago']) ?></td>
                                                        <td>$<?= number_format($row['total'], 0, ',', '.') ?></td>
                                                        <td>
                                                            <span class="badge-estado estado-<?= strtolower($row['estado']) ?>">
                                                                <?= $row['estado'] ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                endwhile;
                                            else:
                                                ?>
                                                <tr>
                                                    <td colspan="6">No hay resultados para los filtros seleccionados.</td>
                                                </tr>
                                                <?php
                                            endif;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>
        <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let ventasChart;
function cargarGraficaVentas() {
    // Obtén los valores de los filtros
    const fecha_inicio = document.querySelector('[name="fecha_inicio"]').value;
    const fecha_fin = document.querySelector('[name="fecha_fin"]').value;
    const tipo_tienda = document.querySelector('[name="tipo_tienda"]').value;

    $.get('/guardiashop/admin_gs/Panel/ajax_grafica_ventas.php', {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        tipo_tienda: tipo_tienda
    }, function(res) {
        if (ventasChart) ventasChart.destroy();
        const ctx = document.getElementById('ventasChart').getContext('2d');
        ventasChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: res.fechas,
                datasets: [
                    {
                        label: 'Tienda Física',
                        data: res.dataFisica,
                        borderColor: '#2c4926',
                        backgroundColor: 'rgba(44, 73, 38, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: '#2c4926'
                    },
                    {
                        label: 'Tienda Online',
                        data: res.dataOnline,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: '#4e73df'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { font: { size: 15 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: { title: { display: true, text: 'Fecha' } },
                    y: { title: { display: true, text: 'Total Vendido ($)' }, beginAtZero: true }
                }
            }
        });
    }, 'json');
}

function cargarTablaVentas() {
    const fecha_inicio = document.querySelector('[name="fecha_inicio"]').value;
    const fecha_fin = document.querySelector('[name="fecha_fin"]').value;
    const tipo_tienda = document.querySelector('[name="tipo_tienda"]').value;

    $.get('/guardiashop/admin_gs/Panel/ajax_tabla_ventas.php', {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        tipo_tienda: tipo_tienda
    }, function(res) {
        $('#tablaVentas').html(res);
    });
}

function cargarTotalesVentas() {
    const fecha_inicio = document.querySelector('[name="fecha_inicio"]').value;
    const fecha_fin = document.querySelector('[name="fecha_fin"]').value;
    const tipo_tienda = document.querySelector('[name="tipo_tienda"]').value;

    $.get('/guardiashop/admin_gs/Panel/ajax_totales_ventas.php', {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        tipo_tienda: tipo_tienda
    }, function(res) {
        $('#totalVendido').text('$' + res.total_vendido.toLocaleString(undefined, {minimumFractionDigits: 0}));
        $('#numVentas').text(res.num_ventas);
        $('#ticketPromedio').text('$' + res.ticket_promedio.toLocaleString(undefined, {minimumFractionDigits: 0}));
    }, 'json');
}

// Cargar al inicio
$(document).ready(function() {
    cargarGraficaVentas();
    cargarTablaVentas();
    cargarTotalesVentas();
    // Cuando cambie cualquier filtro, recargar gráfica, tabla y totales
    $('[name="fecha_inicio"], [name="fecha_fin"], [name="tipo_tienda"]').on('change', function() {
        cargarGraficaVentas();
        cargarTablaVentas();
        cargarTotalesVentas();
    });
    // Si el formulario se envía, evitar recarga y actualizar todo
    $('form').on('submit', function(e) {
        e.preventDefault();
        cargarGraficaVentas();
        cargarTablaVentas();
        cargarTotalesVentas();
    });
});
</script>
</body>
</html>