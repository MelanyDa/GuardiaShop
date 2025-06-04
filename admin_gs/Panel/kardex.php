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
    <title>Kardex</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        /* Cambia la cabecera de la tabla a verde */
        #tablaKardex thead th {
            background-color:rgb(19, 81, 34) !important;
            color: #fff !important;
        }
        /* Cambia el fondo de las filas a un verde muy claro */
        #tablaKardex tbody tr {
            background-color: #eafaf1 !important;
        }
        /* Opcional: resalta la fila al pasar el mouse */
        #tablaKardex tbody tr:hover {
            background-color: #d1f2e3 !important;
        }
        /* Opcional: bordes verdes */
        #tablaKardex, #tablaKardex th, #tablaKardex td {
            border-color: #198754 !important;
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
                        <h1 class="h3" style="color: #2c4926; font-weight: bold;">
                            <i class="fas fa-fw fa-clipboard-list" style="margin-right: 12px;"></i>
                            Kardex
                        </h1>
                    </div>
                    <div class="card shadow mb-4" style="margin-top: 40px;">
                        <div class="card-header py-3">
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="filtroProducto"><b>Filtrar por producto:</b></label>
                                <input type="text" id="filtroProducto" class="form-control" placeholder="Nombre o código del producto...">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tablaKardex" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Fecha/Hora</th>
                                            <th>Producto</th>
                                            <th>Código</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Tipo Movimiento</th>
                                            <th>Cantidad</th>
                                            <th><b>Stock Inicial</b></th> <!-- NUEVA COLUMNA -->
                                            <th>Stock Resultante</th>
                                            <th>Costo Unitario</th>
                                            <th>Admin</th>
                                            <th>Referencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
    <script>
    $(document).ready(function() {
        var tabla = $('#tablaKardex').DataTable({
            "ajax": {
                "url": "/guardiashop/admin_gs/Panel/acciones/movimientos_kardex.php",
                "dataSrc": ""
            },
            "order": [[0, "desc"]],
            "columns": [
                { data: 'fecha_hora', render: function(data){ return moment(data).format('YYYY-MM-DD HH:mm'); } },
                { data: 'nombre_producto' },
                { data: 'codigo' },
                { data: 'nombre_talla' },
                { data: 'color' },
                { data: 'tipo_movimiento', render: function(data){
                    let color = '#6c757d';
                    if(data === 'Compra') color = '#28a745';
                    else if(data === 'Venta') color = '#dc3545';
                    else if(data === 'Devolucion_Cliente') color = '#007bff';
                    else if(data === 'Devolucion_Proveedor') color = '#17a2b8';
                    else if(data === 'Ajuste_Manual_Positivo') color = '#ffc107';
                    else if(data === 'Ajuste_Manual_Negativo') color = '#fd7e14';
                    else if(data === 'Inicial') color = '#343a40';
                    return `<span class='badge' style='background:${color};color:#fff;'>${data.replace(/_/g,' ')}</span>`;
                } },
                { data: 'cantidad_cambio', className: 'text-center' },
                { data: 'stock_inicial', className: 'text-center' }, // NUEVA COLUMNA
                { data: 'stock_resultante', className: 'text-center' },
                { data: 'costo_unitario_movimiento', render: function(data){ return data ? '$'+parseFloat(data).toLocaleString() : '-'; }, className: 'text-right' },
                { data: null, render: function(row){
                    if(row.primer_nombre) return row.primer_nombre + ' ' + (row.primer_apellido||'');
                    return '-';
                } },
                { data: 'referencia_origen', render: function(data){ return data ? data : '-'; } }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            },
            "initComplete": function() {
                $('#tablaKardex').show();
            }
        });
        // Filtro por producto
        $('#filtroProducto').on('keyup', function() {
            tabla.column(1).search(this.value).draw();
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
</body>
</html>