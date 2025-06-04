<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<header class="header" style="background-color:#ffff">
  <!-- Core Style CSS -->
  <link rel="stylesheet" href="../css/core-styleff.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/carrito.css">
    <header class="header" style="background-color:#ffff">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
   <?php
   include '../login/db.php';

    // Si el usuario no está logueado, redirigir a la página de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/login.php");
    exit();
}



include '../login/db.php';



// Obtener datos del usuario logueado
$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    die("❌ No se pudieron obtener los datos del usuario.");
}

// Obtener la última dirección registrada del usuario
$sql_dir = "SELECT * FROM direccion WHERE usuario_id = ? ORDER BY id_direccion DESC LIMIT 1";
$stmt_dir = $conn->prepare($sql_dir);
$stmt_dir->bind_param("i", $id_usuario);
$stmt_dir->execute();
$result_dir = $stmt_dir->get_result();
$direccion = $result_dir->fetch_assoc();
$stmt_dir->close();
?>



 
</header>

<body>
     <?php include '../arc/nav.php'; ?>

  

<div class="pay_area section-padding-80" style="background-color:#fff;">
    <h2 class="text-center mb-4" style="color: #b78732; font-weight: bold;">
         Información de Envío
            </h2>
    <div class="container">
        <div class="row justify-content-between">
            <!-- Formulario de dirección -->
            <div class="col-12 col-md-6">
                <div class="checkout_details_area mt-10 clearfix p-4 rounded" style="background:#f8f8f8; box-shadow: 0 4px 20px #b78732; border: none;">
                    <div class="cart-page-heading mb-4">
                        <h5 class="text-dark">Dirección de Envío</h5>
                    </div>
                    <form action="guardar_direccion.php" method="post">
                        <div class="row g-3">
                            <style>
                                .form-control {
                                    border: 1px solid #000 !important;
                                    box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
                                }
                            </style>
                            <div class="col-md-6">
                                <label for="nombre">Nombres <span class="text-warning">*</span></label>
                              <input type="text" class="form-control rounded" id="nombre" name="primer_nombre"
                                 value="<?php echo htmlspecialchars($usuario['primer_nombre']); ?>"required>
                            </div>
                              <div class="col-md-6">
                                    <label for="apellido">Apellidos <span class="text-warning">*</span></label>
                                    <input type="text" class="form-control rounded" id="apellido" name="primer_apellido"
                                           value="<?php echo htmlspecialchars($usuario['primer_apellido']); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="pais">País <span class="text-warning">*</span></label>
                                    <input type="text" class="form-control rounded" id="pais" name="pais"
                                           value="<?php echo isset($direccion['pais']) ? htmlspecialchars($direccion['pais']) : ''; ?>"
                                           required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="departamento">Departamento <span class="text-warning">*</span></label>
                                    <input type="text" class="form-control rounded" id="departamento" name="departamento"
                                           value="<?php echo isset($direccion['departamento']) ? htmlspecialchars($direccion['departamento']) : ''; ?>"
                                           required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="city">Ciudad <span class="text-warning">*</span></label>
                                    <input type="text" class="form-control rounded" id="city" name="ciudad"
                                           value="<?php echo isset($direccion['ciudad']) ? htmlspecialchars($direccion['ciudad']) : ''; ?>"
                                           required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="postcode">Código Postal <span class="text-warning">*</span></label>
                                    <input type="text" class="form-control rounded" id="postcode" name="codigo_postal"
                                           value="<?php echo isset($direccion['codigo_postal']) ? htmlspecialchars($direccion['codigo_postal']) : ''; ?>"
                                           required>
                                </div>
                                <div class="col-12">
                                    <label for="street_address">Dirección #1 <span class="text-warning">*</span></label>
                                    <input type="text" class="form-control rounded mb-2" id="street_address" name="direccion"
                                           value="<?php echo isset($direccion['direccion']) ? htmlspecialchars($direccion['direccion']) : ''; ?>"
                                           required>
                                    <label for="street_address2">Dirección Adicional</label>
                                    <input type="text" class="form-control rounded" id="street_address2" name="direccion_adiccional"
                                           value="<?php echo isset($direccion['direccion_adiccional']) ? htmlspecialchars($direccion['direccion_adiccional']) : ''; ?>"
                                           >
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="phone_number">Teléfono <span class="text-warning">*</span></label>
                                    <input type="text" class="form-control rounded" id="phone_number" name="telefono"
                                           value="<?php echo isset($direccion['telefono']) ? htmlspecialchars($direccion['telefono']) : ''; ?>"
                                           required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email_address">Correo Electrónico <span class="text-warning">*</span></label>
                                    <input type="email" class="form-control rounded" id="email_address" name="correo"
                                           value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
                                </div>
                                <!-- Campo para Cédula o NIT -->
                                <div class="col-12 col-md-6">
                                    <label for="identificacion">Cédula o NIT <span class="text-warning">*</span></label>
                                    <input type="text" class="form-control rounded" id="identificacion" name="identificacion"
                                           value="<?php echo isset($direccion['identificacion']) ? htmlspecialchars($direccion['identificacion']) : ''; ?>"
                                           required>
                                </div>
                                <!-- El monto del pedido (lo pasamos oculto) -->
                                 <input type="hidden" name="carrito_json" id="carrito_json">
  
                                <!-- Campo oculto para el total del pedido -->
                                  <input type="hidden" name="total" id="total">
                            
                        </div>
                        <div class="col-12 mt-3">
                        <div class="form-check">
                        <!-- CORREGIDO: se agregó name="terminos" para validación -->
                        <input class="form-check-input" type="checkbox" id="customCheck1" name="terminos" required>
                            <label class="form-check-label" for="customCheck1">Acepto términos y condiciones</label>

                        </div>
                        </div>
                    
                                
                          <!-- Botón Comprar -->

                        <div class="text-center">
                            <button type="submit" class="btn btn-block mt-2" style="background-color: #b78732; color: white; border: none; border-radius: 6px; padding: 12px 0; font-weight: bold; transition: background-color 0.3s ease;">
                                Comprar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detalles del pedido -->
            <div class="col-12 col-md-6 col-lg-5">
                <div class="order-details-confirmation p-4 rounded" style="background:#f8f8f8; box-shadow: 0 4px 20px #b78732; border: none;">
                    <div class="cart-page-heading mb-4">
                        <h5 class="text-dark">Tu Pedido</h5>
                        <p class="text-muted">Detalles</p>
                    </div>

                    <ul class="list-unstyled mb-4" id="order-products-list">
                        <li class="d-flex justify-content-between border-bottom py-2"><strong>PRODUCTOS</strong><strong>TOTAL</strong></li>
                        <li class="d-flex justify-content-between border-bottom py-2"><span>Top Cruzado Llama Tropical</span><span>$375.000</span></li>
                        <li class="d-flex justify-content-between border-bottom py-2"><span>Subtotal</span><span>$375.000</span></li>
                        <li class="d-flex justify-content-between border-bottom py-2"><span>Envío</span><span>gratis</span></li>
                        <li class="d-flex justify-content-between border-bottom py-2"><strong>Total</strong><strong>$375.000</strong></li>
                    </ul>
                    

                       

                        

                    
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- ##### New Arrivals Area End ##### -->
    <script src="../assets/js/carrito.js"></script>
    <!-- ##### Footer Area Start ##### -->
    <?php include '../arc/footer.php';?>
    <!-- ##### Footer Area End ##### -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="guardar_direccion.php"]');
    if (form) {
        form.addEventListener('submit', function() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let carritoProcesado = cart.map(item => ({
                id_detalles_productos: Number(item.id_detalles_productos || item.idDetalleProducto), // <-- SIEMPRE esta clave y como número
                quantity: item.quantity,
                price: item.price
            }));
            document.getElementById('carrito_json').value = JSON.stringify(carritoProcesado);
        });
    }
});
</script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('DOMContentLoaded', function() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let orderList = document.getElementById('order-products-list');
        orderList.innerHTML = '';
    
        if (cart.length === 0) {
            orderList.innerHTML = '<li>No hay productos en el carrito.</li>';
            return;
        }
    
        orderList.innerHTML += `
            <li class="d-flex justify-content-between border-bottom py-2"><strong>PRODUCTOS</strong><strong>TOTAL</strong></li>
        `;
    
        let subtotal = 0;
        cart.forEach(item => {
            let totalItem = item.price * item.quantity;
            subtotal += totalItem;
            orderList.innerHTML += `
                <li class="d-flex justify-content-between border-bottom py-2">
                    <span>${item.name} x${item.quantity}</span>
                    <span>$${totalItem.toLocaleString('es-CO')}</span>
                </li>
            `;
        });
    
        orderList.innerHTML += `
            <li class="d-flex justify-content-between border-bottom py-2"><span>Subtotal</span><span>$${subtotal.toLocaleString('es-CO')}</span></li>
            <li class="d-flex justify-content-between border-bottom py-2"><span>Envío</span><span>Gratis</span></li>
            <li class="d-flex justify-content-between border-bottom py-2"><strong>Total</strong><strong>$${subtotal.toLocaleString('es-CO')}</strong></li>
        `;
    
        // Actualiza el input oculto con el total
        let totalInput = document.querySelector('input[name="total"]');
        if (totalInput) {
            totalInput.value = subtotal;
        }
    });    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let orderList = document.getElementById('order-products-list');
    orderList.innerHTML = '';

    if (cart.length === 0) {
        orderList.innerHTML = '<li>No hay productos en el carrito.</li>';
        return;
    }

    orderList.innerHTML += `
        <li class="d-flex justify-content-between border-bottom py-2"><strong>PRODUCTOS</strong><strong>TOTAL</strong></li>
    `;

    let subtotal = 0;
    cart.forEach(item => {
        let totalItem = item.price * item.quantity;
        subtotal += totalItem;
        orderList.innerHTML += `
            <li class="d-flex justify-content-between border-bottom py-2">
                <span>${item.name} x${item.quantity}</span>
                <span>$${totalItem.toLocaleString('es-CO')}</span>
            </li>
        `;
    });

    orderList.innerHTML += `
        <li class="d-flex justify-content-between border-bottom py-2"><span>Subtotal</span><span>$${subtotal.toLocaleString('es-CO')}</span></li>
        <li class="d-flex justify-content-between border-bottom py-2"><span>Envío</span><span>Gratis</span></li>
        <li class="d-flex justify-content-between border-bottom py-2"><strong>Total</strong><strong>$${subtotal.toLocaleString('es-CO')}</strong></li>
    `;

    // Actualiza el input oculto con el total
    let totalInput = document.querySelector('input[name="total"]');
    if (totalInput) {
        totalInput.value = subtotal;
    }
  
// Guardar el carrito actual en el campo oculto antes de enviar el formulario
  // document.querySelector('form').addEventListener('submit', function () {
  //  const carrito = localStorage.getItem('cart');
   // document.getElementById('carrito_json').value = carrito;
//});


});
</script>

</body>

</html>