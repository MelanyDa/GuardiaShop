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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestionar Proveedores</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700,900" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/menu.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/nav.php'); ?>

                <div class="container-fluid">
                    <div class="proveedores-header">
                        <h1 class="h3 mb-4" style="color: #2c4926; font-weight: bold;">
                            <i class="fas fa-truck" style="margin-right:8px;"></i>
                            <strong>Gestionar Proveedores</strong>
                        </h1>
                    </div>
                    <!-- Conexión y consulta -->
                    <?php
                    $conn = new mysqli("localhost", "root", "", "guardiashop");
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT id_proveedor, nombre_empresa, nombre_contacto, correo, telefono, direccion, ciudad, pais, nit_o_ruc, fecha_registro, activo FROM proveedores";
                    $resultado = $conn->query($sql);
                    ?>

                    <!-- Tabla -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold" style="color: #2c4926;">Listado de Proveedores</h6>
                            <button class="btn" style="background-color: #2c4926; color: #fff;" data-toggle="modal" data-target="#agregarProveedorModal">
                                <i class="fas fa-plus"></i> Añadir Proveedor
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead style="background-color: #2c4926; color: #fff;">
                                        <tr>
                                            <th>Empresa</th>
                                            <th>Contacto</th>
                                            <th>Correo</th>
                                            <th>Teléfono</th>
                                            <th>Dirección</th>
                                            <th>Ciudad</th>
                                            <th>País</th>
                                            <th>NIT/RUC</th>
                                            <th>Fecha Registro</th>
                                            <th>Activo</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $resultado->fetch_assoc()): ?>
                                            <tr>
                                                <td style="color: #000;"><?php echo $row['nombre_empresa']; ?></td>
                                                <td style="color: #000;"><?php echo $row['nombre_contacto']; ?></td>
                                                <td style="color: #000;"><?php echo $row['correo']; ?></td>
                                                <td style="color: #000;"><?php echo $row['telefono']; ?></td>
                                                <td style="color: #000;"><?php echo $row['direccion']; ?></td>
                                                <td style="color: #000;"><?php echo $row['ciudad']; ?></td>
                                                <td style="color: #000;"><?php echo $row['pais']; ?></td>
                                                <td style="color: #000;"><?php echo $row['nit_o_ruc']; ?></td>
                                                <td style="color: #000;"><?php echo $row['fecha_registro']; ?></td>
                                                <td style="color: #000;"><?php echo $row['activo'] ? 'Sí' : 'No'; ?></td>
                                                <td class="d-flex justify-content-center align-items-center" style="gap: 5px;">
                                                    <button class="btn btn-danger btn-sm" onclick="eliminarProveedor(<?php echo $row['id_proveedor']; ?>)">
                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                    </button>
                                                    <button class="btn btn-warning btn-sm" onclick='abrirModalEditarProveedor(<?php echo json_encode($row); ?>)'>
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                </td>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <style>
                   #agregarProveedorModal .modal-dialog {
    margin-left: 27vw; /* Ajusta el valor según lo que necesites */
}
                 </style>
                    <!-- Modal para agregar proveedor -->
                    <div class="modal fade" id="agregarProveedorModal" tabindex="-1" role="dialog" aria-labelledby="agregarProveedorModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <form id="formAgregarProveedor" method="POST" action="acciones/proveedores/guardar_proveedor.php">
                                    <div class="modal-header "style="background:#2c4926;color:#fff;">
                                    <h5 class="modal-title"  id="modalAgregarAdminLabel"><i class="fas fa-truck"></i> Nuevo proveedor</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true" style="color: #fff;">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body row">
                                        <div class="form-group col-md-6">
                                            <label style="color: #000;">Nombre Empresa</label>
                                            <input type="text" name="nombre_empresa" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label style="color: #000;">Nombre Contacto</label>
                                            <input type="text" name="nombre_contacto" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label style="color: #000;">Correo</label>
                                            <input type="email" name="correo" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label style="color: #000;">Teléfono</label>
                                            <input type="text" name="telefono" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label style="color: #000;">Dirección</label>
                                            <input type="text" name="direccion" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label style="color: #000;">Ciudad</label>
                                            <input type="text" name="ciudad" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label style="color: #000;">País</label>
                                            <input type="text" name="pais" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label style="color: #000;">NIT o RUC</label>
                                            <input type="text" name="nit_o_ruc" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label style="color: #000;">Activo</label>
                                            <select name="activo" class="form-control">
                                                <option value="1">Sí</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn" style="background-color: #2c4926; color: #fff;">Guardar</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                        <!-- Modal Editar Proveedor -->
                        <div class="modal fade" id="modalEditarProveedor" tabindex="-1" role="dialog" aria-labelledby="modalEditarProveedorLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <form id="formEditarProveedor" method="POST" action="acciones/proveedores/editar_proveedor.php">
                            <div class="modal-content">
                                <div class="modal-header" style="background:#2c4926;color:#fff;">
                                <h5 class="modal-title" id="modalEditarProveedorLabel"><i class="fas fa-edit"></i> Editar Proveedor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body row">
                                <input type="hidden" name="id_proveedor" id="edit_id_proveedor">
                                <div class="form-group col-md-6">
                                    <label style="color: #000;">Nombre Empresa</label>
                                    <input type="text" name="nombre_empresa" id="edit_nombre_empresa" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label style="color: #000;">Nombre Contacto</label>
                                    <input type="text" name="nombre_contacto" id="edit_nombre_contacto" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label style="color: #000;">Correo</label>
                                    <input type="email" name="correo" id="edit_correo" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label style="color: #000;">Teléfono</label>
                                    <input type="text" name="telefono" id="edit_telefono" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label style="color: #000;">Dirección</label>
                                    <input type="text" name="direccion" id="edit_direccion" class="form-control" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label style="color: #000;">Ciudad</label>
                                    <input type="text" name="ciudad" id="edit_ciudad" class="form-control" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label style="color: #000;">País</label>
                                    <input type="text" name="pais" id="edit_pais" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label style="color: #000;">NIT o RUC</label>
                                    <input type="text" name="nit_o_ruc" id="edit_nit_o_ruc" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label style="color: #000;">Activo</label>
                                    <select name="activo" id="edit_activo" class="form-control">
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                    </select>
                                </div>
                                </div>
                                <div class="modal-footer">
                                <button type="submit" class="btn" style="background-color: #2c4926; color: #fff;">Guardar cambios</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] == 1): ?>
<script>
    Swal.fire({
        title: '¡Proveedor eliminado!',
        text: 'El proveedor fue eliminado exitosamente.',
        icon: 'success',
        
    });
</script>
<?php endif; ?>
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

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
    Swal.fire({
        title: '¡Proveedor agregado!',
        text: 'El proveedor fue registrado exitosamente.',
        icon: 'success',
        confirmButtonText: 'Aceptar'
    });
</script>
<?php endif; ?>
<script>
function eliminarProveedor(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'acciones/proveedores/eliminar_proveedor.php?id=' + id;
        }
    });
}
</script>
<script>
function abrirModalEditarProveedor(proveedor) {
    $('#edit_id_proveedor').val(proveedor.id_proveedor);
    $('#edit_nombre_empresa').val(proveedor.nombre_empresa);
    $('#edit_nombre_contacto').val(proveedor.nombre_contacto);
    $('#edit_correo').val(proveedor.correo);
    $('#edit_telefono').val(proveedor.telefono);
    $('#edit_direccion').val(proveedor.direccion);
    $('#edit_ciudad').val(proveedor.ciudad);
    $('#edit_pais').val(proveedor.pais);
    $('#edit_nit_o_ruc').val(proveedor.nit_o_ruc);
    $('#edit_activo').val(proveedor.activo);
    $('#modalEditarProveedor').modal('show');
}
</script>
<?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
<script>
    Swal.fire({
        title: '¡Proveedor actualizado!',
        text: 'Los datos del proveedor fueron actualizados exitosamente.',
        icon: 'success',
        confirmButtonText: 'Aceptar'
    });
</script>
<?php endif; ?>
</body>
</html>
