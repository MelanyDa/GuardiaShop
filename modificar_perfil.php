<?php
// Inicia la sesión para acceder a variables de sesión
@session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login/login.php"); // Ajusta si es necesario
  exit();
}
require_once './login/conexion.php';

$usuario_id = $_SESSION['usuario_id'];
// Obtener datos del usuario para la barra lateral y el formulario de info personal
$sql_usuario = "SELECT primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_de_cumpleaños, rol, fecha_registro, foto_perfil FROM usuario WHERE id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$datos_usuario_actual = []; // Para evitar error si no se encuentra

if ($stmt_usuario) {
    $stmt_usuario->bind_param("i", $usuario_id);
    $stmt_usuario->execute();
    $result_usuario = $stmt_usuario->get_result();
    if ($row_usuario = $result_usuario->fetch_assoc()) {
        $datos_usuario_actual = $row_usuario;
    }
    $stmt_usuario->close();
} else {
    error_log("Error preparando consulta de usuario: " . $conn->error);
}


// --- OBTENER PEDIDOS DEL USUARIO (Se cargarán cuando se muestre la sección) ---
$pedidos_usuario = [];
if (isset($conn)) { // Volver a verificar $conn por si se cerró o hubo error
    $sql_pedidos = "SELECT id_pedido, fecha_orden, total, estado
                    FROM pedido
                    WHERE usuario_id = ?
                    ORDER BY fecha_orden DESC";
    $stmt_pedidos = $conn->prepare($sql_pedidos);
    if ($stmt_pedidos) {
        $stmt_pedidos->bind_param("i", $usuario_id);
        $stmt_pedidos->execute();
        $result_pedidos = $stmt_pedidos->get_result();
        while ($row_pedido = $result_pedidos->fetch_assoc()) {
            $pedidos_usuario[] = $row_pedido;
        }
        $stmt_pedidos->close();
    } else {
        error_log("Error preparando consulta de pedidos: " . $conn->error);
    }
}
// No cerramos $conn aquí si es usado por includes como el footer
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Mi Perfil - GuardiaShop">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mi Perfil - GUARDIASHOP</title>
    <link rel="icon" href="img/core-img/logoguardiashop.ico">
    <link rel="stylesheet" href="css/core-styleff.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/carrito.css">
    <!-- <link rel="stylesheet" href="assets/css/main.css"> -->
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      
        /* Estilos para profile.css (o aquí si prefieres) */
        body {
            font-size: 0.94em; 
        }
        .profile-container {
            display: flex;
            gap: 30px; /* Espacio entre sidebar y content */
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .profile-sidebar {
            flex: 0 0 280px; /* Ancho fijo para la barra lateral, no crece, no se encoge */
            background-color: #f8f9fa; /* Un color de fondo sutil */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .user-info { text-align: center; margin-bottom: 25px; }
        .avatar { position: relative; width: 120px; height: 120px; margin: 0 auto 15px; border-radius: 50%; overflow: hidden; border: 3px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .avatar .change-photo { /* Estilo para el ícono de cambiar foto si lo implementas */
            position: absolute; bottom: 5px; right: 5px; background-color: rgba(0,0,0,0.6); color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer;
        }
        .user-info h3 { margin-bottom: 5px; font-size: 1.4em; color: #333; }
        .user-info p { font-size: 0.9em; color: #666; margin-bottom: 3px; }

        .profile-menu { list-style-type: none; padding: 0; margin: 0; }
        .profile-menu li a {
            display: flex; /* Para alinear ícono y texto */
            align-items: center;
            padding: 16px 20px;
            text-decoration: none;
            color: #333;
            border-radius: 6px;
            margin-bottom: 8px;
            transition: background-color 0.2s, color 0.2s;
            font-size: 1em;
        }
        .profile-menu li a i { margin-right: 12px; width: 20px; text-align: center; /* Para alinear íconos */ }
        .profile-menu li a:hover { background-color: #e9ecef; color: #000; }
        .profile-menu li.active a {
            background-color:#b78732; /* Tu color amarillo/dorado */
            color: white;
            font-weight: 500;
        }

        .profile-content {
            flex-grow: 1; /* Ocupa el espacio restante */
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .profile-section { display: none; /* Ocultas por defecto, JS las muestra */ }
        .profile-section.active { display: block; } /* La sección activa se muestra */

        .section-header { margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .section-header h2 { 
            font-size: 2em;
            color: #333; 
            margin-bottom: 5px; 
        }
        .section-header p { 
            font-size: 1em; 
            color: #666; 
        }

        /* Estilos del formulario (ajusta según tu profile.css) */
        .profile-form .form-group { margin-bottom: 20px; }
        .profile-form label { display: block; margin-bottom: 6px; font-weight: 500; color: #495057; }
        .profile-form input[type="text"],
        .profile-form input[type="date"],
        .profile-form input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 0.95em;
        }
        .profile-form input[type="file"] { padding: 7px; }
        .profile-form .form-actions { margin-top: 25px; text-align: right; }
        .profile-form .btn-primary { /* Hereda o define tu estilo de botón principal */
            background-color:#b78732; color: white; padding: 10px 20px; border:none; border-radius: 4px; cursor: pointer; font-size: 1em;
        }

        /* Estilos para la lista de historial de pedidos */
        .order-history-list { margin-top: 20px; }
        .order-item-summary { background-color: #f9f9f9; border: 1px solid #eee; border-radius: 6px; padding: 15px 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; transition: box-shadow 0.2s ease-in-out; }
        .order-item-summary:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .order-item-summary .order-info h3 { margin-top: 0; margin-bottom: 8px; font-size: 1.3em; color: #333; }
        .order-item-summary .order-info p { margin: 4px 0; font-size: 0.95em; color: #555; }
        .order-item-summary .order-info p strong { color: #444; }
        .order-item-summary .order-actions .btn-secondary,
        .order-item-summary .order-actions .btn-primary { background-color: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-size: 0.9em; transition: background-color 0.2s; border:none; }
        .order-item-summary .order-actions .btn-secondary:hover,
        .order-item-summary .order-actions .btn-primary:hover { background-color: #5a6268; }
        .order-item-summary .order-actions .btn-primary { background-color: #c8a464; /* Tu color amarillo/dorado */ }
        .order-item-summary .order-actions .btn-primary:hover { background-color: #b08f4f; /* Un tono más oscuro */ }

        .status-pendiente { color: #ffc107; font-weight: bold; }
        .status-confirmado, .status-pagado { color: #28a745; font-weight: bold; }
        .status-preparando { color: #17a2b8; font-weight: bold; }
        .status-enviado { color: #007bff; font-weight: bold; }
        .status-en_camino { color: #fd7e14; font-weight: bold; }
        .status-entregado { color: #20c997; font-weight: bold; }
        .status-cancelado { color: #dc3545; font-weight: bold; }
        .status-devuelto { color: #6f42c1; font-weight: bold; }
        .status-fallido, .status-pago_fallido { color: #dc3545; font-weight: bold; }

        @media (max-width: 768px) { /* Para que la sidebar y content se apilen en móviles */
            .profile-container { flex-direction: column; }
            .profile-sidebar { flex: 0 0 auto; width: 100%; margin-bottom: 20px; }
        }
         @media (max-width: 576px) {
            .order-item-summary { flex-direction: column; align-items: flex-start; }
            .order-item-summary .order-actions { margin-top: 10px; width: 100%; }
            .order-item-summary .order-actions .btn-secondary,
            .order-item-summary .order-actions .btn-primary { display: block; text-align: center; }
        }
    </style>
</head>
<body>
    <?php include './arc/nav.php'; ?>
    
    <main>
    <div class="container">
      <div class="profile-container">

        <div class="profile-sidebar">
          <div class="user-info">
            <div class="avatar">
              <img src="<?= isset($datos_usuario_actual['foto_perfil']) && !empty($datos_usuario_actual['foto_perfil']) ? htmlspecialchars($datos_usuario_actual['foto_perfil']) : './assets/images/q.jpeg' ?>" alt="Foto de perfil" id="img-preview" style="object-fit:cover;">
              <!-- <div class="change-photo"><i class="fas fa-camera"></i></div> -->
            </div>
            <h3><?= htmlspecialchars($datos_usuario_actual['primer_nombre'] ?? 'Usuario') . ' ' . htmlspecialchars($datos_usuario_actual['primer_apellido'] ?? '') ?></h3>
            <p>Rol: <?= htmlspecialchars($datos_usuario_actual['rol'] ?? 'Cliente') ?></p>
            <p>Registrado desde: <?= isset($datos_usuario_actual['fecha_registro']) ? htmlspecialchars(date("d/m/Y", strtotime($datos_usuario_actual['fecha_registro']))) : 'N/A' ?></p>
          </div>
<?php
// Mensajes respondidos no leídos
$notificaciones_mensajes = 0;
$stmt_notif = $conn->prepare("SELECT COUNT(*) FROM contactanos WHERE id_usuario = ? AND estado = 'Respondido'");
$stmt_notif->bind_param("i", $usuario_id);
$stmt_notif->execute();
$stmt_notif->bind_result($notificaciones_mensajes);
$stmt_notif->fetch();
$stmt_notif->close();

// Notificaciones de pedidos con estado "Enviado" (puedes ajustar el estado según tu flujo)
$notificaciones_pedidos = 0;
$stmt_notif_ped = $conn->prepare("SELECT COUNT(*) FROM pedido WHERE usuario_id = ? AND estado = 'Enviado'");
$stmt_notif_ped->bind_param("i", $usuario_id);
$stmt_notif_ped->execute();
$stmt_notif_ped->bind_result($notificaciones_pedidos);
$stmt_notif_ped->fetch();
$stmt_notif_ped->close();
?>
          <ul class="profile-menu">
            <li data-section="info-personal" class="active"><a href="javascript:void(0);"><i class="fas fa-user"></i> Información Personal</a></li>
            <li data-section="mis-pedidos"><a href="javascript:void(0);"><i class="fas fa-shopping-bag"></i> Mis Pedidos
    <?php if ($notificaciones_pedidos > 0): ?>
      <span class="badge-pedidos" style="background:#dc3545;color:#fff;border-radius:50%;padding:2px 8px;font-size:0.9em;margin-left:6px;"><?= $notificaciones_pedidos ?></span>
    <?php endif; ?>
  </a></li>
            <li data-section="mis-mensajes"><a href="javascript:void(0);"><i class="fas fa-envelope"></i> Mis Mensajes
    <?php if ($notificaciones_mensajes > 0): ?>
      <span class="badge-mensajes" style="background:#dc3545;color:#fff;border-radius:50%;padding:2px 8px;font-size:0.9em;margin-left:6px;"><?= $notificaciones_mensajes ?></span>
    <?php endif; ?>
  </a></li>
            <!--  <li data-section="metodos-pago"><a href="javascript:void(0);"><i class="fas fa-credit-card"></i> Métodos de Pago</a></li>-->
          </ul>
        </div>

        <div class="profile-content">
          <!-- SECCIÓN: INFORMACIÓN PERSONAL -->
          <div id="info-personal" class="profile-section active">
            <?php if (isset($_SESSION['success_message']) || isset($_SESSION['error_message'])): ?>
              <div class="content-message">
                <div class="message <?= isset($_SESSION['success_message']) ? 'success' : 'error' ?>">
                  <p><?= $_SESSION['success_message'] ?? $_SESSION['error_message'] ?></p>
                  <?php unset($_SESSION['success_message']); unset($_SESSION['error_message']); ?>
                </div>
              </div>
            <?php endif; ?>
            <div class="section-header">
              <h2>Información Personal</h2>
              <p>Actualiza tu información personal y cómo nos comunicamos contigo.</p>
            </div>
            <form class="profile-form" action="procesar_perfil.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="primer_nombre">Primer Nombre</label>
                    <input type="text" id="primer_nombre" name="primer_nombre" value="<?= htmlspecialchars($datos_usuario_actual['primer_nombre'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="segundo_nombre">Segundo Nombre</label>
                    <input type="text" id="segundo_nombre" name="segundo_nombre" value="<?= htmlspecialchars($datos_usuario_actual['segundo_nombre'] ?? '') ?>" >
                </div>
                <div class="form-group">
                    <label for="primer_apellido">Primer Apellido</label>
                    <input type="text" id="primer_apellido" name="primer_apellido" value="<?= htmlspecialchars($datos_usuario_actual['primer_apellido'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="segundo_apellido">Segundo Apellido</label>
                    <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?= htmlspecialchars($datos_usuario_actual['segundo_apellido'] ?? '') ?>" >
                </div>
                <div class="form-group">
                    <label for="fecha_de_cumpleaños">Fecha de cumpleaños</label>
                    <?php
                    $fecha_cumple = '';
                    if (!empty($datos_usuario_actual['fecha_de_cumpleaños']) && $datos_usuario_actual['fecha_de_cumpleaños'] !== '0000-00-00') {
                        $fecha_cumple = htmlspecialchars($datos_usuario_actual['fecha_de_cumpleaños']);
                    }
                    ?>
                    <input type="date" id="fecha_de_cumpleaños" name="fecha_de_cumpleaños" value="<?= $fecha_cumple ?>">
                </div>
                <div class="form-group">
                    <label for="foto_perfil_input">Foto de perfil</label>
                    <input type="file" id="foto_perfil_input" name="foto_perfil" accept="image/*">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Actualizar Información</button>
                </div>
            </form>
          </div>

          <!-- SECCIÓN: MIS PEDIDOS -->
          <div id="mis-pedidos" class="profile-section">
            <div class="section-header">
              <h2>Mis Pedidos</h2>
              <p>Aquí puedes ver el historial y estado de tus compras.</p>
            </div>
            <?php if (!empty($pedidos_usuario)): ?>
              <div class="order-history-list">
                <?php foreach ($pedidos_usuario as $pedido_item): ?>
                  <div class="order-item-summary">
                    <div class="order-info">
                      <!-- Quitar ID, mostrar productos -->
                      <p><strong>Fecha:</strong> <?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($pedido_item['fecha_orden']))); ?></p>
                      <p><strong>Total:</strong> $<?php echo htmlspecialchars(number_format($pedido_item['total'], 0, ',', '.')); ?></p>
                      <p><strong>Estado:</strong>
                        <span class="status-<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($pedido_item['estado']))); ?>">
                            <?php echo ucfirst(htmlspecialchars($pedido_item['estado'])); ?>
                        </span>
                      </p>
                      <?php
                      // Obtener productos del pedido (puedes optimizar esto con una consulta JOIN si tienes muchos pedidos)
                      $productos = [];
                      $stmt_prod = $conn->prepare("
    SELECT p.nombre, dp.cantidad, pi.imagen
    FROM detalles_pedido dp
    JOIN detalles_productos dprod ON dp.id_detalles_productos = dprod.id_detalles_productos
    JOIN productos p ON dprod.id_producto = p.id_producto
    LEFT JOIN producto_imagen pi ON pi.id_producto = p.id_producto
    WHERE dp.id_pedido = ?
    GROUP BY p.id_producto
");
$stmt_prod->bind_param("i", $pedido_item['id_pedido']);
$stmt_prod->execute();
$res_prod = $stmt_prod->get_result();
$productos = [];
while ($prod = $res_prod->fetch_assoc()) {
    $productos[] = $prod;
}
$stmt_prod->close();
                      ?>
                      <div style="margin-top:8px;">
                        <?php foreach ($productos as $prod): ?>
                          <span style="display:inline-block;margin-right:8px;">
                            <span style="font-size:0.97em;"><?= htmlspecialchars($prod['nombre']) ?> x<?= $prod['cantidad'] ?></span>
                          </span>
                        <?php endforeach; ?>
                      </div>
                    </div>
                    <div class="order-actions">
                      <a href="detalle_pedido.php?id_pedido=<?php echo $pedido_item['id_pedido']; ?>" class="btn-primary">Ver Detalles</a>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <p style="text-align: center; padding: 20px;">Aún no has realizado ningún pedido!</p>
            <?php endif; ?>
          
          </div>

          <!-- SECCIÓN: MIS MENSAJES -->
          <div id="mis-mensajes" class="profile-section">
            <div class="section-header">
              <h2>Mis Mensajes</h2>
              <p>Aquí puedes ver el estado de tus mensajes enviados a GuardiaShop.</p>
            </div>
            <?php
            $mensajes_usuario = [];
$stmt_mensajes = $conn->prepare("SELECT id_contacto, mensaje, estado, fecha, respuesta_admin FROM contactanos WHERE id_usuario = ? ORDER BY fecha DESC");
$stmt_mensajes->bind_param("i", $usuario_id);
$stmt_mensajes->execute();
$res_mensajes = $stmt_mensajes->get_result();
while ($row = $res_mensajes->fetch_assoc()) {
    $mensajes_usuario[] = $row;
}
$stmt_mensajes->close();
?>
<?php if (!empty($mensajes_usuario)): ?>
  <div class="order-history-list">
    <?php foreach ($mensajes_usuario as $msg): ?>
      <div class="order-item-summary" style="flex-direction:column;align-items:flex-start;">
        <div class="order-info" style="width:100%;">
          <p><strong>Fecha:</strong> <?= htmlspecialchars(date("d/m/Y H:i", strtotime($msg['fecha']))); ?></p>
          <p><strong>Mensaje:</strong> <?= nl2br(htmlspecialchars($msg['mensaje'])); ?></p>
          <p>
            <strong>Estado:</strong>
            <?php
              $color = "#888";
              if ($msg['estado'] == 'Respondido') $color = "#28a745";
              elseif ($msg['estado'] == 'Leído') $color = "#ffc107";
              elseif ($msg['estado'] == 'Cerrado') $color = "#6c757d";
            ?>
            <span style="color:<?= $color ?>;font-weight:bold;"><?= htmlspecialchars($msg['estado']) ?></span>
          </p>
          <?php if (!empty($msg['respuesta_admin'])): ?>
            <div style="background:#f1f8e9;padding:10px 15px;border-radius:6px;margin-top:8px;">
              <strong>Respuesta del equipo:</strong><br>
              <?= nl2br(htmlspecialchars($msg['respuesta_admin'])) ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <p style="text-align:center;padding:20px;">¡Aún no has enviado mensajes!</p>
<?php endif; ?>
          </div>

          <!-- SECCIÓN: MÉTODOS DE PAGO (Placeholder)
          <div id="metodos-pago" class="profile-section">
              <div class="section-header">
                  <h2>Métodos de Pago</h2>
                  <p>Administra tus métodos de pago guardados (funcionalidad futura).</p>
              </div>
              <p style="text-align: center; padding: 20px;">Esta sección estará disponible próximamente.</p>
          </div>
        </div> -->
      </div>
    </div>
  </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.profile-menu li');
            const sections = document.querySelectorAll('.profile-section');

            function mostrarSeccion(idSeccionAMostrar) {
                sections.forEach(section => {
                    if (section.id === idSeccionAMostrar) {
                        section.style.display = 'block';
                        section.classList.add('active');
                    } else {
                        section.style.display = 'none';
                        section.classList.remove('active');
                    }
                });

                menuItems.forEach(item => {
                    if (item.dataset.section === idSeccionAMostrar) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }

            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    mostrarSeccion(this.dataset.section);

                    // Oculta el numerito de pedidos
                    if (this.dataset.section === 'mis-pedidos') {
                        const badge = this.querySelector('.badge-pedidos');
                        if (badge) badge.style.display = 'none';
                    }
                    // Oculta el numerito de mensajes
                    if (this.dataset.section === 'mis-mensajes') {
                        const badge = this.querySelector('.badge-mensajes');
                        if (badge) badge.style.display = 'none';
                    }
                });
            });

            // Mostrar la sección según el hash de la URL (o la primera por defecto)
            function activarSeccionPorHash() {
                let seccionInicial = 'info-personal';
                if(window.location.hash) {
                    const hashSeccion = window.location.hash.substring(1);
                    if(document.getElementById(hashSeccion)){
                        seccionInicial = hashSeccion;
                    }
                }
                mostrarSeccion(seccionInicial);
                // Activar el menú lateral correspondiente
                menuItems.forEach(item => {
                    if (item.dataset.section === seccionInicial) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
            activarSeccionPorHash();

            // Si el usuario navega manualmente cambiando el hash, actualiza la sección
            window.addEventListener('hashchange', activarSeccionPorHash);


            // Previsualización de imagen de perfil
            const inputFotoPerfil = document.getElementById('foto_perfil_input');
            const imgPreview = document.getElementById('img-preview');
            if (inputFotoPerfil && imgPreview) {
                inputFotoPerfil.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            imgPreview.src = ev.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        // Funciones del menú de navegación y carrito (asegúrate que los IDs/clases coincidan con tu nav.php)
        function toggleUserMenu() { /* ... tu lógica ... */ }
        document.addEventListener('click', function(event) { /* ... tu lógica para cerrar dropdowns ... */ });
        // if (typeof actualizarContadorCarrito === "function") { // Llama a actualizarContadorCarrito si existe
        //     actualizarContadorCarrito();
        // }
    </script>
    <script src="./assets/js/carrito.js"></script> <!-- Verifica la ruta -->

    <?php include './arc/footer.php';?>

    <!-- jQuery y otros plugins (si los necesitas) -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/classy-nav.min.js"></script>
    <script src="js/active.js"></script>
</body>
</html>

