<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title  -->
    <title>GUARDIASHOP</title>

    <!-- Favicon  -->
    <link rel="icon" href="./img/core-img/logoguardiashop.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="css/core-styleff.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/blog.css">
    <link rel="stylesheet" href="assets/css/carrito.css">

</head>

<body class="px-0 pb-0">
  <!-- Header -->
  <?php include './arc/nav.php'; ?>
 
  <!-- Breadcumb Area -->
  <div class="breadcumb_area breadcumb-style-two bg-img" style="background-image: url(img/bg-img/breadcumb2.jpg);">
    <div class="container-fluid h-100 m-0 p-0">
      <div class="row h-100 w-100 align-items-center">
        <div class="col-12">
          <div class="page-title text-center">
            <h2 style="color:#444242; font-size: 48px">Fashion Blog</h2>
            <p class="subtitle">Tendencias, Estilo y Creatividad</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Blog Wrapper Area -->
  <section class="blog-section">
    <div class="container">
      <!-- Blog Section Header -->
      <div class="section-header">
        <h2>ÚLTIMAS TENDENCIAS</h2>
        <div class="divider"></div>
        <p class="section-description">Descubre las últimas novedades del mundo de la moda y el estilo</p>
      </div>

      <!-- Blog Grid -->
      <div class="blog-grid">
        <!-- Article 1 -->
        <article class="blog-card">
          <div class="card-image">
            <img src="assets/productos/gorras/rra4.jpg" alt="Bolso de moda sobre superficie">
            <div class="category-tag">Accesorios</div>
          </div>
          <div class="card-content">
            <h3>El poder expresivo de los accesorios</h3>
            <p>En un mundo donde todo se vuelve digital, moda y creatividad es una forma de expresar quién eres sin decir una palabra.</p>
          </div>
        </article>
        
        <!-- Article 2 -->
        <article class="blog-card">
          <div class="card-image">
            <img src="assets/productos/blusas/lu6.jpg" alt="Patrones coloridos de moda">
            <div class="category-tag">Colecciones</div>
          </div>
          <div class="card-content">
            <h3>Diseño con intención y propósito</h3>
            <p>No se trata solo de vestir bien, sino de vestir con intención: cada prenda que eliges cuenta la historia de estilo única a irrepetible.</p>
          </div>
        </article>
        
        <!-- Article 3 -->
        <article class="blog-card">
          <div class="card-image">
            <img src="assets/productos/camisetas/sa3.jpg" alt="Persona con outfit elegante">
            <div class="category-tag">Estilo Personal</div>
          </div>
          <div class="card-content">
            <h3>La verdadera elegancia</h3>
            <p>La verdadera elegancia para grandes ocasiones: diseño, actitud y ese toque exclusivo que nadie más puede imitar.</p>
          </div>
        </article>
        
        <!-- Article 4 -->
        <article class="blog-card">
          <div class="card-image">
            <img src="assets/productos/shorts/sh1.jpg" alt="Detalle de textiles de moda">
            <div class="category-tag">Exclusividad</div>
          </div>
          <div class="card-content">
            <h3>Prendas que cuentan historias</h3>
            <p>Cuando eliges una prenda exclusiva, no solo estás comprando ropa, estás apostando por tu identidad, por lo que te hace diferente.</p>
          </div>
        </article>
        
        <!-- Article 5 -->
        <article class="blog-card">
          <div class="card-image">
            <img src="assets/productos/gorras/rra6.jpg" alt="Pasarela de moda">
            <div class="category-tag">Tendencias</div>
          </div>
          <div class="card-content">
            <h3>Más allá de las tendencias</h3>
            <p>Nuestro compromiso no es seguir tendencias pasajeras, sino ofrecerte piezas que te hagan sentir especial cada vez que las uses.</p>
          </div>
        </article>
        
        <!-- Article 6 -->
        <article class="blog-card">
          <div class="card-image">
            <img src="assets/productos/blusas/lu5.jpg" alt="Falda con estampado floral">
            <div class="category-tag">Originalidad</div>
          </div>
          <div class="card-content">
            <h3>Tu estilo, tu voz</h3>
            <p>No sigas la corriente. Tu estilo merece destacar con prendas que hablen por ti, sin que digas una sola palabra.</p>
          </div>
        </article>
      </div>

    </div>
  </section>

  <!-- Featured Section -->
  <section class="featured-section">
    <div class="container">
      <div class="section-header">
        <h2>ARTÍCULOS DESTACADOS</h2>
        <div class="divider"></div>
      </div>

      <div class="featured-posts">
        <article class="featured-card">
          <div class="featured-image">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-M20ofnLgcDiSGfNLKzV3PVImmPC1nS.png?rect=689,486,205,163" alt="Pasarela de moda">
            <div class="overlay"></div>
          </div>
          <div class="featured-content">
            <div class="category">Fashion </div>
            <h3>Los mejores momentos de la semana de la moda</h3>
            <p>Descubre las colecciones que definirán las tendencias de la temporada.</p>
            <a href="#" class="btn">Ver artículo</a>
          </div>
        </article>
        
        <article class="featured-card">
          <div class="featured-image">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-M20ofnLgcDiSGfNLKzV3PVImmPC1nS.png?rect=929,294,205,163" alt="Persona con outfit elegante">
            <div class="overlay"></div>
          </div>
          <div class="featured-content">
            <div class="category">Estilo</div>
            <h3>Cómo crear un guardarropa cápsula</h3>
            <p>Menos es más: la guía definitiva para construir un fondo de armario versátil y atemporal.</p>
            <a href="#" class="btn">Ver artículo</a>
          </div>
        </article>
      </div>
    </div>
  </section>

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
    <script src="assets/js/carrito.js"></script>
    <!-- ##### Footer Area Start ##### -->
    <?php include './arc/footer.php';?>
    <!-- ##### Footer Area End ##### -->

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js"></script>
    <!-- Classy Nav js -->
    <script src="js/classy-nav.min.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>

</body>

</html>