<?php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    echo '<tr><td colspan="6">Error de conexión</td></tr>';
    exit;
}

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$tipo_tienda = isset($_GET['tipo_tienda']) ? $_GET['tipo_tienda'] : '';

$condiciones = [];
if ($fecha_inicio) $condiciones[] = "fecha_emision >= '$fecha_inicio'";
if ($fecha_fin) $condiciones[] = "fecha_emision <= '$fecha_fin'";

// Consultas para ambas tiendas
$consultas = [];

if ($tipo_tienda == '' || $tipo_tienda == 'fisica') {
    $where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';
    $consultas[] = "
        SELECT 
            fecha_emision AS fecha,
            cliente_nombre_completo AS cliente,
            'Física' AS tienda,
            metodo_pago_registrado AS metodo_pago,
            total_factura AS total,
            estado_factura AS estado
        FROM factura_venta_f
        $where
    ";
}
if ($tipo_tienda == '' || $tipo_tienda == 'online') {
    $where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';
    $consultas[] = "
        SELECT 
            fecha_emision AS fecha,
            cliente_nombre_completo AS cliente,
            'Online' AS tienda,
            metodo_pago_registrado AS metodo_pago,
            total_factura AS total,
            estado_factura AS estado
        FROM facturas_venta
        $where
    ";
}

$sql = implode(" UNION ALL ", $consultas) . " ORDER BY fecha DESC";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
        ?>
        <tr>
            <td><?= htmlspecialchars($row['fecha']) ?></td>
            <td><?= htmlspecialchars($row['cliente']) ?></td>
            <td>
                <span class="badge <?= $row['tienda'] == 'Online' ? 'badge-info' : 'badge-success' ?>">
                    <?= $row['tienda'] ?>
                </span>
            </td>
            <td><?= htmlspecialchars($row['metodo_pago']) ?></td>
            <td>$<?= number_format($row['total'], 0, ',', '.') ?></td>
            <td>
                <span class="badge-estado estado-<?= strtolower($row['estado']) ?>">
                    <?= $row['estado'] ?>
                </span>
            </td>
        </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="6">No hay resultados para los filtros seleccionados.</td></tr>';
}
?>