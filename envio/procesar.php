<?php
session_start();
require_once '../login/conexion.php';

// Obtén los datos del formulario o sesión
$metodo_pago = $_SESSION['metodo_pago'] ?? '';
$correo = $_SESSION['correo'] ?? '';
$total = $_SESSION['total'] ?? 0;
$id_pedido = $_SESSION['id_pedido'] ?? 0;

// Por defecto, el pago es exitoso
$estado_pago = 'completado';

// RESTRICCIONES SEGÚN MÉTODO DE PAGO
if ($metodo_pago === 'paypal') {
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $estado_pago = 'fallido';
        $_SESSION['motivo_fallo'] = 'El correo electrónico ingresado no es válido.';
    } elseif (strpos($correo, 'fail') !== false) {
        $estado_pago = 'fallido';
        $_SESSION['motivo_fallo'] = 'El correo electrónico contiene palabras no permitidas.';
    }
} elseif ($metodo_pago === 'tarjeta') {
    $numero_tarjeta = $_POST['numero_tarjeta'] ?? '';
    $numero_tarjeta = str_replace(' ', '', $numero_tarjeta); // Quita espacios
    $cvv = $_POST['cvv'] ?? '';
    $fecha_venc = $_POST['fecha_expiracion'] ?? '';
    $banco = $_POST['banco'] ?? '';
    if (!preg_match('/^\d{15,16}$/', $numero_tarjeta)) {
        $estado_pago = 'fallido';
        $_SESSION['motivo_fallo'] = 'El número de tarjeta debe tener 15 o 16 dígitos.';
    } elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
        $estado_pago = 'fallido';
        $_SESSION['motivo_fallo'] = 'El CVV debe tener 3 o 4 dígitos.';
    } elseif (empty($fecha_venc)) {
        $estado_pago = 'fallido';
        $_SESSION['motivo_fallo'] = 'La fecha de vencimiento es obligatoria.';
    } elseif (empty($banco)) {
        $estado_pago = 'fallido';
        $_SESSION['motivo_fallo'] = 'Debes seleccionar el banco emisor de la tarjeta.';
    }
} elseif ($metodo_pago === 'transferencia') {
    // Elimina o comenta la validación de referencia
}

$banco = $_POST['banco'] ?? null;
if ($metodo_pago === 'tarjeta' || $metodo_pago === 'transferencia') {
    $banco_pago = $banco;
} else {
    $banco_pago = null;
}

// Guarda el pago y actualiza el pedido
if ($estado_pago === 'completado') {
    $nuevo_estado = 'confirmado';
    // Obtener el estado anterior antes del cambio
    $stmt_estado = $conn->prepare("SELECT estado FROM pedido WHERE id_pedido = ?");
    $stmt_estado->bind_param("i", $id_pedido);
    $stmt_estado->execute();
    $stmt_estado->bind_result($estado_anterior);
    $stmt_estado->fetch();
    $stmt_estado->close();

    // Cambia el estado del pedido
    $stmt = $conn->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
    $stmt->bind_param("si", $nuevo_estado, $id_pedido);
    $stmt->execute();
    $stmt->close();

    // Insertar en historial (antes de redirigir)
    $stmt_historial = $conn->prepare("INSERT INTO pedido_historial (id_pedido, estado_anterior, estado_nuevo) VALUES (?, ?, ?)");
    $stmt_historial->bind_param("iss", $id_pedido, $estado_anterior, $nuevo_estado);
    $stmt_historial->execute();
    $stmt_historial->close();

    // Inserta el pago
    $stmt_pago = $conn->prepare("INSERT INTO pago (fecha_pago, metodo_pago, id_pedido, estado_pago, monto, banco) VALUES (NOW(), ?, ?, ?, ?, ?)");
    $stmt_pago->bind_param("sisds", $metodo_pago, $id_pedido, $estado_pago, $total, $banco_pago);
    $stmt_pago->execute();
    $stmt_pago->close();

    header("Location: pago_exitoso.php");
    exit();
} else {
    $nuevo_estado = 'fallido';

    // Obtener el estado anterior antes del cambio
    $stmt_estado = $conn->prepare("SELECT estado FROM pedido WHERE id_pedido = ?");
    $stmt_estado->bind_param("i", $id_pedido);
    $stmt_estado->execute();
    $stmt_estado->bind_result($estado_anterior);
    $stmt_estado->fetch();
    $stmt_estado->close();

    // Cambia el estado del pedido
    $stmt = $conn->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
    $stmt->bind_param("si", $nuevo_estado, $id_pedido);
    $stmt->execute();
    $stmt->close();

    // Insertar en historial
    $stmt_historial = $conn->prepare("INSERT INTO pedido_historial (id_pedido, estado_anterior, estado_nuevo) VALUES (?, ?, ?)");
    $stmt_historial->bind_param("iss", $id_pedido, $estado_anterior, $nuevo_estado);
    $stmt_historial->execute();
    $stmt_historial->close();

    // Inserta el pago como fallido
    $stmt_pago = $conn->prepare("INSERT INTO pago (fecha_pago, metodo_pago, id_pedido, estado_pago, monto, banco) VALUES (NOW(), ?, ?, ?, ?, ?)");
    $stmt_pago->bind_param("sisds", $metodo_pago, $id_pedido, $estado_pago, $total, $banco_pago);
    $stmt_pago->execute();
    $stmt_pago->close();

    header("Location: pago_fallido.php");
    exit();
}
?>
