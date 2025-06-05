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
    <title>Contactos de usuario</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
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
        .acciones-btns {
            gap: 0.5rem;
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
                                        <i class="fas fa-fw fa-address-book me-2" style="margin-right: 12px;"></i><b>Contactos</b>
                                    </h1>
                                </div>
                                <?php
                                $conn = new mysqli("localhost", "root", "", "guardiashop");
                                if ($conn->connect_error) {
                                    die("Conexión fallida: " . $conn->connect_error);
                                }
                                $sql = "SELECT 
                                    c.id_contacto, 
                                    u.primer_nombre,
                                    u.segundo_nombre,
                                    u.primer_apellido,
                                    u.segundo_apellido,  
                                    c.correo, 
                                    c.mensaje,
                                    c.fecha, 
                                    c.estado
                                FROM contactanos c
                                INNER JOIN usuario u ON c.id_usuario = u.id";
                                $result = $conn->query($sql);
                                ?>
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold" style="color: #2c4926;">Mensajes de Contacto</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Apellido</th>
                                                        <th>Correo</th>
                                                        <th>Mensaje</th>
                                                        <th>Fecha</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php while ($row = $result->fetch_assoc()) { ?>
                                                    <tr>
                                                        <td style="color: #000;"><?= $row['primer_nombre'] . " " . $row['segundo_nombre']; ?></td>
                                                        <td style="color: #000;"><?= $row['primer_apellido'] . " " . $row['segundo_apellido']; ?></td>
                                                        <td style="color: #000;"><?= $row['correo']; ?></td>
                                                        <td style="color: #000;"><?= $row['mensaje']; ?></td>
                                                        <td style="color: #000;"><?= $row['fecha']; ?></td>
                                                        <td class="estado" style="color: #000;"><?= $row['estado']; ?></td>
                                                        <td>    
                                                            <div class="d-flex justify-content-center acciones-btns">
                                                                <?php $modalId = 'estadoModal' . $row['id_contacto']; ?>
                                                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#<?= $modalId ?>" title="Cambiar estado">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#responderModal<?= $row['id_contacto']; ?>" title="Responder">
                                                                    <i class="fas fa-reply"></i>
                                                                </button>
                                                                <form class="form-eliminar" method="POST" action="acciones/contactos/eliminar_contacto.php" style="display:inline;">
                                                                    <input type="hidden" name="id" value="<?= $row['id_contacto']; ?>">
                                                                    <button type="button" class="btn btn-danger btn-sm btn-eliminar" title="Eliminar">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <!-- Modal Cambiar Estado ... (ya existente) -->
                                                             <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-labelledby="estadoModalLabel<?= $row['id_contacto'] ?>" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <form action="acciones/contactos/cambiar_estado.php" method="POST">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="estadoModalLabel<?= $row['id_contacto'] ?>">Cambiar Estado</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="id" value="<?= $row['id_contacto'] ?>">
                                                                                <div class="form-group">
                                                                                    <label for="estado" style="color: #000;">Selecciona el nuevo estado:</label>
                                                                                    <select class="form-control" name="estado" required>
                                                                                        <option value="">-- Seleccionar --</option>
                                                                                        <option style="color: #000;" value="Nuevo">Nuevo</option>
                                                                                        <option style="color: #000;" value="Leído">Leído</option>
                                                                                        <option style="color: #000;" value="Respondido">Respondido</option>
                                                                                        <option style="color: #000;" value="Cerrado">Cerrado</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" class="btn btn-primary" style="background-color: #2c4926;">Guardar</button>
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <!-- Modal Responder -->
                                                            <div class="modal fade" id="responderModal<?= $row['id_contacto']; ?>" tabindex="-1" role="dialog" aria-labelledby="responderModalLabel<?= $row['id_contacto']; ?>" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <form action="acciones/contactos/responder_contacto.php" method="POST">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="responderModalLabel<?= $row['id_contacto']; ?>">Responder a <?= $row['correo']; ?></h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="correo" value="<?= $row['correo']; ?>">
                                                                            <input type="hidden" name="id_contacto" value="<?= $row['id_contacto']; ?>">
                                                                            <div class="form-group">
                                                                                <label for="asunto">Asunto:</label>
                                                                                <input type="text" class="form-control" name="asunto" required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="mensaje">Mensaje:</label>
                                                                                <textarea class="form-control" name="mensaje" rows="5" required></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-success">Enviar</button>
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                            <?php $conn->close(); ?>
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


<script>
<?php if (isset($_GET['estado_cambiado']) && $_GET['estado_cambiado'] == 1): ?>
Swal.fire({
    icon: 'success',
    title: '¡Estado cambiado con éxito!',
    showConfirmButton: false,
    timer: 1500
});
<?php endif; ?>
</script>


<script>
<?php if (isset($_GET['respuesta']) && $_GET['respuesta'] == 'ok'): ?>
Swal.fire({
    icon: 'success',
    title: '¡Correo enviado!',
    showConfirmButton: false,
    timer: 1500
});
<?php elseif (isset($_GET['respuesta']) && $_GET['respuesta'] == 'error'): ?>
Swal.fire({
    icon: 'error',
    title: 'No se pudo enviar el correo',
    showConfirmButton: false,
    timer: 1500
});

<?php elseif (isset($_GET['success']) && $_GET['success'] == 4): ?>
Swal.fire({
    icon: 'success',
    title: '¡Contacto eliminado con éxito!',
    showConfirmButton: false,
    timer: 1500
});
<?php endif; ?>


</script>
    
</body>
</html>
