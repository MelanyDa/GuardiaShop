<?php
include 'db.php';
$mensaje = '';
$mostrar_formulario = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $sql = "SELECT id, token_expira FROM usuario WHERE token='$token'";
    $result = $conn->query($sql);

    if (!$result) {
        $mensaje = "Error en la consulta: " . $conn->error;
    } elseif ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (strtotime($row['token_expira']) > time()) {
            $mostrar_formulario = true;
        } else {
            $mensaje = "El enlace ha expirado.";
        }
    } else {
        $mensaje = "Token inválido.";
    }
} else {
    $mensaje = "Token no proporcionado.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9e4d5 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-container {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(183, 135, 50, 0.15), 0 1.5px 8px rgba(0,0,0,0.07);
            padding: 38px 32px 28px 32px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            border: 1.5px solid #e6e2c3;
        }
        .reset-container h2 {
            color: #b78732;
            font-weight: 600;
            margin-bottom: 18px;
            font-size: 2rem;
            letter-spacing: 1px;
        }
        .reset-container label {
            display: block;
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 8px;
            text-align: left;
            font-weight: 500;
        }
        .reset-container input[type="password"] {
            border: 1.5px solid #b78732;
            border-radius: 7px;
            padding: 12px;
            font-size: 1rem;
            margin-bottom: 18px;
            outline: none;
            transition: border-color 0.2s;
            background: #f8f9fa;
            width: 100%;
            box-sizing: border-box;
        }
        .reset-container input[type="password"]:focus {
            border-color: #8d6a1e;
            background: #fffbe7;
        }
        .reset-container button {
            background: linear-gradient(90deg, #b78732 60%, #8d6a1e 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 12px 0;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(183, 135, 50, 0.08);
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: 8px;
            width: 100%;
            white-space: normal;
            word-break: break-word;
            text-align: center;
            box-sizing: border-box;
        }
        .reset-container button:hover {
            background: linear-gradient(90deg, #8d6a1e 60%, #b78732 100%);
            box-shadow: 0 4px 16px rgba(183, 135, 50, 0.13);
        }
        .reset-container .volver {
            display: block;
            margin-top: 22px;
            color: #b78732;
            text-decoration: none;
            font-size: 0.98rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .reset-container .volver:hover {
            color: #8d6a1e;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Restablecer contraseña</h2>
        <?php if ($mostrar_formulario): ?>
            <form id="resetForm" method="POST" action="guardar_nueva.php" autocomplete="off">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label for="nueva_contraseña">Nueva contraseña:</label>
                <input type="password" name="nueva_contraseña" id="nueva_contraseña" required placeholder="Nueva contraseña">
                <button type="submit">Actualizar</button>
            </form>
            <a href="login.php" class="volver">Volver al inicio de sesión</a>
            <script>
                document.getElementById('resetForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    var form = this;
                    var formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Contraseña actualizada!',
                            text: 'Tu contraseña ha sido cambiada exitosamente.',
                            confirmButtonColor: '#b78732'
                        }).then(()=>{ window.location.href='login.php'; });
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al actualizar la contraseña.',
                            confirmButtonColor: '#b78732'
                        });
                    });
                });
            </script>
        <?php else: ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo addslashes($mensaje); ?>',
                    confirmButtonColor: '#b78732'
                }).then(()=>{ window.location.href='login.php'; });
            </script>
            <a href="login.php" class="volver">Volver al inicio de sesión</a>
        <?php endif; ?>
    </div>
</body>
</html>
