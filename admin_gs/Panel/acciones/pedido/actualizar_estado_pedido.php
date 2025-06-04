<?php
require_once(__DIR__ . '/../../conexion.php');
if (!isset($_POST['id_pedido']) || !isset($_POST['nuevo_estado'])) {
    http_response_code(400);
    echo "Faltan datos.";
    exit;
}
$id_pedido = intval($_POST['id_pedido']);
$nuevo_estado = $_POST['nuevo_estado'];

// Opcional: Valida que el estado sea uno permitido
$estados_validos = ['pendiente','confirmado','preparando','enviado','en camino','entregado','cancelado','devuelto','fallido'];
if (!in_array($nuevo_estado, $estados_validos)) {
    http_response_code(400);
    echo "Estado no válido.";
    exit;
}

// 1. Obtener el estado anterior
$stmt = $conn->prepare("SELECT estado FROM pedido WHERE id_pedido = ?");
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$stmt->bind_result($estado_anterior);
$stmt->fetch();
$stmt->close();

// 2. Actualiza el estado
$stmt = $conn->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
$stmt->bind_param("si", $nuevo_estado, $id_pedido);
if ($stmt->execute()) {
    // 3. Insertar en historial
    $stmt_hist = $conn->prepare("INSERT INTO pedido_historial (id_pedido, estado_anterior, estado_nuevo) VALUES (?, ?, ?)");
    $stmt_hist->bind_param("iss", $id_pedido, $estado_anterior, $nuevo_estado);
    $stmt_hist->execute();
    $stmt_hist->close();

    echo "ok";
} else {
    http_response_code(500);
    echo "Error al actualizar: " . $conn->error;
}
$stmt->close();
?>