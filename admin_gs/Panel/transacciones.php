<?php
/*
session_start();
if (!isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'super_admin', 'vendedor'])) {
    header('Location: ../login.php');
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
    <title>Contactos de usuario</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .contactos-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            margin-top: 2rem;
        }
        .contactos-header h1 {
            color: #2c4926;
            font-weight: bold;
            margin-bottom: 0;
        }
        .table thead th {
            background: #2c4926;
            color: #fff;
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
        .acciones-btns, td .btn, td form {
            display: inline-block;
            margin: 0 2px;
        }
        .card.shadow {
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(44, 73, 38, 0.08);
            border: none;
        }
        .estado {
            font-weight: bold;
            border-radius: 1rem;
            padding: 0.3em 1em;
            background: #e9ecef;
        }
        .modal-header {
            background-color: #2c4926;
            color: white;
        }
        .modal-footer {
            text-align: center;
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
                                <div class="contactos-header">
                                    <h1 class="h3 mb-4 font-weight-bold" style="color: #2c4926;margin-left:10px;">
                                        <i class="fas fa-fw fa-money-bill" style="margin-right: 12px;"></i><b>Transacciones</b>
                                    </h1>
                                </div>
                                <div class="card shadow mb-4">
                                    <div class="table-responsive p-4">
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "guardiashop");
                                        if ($conn->connect_error) {
                                            die("Conexión fallida: " . $conn->connect_error);
                                        }
                                        $sql = "SELECT p.id_pago, p.fecha_pago, p.metodo_pago, p.id_pedido, p.estado_pago, p.monto, p.banco,
                                                       u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.segundo_apellido
                                                FROM pago p
                                                INNER JOIN pedido pe ON p.id_pedido = pe.id_pedido
                                                INNER JOIN usuario u ON pe.usuario_id = u.id
                                                ORDER BY p.fecha_pago DESC";
                                        $result = $conn->query($sql);
                                        ?>
                                        <table class="table table-hover table-striped align-middle" id="dataTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Fecha de pago</th>
                                                    <th>Método de pago</th>
                                                    <th>Banco</th>
                                                    <th>ID Pedido</th>
                                                    <th>Estado</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['primer_nombre'] . ' ' . $row['segundo_nombre'] . ' ' . $row['primer_apellido'] . ' ' . $row['segundo_apellido']); ?></td>
                                                        <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_pago'])); ?></td>
                                                        <td><?php echo ucfirst($row['metodo_pago']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['banco'] ?? ''); ?></td>
                                                        <td><?php echo $row['id_pedido']; ?></td>
                                                        <td>
                                                            <?php
                                                                $estado = $row['estado_pago'];
                                                                $clase = '';
                                                                $texto = '';
                                                                if ($estado == 'pendiente') {
                                                                    $clase = 'badge badge-warning';
                                                                    $texto = 'Pendiente';
                                                                } elseif ($estado == 'completado') {
                                                                    $clase = 'badge badge-success';
                                                                    $texto = 'Completado';
                                                                } elseif ($estado == 'fallido') {
                                                                    $clase = 'badge badge-danger';
                                                                    $texto = 'Fallido';
                                                                }
                                                            ?>
                                                            <span class="<?php echo $clase; ?>" style="font-size:1em; padding:0.5em 1em; border-radius:1em;"> <?php echo $texto; ?> </span>
                                                        </td>
                                                        <td><?php echo '$' . number_format($row['monto'], 2, ',', '.'); ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                        <?php $conn->close(); ?>
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
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
$(document).ready(function() {
    if ( $.fn.DataTable.isDataTable('#dataTable') ) {
        $('#dataTable').DataTable().destroy();
    }
    $('#dataTable').DataTable({
        "ordering": false
    });
});
</script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const eliminarButtons = document.querySelectorAll('.btn-eliminar');
        eliminarButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('form');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
    </script>



</body>
</html>