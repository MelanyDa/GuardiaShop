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
    <title>Gestionar Admins</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .btn-agregar-admin {
            background: #2c4926;
            color: #fff;
            font-weight: bold;
            border-radius: 2rem;
            padding: 0.5rem 1.5rem;
            transition: background 0.2s;
        }
        .btn-agregar-admin:hover {
            background: #1e321a;
            color: #fff;
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
                    <div class="usuarios-header">
                        <h1 class="h3"><i class="fas fa-user-shield me-2" style="margin-right: 12px;"></i>Gestionar Administradores</h1>
                        <button class="btn btn-agregar-admin" data-toggle="modal" data-target="#modalAgregarAdmin">
                            <i class="fas fa-user-plus"></i> Nuevo Admin
                        </button>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #2c4926;">Listado de Administradores</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead style="background: #2c4926; color: #fff;">
                                        <tr>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Correo</th>
                                            <th>Fecha de Cumpleaños</th>
                                            <th>Fecha Registro</th>
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
                                        // Solo admins
                                        $sql = "SELECT id, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, fecha_de_cumpleaños, rol, fecha_registro FROM usuario WHERE rol IN ('admin','vendedor')";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td style='color: #000;'>" . htmlspecialchars($row['primer_nombre']) . " " . htmlspecialchars($row['segundo_nombre']) . "</td>";
                                            echo "<td style='color: #000;'>" . htmlspecialchars($row['primer_apellido']) . " " . htmlspecialchars($row['segundo_apellido']) . "</td>";
                                            echo "<td style='color: #000;'>" . htmlspecialchars($row['correo']) . "</td>";
                                            echo "<td style='color: #000;'>" . htmlspecialchars($row['fecha_de_cumpleaños']) . "</td>";
                                            echo "<td style='color: #000;'>" . htmlspecialchars($row['fecha_registro']) . "</td>";
                                            echo "<td style='color: #000;'>" . htmlspecialchars($row['rol']) . "</td>";
                                            echo "<td class='acciones-btns'>
                                                <button type='button' class='btn btn-warning btn-sm' onclick='abrirModalEditarAdmin(" . json_encode($row) . ")'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                                <button type='button' class='btn btn-danger btn-sm' onclick='eliminarAdmin(" . $row['id'] . ")'>
                                                    <i class='fas fa-trash-alt'></i>
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

                    <!-- Modal Agregar Admin -->
                    <div class="modal fade" id="modalAgregarAdmin" tabindex="-1" role="dialog" aria-labelledby="modalAgregarAdminLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="POST" action="acciones/admin/crear_admin.php">
                                <div class="modal-content">
                                    <div class="modal-header" style="background:#2c4926;color:#fff;">
                                        <h5 class="modal-title" id="modalAgregarAdminLabel"><i class="fas fa-user-plus"></i> Nuevo Administrador</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label style="color: #000;">Nombres</label>
                                            <input type="text" class="form-control" name="primer_nombre" placeholder="Primer nombre" required>
                                            <input type="text" class="form-control mt-2" name="segundo_nombre" placeholder="Segundo nombre">
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Apellidos</label>
                                            <input type="text" class="form-control" name="primer_apellido" placeholder="Primer apellido" required>
                                            <input type="text" class="form-control mt-2" name="segundo_apellido" placeholder="Segundo apellido">
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Correo</label>
                                            <input type="email" class="form-control" name="correo" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Rol</label>
                                            <select class="form-control" name="rol" required>
                                            <option value="admin">Admin</option>
                                            <option value="vendedor">vendedor</option>
                                            <option value="superadmin">Super Admin</option>
                                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Fecha de Cumpleaños</label>
                                            <input type="date" class="form-control" name="fecha_de_cumpleaños" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Contraseña</label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn" style="background-color: #2c4926; color:#fff">Crear</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Editar Admin -->
                    <div class="modal fade" id="modalEditarAdmin" tabindex="-1" role="dialog" aria-labelledby="modalEditarAdminLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="formEditarAdmin" method="POST" action="acciones/admin/actualizar_admin.php">
                                <div class="modal-content">
                                    <div class="modal-header" style="background:#2c4926;color:#fff;">
                                        <h5 class="modal-title" id="modalEditarAdminLabel"><i class="fas fa-edit"></i> Editar Administrador</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="edit_id">
                                        <div class="form-group">
                                            <label style="color: #000;">Nombres</label>
                                            <input type="text" class="form-control" name="primer_nombre" id="edit_primer_nombre" required>
                                            <input type="text" class="form-control mt-2" name="segundo_nombre" id="edit_segundo_nombre">
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Apellidos</label>
                                            <input type="text" class="form-control" name="primer_apellido" id="edit_primer_apellido" required>
                                            <input type="text" class="form-control mt-2" name="segundo_apellido" id="edit_segundo_apellido">
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Correo</label>
                                            <input type="email" class="form-control" name="correo" id="edit_correo" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Fecha de Cumpleaños</label>
                                            <input type="date" class="form-control" name="fecha_de_cumpleaños" id="edit_fecha_de_cumpleaños" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Rol</label>
                                            <select class="form-control" name="rol" id="edit_rol" required>
                                            <option value="admin">Admin</option>
                                            <option value="vendedor">vendedor</option>
                                            <option value="superadmin">Super Admin</option>
                                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label style="color: #000;">Nueva Contraseña (opcional)</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn" style="background-color:#2c4926; color:#fff">Guardar cambios</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function abrirModalEditarAdmin(admin) {
                            $('#edit_id').val(admin.id);
                            $('#edit_primer_nombre').val(admin.primer_nombre);
                            $('#edit_segundo_nombre').val(admin.segundo_nombre);
                            $('#edit_primer_apellido').val(admin.primer_apellido);
                            $('#edit_segundo_apellido').val(admin.segundo_apellido);
                            $('#edit_correo').val(admin.correo);
                            $('#edit_fecha_de_cumpleaños').val(admin.fecha_de_cumpleaños);
                            $('#edit_rol').val(admin.rol);
                            $('#modalEditarAdmin').modal('show');
                        }

                        function eliminarAdmin(id) {
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
                                    window.location.href = "acciones/admin/eliminar_admin.php?id=" + id;
                                }
                            });
                        }
                    </script>
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
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }
            $('#dataTable').DataTable({
                "ordering": false
            });
        });
    </script>
    <script>
$(document).ready(function() {
    // Mostrar SweetAlert según el parámetro success/error en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        Swal.fire('¡Éxito!', 'Administrador creado correctamente.', 'success');
    }
    if (urlParams.get('success') === '2') {
        Swal.fire('¡Éxito!', 'Administrador editado correctamente.', 'success');
    }
    if (urlParams.get('success') === '3') {
        Swal.fire('¡Éxito!', 'Administrador eliminado correctamente.', 'success');
    }
    if (urlParams.get('error')) {
        Swal.fire('Error', 'Ocurrió un error en la operación.', 'error');
    }
});
</script>
</body>
</html>