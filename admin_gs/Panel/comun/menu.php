<!-- Sidebar -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color: #2c4926; color: #ffffff;" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-user-shield"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Admin GuardiaShop</div>
</a>

<hr class="sidebar-divider my-0">

<li class="nav-item <?php if($current_page == 'index.php') echo 'active'; ?>">
    <a class="nav-link" href="index.php">
        <i class="fas fa-fw fa-chart-line"></i>
        <span>Panel</span>
    </a>
</li>

<hr class="sidebar-divider">

<li class="nav-item <?php if($current_page == 'g_usuarios.php') echo 'active'; ?>">
    <a class="nav-link" href="g_usuarios.php">
        <i class="fas fa-fw fa-user"></i>
        <span>Gestionar Usuarios</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'atributos.php') echo 'active'; ?>">
    <a class="nav-link" href="atributos.php">
        <i class="fas fa-fw fa-tags"></i>
        <span>Atributos</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'g_productos.php') echo 'active'; ?>">
    <a class="nav-link" href="g_productos.php">
        <i class="fas fa-fw fa-shopping-cart"></i>
        <span>Gestionar Productos</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'g_pedidos.php') echo 'active'; ?>">
    <a class="nav-link" href="g_pedidos.php">
        <i class="fas fa-fw fa-box"></i>
        <span>Gestionar Pedidos</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'g_compras.php') echo 'active'; ?>">
    <a class="nav-link" href="g_compras.php">
        <i class="fas fa-fw fa-box"></i>
        <span>Gestionar Compras</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'transacciones.php') echo 'active'; ?>">
    <a class="nav-link" href="transacciones.php">
        <i class="fas fa-fw fa-money-bill"></i>
        <span>Transacciones</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'g_contactos.php') echo 'active'; ?>">
    <a class="nav-link" href="g_contactos.php">
        <i class="fas fa-fw fa-address-book"></i>
        <span>Gestionar Contactos</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'vender.php') echo 'active'; ?>">
    <a class="nav-link" href="vender.php">
        <i class="fas fa-fw fa-dollar-sign"></i>
        <span>Vender</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'comprar.php') echo 'active'; ?>">
    <a class="nav-link" href="comprar.php">
        <i class="fas fa-fw fa-money-bill"></i>
        <span>comprar</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'facturas_o.php') echo 'active'; ?>">
    <a class="nav-link" href="facturas_o.php">
        <i class="fas fa-fw fa-file-invoice"></i>
        <span>Facturas</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'facturas_f.php') echo 'active'; ?>">
    <a class="nav-link" href="facturas_f.php">
        <i class="fas fa-fw fa-file-invoice"></i>
        <span>Facturas Ventas Fisico</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'reportes_ventas.php') echo 'active'; ?>">
    <a class="nav-link" href="reportes_ventas.php">
        <i class="fas fa-fw fa-chart-line"></i>
        <span>Reportes de venta</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'kardex.php') echo 'active'; ?>">
    <a class="nav-link" href="kardex.php">
        <i class="fas fa-fw fa-clipboard-list"></i>
        <span>kardex</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'g_proveedores.php') echo 'active'; ?>">
    <a class="nav-link" href="g_proveedores.php">
        <i class="fas fa-fw fa-truck"></i>
        <span>Proveedores</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'g_admins.php') echo 'active'; ?>">
    <a class="nav-link" href="g_admins.php">
        <i class="fas fa-fw fa-user-cog"></i>
        <span>Gestionar Admins</span>
    </a>
</li>
<li class="nav-item <?php if($current_page == 'copias_seguridad.php') echo 'active'; ?>">
    <a class="nav-link" href="copias_seguridad.php">
        <i class="fas fa-fw fa-file-archive"></i>
        <span>Copias de seguridad</span>
    </a>
</li>

<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
<button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>
</ul>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('accordionSidebar');
    const body = document.body;

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            body.classList.toggle('sidebar-collapsed'); // Para mover el contenido también
        });
    }
});
</script>

<!-- End of Sidebar -->
<style>/* Sidebar */
.sidebar {
    width: 250px; /* Ancho normal del sidebar */
    min-height: 100vh;
    background-color: #2c4926;
    transition: width 0.3s ease; /* Transición para cuando el sidebar se colapsa */
}

/* Estilo para el Sidebar cuando se colapsa */
.sidebar.collapsed {
    width: 80px; /* Ancho del sidebar cuando está colapsado */
}

/* Asegurando que los enlaces y los íconos se ajusten al tamaño cuando está colapsado */
.sidebar.collapsed .nav-link span {
    display: none;
}

.sidebar.collapsed .nav-link i {
    margin: 0 auto;
}

/* Botón de Toggle */
#sidebarToggle {
    cursor: pointer;
    background-color: transparent;
    border: none;
    color: white;
    font-size: 1.5rem;
}


</style>
