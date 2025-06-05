<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Restringir acceso solo a admin, super_admin o vendedor
if (!isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'super_admin', 'vendedor'])) {
    header('Location: /guardiashop/login/login.php');
    exit();
}

include $_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/conexion.php';

$id_usuario = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : (isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null);
$mensaje = '';

// Actualizar datos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primer_nombre = $conn->real_escape_string($_POST['primer_nombre']);
    $segundo_nombre = $conn->real_escape_string($_POST['segundo_nombre']);
    $primer_apellido = $conn->real_escape_string($_POST['primer_apellido']);
    $segundo_apellido = $conn->real_escape_string($_POST['segundo_apellido']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $fecha_de_cumpleanos = $conn->real_escape_string($_POST['fecha_de_cumpleanos']);

    $sql = "UPDATE usuario SET primer_nombre='$primer_nombre', segundo_nombre='$segundo_nombre', primer_apellido='$primer_apellido', segundo_apellido='$segundo_apellido', correo='$correo', fecha_de_cumpleaños='$fecha_de_cumpleanos' WHERE id=$id_usuario";
    if ($conn->query($sql)) {
        $mensaje = '<div class="alert alert-success">¡Datos actualizados correctamente!</div>';
        // Actualizar datos en sesión
        $_SESSION['admin_usuario'] = $primer_nombre . ' ' . $primer_apellido;
        $_SESSION['admin_email'] = $correo;
    } else {
        $mensaje = '<div class="alert alert-danger">Error al actualizar los datos: ' . $conn->error . '</div>';
    }
}

// Consultar datos actuales
$sql = "SELECT * FROM usuario WHERE id=$id_usuario";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .perfil-container { max-width: 600px; margin: 40px auto; }
        .perfil-avatar { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #007bff; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container perfil-container">
        <h2 class="mb-4 text-center">Mi Perfil</h2>
        <?php echo $mensaje; ?>
        <div class="text-center">
            <img src="<?php echo isset($_SESSION['user_icon']) ? '/' . ltrim($_SESSION['user_icon'], '/') : '/guardiashop/admin_gs/Panel/img/user.png'; ?>" alt="Avatar" class="perfil-avatar">
        </div>
        <form method="post" class="mt-4">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Primer nombre</label>
                    <input type="text" name="primer_nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['primer_nombre']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Segundo nombre</label>
                    <input type="text" name="segundo_nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['segundo_nombre']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Primer apellido</label>
                    <input type="text" name="primer_apellido" class="form-control" value="<?php echo htmlspecialchars($usuario['primer_apellido']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Segundo apellido</label>
                    <input type="text" name="segundo_apellido" class="form-control" value="<?php echo htmlspecialchars($usuario['segundo_apellido']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
            </div>
            <div class="form-group">
                <label>Fecha de cumpleaños</label>
                <input type="date" name="fecha_de_cumpleanos" class="form-control" value="<?php echo htmlspecialchars($usuario['fecha_de_cumpleaños']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Guardar cambios</button>
        </form>
        <div class="mt-4 text-center">
            
            <a href="/guardiashop/admin_gs/Panel/index.php" class="btn btn-secondary">Volver al inicio</a>
        </div>
    </div>
</body>
</html>
