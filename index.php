<?php
session_start();
?>
<!DOCTYPE html> 
<html lang="en">
<header class="header" style="background-color:#ffff">
  <!-- Core Style CSS -->
  <link rel="stylesheet" href="css/core-styleff.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/carrito.css">
        
    <?php include './arc/nav.php'; ?>

<!-- HERO/BANNER -->
<section class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-wrapper">
    <div class="hero-text">
      <h6 class="hero-subtitle">GUARDIASHOP</h6>
      <h1 class="hero-title">Nueva Colección</h1>
      <a href="./shop.php" class="btn-primary">Ver Colección</a>
    </div>
  </div>
</section>

<style>
   html { font-size: 10px; }
   /* body { zoom: 0.90; } */ /* Elimina o comenta esta línea */
   .container, .productos-grid, .producto-card, .modal-content { font-size: 0.95em; }

    .hero-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* O usa 'cover' si prefieres llenar el área sin bordes */
  }

  .hero {
    position: relative;
    height: 80vh;
    overflow: hidden;
  }
  .hero-carousel {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
  }
  .hero-slide {
    min-width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    position: absolute;
  }
  .hero-slide.active {
    opacity: 1;
    position: relative;
  }
  .hero-overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
  }
  .hero-wrapper {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .hero-text {
    text-align: center;
    color: white;
  }
  
  /* modificar perfil*/ 
  .user-menu {
    position: relative;
    display: inline-block;
  }

  .menu-toggle {
    cursor: pointer;
    margin-left: 8px; /* Espacio entre el nombre y los tres puntos */
  }

  .user-dropdown {
    position: absolute;
    right: 0;
    margin-top: 10px;
    background-color: #fffdf8; /* Un fondo blanco cálido */
    border: 1px solid #e2d6c0; /* Borde suave en beige dorado */
    box-shadow:
      0 2px 6px rgba(183, 135, 50, 0.2),   /* sombra dorada */
      0 4px 12px rgba(44, 73, 38, 0.15);   /* sombra verde oscura */
    border-radius: 8px;
    z-index: 1000;
  }

  .user-dropdown a {
    display: block;
    padding: 10px 15px;
    text-decoration: none;
    color: #444242; /* Gris profundo */
    font-size: 14px;
    transition: background-color 0.2s ease, color 0.2s ease;
  }

  .user-dropdown a:hover {
    background-color: #efd9ab; /* Crema claro */
    color: #2c4926; /* Verde oscuro */
  }

  .hidden {
    display: none;
  }
</style>
<!-- ##### Welcome Area End ##### -->
<script>
  function toggleUserMenu() {
    const dropdown = document.getElementById('user-dropdown');
    dropdown.classList.toggle('hidden');
  }

  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('user-dropdown');
    const toggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.user-menu');

    if (!menu.contains(event.target)) {
      dropdown.classList.add('hidden');
    }
  });
</script>




     
    <!-- ##### Top Catagory Area Start ##### -->
    <!-- CATEGORÍAS -->
    
      <div class="categoria-texto"> 
            <h2> Categoria de productos</h2>
          </div>
        <section class="categories">
          
          <div class="category-card">
            <a href="camisetas.php">
              <img src="assets/images/img1.png" alt="Camisetas">
              <span>Camisetas</span>
            </a>
          </div>
          <div class="category-card">
            <a href="blusas.php">
              <img src="assets/images/bhome.png" alt="Blusas">
              <span>Blusas</span>
            </a>
          </div>
          <div class="category-card">
            <a href="short.php">
              <img src="assets/images/producto41.jpg" alt="Shorts">
              <span>Shorts</span>
            </a>
          </div>
          <div class="category-card">
            <a href="gorras.php">
              <img src="assets/images/ghome2.png" alt="Gorras">
              <span>Gorras</span>
            </a>
          </div>
        </section>
  
<!-- VENTAS NACIONALES -->
<?php
require_once 'login/conexion.php'; // Asegúrate que esta ruta sea correcta


$detalles_a_mostrar_ids = [
  
    9,   // Blusa Encanto Volante, CAQUI, S
    21,   // Blusa Encanto Volante, CAQUI, S
    118, // Short Corte Ejecutivo, AZUL MARINO, M
    59, 
    165, // Shorts Estilo Noble, VERDE, S
    75,  // Camisa Textura Urbana, CAQUI, L
    218, // Camisa Brisa Vintage, ROSADO, M
    54   // Gorra Estilo Vintage, BEIGE, Única
];
// ---------------------------------------------------------------------------

$productos_populares = [];

if (isset($conn)) {
    foreach ($detalles_a_mostrar_ids as $id_detalle_producto) {
        // Consulta para obtener datos de la variante y la imagen asociada al color (o la general)
        $sql = "SELECT
                    dp.id_detalles_productos,
                    dp.id_producto,
                    dp.id_color, -- ID del color de esta variante específica
                    p.nombre AS nombre_producto,
                    dp.precio_producto AS precio,
                    COALESCE(
                        (SELECT pi_color.imagen -- 1.  imagen específica del color de la variante
                         FROM producto_imagen pi_color
                         WHERE pi_color.id_producto = dp.id_producto AND pi_color.id_color_asociado = dp.id_color
                         ORDER BY pi_color.id_imagen ASC 
                         LIMIT 1),
                        (SELECT pi_general.imagen -- 2. Sino, la primera imagen general del producto (sin color asociado)
                         FROM producto_imagen pi_general
                         WHERE pi_general.id_producto = dp.id_producto AND pi_general.id_color_asociado IS NULL
                         ORDER BY pi_general.id_imagen ASC
                         LIMIT 1),
                        (SELECT pi_cualquiera.imagen -- 3. cualquier imagen del producto
                         FROM producto_imagen pi_cualquiera
                         WHERE pi_cualquiera.id_producto = dp.id_producto
                         ORDER BY pi_cualquiera.id_imagen ASC
                         LIMIT 1)
                    ) AS ruta_imagen
                FROM
                    detalles_productos dp
                JOIN
                    productos p ON dp.id_producto = p.id_producto
                WHERE
                    dp.id_detalles_productos = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_detalle_producto);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $productos_populares[] = $result->fetch_assoc();
            }
            $stmt->close();
        }
    }
    // $conn->close();
}
?>

<section class="productos-populares">
    <div class="container">
        <h3 class="titulo-seccion">Algunos Productos</h3>
        <div class="productos-grid">
            <?php if (!empty($productos_populares)): ?>
                <?php foreach ($productos_populares as $producto): ?>
                    <?php
                        $nombre_para_js = htmlspecialchars(addslashes($producto['nombre_producto']), ENT_QUOTES);
                        $precio_para_js = !empty($producto['precio']) ? floatval($producto['precio']) : 0;
                        $precio_formateado = !empty($producto['precio']) ? '$' . number_format($producto['precio'], 0, ',', '.') : 'Precio no disponible';
                        // La ruta ya viene de la BD como 'images/blusas/lu1.png', etc.
                        $ruta_imagen_completa = !empty($producto['ruta_imagen']) ? htmlspecialchars($producto['ruta_imagen']) : 'images/placeholder.png';
                        $alt_imagen = htmlspecialchars($producto['nombre_producto']);
                        $id_producto_general = $producto['id_producto'];
                    ?>
                    <div class="producto-card">
                        <img src="<?php echo $ruta_imagen_completa; ?>" alt="<?php echo $alt_imagen; ?>">
                        <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                        <p class="precio"><?php echo $precio_formateado; ?>
                            <?php if (!empty($producto['precio'])): ?>
                            <?php endif; ?>
                        </p>
                        <a href="./shop.php" class="btn-primary">Ver más</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay productos populares para mostrar en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Agrega SweetAlert2 si no lo tienes ya -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.search.includes('compra=ok')) {
        localStorage.removeItem('cart');
        cart = []; // <-- Esto es importante
        if (typeof actualizarContadorCarrito === 'function') {
            actualizarContadorCarrito();
        }
        if (typeof updateCartDisplay === 'function') {
            updateCartDisplay();
        }
        setTimeout(function() {
            Swal.fire({
                icon: 'success',
                title: '¡Gracias por tu compra!',
                text: 'Tu pedido ha sido registrado exitosamente.',
                confirmButtonColor: '#B79F5E'
            });
        }, 300);
        if (window.history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.delete('compra');
            window.history.replaceState({}, document.title, url.pathname);
        }
    }
});
</script>

    <!-- ##### New Arrivals Area End ##### -->
    <script src="assets/js/carrito.js"></script>
    <!-- ##### Footer Area Start ##### -->
    <?php include './arc/footer.php';?>
    <!-- ##### Footer Area End ##### -->
<script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="ODY2RbTbejKtpf8eFtp3x";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>

</body>

</html>