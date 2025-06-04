<?php
/*
session_start();
if (!isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'super_admin', 'vendedor'])) {
    header('Location: ../login.php');
    exit();
}
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/conexion.php');
require_once __DIR__ . '/libs_backup/vendor/autoload.php';
use Ifsnop\Mysqldump as Mysqldump;

// 1. Crear carpeta backups si no existe
$backup_dir = __DIR__ . '/../backups';
if (!file_exists($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

// 2. Generar copia de seguridad manual
if (isset($_POST['backup_manual'])) {
    $fecha = date('Y-m-d_H-i-s');
    $nombre_archivo = "backup_guardiashop_{$fecha}.sql";
    $ruta_archivo = $backup_dir . '/' . $nombre_archivo;

    $exito = false;
    $error_backup = '';
    try {
        $dump = new Mysqldump\Mysqldump('mysql:host=localhost;dbname=guardiashop', 'root', '');
        $dump->start($ruta_archivo);
        $exito = true;
    } catch (Exception $e) {
        $error_backup = $e->getMessage();
    }

    if ($exito && file_exists($ruta_archivo)) {
        $tamano = filesize($ruta_archivo);
        $fecha_actual = date('Y-m-d H:i:s');
        // Registrar en la base de datos
        $stmt = $conn->prepare("INSERT INTO copias_seguridad (nombre_archivo, fecha, tamano, frecuencia) VALUES (?, ?, ?, ?)");
        $frecuencia = 'manual';
        $stmt->bind_param('ssis', $nombre_archivo, $fecha_actual, $tamano, $frecuencia);
        $stmt->execute();
        $stmt->close();
        $swal = [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Copia de seguridad creada correctamente.'
        ];
    } else {
        $swal = [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'No se pudo crear la copia de seguridad. ' . $error_backup
        ];
    }
}

// 3. Eliminar copia de seguridad
if (isset($_POST['eliminar_copia']) && isset($_POST['id_copia'])) {
    $id_copia = intval($_POST['id_copia']);
    $sql = "SELECT nombre_archivo FROM copias_seguridad WHERE id = $id_copia";
    $res = $conn->query($sql);
    if ($row = $res->fetch_assoc()) {
        $archivo = $backup_dir . '/' . $row['nombre_archivo'];
        if (file_exists($archivo)) {
            unlink($archivo);
        }
        $conn->query("DELETE FROM copias_seguridad WHERE id = $id_copia");
        echo "<script>Swal.fire('Eliminado', 'La copia de seguridad fue eliminada.', 'success');</script>";
    }
}

// 4. Descargar copia de seguridad
if (isset($_GET['descargar']) && is_numeric($_GET['descargar'])) {
    $id_copia = intval($_GET['descargar']);
    $sql = "SELECT nombre_archivo FROM copias_seguridad WHERE id = $id_copia";
    $res = $conn->query($sql);
    if ($row = $res->fetch_assoc()) {
        $archivo = $backup_dir . '/' . $row['nombre_archivo'];
        if (file_exists($archivo)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($archivo) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivo));
            readfile($archivo);
            exit;
        }
    }
}

// 5. Obtener todas las copias de seguridad
$copias = $conn->query("SELECT * FROM copias_seguridad ORDER BY fecha DESC");

// 6. Guardar frecuencia automática
if (isset($_POST['frecuencia'])) {
    $frecuencia = $_POST['frecuencia'];
    $dias_personalizados = ($frecuencia === 'personalizada' && !empty($_POST['dias_personalizados'])) ? intval($_POST['dias_personalizados']) : null;
    // Si ya existe el registro, actualiza; si no, inserta
    $res = $conn->query("SELECT id FROM configuracion_backup WHERE id=1");
    if ($res && $res->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE configuracion_backup SET frecuencia=?, dias_personalizados=? WHERE id=1");
        $stmt->bind_param('si', $frecuencia, $dias_personalizados);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO configuracion_backup (id, frecuencia, dias_personalizados) VALUES (1, ?, ?)");
        $stmt->bind_param('si', $frecuencia, $dias_personalizados);
        $stmt->execute();
        $stmt->close();
    }
    echo "<script>Swal.fire('¡Guardado!', 'Frecuencia automática actualizada.', 'success');</script>";
}

// 7. Obtener frecuencia actual
$frecuencia_actual = 'diaria';
$dias_personalizados_actual = '';
$res = $conn->query("SELECT frecuencia, dias_personalizados FROM configuracion_backup WHERE id=1");
if ($res && $row = $res->fetch_assoc()) {
    $frecuencia_actual = $row['frecuencia'];
    $dias_personalizados_actual = $row['dias_personalizados'];
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
    <title>Copias de seguridad</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .modern-header {
            background: linear-gradient(90deg, #2c4926 60%, #6bbf59 100%);
            color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(44,73,38,0.15);
            padding: 2rem 2rem 1.5rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .modern-header i {
            font-size: 2.8rem;
            opacity: 0.9;
        }
        .modern-card {
            border-radius: 1.2rem;
            box-shadow: 0 2px 16px rgba(44,73,38,0.10);
            border: none;
            background: #f8fafb;
        }
        .modern-card .card-header {
            background: none;
            border-bottom: 1px solid #e3e6f0;
            border-radius: 1.2rem 1.2rem 0 0;
        }
        .modern-card .btn-success {
            background: linear-gradient(90deg, #6bbf59 60%, #2c4926 100%);
            border: none;
            color: #fff;
            font-weight: bold;
            transition: box-shadow 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px rgba(107,191,89,0.10);
        }
        .modern-card .btn-success:hover {
            box-shadow: 0 4px 16px rgba(44,73,38,0.18);
            transform: translateY(-2px) scale(1.04);
        }
        .modern-card .btn-outline-primary {
            border-radius: 0.5rem;
        }
        .modern-card .form-control, .modern-card .form-select {
            border-radius: 0.5rem;
        }
        .modern-table thead {
            background: linear-gradient(90deg, #2c4926 60%, #6bbf59 100%);
            color: #fff;
        }
        .modern-table tbody tr {
            transition: background 0.2s;
        }
        .modern-table tbody tr:hover {
            background: #eafbe6;
        }
        .badge-modern {
            background: #6bbf59;
            color: #fff;
            font-size: 0.9rem;
            border-radius: 0.5rem;
            padding: 0.3em 0.8em;
        }
        @media (max-width: 600px) {
            .modern-header { flex-direction: column; text-align: center; }
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
                    <div class="modern-header mb-4">
                        <i class="fas fa-database"></i>
                        <div>
                            <h1 class="h3 mb-1 font-weight-bold">Copias de seguridad <span class="badge-modern">Panel</span></h1>
                            <div style="font-size:1rem;opacity:0.85;">Gestiona y descarga tus respaldos de la base de datos de forma segura y automática.</div>
                        </div>
                    </div>
                    <div class="card modern-card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <!-- <form class="form-inline mb-2 mb-md-0" method="post" action="">
                                <label for="frecuencia" class="mr-2 font-weight-bold">Frecuencia automática:</label>
                                <select name="frecuencia" id="frecuencia" class="form-control mr-2">
                                    <option value="diaria" <?php if($frecuencia_actual=='diaria') echo 'selected'; ?>>Diaria</option>
                                    <option value="semanal" <?php if($frecuencia_actual=='semanal') echo 'selected'; ?>>Semanal</option>
                                    <option value="mensual" <?php if($frecuencia_actual=='mensual') echo 'selected'; ?>>Mensual</option>
                                    <option value="personalizada" <?php if($frecuencia_actual=='personalizada') echo 'selected'; ?>>Personalizada</option>
                                </select>
                                <input type="number" name="dias_personalizados" id="dias_personalizados" class="form-control mr-2" style="width:120px; display:<?php echo ($frecuencia_actual=='personalizada')?'block':'none'; ?>;" min="1" placeholder="Cada X días" value="<?php echo htmlspecialchars($dias_personalizados_actual); ?>">
                                <button type="submit" class="btn btn-outline-primary">Guardar</button>
                            </form> -->
                            <form method="post" action="">
                                <button type="submit" name="backup_manual" class="btn btn-success font-weight-bold shadow-sm" data-toggle="tooltip" data-placement="top" title="Generar copia ahora">
                                    <i class="fas fa-cloud-download-alt mr-1"></i> Realizar copia manual
                                </button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle modern-table" id="tablaCopias">
                                    <thead>
                                        <tr>
                                            <th>Archivo</th>
                                            <th>Fecha</th>
                                            <th>Tamaño</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($copias && $copias->num_rows > 0): ?>
                                            <?php while($copia = $copias->fetch_assoc()): ?>
                                                <tr>
                                                    <td><i class="fas fa-file-archive text-success mr-2"></i> <?php echo htmlspecialchars($copia['nombre_archivo']); ?></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($copia['fecha'])); ?></td>
                                                    <td><?php echo round($copia['tamano']/1024, 2); ?> KB</td>
                                                    <td>
                                                        <a href="?descargar=<?php echo $copia['id']; ?>" class="btn btn-sm "  style="background-color: #2c4926; color: #fff;"   title="Descargar"><i class="fas fa-download"></i></a>
                                                        <form method="post" action="" style="display:inline-block;" onsubmit="return confirm('¿Seguro que deseas eliminar esta copia?');">
                                                            <input type="hidden" name="id_copia" value="<?php echo $copia['id']; ?>">
                                                            <button type="submit" name="eliminar_copia" class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No hay copias de seguridad registradas aún.</td>
                                            </tr>
                                        <?php endif; ?>
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
    <script>
        $(document).ready(function() {
            $('#tablaCopias').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                },
                "ordering": false,
                "searching": false,
                "lengthChange": false,
                "info": false
            });
            // Mostrar input personalizado solo si se elige "personalizada"
            $('#frecuencia').on('change', function() {
                if($(this).val() === 'personalizada') {
                    $('#dias_personalizados').show();
                } else {
                    $('#dias_personalizados').hide();
                }
            });
            // Tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <?php if (isset($swal)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?php echo $swal['icon']; ?>',
                title: '<?php echo $swal['title']; ?>',
                text: '<?php echo $swal['text']; ?>'
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>