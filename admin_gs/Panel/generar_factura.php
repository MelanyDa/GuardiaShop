<?php
require('fpdf/fpdf.php');

if (ob_get_length()) {
    ob_end_clean();
}

$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    http_response_code(500);
    exit;
}

$datos = json_decode($_POST['datosFactura'], true);
$cliente = $datos['cliente'];
$metodo_pago = $datos['metodo_pago'];
$carrito = $datos['carrito'];
$subtotal = $datos['subtotal'];
$total = $datos['total'];
$impuestos = $total - $subtotal;
 
$numero_factura = uniqid('FAC-');
$fecha_emision = date('Y-m-d');
$fecha_vencimiento = date('Y-m-d', strtotime('+30 days'));
$fecha_creacion = date('Y-m-d H:i:s');

$subtotal_con_iva = 0;
$subtotal_sin_iva = 0;
$total_iva = 0;
foreach ($carrito as $item) {
    $id_producto = intval($item['id_producto']);
    $cantidad = intval($item['cantidad']);
    // LIMPIAR EL PRECIO POR SI LLEGA FORMATEADO
    $precio_con_iva = floatval(str_replace(['$', '.', ','], ['', '', ''], $item['precio']));
    $subtotal_item = $precio_con_iva * $cantidad;
    $subtotal_item_sin_iva = $subtotal_item / 1.19;
    $iva_item = $subtotal_item - $subtotal_item_sin_iva;
    $subtotal_con_iva += $subtotal_item;
    $subtotal_sin_iva += $subtotal_item_sin_iva;
    $total_iva += $iva_item;
}
$total_factura = $subtotal_con_iva;

$subtotal_base = isset($datos['subtotal']) ? floatval($datos['subtotal']) : 0;

$stmt = $conexion->prepare("INSERT INTO factura_venta_f (numero_factura, fecha_emision, fecha_vencimiento, cliente_nombre_completo, cliente_direccion_fiscal, cliente_identificacion_fiscal, correo, subtotal_base, total_impuestos, total_factura, estado_factura, metodo_pago_registrado, notas_factura, fecha_creacion_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$estado_factura = 'Pagada';
$notas = '';
$stmt->bind_param(
    "sssssssdddssss", // 14 letras para 14 valores
    $numero_factura,
    $fecha_emision,
    $fecha_vencimiento,
    $cliente['nombre'],
    $cliente['direccion'],
    $cliente['identificacion'],
    $cliente['correo'],
    $subtotal_base, // subtotal_base recibido del frontend
    $total_iva,        // total_impuestos
    $total_factura,    // total_factura (total pagado)
    $estado_factura,
    $metodo_pago,
    $notas,
    $fecha_creacion
);
$stmt->execute();
$id_factura = $stmt->insert_id;

foreach ($carrito as $item) {
    $id_detalles_productos = intval($item['id_detalles_productos']);
    $id_producto = intval($item['id_producto']);
    $cantidad = intval($item['cantidad']);
    $precio_con_iva = floatval(str_replace(['$', '.', ','], ['', '', ''], $item['precio']));
    $subtotal_item = $precio_con_iva * $cantidad;

    // 1. Guardar el detalle de la factura
    $stmt_detalle = $conexion->prepare("INSERT INTO detalles_factura_f (id_factura_f, id_producto, cantidad, precio, subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmt_detalle->bind_param("iiidd", $id_factura, $id_producto, $cantidad, $precio_con_iva, $subtotal_item);
    $stmt_detalle->execute();
    $stmt_detalle->close();

    // 2. Validar stock antes de descontar
    $stmt_check = $conexion->prepare("SELECT stock FROM detalles_productos WHERE id_detalles_productos = ?");
    if (!$stmt_check) {
        die("Error en prepare: " . $conexion->error);
    }
    $stmt_check->bind_param("i", $id_detalles_productos);
    $stmt_check->execute();
    $stmt_check->bind_result($stock_actual);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($stock_actual < $cantidad) {
        echo '<script>alert("No hay suficiente stock para el producto seleccionado."); window.close();</script>';
        exit;
    }

    // 3. Descontar stock
    $stmt_stock = $conexion->prepare("UPDATE detalles_productos SET stock = stock - ? WHERE id_detalles_productos = ?");
    $stmt_stock->bind_param("ii", $cantidad, $id_detalles_productos);
    $stmt_stock->execute();
    $stmt_stock->close();

    // 4. Registrar movimiento en inventario (Kardex)
    // Obtener stock inicial antes del movimiento
    $stmt_stock_ini = $conexion->prepare("SELECT stock FROM detalles_productos WHERE id_detalles_productos = ?");
    $stmt_stock_ini->bind_param("i", $id_detalles_productos);
    $stmt_stock_ini->execute();
    $stmt_stock_ini->bind_result($stock_inicial);
    $stmt_stock_ini->fetch();
    $stmt_stock_ini->close();

    // El stock ya fue descontado arriba, así que el stock resultante es:
    $stock_resultante = $stock_inicial - $cantidad;

    // Registrar en movimientos_inventario
    $stmt_mov = $conexion->prepare("INSERT INTO movimientos_inventario 
        (id_detalles_productos, stock_inicial, tipo_movimiento, cantidad_cambio, stock_resultante, fecha_hora, referencia_origen) 
        VALUES (?, ?, 'Venta', ?, ?, NOW(), ?)");
    $referencia_origen = $id_factura; // Puedes guardar el id_factura como referencia
    $cantidad_cambio = -$cantidad; // Negativo porque es una venta
    $stmt_mov->bind_param("iiiis", $id_detalles_productos, $stock_inicial, $cantidad_cambio, $stock_resultante, $referencia_origen);
    $stmt_mov->execute();
    $stmt_mov->close();
}
$stmt->close();

$pdf = new FPDF();
$pdf->AddPage();

// COLORES DE LA EMPRESA
$color_dorado = [183, 135, 50]; // #b78732
$color_verde = [44, 73, 38];    // #2c4926
$color_piel = [183, 159, 94];   // #B79F5E
$color_gris = [68, 66, 66];     // #444242
$color_piel_claro = [239, 217, 171]; // #EFD9AB

// ENCABEZADO MODERNO
$pdf->Image('img/logo2.png', 12, 8, 22);
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor($color_dorado[0], $color_dorado[1], $color_dorado[2]);
$pdf->SetXY(38, 10);
$pdf->Cell(0, 10, utf8_decode('GUARDIA SHOP'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->SetXY(38, 20);
$pdf->Cell(0, 8, utf8_decode('Factura de Venta Electrónica'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor($color_gris[0], $color_gris[1], $color_gris[2]);
$pdf->SetXY(150, 10);
$pdf->Cell(0, 8, utf8_decode('gguardiashop@gmail.com'), 0, 1, 'L');
$pdf->SetXY(150, 18);
$pdf->Cell(0, 8, utf8_decode('www.guardiashop.com'), 0, 1, 'L');
$pdf->SetTextColor(0,0,0);

// DATOS DEL CLIENTE Y FACTURA EN CAJAS
$pdf->SetY(45);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor($color_piel_claro[0], $color_piel_claro[1], $color_piel_claro[2]);
$pdf->SetDrawColor($color_dorado[0], $color_dorado[1], $color_dorado[2]);
$pdf->Cell(100, 8, utf8_decode('Datos del Cliente'), 1, 0, 'L', true);
$pdf->Cell(0, 8, utf8_decode('Datos de la Factura'), 1, 1, 'L', true);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 7, utf8_decode("Nombre: {$cliente['nombre']}"), 1, 0, 'L');
$pdf->Cell(0, 7, utf8_decode("Factura N°: $numero_factura"), 1, 1, 'L');
$pdf->Cell(100, 7, utf8_decode("Identificación: {$cliente['identificacion']}"), 1, 0, 'L');
$pdf->Cell(0, 7, utf8_decode("Fecha: $fecha_emision"), 1, 1, 'L');
$pdf->Cell(100, 7, utf8_decode("Dirección: {$cliente['direccion']}"), 1, 0, 'L');
$pdf->Cell(0, 7, utf8_decode("Método de pago: $metodo_pago"), 1, 1, 'L');
$pdf->Cell(100, 7, utf8_decode("Correo: {$cliente['correo']}"), 1, 0, 'L');
$pdf->Cell(0, 7, utf8_decode("Vencimiento: $fecha_vencimiento"), 1, 1, 'L');

$pdf->Ln(10);

// TABLA DE PRODUCTOS MODERNA (ancho total 190 mm)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor($color_dorado[0], $color_dorado[1], $color_dorado[2]);
$pdf->SetTextColor(255,255,255);
// Nuevos anchos: Producto 65, Talla 20, Color 25, Cant. 15, Precio 30, Subtotal 35 = 190
$pdf->Cell(65, 8, utf8_decode('Producto'), 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Talla', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Color', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Cant.', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Precio', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Subtotal', 1, 1, 'C', true);
$pdf->SetTextColor($color_gris[0], $color_gris[1], $color_gris[2]);

function limpiarTextoFPDF($texto) {
    // Reemplaza comillas tipográficas y otros caracteres especiales por equivalentes simples
    $texto = str_replace(['"', '–', '—'], '"', $texto); // comillas dobles y guiones largos
    $texto = str_replace('…', '...', $texto);                // puntos suspensivos
    return utf8_decode($texto);
}

$pdf->SetFont('Arial', '', 9);
foreach ($carrito as $item) {
    $producto = trim(preg_replace('/\s+/', ' ', $item['producto']));
    $cantidad = intval($item['cantidad']);
    $precio_con_iva = floatval(str_replace(['$', '.', ','], ['', '', ''], $item['precio']));
    $subtotal_item = $precio_con_iva * $cantidad;
    $pdf->Cell(65, 8, limpiarTextoFPDF($producto), 1);
    $pdf->Cell(20, 8, utf8_decode($item['talla']), 1, 0, 'C');
    $pdf->Cell(25, 8, utf8_decode($item['color']), 1, 0, 'C');
    $pdf->Cell(15, 8, $cantidad, 1, 0, 'C');
    $pdf->Cell(30, 8, '$' . number_format($precio_con_iva, 0, ',', '.'), 1, 0, 'R');
    $pdf->Cell(35, 8, '$' . number_format($subtotal_item, 0, ',', '.'), 1, 1, 'R');
}

$pdf->Ln(5);

// CAJA DE TOTALES (alineada con la tabla, ancho 190 mm)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor($color_dorado[0], $color_dorado[1], $color_dorado[2]);
$pdf->SetTextColor(255,255,255);
$pdf->SetX($pdf->GetX());
$pdf->Cell(155, 10, utf8_decode('Total Pagado:'), 1, 0, 'R', true);
$pdf->Cell(35, 10, '$' . number_format($total_factura, 0, ',', '.'), 1, 1, 'R', true);
$pdf->SetTextColor(0,0,0);

// FOOTER BLANCO
$pdf->SetY(263);
$pdf->SetFont('Arial', 'I', 8);
$pdf->SetTextColor($color_gris[0], $color_gris[1], $color_gris[2]);
$pdf->Cell(0, 6, utf8_decode('Gracias por su compra. GuardiaShop © ' . date('Y') . ' | gguardiashop@gmail.com'), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('www.guardiashop.com'), 0, 0, 'C');
$pdf->SetTextColor(0,0,0);

// Salida del PDF
$ruta_pdf = __DIR__ . "/factura/factura_" . $numero_factura . ".pdf";
$pdf->Output('F', $ruta_pdf); // Guarda el PDF en el servidor

// --- PHPMailer ---
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/libs/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/libs/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/libs/vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gguardiashop@gmail.com';
    $mail->Password = 'dkgu cxev aksl ripl';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('gguardiashop@gmail.com', 'GuardiaShop');
    $mail->addAddress($cliente['correo'], $cliente['nombre']);
    $mail->Subject = 'Factura de tu compra en GuardiaShop';
    $mail->Body    = 'Adjuntamos la factura de tu compra. ¡Gracias por confiar en nosotros!';
    $mail->addAttachment($ruta_pdf);

    $mail->send();
   // Mostrar alert y cerrar ventana hija
    echo '<script>
        alert("Factura enviada exitosamente al correo.");
        window.close();
    </script>';
    exit;
} catch (Exception $e) {
    // Mostrar alert de error y cerrar ventana hija
    echo '<script>
        alert("Error al enviar la factura: ' . addslashes($mail->ErrorInfo) . '");
        window.close();
    </script>';
    exit;
}