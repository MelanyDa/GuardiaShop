<?php
require('fpdf/fpdf.php'); // Asegúrate de tener FPDF en tu proyecto

$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$numero_factura = isset($_GET['factura']) ? $_GET['factura'] : '';
if (!$numero_factura) {
    die('No se especificó la factura.');
}

// Consulta la información de la compra
$sql = "SELECT c.*, pr.nombre_empresa AS proveedor
        FROM compras c
        JOIN proveedores pr ON c.id_proveedor = pr.id_proveedor
        WHERE c.Número_de_factura = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $numero_factura);
$stmt->execute();
$result = $stmt->get_result();
$compra = $result->fetch_assoc();
$stmt->close();

if (!$compra) {
    die('Factura no encontrada.');
}

// Consulta los detalles de la compra
$sql = "SELECT dc.*, p.nombre AS producto, t.nombre_talla, col.nombre AS color
        FROM detalles_compra dc
        JOIN detalles_productos dp ON dc.id_detalles_productos = dp.id_detalles_productos
        JOIN productos p ON dp.id_producto = p.id_producto
        JOIN talla_productos t ON dp.id_tallas = t.id_talla
        JOIN color_productos col ON dp.id_color = col.id_color
        WHERE dc.id_compra = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $compra['id_compra']);
$stmt->execute();
$detalles = $stmt->get_result();

// Generar PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Encabezado elegante
$pdf->SetFillColor(44, 73, 38); // Verde oscuro
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,15,utf8_decode('Factura de Compra'),0,1,'C',true);

$pdf->Ln(2);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',12);

// Información principal
$pdf->Cell(40,8,utf8_decode('Proveedor:'),0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,utf8_decode($compra['proveedor']),0,1);

$pdf->SetFont('Arial','',12);
$pdf->Cell(40,8,utf8_decode('N° Factura:'),0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,utf8_decode($compra['Número_de_factura']),0,1);

$pdf->SetFont('Arial','',12);
$pdf->Cell(40,8,utf8_decode('Fecha:'),0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,date('d/m/Y H:i', strtotime($compra['fecha_compra'])),0,1);

$pdf->Ln(5);

// Tabla de productos
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(233,236,239); // Gris claro
$pdf->SetTextColor(44,73,38);
$pdf->Cell(50,10,utf8_decode('Producto'),1,0,'C',true);
$pdf->Cell(25,10,utf8_decode('Color'),1,0,'C',true);
$pdf->Cell(20,10,utf8_decode('Talla'),1,0,'C',true);
$pdf->Cell(20,10,utf8_decode('Cantidad'),1,0,'C',true);
$pdf->Cell(35,10,utf8_decode('Costo Unitario'),1,0,'C',true);
$pdf->Cell(35,10,utf8_decode('Subtotal'),1,1,'C',true);

$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0,0,0);
$total = 0;
while ($detalle = $detalles->fetch_assoc()) {
    $subtotal = $detalle['cantidad_comprada'] * $detalle['costo_unitario'];
    $pdf->Cell(50,10,utf8_decode($detalle['producto']),1);
    $pdf->Cell(25,10,utf8_decode($detalle['color']),1);
    $pdf->Cell(20,10,utf8_decode($detalle['nombre_talla']),1,0,'C');
    $pdf->Cell(20,10,$detalle['cantidad_comprada'],1,0,'C');
    $pdf->Cell(35,10,'$'.number_format($detalle['costo_unitario'],0,',','.'),1,0,'R');
    $pdf->Cell(35,10,'$'.number_format($subtotal,0,',','.'),1,1,'R');
    $total += $subtotal;
}

// Total
$pdf->SetFont('Arial','B',13);
$pdf->SetFillColor(44,73,38);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(150,12,utf8_decode('TOTAL'),1,0,'R',true);
$pdf->Cell(35,12,'$'.number_format($total,0,',','.'),1,1,'R',true);

$pdf->Ln(8);
$pdf->SetFont('Arial','I',10);
$pdf->SetTextColor(120,120,120);
$pdf->Cell(0,8,utf8_decode('Gracias por su compra.'),0,1,'C');

$pdf->Output();
?>