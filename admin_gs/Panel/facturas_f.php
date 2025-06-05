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
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title> Gestionar Facturas Físicas</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <div class="facturas-header">
                        <h1 class="h3">
                            <i class="fas fa-fw fa-file-invoice" style="margin-right: 12px;"></i>
                            Facturas Ventas Física
                        </h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #2c4926;">Listado de Facturas Físicas</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                           
                                            <th>Número Factura</th>
                                            <th>Cliente</th>
                                            <th>Identificación </th>
                                            
                                            <th>Correo</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Método de Pago</th>
                                            
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "guardiashop");
                                        if ($conn->connect_error) {
                                            die("Conexión fallida: " . $conn->connect_error);
                                        }
                                    
                                        $sql = "SELECT * FROM factura_venta_f ORDER BY fecha_creacion_registro DESC";
                                        $result = $conn->query($sql);
                                        $i = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            // Badge de estado
                                            $estado = strtolower($row['estado_factura']);
                                            $estadoClass = 'estado-pendiente';
                                            if ($estado == 'pagada') $estadoClass = 'estado-pagada';
                                            elseif ($estado == 'vencida') $estadoClass = 'estado-vencida';
                                            elseif ($estado == 'anulada') $estadoClass = 'estado-anulada';
                                           // Consulta productos de la factura
                                                $id_factura = $row['id_factura'];
                                                $sql_productos = "SELECT p.nombre, d.cantidad, d.precio, d.subtotal 
                                                                    FROM detalles_factura_f d
                                                                    JOIN productos p ON d.id_producto = p.id_producto
                                                                    WHERE d.id_factura_f = $id_factura";
                                                $result_productos = $conn->query($sql_productos);
                                                if (!$result_productos) {
                                                    die("Error en la consulta de productos: " . $conn->error . "<br>SQL: $sql_productos");
                                                }

                                                $lista_productos = "";
                                                if ($result_productos->num_rows > 0) {
                                                    $lista_productos .= "<ul>";
                                                    while ($prod = $result_productos->fetch_assoc()) {
                                                        $lista_productos .= "<li><strong>{$prod['nombre']}</strong> - Cantidad: {$prod['cantidad']} - Precio: $" . number_format($prod['precio'], 2) . " - Subtotal: $" . number_format($prod['subtotal'], 2) . "</li>";
                                                    }
                                                    $lista_productos .= "</ul>";
                                                } else {
                                                    $lista_productos = "<p>No hay productos en esta factura.</p>";
                                                }
                                            echo "<tr>";
                                            
                                            echo "<td>{$row['numero_factura']}</td>";
                                           
                                            echo "<td>{$row['cliente_nombre_completo']}</td>";
                                            echo "<td>{$row['cliente_identificacion_fiscal']}</td>";
                                            
                                            echo "<td>{$row['correo']}</td>";
                                            echo "<td>$" . number_format($row['total_factura'], 2) . "</td>";
                                            echo "<td><span class='badge badge-estado {$estadoClass}'>" . ucfirst($row['estado_factura']) . "</span></td>";
                                            echo "<td>{$row['metodo_pago_registrado']}</td>";
                                            
                                            echo "<td class='acciones-btns'>
                                                    <button class='btn btn-info btn-sm' data-toggle='modal' data-target='#detalleFactura{$row['id_factura']}' title='Ver Detalle'><i class='fas fa-eye'></i></button>
                                                     <a class='btn btn-danger btn-sm' href='factura/factura_{$row['numero_factura']}.pdf' target='_blank' title='Ver PDF'><i class='fas fa-file-pdf'></i></a>
                                                  </td>";
                                            echo "</tr>";
                                            // Modal detalle factura
                                            echo "
                                            <div class='modal fade' id='detalleFactura{$row['id_factura']}' tabindex='-1' role='dialog' aria-labelledby='detalleFacturaLabel{$row['id_factura']}' aria-hidden='true'>
                                                <div class='modal-dialog modal-lg' role='document'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header' style='background:#2c4926;color:#fff;'>
                                                            <h5 class='modal-title' id='detalleFacturaLabel{$row['id_factura']}'>Detalle de la Factura #{$row['numero_factura']}</h5>
                                                            <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                                                                <span aria-hidden='true' style='color:white;'>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class='modal-body'>
                                                            <p><strong>Cliente:</strong> {$row['cliente_nombre_completo']}</p>
                                                            <p><strong>Identificación Fiscal:</strong> {$row['cliente_identificacion_fiscal']}</p>
                                                            <p><strong>Dirección Fiscal:</strong> {$row['cliente_direccion_fiscal']}</p>
                                                            <p><strong>Correo:</strong> {$row['correo']}</p>
                                                            <p><strong>Fecha Emisión:</strong> {$row['fecha_emision']}</p>
                                                            <p><strong>Fecha Vencimiento:</strong> {$row['fecha_vencimiento']}</p>
                                                            <p><strong>Método de Pago:</strong> {$row['metodo_pago_registrado']}</p>
                                                            <p><strong>Productos:</strong> {$lista_productos}</p>
                                                            <p><strong>Total Factura:</strong> $" . number_format($row['total_factura'], 2) . "</p>
                                                            <p><strong>Estado:</strong> <span class='badge badge-estado {$estadoClass}'>" . ucfirst($row['estado_factura']) . "</span></p>
                                                            <p><strong>Fecha de Registro:</strong> {$row['fecha_creacion_registro']}</p>
                                                        </div>
                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                            $i++;
                                        }
                                        $conn->close();
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
</body>
</html>
