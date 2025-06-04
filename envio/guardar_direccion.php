<?php
session_start();
include('../login/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que el usuario esté autenticado
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../login/login.php");
        exit();
    }

    $usuario_id = $_SESSION['usuario_id'];
    $pais = $_POST['pais'];
    $departamento = $_POST['departamento'];
    $ciudad = $_POST['ciudad'];
    $codigo_postal = $_POST['codigo_postal'];
    $direccion = $_POST['direccion'];
    $direccion_adiccional = $_POST['direccion_adiccional'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $identificacion = $_POST['identificacion'];
    $total = $_POST['total'];

    // Guarda la dirección en la sesión
    $_SESSION['direccion_envio'] = [
        'pais' => $pais,
        'departamento' => $departamento,
        'ciudad' => $ciudad,
        'codigo_postal' => $codigo_postal,
        'direccion' => $direccion,
        'direccion_adiccional' => $direccion_adiccional,
        'telefono' => $telefono,
        'identificacion' => $identificacion
    ];

    // INSERTAR dirección en la base de datos
    $insert_dir = "INSERT INTO direccion (usuario_id, pais, departamento, ciudad, codigo_postal, direccion, direccion_adiccional, telefono, identificacion)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_dir = $conn->prepare($insert_dir); // <-- usa $conn aquí
    if (!$stmt_dir) {
        die("Error en prepare: " . $conn->error);
    }
    $stmt_dir->bind_param(
        "issssssss",
        $usuario_id,
        $pais,
        $departamento,
        $ciudad,
        $codigo_postal,
        $direccion,
        $direccion_adiccional,
        $telefono,
        $identificacion
    );
    $stmt_dir->execute();
    $stmt_dir->close();

    // Guarda el carrito y el total en la sesión
    if (isset($_POST['carrito_json'])) {
        $_SESSION['carrito'] = json_decode($_POST['carrito_json'], true);
    }
    $_SESSION['total'] = $_POST['total'] ?? 0;

    // Opcional: actualiza el correo en la base de datos
    $update_correo = "UPDATE usuario SET correo=? WHERE id=?";
    $stmt_correo = $conn->prepare($update_correo);
    $stmt_correo->bind_param("si", $correo, $usuario_id);
    $stmt_correo->execute();
    $stmt_correo->close();
    $_SESSION['correo'] = $correo;

    // Crear el pedido (estado pendiente)
    $estado_pedido = 'pendiente';
    $usuario_id = $_SESSION['usuario_id'];
    $total = $_SESSION['total'];
    $fecha_orden = date('Y-m-d H:i:s');

    $stmt_pedido = $conn->prepare("INSERT INTO pedido (usuario_id, fecha_orden, estado, total) VALUES (?, ?, ?, ?)");
    $stmt_pedido->bind_param("issi", $usuario_id, $fecha_orden, $estado_pedido, $total);
    $stmt_pedido->execute();
    $id_pedido = $stmt_pedido->insert_id;
    $stmt_pedido->close();

    $_SESSION['id_pedido'] = $id_pedido;

    // Insertar en pedido_historial (primer registro: pendiente -> pendiente)
    $estado_anterior = null;
    $estado_nuevo = 'pendiente';
    $stmt_historial = $conn->prepare("INSERT INTO pedido_historial (id_pedido, estado_anterior, estado_nuevo) VALUES (?, ?, ?)");
    $stmt_historial->bind_param("iss", $id_pedido, $estado_anterior, $estado_nuevo);
    $stmt_historial->execute();
    $stmt_historial->close();

    // Insertar detalles del pedido SOLO con los productos del carrito
    if (!empty($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $item) {
            $id_detalles_productos = $item['id_detalles_productos'];
            $cantidad = $item['quantity'];
            $precio_unitario = $item['price'];
            $subtotal = $cantidad * $precio_unitario;
            if ($id_detalles_productos) {
                $stmt_detalle = $conn->prepare("INSERT INTO detalles_pedido (id_pedido, id_detalles_productos, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
                $stmt_detalle->bind_param("iiidd", $id_pedido, $id_detalles_productos, $cantidad, $precio_unitario, $subtotal);
                $stmt_detalle->execute();
                $stmt_detalle->close();
            }
        }
    }

    header("Location: pago.php");
    exit();
} else {
    header("Location: envio.php");
    exit();
}
?>
