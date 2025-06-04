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
    <title>Gestionar usuarios</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .usuarios-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            margin-top: 2rem;
        }
        .usuarios-header h1 {
            color: #2c4926;
            font-weight: bold;
            margin-bottom: 0;
        }
        .btn-agregar-usuario {
            font-weight: bold;
            background: #2c4926;
            color: #fff;
            border-radius: 2rem;
            box-shadow: 0 2px 8px rgba(44, 73, 38, 0.13);
            transition: background 0.2s;
        }
        .btn-agregar-usuario:hover {
            background: #25601d;
            color: #fff;
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
        .acciones-btns {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        .card.shadow {
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(44, 73, 38, 0.08);
            border: none;
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
                    <div class="usuarios-header">
                        <h1 class="h3" style="margin-left: 10px;"><i class="fas fa-user me-2" style="margin-right: 12px;"></i>Gestionar usuarios</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Correo</th>
                                            <th>Fecha de Cumpleaños</th>
                                            <th>Rol</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "guardiashop");
                                        if ($conn->connect_error) {
                                            die("Conexión fallida: " . $conn->connect_error);
                                        }
                                        $sql = "SELECT id, primer_nombre,segundo_nombre,primer_apellido,segundo_apellido, correo, fecha_de_cumpleaños, rol FROM usuario WHERE rol='cliente'";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td style='color: #000;'>" . $row['primer_nombre'] . " " . $row['segundo_nombre'] . "</td>";
                                            echo "<td style='color: #000;'>" . $row['primer_apellido'] . " " . $row['segundo_apellido'] . "</td>";
                                            echo "<td style='color: #000;'>" . $row['correo'] . "</td>";
                                            echo "<td style='color: #000;'>" . $row['fecha_de_cumpleaños'] . "</td>";
                                            echo "<td style='color: #000;'>" . $row['rol'] . "</td>";
                                            echo "<td class='acciones-btns'>
                                                <button type='button' class='btn btn-danger btn-sm' style='width: 40px;' onclick='eliminarUsuario(" . $row['id'] . ")'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </button>
                                                <button type='button' class='btn btn-warning btn-sm' style='width: 80px; color: #fff;'
                                                    onclick='abrirModalEditarUsuario(" . json_encode($row) . ")'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <script>
                        function eliminarUsuario(userId) {
                            Swal.fire({
                                title: '¿Estás seguro?',
                                text: "¡No podrás revertir esto!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: '¡Sí, eliminar!',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "acciones/usuarios/eliminar_usuario.php?id=" + userId;
                                }
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formEditarUsuario" method="POST" action="acciones/usuarios/actualizar_usuario.php">
      <div class="modal-content">
        <div class="modal-header" style="background:#2c4926;color:#fff;">
          <h5 class="modal-title" id="modalEditarUsuarioLabel"><i class="fas fa-edit"></i> Editar Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="form-group">
            <label>Nombres</label>
            <input type="text" class="form-control" name="primer_nombre" id="edit_primer_nombre" required>
            <input type="text" class="form-control mt-2" name="segundo_nombre" id="edit_segundo_nombre">
          </div>
          <div class="form-group">
            <label>Apellidos</label>
            <input type="text" class="form-control" name="primer_apellido" id="edit_primer_apellido" required>
            <input type="text" class="form-control mt-2" name="segundo_apellido" id="edit_segundo_apellido">
          </div>
          <div class="form-group">
            <label>Correo</label>
            <input type="email" class="form-control" name="correo" id="edit_correo" required>
          </div>
          <div class="form-group">
            <label>Fecha de Cumpleaños</label>
            <input type="date" class="form-control" name="fecha_de_cumpleaños" id="edit_fecha_de_cumpleaños" required>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar cambios</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
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
    if ( $.fn.DataTable.isDataTable('#dataTable') ) {
        $('#dataTable').DataTable().destroy();
    }
    $('#dataTable').DataTable({
        "ordering": false
    });
});
</script>

<script>
function abrirModalEditarUsuario(usuario) {
    $('#edit_id').val(usuario.id);
    $('#edit_primer_nombre').val(usuario.primer_nombre);
    $('#edit_segundo_nombre').val(usuario.segundo_nombre);
    $('#edit_primer_apellido').val(usuario.primer_apellido);
    $('#edit_segundo_apellido').val(usuario.segundo_apellido);
    $('#edit_correo').val(usuario.correo);
    $('#edit_fecha_de_cumpleaños').val(usuario.fecha_de_cumpleaños);
    $('#edit_rol').val(usuario.rol);
    $('#modalEditarUsuario').modal('show');
}
</script>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Usuario eliminado!',
            text: 'El usuario se ha eliminado correctamente.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '/guardiashop/admin_gs/panel/g_usuarios.php';
        });
    </script>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Usuario actualizado!',
            text: 'El usuario se ha actualizado correctamente.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '/guardiashop/admin_gs/panel/g_usuarios.php';
        });
    </script>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 3): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al actualizar el usuario.',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>
</body>
</html>