<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" id="mainNavbar">

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>

<!-- Topbar Search -->


<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto align-items-center">


    <!-- Menú de accesos rápidos según rol -->
    <?php if (isset($_SESSION['admin_rol']) && $_SESSION['admin_rol'] === 'admin') : ?>
        <li class="nav-item mx-2">
            <a class="nav-link" href="admin_dashboard.php" title="Panel de administración">
                <i class="fas fa-tachometer-alt"></i>
            </a>
        </li>
        <li class="nav-item mx-2">
            <a class="nav-link" href="usuarios.php" title="Gestión de usuarios">
                <i class="fas fa-users-cog"></i>
            </a>
        </li>
    <?php endif; ?>

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-search fa-fw"></i>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
            aria-labelledby="searchDropdown">
            <form class="form-inline mr-auto w-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small"
                        placeholder="Search for..." aria-label="Search"
                        aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </li>

    <?php if (isset($_SESSION['nombre_usuario'])) : ?>
    <a class="nav-link" href="ruta_de_inicio_de_sesion">login</a>   <?php endif; ?>
    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle position-relative" href="#" id="alertsDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw" style="color:rgb(0, 0, 0);"></i>
            <!-- Counter - Alerts -->
            <span class="badge badge-danger badge-counter animate__animated animate__bounceIn" data-toggle="tooltip" data-placement="bottom" title="Notificaciones">
                <?php echo isset($_SESSION['notificaciones']) ? $_SESSION['notificaciones'] : '0'; ?>
            </span>
        </a>
        <!-- Aquí puedes agregar el dropdown de notificaciones si lo deseas -->
    </li>

    <!-- Nav Item - Messages -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle position-relative" href="#" id="messagesDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-envelope fa-fw" style="color:rgb(0, 0, 0);"></i>
            <!-- Counter - Messages -->
            <span class="badge badge-danger badge-counter animate__animated animate__bounceIn" data-toggle="tooltip" data-placement="bottom" title="Mensajes">
                <?php echo isset($_SESSION['mensajes']) ? $_SESSION['mensajes'] : '0'; ?>
            </span>
        </a>
        <!-- Aquí puedes agregar el dropdown de mensajes si lo deseas -->
    </li>

    <!-- Nav Item - User Information --> 
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small text-nowrap">
                <span style="color: black;">
                    <?php echo isset($_SESSION['admin_usuario']) ? $_SESSION['admin_usuario'] : 'Usuario no definido'; ?>
                </span>
            </span>
            <img src="<?php echo isset($_SESSION['user_icon']) ? $_SESSION['user_icon'] : 'img/user.png'; ?>" 
                 alt="User Icon" class="rounded-circle border border-primary" style="width: 42px; height: 42px; object-fit: cover; transition: box-shadow 0.3s;" onmouseover="this.style.boxShadow='0 0 10px #007bff';" onmouseout="this.style.boxShadow='none';">
            <span class="ml-2 badge badge-success" title="En línea" style="height:10px;width:10px;border-radius:50%;display:inline-block;"></span>
        </a>
        <?php if (isset($_SESSION['admin_usuario'])) : ?>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <div class="dropdown-header text-center">
                    <strong><?php echo $_SESSION['admin_usuario']; ?></strong><br>
                    <small><?php echo $_SESSION['admin_email'] ?? ''; ?></small>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="perfil.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Mi perfil
                </a>
                <a class="dropdown-item" href="salira.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Salir
                </a>
            </div>
        <?php endif; ?>
    </li>

</ul>

</nav>
<!-- End of Topbar -->

<!-- Scripts para tooltips y modo oscuro -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
<script>
    // Inicializar tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    // Modo oscuro
    function toggleDarkMode() {
        const navbar = document.getElementById('mainNavbar');
        document.body.classList.toggle('bg-dark');
        navbar.classList.toggle('navbar-dark');
        navbar.classList.toggle('bg-dark');
        navbar.classList.toggle('navbar-light');
        navbar.classList.toggle('bg-white');
    }
</script>