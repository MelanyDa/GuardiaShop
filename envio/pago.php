<?php
session_start();

if (!isset($_SESSION['total'])) {
    echo "Error: Faltan datos necesarios para continuar.";
    exit();
}

$total = $_SESSION['total'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Método de Pago</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>

    /* Estilos para el formulario */
    body {
      font-family: 'Open Sans', sans-serif;
      background-color: #f4f7fc;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      max-width: 600px;
      margin: 40px auto;
      padding: 30px;
      border-radius: 12px;
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #2c3e50;
      font-size: 24px;
      margin-bottom: 20px;
      font-weight: 600;
      text-align: center;
    }

    .radio-group {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-bottom: 30px;
    }

    .radio-option {
      display: flex;
      align-items: center;
      border: 2px solid #ccc;
      border-radius: 8px;
      padding: 12px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .radio-option:hover {
      background-color: #ecf0f1;
      border-color: #bdc3c7;
    }

    .radio-option input {
      margin-right: 15px;
      transform: scale(1.3);
    }

    .radio-option i {
      font-size: 24px;
      margin-right: 15px;
      color: #34495e;
      width: 30px;
      text-align: center;
    }

    .btn {
      background-color: #f39c12;
      color: white;
      padding: 15px 30px;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      display: block;
      width: 100%;
      transition: background-color 0.3s ease;
      margin-top: 20px;
    }

    .btn:hover {
      background-color: #e67e22;
    }

    .btn-secundario {
      background-color: #bdc3c7;
      color: #2c3e50;
      margin-top: 18px;
      text-align: left;
      display: inline-block;
      width: auto;
      padding: 10px 22px;
      font-size: 15px;
    }

    .btn-secundario:hover {
      background-color: #95a5a6;
      color: #fff;
    }

    .resumen {
      margin-top: 30px;
      border-top: 2px solid #ecf0f1;
      padding-top: 20px;
      text-align: center;
      font-size: 18px;
    }

    .resumen strong {
      font-weight: 600;
      color: #2c3e50;
    }

    .btn-volver {
      background: linear-gradient(90deg, #f8c471 0%, #f39c12 100%);
      color: #2c3e50 !important;
      border: none;
      border-radius: 25px;
      padding: 12px 32px;
      font-size: 16px;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(243, 156, 18, 0.15);
      transition: background 0.3s, color 0.3s, transform 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      margin-top: 18px;
      text-decoration: none;
    }

    .btn-volver:hover {
      background: linear-gradient(90deg, #f39c12 0%, #f8c471 100%);
      color: #fff !important;
      transform: translateX(-4px) scale(1.04);
    }

    .btn-volver i {
      transition: transform 0.3s;
    }

    .btn-volver:hover i {
      transform: translateX(-4px) scale(1.2);
    }
  </style> 
  <meta charset="UTF-8" />
    
</head>
<body>

<div class="container">
  
  <h2>Selecciona tu Método de Pago</h2>
  <form method="POST" action="procesar_pago.php">
    <div class="radio-group">
      <label class="radio-option">
        <input type="radio" name="metodo_pago" value="paypal" required>
        <i class="fab fa-paypal"></i> PayPal
      </label>
      <label class="radio-option">
        <input type="radio" name="metodo_pago" value="tarjeta">
        <i class="fas fa-credit-card"></i> Tarjeta de Crédito
      </label>
      <label class="radio-option">
        <input type="radio" name="metodo_pago" value="transferencia">
        <i class="fas fa-university"></i> Transferencia Bancaria
      </label>
    </div>

    <input type="hidden" name="total" value="<?php echo $_SESSION['total']; ?>">

    <button class="btn" type="submit">Procesar Pago</button>
  </form>

  <div class="resumen">
    <p><strong>Total a Pagar:</strong> $<?php echo number_format($total, 2); ?></p>
  </div>
      <a href="envio.php" class="btn btn-secundario btn-volver">
  <i class="fas fa-arrow-left"></i> Volver a envío
</a>
  
</div>


</body>
</html>
