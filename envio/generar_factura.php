<?php
require_once __DIR__ . '/../login/db.php';
require('fpdf/fpdf.php');

session_start();

$id_pedido = $_GET['id_pedido'] ?? null;
if (!$id_pedido) {
    die('No se recibió el número de pedido.');
}

// Obtener datos del cliente desde sesión
$nombre = $_SESSION['nombre'] ?? 'Cliente';
$correo = $_SESSION['correo'] ?? '';
$metodo_pago = $_SESSION['metodo_pago'] ?? 'No especificado';

// Obtener fecha del pedido
$stmtFecha = $conn->prepare("SELECT fecha_orden FROM pedido WHERE id_pedido = ?");
$stmtFecha->bind_param("i", $id_pedido);
$stmtFecha->execute();
$resultFecha = $stmtFecha->get_result();
$pedidoData = $resultFecha->fetch_assoc();
$fecha_pedido = $pedidoData['fecha_orden'] ?? '';
$stmtFecha->close();

// Obtener detalles del pedido
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
$subtotal = 0;
foreach ($result as $row) {
    $productos[] = $row;
    $subtotal += $row['subtotal'];
}
$stmt->close();

$total = $subtotal; // Si tienes impuestos, cámbialo aquí
$impuestos = 0; // Si tienes impuestos, cámbialo aquí

$numero_factura = 'FAC-' . str_pad($id_pedido, 6, '0', STR_PAD_LEFT);
$fecha_emision = $fecha_pedido ?: date('Y-m-d');
$fecha_vencimiento = date('Y-m-d', strtotime($fecha_emision . ' +30 days'));

// --- OBTENER DIRECCIÓN DEL CLIENTE ---
$stmtDir = $conn->prepare("SELECT pais, departamento, ciudad, codigo_postal, direccion, direccion_adiccional, identificacion FROM direccion WHERE usuario_id = (SELECT usuario_id FROM pedido WHERE id_pedido = ?)");
$stmtDir->bind_param("i", $id_pedido);
$stmtDir->execute();
$resultDir = $stmtDir->get_result();
$direccionData = $resultDir->fetch_assoc();
$stmtDir->close();

$municipio = $direccionData['ciudad'] ?? '';
$departamento = $direccionData['departamento'] ?? '';
$direccion1 = $direccionData['direccion'] ?? '';
$direccion2 = $direccionData['direccion_adiccional'] ?? ''; // <-- usa el nombre correcto aquí
$identificacion = $direccionData['identificacion'] ?? '';

// Unir dirección principal y adicional en una sola línea
$direccion_completa = $direccion1;
if (!empty($direccion2)) {
    $direccion_completa .= ', ' . $direccion2;
}

// --- GENERAR PDF ---
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
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
$pdf->SetX(10);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode("Identificación: $identificacion"), 0, 1); // <-- Nuevo ítem
$pdf->SetX(10);
$pdf->Cell($ancho_columna, $espacioY, utf8_decode("Dirección: $direccion_completa"), 0, 1);

// Agrega este salto de línea para separar
$pdf->Ln(8); // Puedes ajustar el número para más o menos espacio

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
foreach ($productos as $item) {
    $pdf->Cell(60, 8, limpiar_utf8($item['nombre']), 1, 0, 'L');
    $pdf->Cell(25, 8, limpiar_utf8($item['nombre_talla'] ?? '-'), 1, 0, 'C');
    $pdf->Cell(25, 8, limpiar_utf8($item['nombre_color'] ?? '-'), 1, 0, 'C');
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

// --- GUARDAR PDF EN CARPETA ESPECÍFICA ---
$carpeta_destino = __DIR__ . "/facturas_clientes";
if (!file_exists($carpeta_destino)) {
    mkdir($carpeta_destino, 0777, true);
}
$ruta_pdf = $carpeta_destino . "/factura_" . $numero_factura . ".pdf";
$pdf->Output('F', $ruta_pdf); // Guarda el PDF en la carpeta

// --- VERIFICAR SI YA EXISTE LA FACTURA ---
// $check = $conn->prepare("SELECT 1 FROM facturas_venta WHERE numero_factura = ?");
// $check->bind_param("s", $numero_factura);
// $check->execute();
// $check->store_result();
// if ($check->num_rows > 0) {
//     $check->close();
// } else {
//     $check->close();
//     // --- GUARDAR FACTURA EN LA BASE DE DATOS ---
//     $insert_factura = "INSERT INTO facturas_venta 
//         (id_pedido, numero_factura, fecha_emision, fecha_vencimiento, cliente_nombre_completo, cliente_direccion_fiscal, cliente_identificacion_fiscal, subtotal_base, total_impuestos, total_factura, estado_factura, metodo_pago_registrado, notas_factura)
//         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

//     $stmt_factura = $conn->prepare($insert_factura);
//     $estado_factura = 'pagada';
//     $notas_factura = '';

//     $stmt_factura->bind_param(
//         "issssssdddsss",
//         $id_pedido,
//         $numero_factura,
//         $fecha_emision,
//         $fecha_vencimiento,
//         $nombre,
//         $direccion_completa, // Dirección fiscal
//         $identificacion,     // Identificación fiscal (nuevo)
//         $subtotal,
//         $impuestos,
//         $total,
//         $estado_factura,
//         $metodo_pago,
//         $notas_factura
//     );
//     $stmt_factura->execute();
//     $stmt_factura->close();
// }

// --- DESCARGA DIRECTA AL USUARIO ---
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="factura_' . $numero_factura . '.pdf"');
readfile($ruta_pdf);
exit;

function limpiar_utf8($texto) {
    $texto = utf8_decode($texto);
    $texto = str_replace(
        ['á','é','í','ó','ú','ñ','Á','É','Í','Ó','Ú','Ñ'],
        ['a','e','i','o','u','n','A','E','I','O','U','N'],
        $texto
    );
    return $texto;
}

function limpiar_nombre($nombre) {
    // Quita signos de interrogación y caracteres extraños
    $nombre = str_replace(['?', '¿'], '', $nombre);
    return trim($nombre);
}