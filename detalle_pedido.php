<?php
@session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login/login.php");
    exit();
}
require_once './login/conexion.php';

$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;
$usuario_id = $_SESSION['usuario_id'];

// Verifica que el pedido pertenezca al usuario
$stmt = $conn->prepare("SELECT * FROM pedido WHERE id_pedido = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id_pedido, $usuario_id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pedido) {
    echo "<script>alert('Pedido no encontrado o no tienes acceso.');window.location='modificar_perfil.php#mis-pedidos';</script>";
    exit();
}

// Obtén los productos del pedido (solo una imagen por producto)
$stmt = $conn->prepare("
    SELECT p.nombre, pi.imagen, dp.cantidad, dp.precio_unitario, tp.nombre_talla AS talla, cp.nombre AS color
    FROM detalles_pedido dp
    JOIN detalles_productos dprod ON dp.id_detalles_productos = dprod.id_detalles_productos
    JOIN productos p ON dprod.id_producto = p.id_producto
    LEFT JOIN (
        SELECT id_producto, MIN(imagen) AS imagen
        FROM producto_imagen
        GROUP BY id_producto
    ) pi ON pi.id_producto = p.id_producto
    LEFT JOIN talla_productos tp ON dprod.id_tallas = tp.id_talla
    LEFT JOIN color_productos cp ON dprod.id_color = cp.id_color
    WHERE dp.id_pedido = ?
");
if (!$stmt) {
    die("Error en la consulta SQL: " . $conn->error);
}
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$res = $stmt->get_result();
$productos = [];
while ($row = $res->fetch_assoc()) $productos[] = $row;
$stmt->close();

// Función para color de estado
function estado_color($estado) {
    $estado = strtolower($estado);
    if (in_array($estado, ['entregado', 'confirmado'])) return 'green';
    if (in_array($estado, ['cancelado', 'fallido', 'devuelto'])) return 'red';
    return '#888';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: #f8f9fa;
        }
        .detalle-producto { display: flex; align-items: center; margin-bottom: 18px; background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 2px 8px #eee; }
        .detalle-producto img { width: 70px; height: 70px; object-fit: cover; border-radius: 6px; margin-right: 18px; }
        .detalle-producto-info { flex: 1; }
        .detalle-producto-info span { display: block; margin-bottom: 3px; font-size: 1.05em; }
        .detalle-resumen { margin: 40px auto; background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px #e6e2c3; max-width: 600px; }
        .detalle-resumen h2 { color: #b78732; font-weight: 600; }
        .volver-link { color: #b78732; text-decoration: none; font-weight: 500; }
        .volver-link:hover { text-decoration: underline; color: #8d6a1e; }
        .estado-pedido {
            font-weight: 600;
            font-size: 1.1em;
            padding: 4px 14px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="detalle-resumen">
        <h2>Detalle del Pedido</h2>
        <p><strong>Fecha:</strong> <?= htmlspecialchars(date("d/m/Y H:i", strtotime($pedido['fecha_orden']))) ?></p>
        <p>
            <strong>Estado:</strong>
            <span class="estado-pedido" style="background:<?= estado_color($pedido['estado']) ?>22;color:<?= estado_color($pedido['estado']) ?>">
                <?= ucfirst(htmlspecialchars($pedido['estado'])) ?>
            </span>
        </p>
        <hr>
        <?php foreach ($productos as $prod): ?>
            <div class="detalle-producto">
                <img src="<?= htmlspecialchars($prod['imagen'] ?? './assets/images/placeholder.png') ?>" alt="">
                <div class="detalle-producto-info">
                    <span><strong><?= htmlspecialchars($prod['nombre']) ?></strong></span>
                    <span>Cantidad: <?= $prod['cantidad'] ?></span>
                    <?php if (!empty($prod['talla'])): ?><span>Talla: <?= htmlspecialchars($prod['talla']) ?></span><?php endif; ?>
                    <?php if (!empty($prod['color'])): ?><span>Color: <?= htmlspecialchars($prod['color']) ?></span><?php endif; ?>
                    <span>Precio unitario: $<?= number_format($prod['precio_unitario'], 0, ',', '.') ?></span>
                    <span>Subtotal: $<?= number_format($prod['precio_unitario'] * $prod['cantidad'], 0, ',', '.') ?></span>
                </div>
            </div>
        <?php endforeach; ?>
        <hr>
        <p style="font-size:1.2em;"><strong>Total:</strong> $<?= number_format($pedido['total'], 0, ',', '.') ?></p>
        <a href="modificar_perfil.php#mis-pedidos" class="volver-link">&larr; Volver a mis pedidos</a>
    </div>
</body>
</html>