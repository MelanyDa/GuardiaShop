<?php
session_start();
include('../login/db.php');
// Ajusta la ruta si es necesario

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
$id_pago = uniqid('TRANSFERENCIA_');
?>

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
      width: 180px;
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
    .otro-banco-fields {
      display: none;
      margin-top: 10px;
      background: #f5faff;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #b3e5fc;
    }
</style>

<div class="form-container">
  <div class="pse-logo">
    <img src="../assets/images/transferencia-bancaria.jpg" alt="Logo Transferencia">
  </div>
  <h2>Pago por Transferencia Bancaria</h2>
  <form method="POST" action="procesar.php">
    <div class="form-group">
      <label for="banco">Banco:</label>
      <select name="banco" id="banco" required onchange="mostrarCamposBanco()">
        <option value="">Seleccione un banco</option>
        <option value="bancolombia">Bancolombia</option>
        <option value="nequi">Nequi</option>
  
      </select>
    </div>
    <div class="form-group">
      <label for="nombre_titular">Nombre del titular:</label>
      <input type="text" name="nombre" required>
    </div>
    <div class="form-group">
      <label for="numero_cuenta">Número de cuenta:</label>
      <input type="text" name="numero_cuenta" required pattern="[0-9]{6,20}">
    </div>
    <div class="form-group">
      <label for="tipo_cuenta">Tipo de cuenta:</label>
      <select name="tipo_cuenta" required>
        <option value="">Seleccione</option>
        <option value="ahorros">Ahorros</option>
        <option value="corriente">Corriente</option>
      </select>
    </div>
    <div class="form-group">
      <label for="correo">Correo electrónico:</label>
      <input type="email" name="correo" required>
    </div>
    <input type="hidden" name="metodo_pago" value="transferencia">
    <input type="hidden" name="id_pedido" value="<?php echo htmlspecialchars($_SESSION['id_pedido']); ?>">
    <input type="hidden" name="total" value="<?php echo htmlspecialchars($_SESSION['total']); ?>">
    <input type="hidden" name="metodo_pago" value="transferencia">
    <button type="submit">Pagar por Transferencia</button>
  </form>
</div>

<script>
function mostrarCamposBanco() {
  var banco = document.getElementById('banco').value;
  var otroFields = document.getElementById('otroBancoFields');
  if (banco === 'otro') {
    otroFields.style.display = 'block';
    document.getElementById('nombre_banco_otro').required = true;
    document.getElementById('codigo_banco_otro').required = true;
  } else {
    otroFields.style.display = 'none';
    document.getElementById('nombre_banco_otro').required = false;
    document.getElementById('codigo_banco_otro').required = false;
  }
 


}

</script>
