<?php
session_start();
include('../login/db.php');

if (!isset($_SESSION['id_pedido']) && isset($_GET['id_pedido'])) {
    $_SESSION['id_pedido'] = $_GET['id_pedido'];
}
if (!isset($_SESSION['total']) && isset($_GET['total'])) {
    $_SESSION['total'] = $_GET['total'];
}

$primernombre = $_POST['nombre'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo = $_POST['metodo_pago'] ?? null;
    $id_pedido = $_POST['id_pedido'] ?? null;
    $monto = $_POST['total'] ?? null;

    if (!$metodo || !$id_pedido || !$monto) {
        echo "Faltan datos para procesar el pago.";
        exit();
    }
}


// Generar un ID de pago simulado
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pagar con Tarjeta de Credito</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
    }
    .form-container {
      max-width: 500px;
      margin: 40px auto;
      background-color: #fff;
      padding: 30px 30px 20px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .pse-logo {
      display: flex;
      justify-content: center;
      margin-bottom: 18px;
    }
    .pse-logo img {
      width: 120px;
      height: auto;
    }
    h2 {
      color: #2c3e50;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 18px;
      text-align: left;
    }
    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    input, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }
    button {
      background-color: #1e87f0;
      color: white;
      border: none;
      padding: 15px 30px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      margin-top: 10px;
    }
    button:hover {
      background-color: #0f7ae5;
    }
  </style>
</head>
<body>

<div class="form-container">
  <div class="pse-logo">
    <img src="../assets/images/tarjeta-credito.png" alt="Logo Tarjeta" style="width: 200px; height: auto;">
  </div>
  <h2>Pago con Tarjeta de Crédito</h2>
  <form method="POST" action="procesar.php">
    <div class="form-group">
      <label for="nombre_tarjeta">Nombre en la tarjeta:</label>
      <input type="text" name="nombre" required>
    </div>
    <div class="form-group">
      <label for="numero_tarjeta">Número de tarjeta:</label>
      <input type="text" name="numero_tarjeta" maxlength="19" pattern="[0-9\s]{13,19}" placeholder="XXXX XXXX XXXX XXXX" required>
    </div>
    <div class="form-group">
      <label for="fecha_expiracion">Fecha de expiración:</label>
      <input type="text" name="fecha_expiracion" maxlength="5" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" placeholder="MM/AA" required>
    </div>
    <div class="form-group">
      <label for="cvv">CVV:</label>
      <input type="password" name="cvv" maxlength="4" pattern="[0-9]{3,4}" required>
    </div>
    <div class="form-group">
      <label for="correo">Correo electrónico:</label>
      <input type="email" name="correo" required>
    </div>
    <div class="form-group">
      <label for="telefono">Teléfono:</label>
      <input type="tel" name="telefono" required pattern="[0-9]{7,10}">
    </div>
    <div class="form-group">
      <label for="banco">Banco emisor:</label>
      <select name="banco" required>
        <option value="">Selecciona un banco</option>
        <option value="Bancolombia">Bancolombia</option>
        <option value="Davivienda">Davivienda</option>
        <option value="Banco de Bogotá">Banco de Bogotá</option>
        <option value="BBVA">BBVA</option>
        <option value="Banco Popular">Banco Popular</option>
        <option value="Banco de Occidente">Banco de Occidente</option>
        <option value="Otro">Otro</option>
      </select>
    </div>
    <input type="hidden" name="id_pedido" value="<?php echo $_SESSION['id_pedido']; ?>">
    <input type="hidden" name="total" value="<?php echo $_SESSION['total']; ?>">
    <input type="hidden" name="metodo_pago" value="tarjeta">
   
    <button type="submit">Pagar con Tarjeta</button>
  </form>
</div>

</body>
</html>