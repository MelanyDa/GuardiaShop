<?php
session_start();
include('../login/db.php');

if (!isset($_SESSION['id_pedido']) || !isset($_SESSION['total'])) {
    die("No hay pedido en curso.");
}

$id_pedido = $_SESSION['id_pedido'];
$total = $_SESSION['total'];

// Procesar formulario de datos del usuario
$nombre = '';
$correo = '';
$mostrar_formulario = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['datos_usuario'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    if ($nombre !== '' && $correo !== '') {
        $mostrar_formulario = false;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar con PayPal - GuardiaShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e3e9f7 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 420px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(52, 73, 94, 0.10);
            padding: 36px 30px 30px 30px;
            text-align: center;
        }
        .paypal-logo {
            margin-bottom: 18px;
        }
        .paypal-logo img {
            height: 38px;
        }
        h2 {
            color: #003087;
            margin-bottom: 18px;
        }
        .resumen {
            background: #f4f8fb;
            border-radius: 10px;
            padding: 18px 20px;
            margin: 22px auto 18px auto;
            box-shadow: 0 2px 10px #b2bec311;
            max-width: 320px;
            text-align: left;
            display: inline-block;
        }
        .resumen p {
            margin: 10px 0;
            color: #444;
            font-size: 16px;
        }
        .paypal-btn {
            background: linear-gradient(90deg, #009cde 60%, #003087 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 14px 0;
            width: 100%;
            font-size: 17px;
            margin: 18px 0 8px 0;
            cursor: pointer;
            box-shadow: 0 2px 8px #009cde33;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .paypal-btn:hover {
            opacity: 0.92;
            background: linear-gradient(90deg, #003087 60%, #009cde 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .nota {
            color: #888;
            font-size: 14px;
            margin-top: 18px;
        }
        .form-datos {
            margin: 0 auto 18px auto;
            max-width: 320px;
            background: #f4f8fb;
            border-radius: 10px;
            padding: 18px 20px;
            box-shadow: 0 2px 10px #b2bec311;
            text-align: left;
            display: inline-block;
        }
        .form-datos label {
            font-weight: bold;
            color: #003087;
            display: block;
            margin-bottom: 6px;
        }
        .form-datos input[type="text"],
        .form-datos input[type="email"] {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 14px;
            border: 1px solid #b2bec3;
            border-radius: 6px;
            font-size: 15px;
        }
        .form-datos button {
            background: linear-gradient(90deg, #009cde 60%, #003087 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 10px 0;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 8px #009cde33;
            transition: 0.2s;
        }
        .form-datos button:hover {
            opacity: 0.92;
            background: linear-gradient(90deg, #003087 60%, #009cde 100%);
        }
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="paypal-logo">
            <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal">
        </div>
        <h2>Pagar con PayPal</h2>

        <?php if ($mostrar_formulario): ?>
            <form class="form-datos" method="POST">
                <label for="nombre">Nombre completo</label>
                <input type="text" name="nombre" id="nombre" required value="<?= htmlspecialchars($nombre) ?>">

                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" required value="<?= htmlspecialchars($correo) ?>">

                <button type="submit" name="datos_usuario" value="1">
                    Continuar con el pago
                </button>
            </form>
        <?php else: ?>
            <div class="resumen">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre) ?></p>
                <p><strong>Correo:</strong> <?= htmlspecialchars($correo) ?></p>
                <p><strong>Número de pedido:</strong> <?= htmlspecialchars($id_pedido) ?></p>
                <p><strong>Total a pagar:</strong> $<?= number_format($total, 2, ',', '.') ?> COP</p>
            </div>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="id_pedido" value="<?= $id_pedido ?>">
                <input type="hidden" name="total" value="<?= $total ?>">
                <input type="hidden" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
                <input type="hidden" name="correo" value="<?= htmlspecialchars($correo) ?>">
                <input type="hidden" name="metodo_pago" value="PayPal">
                <button type="submit" name="resultado" value="exito" class="paypal-btn">
                    <i class="fab fa-paypal"></i> Pagar ahora con PayPal
                </button>
            </form>
            <div class="nota">
                Serás redirigido automáticamente después de simular el pago.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

