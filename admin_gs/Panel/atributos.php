<?php
/*
session_start();
if (!isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'super_admin', 'vendedor'])) {
    header('Location: ../login.php');
    exit();
}
*/
?>
<?php
session_start(); // <-- Añade esto al inicio
include_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/conexion.php');

// Procesar formularios antes de cualquier salida HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // AGREGAR
    if (isset($_POST['agregar_categoria'])) {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre_categoria']);
        $desc = mysqli_real_escape_string($conn, $_POST['descripcion_categoria']);
        mysqli_query($conn, "INSERT INTO categoria (nombre, descripcion) VALUES ('$nombre', '$desc')");
        $_SESSION['exito'] = "Categoría añadida con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['agregar_sesion'])) {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre_sesion']);
        mysqli_query($conn, "INSERT INTO sesiones (nombre) VALUES ('$nombre')");
        $_SESSION['exito'] = "Sesión añadida con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['agregar_color'])) {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre_color']);
        $hex = mysqli_real_escape_string($conn, $_POST['codigo_hexadecimal']);
        mysqli_query($conn, "INSERT INTO color_productos (nombre, codigo_hexadecimal) VALUES ('$nombre', '$hex')");
        $_SESSION['exito'] = "Color añadido con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['agregar_talla'])) {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre_talla']);
        mysqli_query($conn, "INSERT INTO talla_productos (nombre_talla) VALUES ('$nombre')");
        $_SESSION['exito'] = "Talla añadida con éxito";
        header("Location: atributos.php");
        exit;
    }

    // ELIMINAR
    if (isset($_POST['eliminar_categoria'])) {
        $id = intval($_POST['eliminar_categoria']);
        mysqli_query($conn, "DELETE FROM categoria WHERE id_categoria = $id");
        $_SESSION['exito'] = "Categoría eliminada con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['eliminar_sesion'])) {
        $id = intval($_POST['eliminar_sesion']);
        mysqli_query($conn, "DELETE FROM sesiones WHERE id_sesion = $id");
        $_SESSION['exito'] = "Sesión eliminada con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['eliminar_color'])) {
        $id = intval($_POST['eliminar_color']);
        mysqli_query($conn, "DELETE FROM color_productos WHERE id_color = $id");
        $_SESSION['exito'] = "Color eliminado con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['eliminar_talla'])) {
        $id = intval($_POST['eliminar_talla']);
        mysqli_query($conn, "DELETE FROM talla_productos WHERE id_talla = $id");
        $_SESSION['exito'] = "Talla eliminada con éxito";
        header("Location: atributos.php");
        exit;
    }

    // EDITAR
    if (isset($_POST['editar_categoria'])) {
        $id = intval($_POST['editar_categoria']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre_categoria_edit']);
        $desc = mysqli_real_escape_string($conn, $_POST['descripcion_categoria_edit']);
        mysqli_query($conn, "UPDATE categoria SET nombre='$nombre', descripcion='$desc' WHERE id_categoria = $id");
        $_SESSION['exito'] = "Categoría editada con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['editar_sesion'])) {
        $id = intval($_POST['editar_sesion']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre_sesion_edit']);
        mysqli_query($conn, "UPDATE sesiones SET nombre='$nombre' WHERE id_sesion = $id");
        $_SESSION['exito'] = "Sesión editada con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['editar_color'])) {
        $id = intval($_POST['editar_color']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre_color_edit']);
        $hex = mysqli_real_escape_string($conn, $_POST['codigo_hexadecimal_edit']);
        mysqli_query($conn, "UPDATE color_productos SET nombre='$nombre', codigo_hexadecimal='$hex' WHERE id_color = $id");
        $_SESSION['exito'] = "Color editado con éxito";
        header("Location: atributos.php");
        exit;
    }
    if (isset($_POST['editar_talla'])) {
        $id = intval($_POST['editar_talla']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre_talla_edit']);
        mysqli_query($conn, "UPDATE talla_productos SET nombre_talla='$nombre' WHERE id_talla = $id");
        $_SESSION['exito'] = "Talla editada con éxito";
        header("Location: atributos.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title> Gestionar Atributos</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/menu.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/nav.php'); ?>
                <div class="container-fluid">
                        <!-- SweetAlert de éxito -->
                    <?php if (isset($_SESSION['exito'])): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: '<?php echo $_SESSION['exito']; ?>',
                                    confirmButtonColor: '#3085d6'
                                });
                            });
                        </script>
                        <?php unset($_SESSION['exito']); ?>
                    <?php endif; ?>
                    <h1 class="h3 mb-4 font-weight-bold" style="color: #2c4926;margin-left:10px;">
                        <i class="fas fa-fw fa-tags me-2" style="margin-right: 12px;"></i>
                        <b>Atributos</b>
                    </h1>
                    <div class="row">
                        <!-- Categorías -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow border-left-success">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-list"></i> Categorías</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="color: #000;">Nombre</th>
                                                    <th style="color: #000;">Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cat = mysqli_query($conn, "SELECT * FROM categoria");
                                                while($row = mysqli_fetch_assoc($cat)){
                                                    echo "<tr>
                                                        <td style='color: #000;'>{$row['nombre']}</td>
                                                        <td style='color: #000;'>{$row['descripcion']}</td>
                                                        </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <form method="POST" class="mt-3">
                                        <div class="form-row">
                                            <div class="col">
                                                <input type="text" name="nombre_categoria" class="form-control" placeholder="Nombre" required>
                                            </div>
                                            <div class="col">
                                                <input type="text" name="descripcion_categoria" class="form-control" placeholder="Descripción">
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" name="agregar_categoria" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="mt-2">
    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCategoria()"><i class="fas fa-trash"></i> Eliminar</button>
    <button type="button" class="btn btn-secondary btn-sm" onclick="editarCategoria()"><i class="fas fa-edit"></i> Editar</button>
</div>
                                </div>
                            </div>
                        </div>
                        <!-- Sesiones -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow border-left-primary">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-layer-group"></i> Sesiones</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="color: #000;">Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ses = mysqli_query($conn, "SELECT * FROM sesiones");
                                                while($row = mysqli_fetch_assoc($ses)){
                                                    echo "<tr>
                                                            <td style='color: #000;'>{$row['nombre']}</td>
                                                        </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <form method="POST" class="mt-3">
                                        <div class="form-row">
                                            <div class="col">
                                                <input type="text" name="nombre_sesion" class="form-control" placeholder="Nombre" required>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" name="agregar_sesion" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="mt-2">
   <button type="button" class="btn btn-danger btn-sm" onclick="eliminarSesion()"><i class="fas fa-trash"></i> Eliminar</button>
    <button type="button" class="btn btn-secondary btn-sm" onclick="editarSesion()"><i class="fas fa-edit"></i> Editar</button>
</div>
                                </div>
                            </div>
                        </div>
                        <!-- Colores -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow border-left-warning">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-palette"></i> Colores</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="color: #000;">Nombre</th>
                                                    <th style="color: #000;">Color</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $col = mysqli_query($conn, "SELECT * FROM color_productos");
                                                while($row = mysqli_fetch_assoc($col)){
                                                    echo "<tr>
                                                            <td style='color: #000;'>{$row['nombre']}</td>
                                                            <td style='color: #000;'><span style='display:inline-block;width:25px;height:25px;background:{$row['codigo_hexadecimal']};border-radius:4px;border:1px solid #ccc;'></span> {$row['codigo_hexadecimal']}</td>
                                                        </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <form method="POST" class="mt-3">
                                        <div class="form-row">
                                            <div class="col">
                                                <input type="text" name="nombre_color" class="form-control" placeholder="Nombre" required>
                                            </div>
                                            <div class="col">
                                                <input type="color" name="codigo_hexadecimal" class="form-control" style="height:38px;" required>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" name="agregar_color" class="btn btn-warning btn-sm"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="mt-2">
     <button type="button" class="btn btn-danger btn-sm" onclick="eliminarColor()"><i class="fas fa-trash"></i> Eliminar</button>
    <button type="button" class="btn btn-secondary btn-sm" onclick="editarColor()"><i class="fas fa-edit"></i> Editar</button>
</div>
                                </div>
                            </div>
                        </div>
                        <!-- Tallas -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow border-left-info">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-ruler"></i> Tallas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="color: #000;">Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $tal = mysqli_query($conn, "SELECT * FROM talla_productos");
                                                while($row = mysqli_fetch_assoc($tal)){
                                                    echo "<tr>
                                                            <td style='color: #000;'>{$row['nombre_talla']}</td>
                                                        </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <form method="POST" class="mt-3">
                                        <div class="form-row">
                                            <div class="col">
                                                <input type="text" name="nombre_talla" class="form-control" placeholder="Nombre" required>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" name="agregar_talla" class="btn btn-info btn-sm"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarTalla()"><i class="fas fa-trash"></i> Eliminar</button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="editarTalla()"><i class="fas fa-edit"></i> Editar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Page Content -->
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
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

<script>
  // --------- CATEGORÍA ---------
function eliminarCategoria() {
    <?php
    $cat = mysqli_query($conn, "SELECT * FROM categoria");
    $categorias = [];
    while($row = mysqli_fetch_assoc($cat)){
        $categorias[$row['id_categoria']] = $row['nombre'];
    }
    ?>
    let categorias = <?php echo json_encode($categorias); ?>;
    let options = '';
    for (let id in categorias) {
        options += `<option value="${id}">${categorias[id]}</option>`;
    }
    Swal.fire({
        title: 'Eliminar Categoría',
        html: `<select id="categoriaSelect" class="form-control">${options}</select>`,
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => document.getElementById('categoriaSelect').value
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="eliminar_categoria" value="${result.value}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function editarCategoria() {
    <?php
    $cat = mysqli_query($conn, "SELECT * FROM categoria");
    $categorias = [];
    while($row = mysqli_fetch_assoc($cat)){
        $categorias[$row['id_categoria']] = ['nombre' => $row['nombre'], 'descripcion' => $row['descripcion']];
    }
    ?>
    let categorias = <?php echo json_encode($categorias); ?>;
    let options = '';
    for (let id in categorias) {
        options += `<option value="${id}">${categorias[id]['nombre']}</option>`;
    }
    Swal.fire({
        title: 'Editar Categoría',
        html: `<select id="categoriaSelect" class="form-control mb-2">${options}</select>
               <input id="nombreEdit" class="form-control mb-2" placeholder="Nuevo nombre">
               <input id="descEdit" class="form-control" placeholder="Nueva descripción">`,
        showCancelButton: true,
        confirmButtonText: 'Editar',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            let select = document.getElementById('categoriaSelect');
            select.addEventListener('change', function() {
                document.getElementById('nombreEdit').value = categorias[this.value]['nombre'];
                document.getElementById('descEdit').value = categorias[this.value]['descripcion'];
            });
            select.dispatchEvent(new Event('change'));
        },
        preConfirm: () => {
            return {
                id: document.getElementById('categoriaSelect').value,
                nombre: document.getElementById('nombreEdit').value,
                descripcion: document.getElementById('descEdit').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="editar_categoria" value="${result.value.id}">
                              <input type="hidden" name="nombre_categoria_edit" value="${result.value.nombre}">
                              <input type="hidden" name="descripcion_categoria_edit" value="${result.value.descripcion}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// --------- SESIÓN ---------
function eliminarSesion() {
    <?php
    $ses = mysqli_query($conn, "SELECT * FROM sesiones");
    $sesiones = [];
    while($row = mysqli_fetch_assoc($ses)){
        $sesiones[$row['id_sesion']] = $row['nombre'];
    }
    ?>
    let sesiones = <?php echo json_encode($sesiones); ?>;
    let options = '';
    for (let id in sesiones) {
        options += `<option value="${id}">${sesiones[id]}</option>`;
    }
    Swal.fire({
        title: 'Eliminar Sesión',
        html: `<select id="sesionSelect" class="form-control">${options}</select>`,
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => document.getElementById('sesionSelect').value
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="eliminar_sesion" value="${result.value}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function editarSesion() {
    <?php
    $ses = mysqli_query($conn, "SELECT * FROM sesiones");
    $sesiones = [];
    while($row = mysqli_fetch_assoc($ses)){
        $sesiones[$row['id_sesion']] = $row['nombre'];
    }
    ?>
    let sesiones = <?php echo json_encode($sesiones); ?>;
    let options = '';
    for (let id in sesiones) {
        options += `<option value="${id}">${sesiones[id]}</option>`;
    }
    Swal.fire({
        title: 'Editar Sesión',
        html: `<select id="sesionSelect" class="form-control mb-2">${options}</select>
               <input id="nombreSesionEdit" class="form-control" placeholder="Nuevo nombre">`,
        showCancelButton: true,
        confirmButtonText: 'Editar',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            let select = document.getElementById('sesionSelect');
            select.addEventListener('change', function() {
                document.getElementById('nombreSesionEdit').value = sesiones[this.value];
            });
            select.dispatchEvent(new Event('change'));
        },
        preConfirm: () => {
            return {
                id: document.getElementById('sesionSelect').value,
                nombre: document.getElementById('nombreSesionEdit').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="editar_sesion" value="${result.value.id}">
                              <input type="hidden" name="nombre_sesion_edit" value="${result.value.nombre}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// --------- COLOR ---------
function eliminarColor() {
    <?php
    $col = mysqli_query($conn, "SELECT * FROM color_productos");
    $colores = [];
    while($row = mysqli_fetch_assoc($col)){
        $colores[$row['id_color']] = $row['nombre'];
    }
    ?>
    let colores = <?php echo json_encode($colores); ?>;
    let options = '';
    for (let id in colores) {
        options += `<option value="${id}">${colores[id]}</option>`;
    }
    Swal.fire({
        title: 'Eliminar Color',
        html: `<select id="colorSelect" class="form-control">${options}</select>`,
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => document.getElementById('colorSelect').value
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="eliminar_color" value="${result.value}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function editarColor() {
    <?php
    $col = mysqli_query($conn, "SELECT * FROM color_productos");
    $colores = [];
    while($row = mysqli_fetch_assoc($col)){
        $colores[$row['id_color']] = ['nombre' => $row['nombre'], 'codigo_hexadecimal' => $row['codigo_hexadecimal']];
    }
    ?>
    let colores = <?php echo json_encode($colores); ?>;
    let options = '';
    for (let id in colores) {
        options += `<option value="${id}">${colores[id]['nombre']}</option>`;
    }
    Swal.fire({
        title: 'Editar Color',
        html: `<select id="colorSelect" class="form-control mb-2">${options}</select>
               <input id="nombreColorEdit" class="form-control mb-2" placeholder="Nuevo nombre">
               <input id="hexColorEdit" type="color" class="form-control" style="height:38px;">`,
        showCancelButton: true,
        confirmButtonText: 'Editar',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            let select = document.getElementById('colorSelect');
            select.addEventListener('change', function() {
                document.getElementById('nombreColorEdit').value = colores[this.value]['nombre'];
                document.getElementById('hexColorEdit').value = colores[this.value]['codigo_hexadecimal'];
            });
            select.dispatchEvent(new Event('change'));
        },
        preConfirm: () => {
            return {
                id: document.getElementById('colorSelect').value,
                nombre: document.getElementById('nombreColorEdit').value,
                hex: document.getElementById('hexColorEdit').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="editar_color" value="${result.value.id}">
                              <input type="hidden" name="nombre_color_edit" value="${result.value.nombre}">
                              <input type="hidden" name="codigo_hexadecimal_edit" value="${result.value.hex}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// --------- TALLA ---------
function eliminarTalla() {
    <?php
    $tal = mysqli_query($conn, "SELECT * FROM talla_productos");
    $tallas = [];
    while($row = mysqli_fetch_assoc($tal)){
        $tallas[$row['id_talla']] = $row['nombre_talla'];
    }
    ?>
    let tallas = <?php echo json_encode($tallas); ?>;
    let options = '';
    for (let id in tallas) {
        options += `<option value="${id}">${tallas[id]}</option>`;
    }
    Swal.fire({
        title: 'Eliminar Talla',
        html: `<select id="tallaSelect" class="form-control">${options}</select>`,
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => document.getElementById('tallaSelect').value
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="eliminar_talla" value="${result.value}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function editarTalla() {
    <?php
    $tal = mysqli_query($conn, "SELECT * FROM talla_productos");
    $tallas = [];
    while($row = mysqli_fetch_assoc($tal)){
        $tallas[$row['id_talla']] = $row['nombre_talla'];
    }
    ?>
    let tallas = <?php echo json_encode($tallas); ?>;
    let options = '';
    for (let id in tallas) {
        options += `<option value="${id}">${tallas[id]}</option>`;
    }
    Swal.fire({
        title: 'Editar Talla',
        html: `<select id="tallaSelect" class="form-control mb-2">${options}</select>
               <input id="nombreTallaEdit" class="form-control" placeholder="Nuevo nombre">`,
        showCancelButton: true,
        confirmButtonText: 'Editar',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            let select = document.getElementById('tallaSelect');
            select.addEventListener('change', function() {
                document.getElementById('nombreTallaEdit').value = tallas[this.value];
            });
            select.dispatchEvent(new Event('change'));
        },
        preConfirm: () => {
            return {
                id: document.getElementById('tallaSelect').value,
                nombre: document.getElementById('nombreTallaEdit').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="editar_talla" value="${result.value.id}">
                              <input type="hidden" name="nombre_talla_edit" value="${result.value.nombre}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>


</body>
</html>