<?php
session_start();
include('../login/db.php');

if (isset($_POST['metodo_pago'])) {
    $_SESSION['metodo_pago'] = $_POST['metodo_pago'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo = $_POST['metodo_pago'] ?? null;
    $monto = $_POST['total'] ?? null;

    if (!$metodo || !$monto) {
        die("❌ Faltan datos para procesar el pago.");
    }

    // Redirigir al formulario del método correspondiente
    switch ($metodo) {
        case 'paypal':
            header("Location: paypal.php?total=$monto");
            break;
        case 'tarjeta':
            header("Location: tarjeta_credito.php?total=$monto");
            break;
        case 'transferencia':
            header("Location: transferencia.php?total=$monto");
            break;
        default:
            die("Método de pago no reconocido.");
    }
    exit();
} else {
    die("Acceso no válido.");
}

