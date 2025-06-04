<?php
session_start();
require_once '../login/conexion.php'; // <-- Agrega esta línea

// Obtener datos de sesión
$nombre = $_SESSION['nombre'] ?? 'Cliente';
$correo = $_SESSION['correo'] ?? '';
$metodo_pago = $_SESSION['metodo_pago'] ?? 'No especificado';
$total = $_SESSION['total'] ?? 0;
$fecha = date('Y-m-d H:i');

// Obtener el estado anterior antes de actualizar
$stmt_estado = $conn->prepare("SELECT estado FROM pedido WHERE id_pedido = ?");
$stmt_estado->bind_param("i", $id_pedido);
$stmt_estado->execute();
$stmt_estado->bind_result($estado_anterior);
$stmt_estado->fetch();
$stmt_estado->close();

// Cambiar estado del pedido a fallido
$nuevo_estado = 'fallido';
$stmt_update = $conn->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
$stmt_update->bind_param("si", $nuevo_estado, $id_pedido);
$stmt_update->execute();
$stmt_update->close();

// Insertar en historial (nuevo registro)
$stmt_historial = $conn->prepare("INSERT INTO pedido_historial (id_pedido, estado_anterior, estado_nuevo) VALUES (?, ?, ?)");
$stmt_historial->bind_param("iss", $id_pedido, $estado_anterior, $nuevo_estado);
$stmt_historial->execute();
$stmt_historial->close();

$motivo_fallo = $_SESSION['motivo_fallo'] ?? 'No se especificó el motivo del fallo.';
unset($_SESSION['motivo_fallo']); // Limpia para futuros intentos
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pago Fallido | GuardiaShop</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(135deg, #f8fafc 0%, #e3e9f7 100%);
      color: #222;
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }
    .container {
      max-width: 900px;
      margin: 60px auto;
      background: #fff;
      color: #2d3436;
      border-radius: 16px;
      padding: 40px 50px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.12);
      text-align: center;
      position: relative;
    }
    .fail-badge {
      display: inline-block;
      background: linear-gradient(90deg, #e74c3c 60%, #f8d7da 100%);
      color: #fff;
      font-weight: bold;
      font-size: 1.1rem;
      padding: 8px 28px;
      border-radius: 30px;
      margin-bottom: 18px;
      letter-spacing: 1px;
      box-shadow: 0 2px 10px #e74c3c33;
      position: absolute;
      top: -22px;
      left: 50%;
      transform: translateX(-50%);
    }
    .error-icon {
      font-size: 54px;
      color: #e74c3c;
      margin-bottom: 15px;
      margin-top: 10px;
      text-shadow: 0 2px 12px #e74c3c44;
    }
    h1 {
      color: #e74c3c;
      margin-bottom: 10px;
      font-size: 2rem;
      letter-spacing: 1px;
      margin-top: 30px;
    }
    .info-cliente {
      text-align: left;
      margin: 30px auto 0 auto;
      font-size: 16px;
      background: #f4f8fb;
      border-radius: 10px;
      padding: 18px 24px;
      box-shadow: 0 2px 10px #b2bec311;
      max-width: 500px;
    }
    .info-cliente p {
      margin: 8px 0;
    }
    .resumen-total {
      font-size: 18px;
      margin-top: 25px;
      font-weight: bold;
      color: #e74c3c;
    }
    .acciones {
      margin-top: 40px;
    }
    .btn {
      display: inline-block;
      padding: 12px 24px;
      font-size: 15px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      margin: 0 10px;
      transition: 0.3s ease;
      background-color: #e74c3c;
      color: white;
      font-weight: bold;
    }
    .btn:hover {
      background-color: #c0392b;
      opacity: 0.92;
      transform: translateY(-2px);
    }
    @media (max-width: 768px) {
      .container {
        padding: 25px;
      }
      .btn {
        padding: 10px 18px;
        font-size: 14px;
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <span class="fail-badge"><i class="fas fa-times-circle"></i> Pago fallido</span>
  <div class="error-icon"><i class="fas fa-times-circle"></i></div>
  <h1>¡Tu pago no se pudo procesar!</h1>
  <p>Hubo un problema al intentar procesar tu compra.<br>Puedes intentarlo de nuevo o contactar soporte.</p>
  <div style="color:#e74c3c; font-weight:bold; margin:18px 0 10px 0;">
    Motivo: <?= htmlspecialchars($motivo_fallo) ?>
  </div>

  <div class="info-cliente">
    <p><strong>Nombre del Cliente:</strong> <?= htmlspecialchars($nombre) ?></p>
    <p><strong>Correo Electrónico:</strong> <?= htmlspecialchars($correo) ?></p>
    <p><strong>Fecha del Intento:</strong> <?= htmlspecialchars($fecha) ?></p>
    <p><strong>Método de Pago:</strong> <?= htmlspecialchars($metodo_pago) ?></p>
  </div>

  <div class="resumen-total">
    Monto Intentado: $<?= number_format($total, 0, ",", ".") ?> COP
  </div>

  <div class="acciones">
    <a href="../index.php" class="btn"><i class="fas fa-home"></i> Volver al Inicio</a>
  </div>
</div>

</body>
</html>
