<?php
require('fpdf/fpdf.php');
require_once __DIR__ . '/../src/PHPMailer.php';
require_once __DIR__ . '/../src/SMTP.php';
require_once __DIR__ . '/../src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../login/db.php';
require __DIR__ . '/../vendor/autoload.php'; // PHPMailer

session_start();

$id_pedido = $_SESSION['id_pedido'] ?? null;
if (!$id_pedido) {
    die('No se encontró el pedido.');
}

// Busca los datos del cliente y la factura
$stmt = $conn->prepare("SELECT f.numero_factura, f.cliente_nombre_completo, u.correo 
                        FROM facturas_venta f 
                        JOIN pedido p ON f.id_pedido = p.id_pedido 
                        JOIN usuario u ON p.usuario_id = u.id 
                        WHERE f.id_pedido = ?");
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$stmt->bind_result($numero_factura, $nombre, $correo);
$stmt->fetch();
$stmt->close();


// Recibir id_pedido y total por GET o sesión
$id_pedido = $_GET['id_pedido'] ?? $_SESSION['id_pedido'] ?? null;
$total = $_GET['total'] ?? $_SESSION['total'] ?? null;

if (!$id_pedido) {
    die("❌ No se encontró el pedido.");
}

// Datos básicos del cliente desde sesión
$nombre = $_SESSION['nombre'] ?? 'Cliente';
$correo = $_SESSION['correo'] ?? '';

// Obtener fecha del pedido desde la tabla pedido
$stmtFecha = $conn->prepare("SELECT fecha_orden FROM pedido WHERE id_pedido = ?");
$stmtFecha->bind_param("i", $id_pedido);
$stmtFecha->execute();
$resultFecha = $stmtFecha->get_result();
$pedidoData = $resultFecha->fetch_assoc();
$fecha_pedido = $pedidoData['fecha_orden'] ?? '';
$stmtFecha->close();

// Obtener detalles del pedido con nombre del producto
$sql = "
    SELECT dp.cantidad, dp.precio_unitario, dp.subtotal, p.nombre,
           tp.nombre_talla, cp.nombre AS nombre_color
    FROM detalles_pedido dp
    JOIN detalles_productos dprod ON dp.id_detalles_productos = dprod.id_detalles_productos
    JOIN productos p ON dprod.id_producto = p.id_producto
    LEFT JOIN talla_productos tp ON dprod.id_tallas = tp.id_talla
    LEFT JOIN color_productos cp ON dprod.id_color = cp.id_color
    WHERE dp.id_pedido = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}
$stmt->close();

// Depuración: imprime los productos si no aparecen
if (empty($productos)) {
    echo "<pre>NO HAY PRODUCTOS PARA ESTE PEDIDO. Revisa detalles_pedido. ID Pedido: $id_pedido</pre>";
}

$metodo_pago = $_SESSION['metodo_pago'] ?? 'No especificado';
 
function limpiar_nombre($nombre) {
    // Quita signos de interrogación y caracteres extraños
    $nombre = str_replace(['?', '¿'], '', $nombre);
    return trim($nombre);
}

// Agrupar productos por nombre, talla y color
$productos_agrupados = [];
foreach ($productos as $prod) {
    $key = $prod['nombre'] . '|' . ($prod['nombre_talla'] ?? '-') . '|' . ($prod['nombre_color'] ?? '-');
    if (!isset($productos_agrupados[$key])) {
        $productos_agrupados[$key] = $prod;
    } else {
        $productos_agrupados[$key]['cantidad'] += $prod['cantidad'];
        $productos_agrupados[$key]['subtotal'] += $prod['subtotal'];
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resumen de Compra | GuardiaShop</title>
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
    .success-icon {
      font-size: 54px;
      color: #27ae60;
      margin-bottom: 15px;
      margin-top: 10px;
      text-shadow: 0 2px 12px #27ae6044;
    }
    .badge-exito {
      display: inline-block;
      background: linear-gradient(90deg, #27ae60 60%, #b2f7cc 100%);
      color: #fff;
      font-weight: bold;
      font-size: 1.1rem;
      padding: 8px 28px;
      border-radius: 30px;
      margin-bottom: 18px;
      letter-spacing: 1px;
      box-shadow: 0 2px 10px #27ae6033;
      position: absolute;
      top: -22px;
      left: 50%;
      transform: translateX(-50%);
    }
    h1 {
      color: #27ae60;
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
      color: #0984e3;
    }
    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #f1f2f6;
    }
    td {
      background-color: #ffffff;
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
      background-color: #3182ce;
      color: white;
      font-weight: bold;
    }
    .btn-descargar {
      background-color: #0984e3;
      color: white;
    }
    .btn-inicio {
      background-color: #27ae60;
      color: white;
    }
    .btn:hover {
      background-color: #2563a6;
      opacity: 0.92;
      transform: translateY(-2px);
    }
    @media (max-width: 768px) {
      .container {
        padding: 25px;
      }
      table, thead, tbody, th, td, tr {
        font-size: 14px;
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
  <span class="badge-exito"><i class="fas fa-check-circle"></i> Pago exitoso</span>
  <div class="success-icon"><i class="fas fa-check-circle"></i></div>
  <h1>¡Gracias por tu compra en GuardiaShop!</h1>
  <p>Tu pedido fue procesado exitosamente.</p>
  <p style="color: #0984e3; font-weight: bold; margin-top: 10px;">
    <i class="fas fa-envelope"></i> Tu factura fue enviada a tu correo: <?php echo htmlspecialchars($correo); ?>
  </p>

  <div class="info-cliente">
    <p><strong>Nombre del Cliente:</strong> <?php echo htmlspecialchars($nombre); ?></p>
    <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($correo); ?></p>
    <p><strong>Fecha del Pedido:</strong> <?php echo htmlspecialchars($fecha_pedido); ?></p>
    <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($metodo_pago); ?></p>
  </div>

  <div class="resumen-total">
    Total Pagado: $<?php echo number_format($total, 0, ",", "."); ?> COP
  </div>

  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th>Talla</th>
        <th>Color</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($productos_agrupados as $prod): ?>
        <tr>
          <td><?php echo limpiar_nombre($prod['nombre']); ?></td>
          <td><?php echo htmlspecialchars($prod['nombre_talla'] ?? '-'); ?></td>
          <td><?php echo htmlspecialchars($prod['nombre_color'] ?? '-'); ?></td>
          <td><?php echo (int)$prod['cantidad']; ?></td>
          <td>$<?php echo number_format($prod['precio_unitario'], 0, ",", "."); ?></td>
          <td>$<?php echo number_format($prod['subtotal'], 0, ",", "."); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="acciones">
    <a href="generar_factura.php?id_pedido=<?php echo $id_pedido; ?>" class="btn btn-descargar" target="_blank"><i class="fas fa-file-download"></i> Descargar Comprobante</a>
<a href="../index.php?compra=ok" class="btn btn-inicio"><i class="fas fa-home"></i> Volver al Inicio</a>  </div>
</div>
<?php


// Generar datos para la factura
$numero_factura = 'FAC-' . str_pad($id_pedido, 6, '0', STR_PAD_LEFT);
$fecha_emision = $fecha_pedido ?: date('Y-m-d');
$fecha_vencimiento = date('Y-m-d', strtotime($fecha_emision . ' +30 days'));
$subtotal = 0;
foreach ($productos as $row) {
    $subtotal += $row['subtotal'];
}
$impuestos = 0; // Modifica si tienes impuestos
$estado_factura = 'Pagada';
$notas_factura = '';

// --- OBTENER DIRECCIÓN DEL CLIENTE ---
$stmtDir = $conn->prepare("SELECT pais, departamento, ciudad, codigo_postal, direccion, direccion_adiccional, identificacion FROM direccion WHERE usuario_id = (SELECT usuario_id FROM pedido WHERE id_pedido = ?)");
$stmtDir->bind_param("i", $id_pedido);
$stmtDir->execute();
$resultDir = $stmtDir->get_result();
$direccionData = $resultDir->fetch_assoc();
$stmtDir->close();

$direccion1 = $direccionData['direccion'] ?? '';
$direccion2 = $direccionData['direccion_adiccional'] ?? '';
$identificacion = $direccionData['identificacion'] ?? '';
$direccion_completa = $direccion1;
if (!empty($direccion2)) {
    $direccion_completa .= ', ' . $direccion2;
}

// --- GUARDAR FACTURA EN LA BASE DE DATOS SOLO SI NO EXISTE ---
$check = $conn->prepare("SELECT 1 FROM facturas_venta WHERE numero_factura = ?");
$check->bind_param("s", $numero_factura);
$check->execute();
$check->store_result();
if ($check->num_rows == 0) {
    $check->close();
    $insert_factura = "INSERT INTO facturas_venta 
        (id_pedido, numero_factura, fecha_emision, fecha_vencimiento, cliente_nombre_completo, cliente_direccion_fiscal, cliente_identificacion_fiscal, subtotal_base, total_impuestos, total_factura, estado_factura, metodo_pago_registrado, notas_factura)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_factura = $conn->prepare($insert_factura);
    $stmt_factura->bind_param(
        "issssssdddsss",
        $id_pedido,
        $numero_factura,
        $fecha_emision,
        $fecha_vencimiento,
        $nombre,
        $direccion_completa,
        $identificacion,
        $subtotal,
        $impuestos,
        $total,
        $estado_factura,
        $metodo_pago,
        $notas_factura
    );
    $stmt_factura->execute();
    $stmt_factura->close();
} else {
    $check->close();
}

// Obtener el estado anterior antes de actualizar
$stmt_estado = $conn->prepare("SELECT estado FROM pedido WHERE id_pedido = ?");
$stmt_estado->bind_param("i", $id_pedido);
$stmt_estado->execute();
$stmt_estado->bind_result($estado_anterior);
$stmt_estado->fetch();
$stmt_estado->close();

// Cambiar estado del pedido a confirmado
$nuevo_estado = 'confirmado';
if ($estado_anterior !== $nuevo_estado) {
    $stmt_update = $conn->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
    $stmt_update->bind_param("si", $nuevo_estado, $id_pedido);
    $stmt_update->execute();
    $stmt_update->close();

    // Insertar en historial (nuevo registro)
    $stmt_historial = $conn->prepare("INSERT INTO pedido_historial (id_pedido, estado_anterior, estado_nuevo) VALUES (?, ?, ?)");
    $stmt_historial->bind_param("iss", $id_pedido, $estado_anterior, $nuevo_estado);
    $stmt_historial->execute();
    $stmt_historial->close();
}

// Descontar stock aquí
$stmt_detalles = $conn->prepare("SELECT id_detalles_productos, cantidad FROM detalles_pedido WHERE id_pedido = ?");
$stmt_detalles->bind_param("i", $id_pedido);
$stmt_detalles->execute();
$res_detalles = $stmt_detalles->get_result();
while ($row = $res_detalles->fetch_assoc()) {
    // Obtener el stock inicial antes de descontar
    $stmt_stock_ini = $conn->prepare("SELECT stock FROM detalles_productos WHERE id_detalles_productos = ?");
    $stmt_stock_ini->bind_param("i", $row['id_detalles_productos']);
    $stmt_stock_ini->execute();
    $stmt_stock_ini->bind_result($stock_inicial);
    $stmt_stock_ini->fetch();
    $stmt_stock_ini->close();

    // Descontar stock
    $stmt_update = $conn->prepare("UPDATE detalles_productos SET stock = stock - ? WHERE id_detalles_productos = ?");
    $stmt_update->bind_param("ii", $row['cantidad'], $row['id_detalles_productos']);
    $stmt_update->execute();
    $stmt_update->close();

    // Obtener el stock resultante después de descontar
    $stmt_stock = $conn->prepare("SELECT stock FROM detalles_productos WHERE id_detalles_productos = ?");
    $stmt_stock->bind_param("i", $row['id_detalles_productos']);
    $stmt_stock->execute();
    $stmt_stock->bind_result($stock_resultante);
    $stmt_stock->fetch();
    $stmt_stock->close();

    // Registrar movimiento en inventario con stock inicial
    $tipo_movimiento = 'Venta';
    $cantidad_cambio = -$row['cantidad'];
    $referencia_origen = $id_pedido;
    $stmt_mov = $conn->prepare("INSERT INTO movimientos_inventario (id_detalles_productos, stock_inicial, tipo_movimiento, cantidad_cambio, stock_resultante, referencia_origen) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_mov->bind_param("iisiss", $row['id_detalles_productos'], $stock_inicial, $tipo_movimiento, $cantidad_cambio, $stock_resultante, $referencia_origen);
    $stmt_mov->execute();
    $stmt_mov->close();
}
$stmt_detalles->close();

// Generar PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('../assets/images/logo2.png', 10, 10, 30);
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('GUARDIA SHOP'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, utf8_decode('Factura de Venta'), 0, 1, 'C');
$pdf->Ln(25);

$yInicio = $pdf->GetY();
$espacioY = 6;
$ancho_columna = 80;

// Datos del Cliente
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetXY(10, $yInicio);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode('Datos del Cliente'), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->SetX(10);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode("Nombre: $nombre"), 0, 1);
$pdf->SetX(10);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode("Correo: $correo"), 0, 1);

// Datos de la Factura
$columna_derecha_x = 140;
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetXY($columna_derecha_x, $yInicio);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode('Datos de Factura'), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->SetX($columna_derecha_x);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode("Factura N°: $numero_factura"), 0, 1);
$pdf->SetX($columna_derecha_x);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode("Fecha: $fecha_emision"), 0, 1);
$pdf->SetX($columna_derecha_x);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode("Método de pago: $metodo_pago"), 0, 1);
$pdf->SetX($columna_derecha_x);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode("Vencimiento: $fecha_vencimiento"), 0, 1);

$pdf->Ln(10);

// Tabla de productos
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(60, 8, utf8_decode('Producto'), 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Talla', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Color', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Cant.', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Precio Unit.', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Subtotal', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
foreach ($productos_agrupados as $item) {
    $pdf->Cell(60, 8, limpiar_nombre($item['nombre']), 1, 0, 'L');
    $pdf->Cell(25, 8, limpiar_nombre($item['nombre_talla'] ?? '-'), 1, 0, 'C');
    $pdf->Cell(25, 8, limpiar_nombre($item['nombre_color'] ?? '-'), 1, 0, 'C');
    $pdf->Cell(15, 8, $item['cantidad'], 1, 0, 'C');
    $pdf->Cell(30, 8, '$' . number_format($item['precio_unitario'], 0, ',', '.'), 1, 0, 'R');
    $pdf->Cell(30, 8, '$' . number_format($item['subtotal'], 0, ',', '.'), 1, 1, 'R');
}

$pdf->Ln(5);

// Totales
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(145, 8, utf8_decode('Subtotal:'), 0, 0, 'R');
$pdf->Cell(40, 8, '$' . number_format($subtotal, 0, ',', '.'), 0, 1, 'R');

$pdf->Cell(145, 8, utf8_decode('Impuestos:'), 0, 0, 'R');
$pdf->Cell(40, 8, '$' . number_format($impuestos, 0, ',', '.'), 0, 1, 'R');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(145, 10, utf8_decode('Total a Pagar:'), 0, 0, 'R');
$pdf->Cell(40, 10, '$' . number_format($total, 0, ',', '.'), 0, 1, 'R');

// Footer
$pdf->SetY(263);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, utf8_decode('Gracias por su compra. GuardiaShop © ' . date('Y')), 0, 0, 'C');

// Guardar PDF en carpeta
$carpeta_destino = __DIR__ . "/facturas_clientes";
if (!file_exists($carpeta_destino)) {
    mkdir($carpeta_destino, 0777, true);
}
$ruta_pdf = $carpeta_destino . "/factura_" . $numero_factura . ".pdf";
$pdf->Output('F', $ruta_pdf);

// Enviar por correo
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gguardiashop@gmail.com';
    $mail->Password = 'dkgu cxev aksl ripl';
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';

    $mail->setFrom('gguardiashop@gmail.com', 'GuardiaShop');
    $mail->addAddress($correo, $nombre);
    $mail->Subject = 'Factura de tu compra en GuardiaShop';
    $mail->Body    = 'Adjuntamos la factura de tu compra. ¡Gracias por confiar en nosotros!';
    $mail->addAttachment($ruta_pdf);

    $mail->send();
    // Opcional: mensaje de éxito (no usar alert aquí, solo para depuración)
    // echo "Factura enviada exitosamente al correo.";
} catch (Exception $e) {
    echo "Error al enviar la factura: {$mail->ErrorInfo}";
}
?>

</body>
</html>
