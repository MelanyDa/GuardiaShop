<header class="header">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container navbar">
        <!-- Logo -->
        <a href="index.php" class="logo">
    <img src="/guardiashop/assets/images/logo2.png" alt="GuardiaShop Logo">
        </a>

        <!-- Enlaces de Navegación (para escritorio) -->
        <nav class="nav-links-desktop">
            <ul>
    <li><a href="/guardiashop/index.php">Inicio</a></li>
    <li><a href="/guardiashop/shop.php">Tienda</a></li>
    <li><a href="/guardiashop/nosotros.php">Sobre Nosotros</a></li>
    <li><a href="/guardiashop/contact.php">Contacto</a></li>
    <li><a href="/guardiashop/blog.php">Blog</a></li>
</ul>
        </nav>

        <!-- Iconos y Menú de Usuario (para escritorio) -->
        <div class="navbar-right-desktop">
            <?php if (isset($_SESSION['usuario_nombre'])): ?>
                <div class="user-menu-desktop">
                    <span class="user-name-desktop"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                    <button class="user-menu-toggle-desktop" onclick="toggleUserMenuDesktop()">
<i class="fa fa-user user-icon-svg"></i>
                    </button>
                    <div id="user-dropdown-desktop" class="user-dropdown-desktop">
                        <a href="modificar_perfil.php">Modificar perfil</a>
                        <a href="/guardiashop/login/logout.php" class="logout-link">Cerrar sesión</a>
                    </div>
                </div>
            <?php else: ?>
<a href="login/login.php?redirect=<?php echo urlencode(basename($_SERVER['REQUEST_URI'])); ?>" class="btn btn-warning btn-login-desktop">Iniciar sesión</a>            <?php endif; ?>
            <div id="cart-icon-desktop" class="cart-icon-desktop" onclick="toggleCart()">
<i class="fa fa-shopping-cart cart-icon-svg"></i>                <!-- O puedes usar un ícono de fuente: <i class="fas fa-shopping-cart"></i> -->
                <span id="cart-count-desktop" class="cart-count-desktop">0</span>
            </div>
        </div>

        <!-- Botón Hamburguesa (para móvil) -->
        <button class="hamburger-btn" onclick="toggleMobileMenu()">
            <span></span> <!-- Líneas del ícono hamburguesa -->
            <span></span>
            <span></span>
        </button>
    </div>

    <!-- Menú Desplegable Móvil (Hamburguesa) -->
    <nav class="mobile-nav hidden" id="mobileNav">
        <?php if (isset($_SESSION['usuario_nombre'])): ?>
            <div class="mobile-user-info">
                <span class="mobile-user-name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                <button class="mobile-user-menu-toggle" onclick="toggleMobileUserSubmenu()">
<i class="fa fa-user user-icon-svg"></i>
                </button>
            </div>
            <div id="mobile-user-submenu" class="mobile-user-submenu hidden">
                <a href="modificar_perfil.php">Modificar perfil</a>
                <a href="/guardiashop/login/logout.php" class="logout-link">Cerrar sesión</a>
            </div>
        <?php else: ?>
<a href="login/login.php?redirect=<?php echo urlencode(basename($_SERVER['REQUEST_URI'])); ?>" class="btn btn-warning btn-login-desktop">Iniciar sesión</a>        <?php endif; ?>

        <a href="/guardiashop/index.php" class="mobile-nav-link">Inicio</a>
<a href="/guardiashop/shop.php" class="mobile-nav-link">Tienda</a>
<a href="/guardiashop/nosotros.php" class="mobile-nav-link">Sobre Nosotros</a>
<a href="/guardiashop/contact.php" class="mobile-nav-link">Contacto</a>
<a href="/guardiashop/blog.php" class="mobile-nav-link">Blog</a>        
        <a href="/guardiashop/carrito.php" class="mobile-cart-link">
  <i class="fa fa-shopping-cart cart-icon-svg"></i> Carrito (<span id="cart-count-mobile">0</span>)
</a>
    </nav>
</header>

<div id="cart" class="hidden">
  <h2>Mi Carrito</h2>
  <ul id="cart-items"></ul>
      <a href="carrito.php" class="btn-clear btn-as-button">Ver carrito</a>
</div>

<script>

  function toggleUserMenuDesktop() {
    const dropdown = document.getElementById('user-dropdown-desktop');
    if (dropdown) {
        dropdown.classList.toggle('active');
    }
}
  function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    mobileNav.classList.toggle('active');
    mobileNav.classList.remove('hidden');
    hamburgerBtn.classList.toggle('active');
}


function toggleUserMenuDesktop() {
    const dropdown = document.getElementById('user-dropdown-desktop');
    if (dropdown) {
        dropdown.classList.toggle('active');
    }
}

function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    mobileNav.classList.toggle('active');
    mobileNav.classList.remove('hidden');
    hamburgerBtn.classList.toggle('active');
}

// Cerrar menú de usuario y menú móvil al hacer clic fuera
document.addEventListener('click', function(event) {
    // Dropdown usuario escritorio
    const dropdownDesktop = document.getElementById('user-dropdown-desktop');
    const userMenuDesktop = document.querySelector('.user-menu-desktop');
    if (dropdownDesktop && userMenuDesktop) {
        if (!userMenuDesktop.contains(event.target) && dropdownDesktop.classList.contains('active')) {
            dropdownDesktop.classList.remove('active');
        }
    }
    // Menú móvil
    const mobileNav = document.getElementById('mobileNav');
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    if (mobileNav && mobileNav.classList.contains('active')) {
        if (!mobileNav.contains(event.target) && !hamburgerBtn.contains(event.target)) {
            mobileNav.classList.remove('active');
            hamburgerBtn.classList.remove('active');
        }
    }
});

// Cerrar menú móvil y dropdown usuario al cambiar a pantalla grande
window.addEventListener('resize', function() {
    const mobileNav = document.getElementById('mobileNav');
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    const dropdownDesktop = document.getElementById('user-dropdown-desktop');
    if (window.innerWidth > 991) { // Ajusta el breakpoint según tu CSS
        if (mobileNav) mobileNav.classList.remove('active');
        if (hamburgerBtn) hamburgerBtn.classList.remove('active');
        if (dropdownDesktop) dropdownDesktop.classList.remove('active');
    }
});

function toggleMobileUserSubmenu() {
    const submenu = document.getElementById('mobile-user-submenu');
    if (submenu) {
        submenu.classList.toggle('hidden'); // Aquí 'hidden' está bien
    }
}

// Cerrar dropdown de usuario en escritorio si se hace clic fuera
document.addEventListener('click', function(event) {
    const dropdownDesktop = document.getElementById('user-dropdown-desktop');
    const userMenuDesktop = document.querySelector('.user-menu-desktop'); // Contenedor del nombre y toggle

    if (dropdownDesktop && userMenuDesktop) { // Solo si existen
        // Si el clic NO fue dentro del userMenuDesktop Y el dropdown está activo
        if (!userMenuDesktop.contains(event.target) && dropdownDesktop.classList.contains('active')) {
            dropdownDesktop.classList.remove('active');
        }
    }

        const mobileNav = document.getElementById('mobileNav');
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    if (mobileNav && mobileNav.classList.contains('active')) {
        // Si el clic no fue en el botón hamburguesa NI dentro del menú móvil
        if (!hamburgerBtn.contains(event.target) && !mobileNav.contains(event.target)) {
                   }
    }
});

function actualizarContadorCarrito() {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  let total = cart.reduce((sum, item) => sum + item.quantity, 0);
  const cartCountDesktop = document.getElementById('cart-count-desktop');
  const cartCountMobile = document.getElementById('cart-count-mobile');
  if (cartCountDesktop) cartCountDesktop.textContent = total;
  if (cartCountMobile) cartCountMobile.textContent = total;
}
    document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);

</script>

<style>
@media (max-width: 991px) {
  #cart {
    display: none !important;
  }
  .mobile-nav {
    transition: max-height 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.3s;
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    pointer-events: none;
  }
  .mobile-nav.active {
    max-height: 600px; /* Ajusta según el contenido */
    opacity: 1;
    pointer-events: auto;
  }
}
</style>