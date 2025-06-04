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
    <link rel="icon" href="img/core-img/logoguardiashop.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="css/core-styleff.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/nosotros.css">
    <link rel="stylesheet" href="assets/css/carrito.css">
        <?php include './arc/nav.php'; ?>

</head>

<body>

   
        <!-- Breadcumb Area -->
    <div class="breadcumb_area breadcumb-style-two bg-img" style="background-image: url(img/bg-img/breadcumb2.jpg);">
        <div class="container-fluid h-100 m-0 p-0">
        <div class="row h-100 w-100 align-items-center">
            <div class="col-12">
            <div class="page-title text-center">
                <h2 style="color:#444242; font-size: 48px">NOSOTROS</h2>
                <p class="subtitle">Conoce nuestra historia, visión y el equipo detrás de cada creación</p>
            </div>
            </div>
        </div>
        </div>
    </div>

    <section class="about-section">
                <div class="container">
                <div class="about-story">
                    <div class="story-content" data-aos="fade-right" data-aos-delay="100" data-aos-duration="1000">
                    <h2>Nuestra Historia</h2>
                    <p>Fundada en 2025, Guardiashop nació de la pasión por la moda urbana y el deseo de ofrecer prendas que no solo destaquen por su estilo, sino que conecten con quienes las usan. </p>
                    <p>Desde sus inicios como un proyecto digital impulsado por la creatividad y la innovación, Guardiashop ha crecido hasta convertirse en una tienda reconocida por combinar calidad, diseño moderno y tecnología inteligente para transformar la experiencia de compra.</p>
                    <a href="#" class="btn-outline">Descubrir más</a>
                    </div>
                    <div class="story-image" data-aos="fade-left" data-aos-delay="300" data-aos-duration="1000">
                    <div class="image-frame">
                    <img src="img/bg-img/blog1.jpg" alt="Historia de Guardiashop" width="205" height="163">
                    </div>
                    </div>
                </div>
                
                <!-- Valores -->
                <div class="about-values">
                    <h2 data-aos="fade-up" data-aos-duration="800">Nuestros Valores</h2>
                    <div class="values-grid">
                    <div class="value-card" data-aos="zoom-in" data-aos-delay="100" data-aos-duration="800">
                        <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </div>
                        <h3>Pasión</h3>
                        <p>Cada prenda está diseñada con amor y dedicación, reflejando nuestra pasión por la moda y el arte.</p>
                    </div>
                    
                    <div class="value-card" data-aos="zoom-in" data-aos-delay="200" data-aos-duration="800">
                        <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <h3>Calidad</h3>
                        <p>Utilizamos los mejores materiales y técnicas artesanales para garantizar productos duraderos y de alta calidad.</p>
                    </div>
                    
                    <div class="value-card" data-aos="zoom-in" data-aos-delay="300" data-aos-duration="800">
                        <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <h3>Originalidad</h3>
                        <p>Creamos diseños únicos que destacan por su originalidad y capacidad para expresar la individualidad.</p>
                    </div>
                    
                    <div class="value-card" data-aos="zoom-in" data-aos-delay="400" data-aos-duration="800">
                        <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <h3>Sostenibilidad</h3>
                        <p>Nos comprometemos con prácticas sostenibles y éticas en toda nuestra cadena de producción.</p>
                    </div>
                    </div>
                </div>
                
                <!-- Equipo -->
                <div class="about-team">
                    <h2 data-aos="fade-up" data-aos-duration="800">Nuestro Equipo</h2>
                    <p class="team-intro" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">Detrás de cada colección hay un equipo apasionado y talentoso que trabaja incansablemente para crear piezas excepcionales.</p>
                    
                    <div class="team-grid">
                    <div class="team-member" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
                        <div class="member-photo">
                        <img src="img/bg-img/blog2.jpg" alt="Directora Creativa">
                        <div class="member-social">
                            <a href="#" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            </a>
                            <a href="#" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                            </a>
                        </div>
                        </div>
                        <h3>MELANY</h3>
                        <p class="member-role">Directora Creativa</p>
                        <p class="member-bio">Con más de 15 años de experiencia en la industria de la moda, Elena aporta su visión única y su pasión por el diseño a cada colección.</p>
                    </div>
                    
                    <div class="team-member" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
                        <div class="member-photo">
                        <img src="img/bg-img/blog3.jpg" alt="Director de Sostenibilidad">
                        <div class="member-social">
                            <a href="#" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            </a>
                            <a href="#" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                            </a>
                        </div>
                        </div>
                        <h3>ANGIE</h3>
                        <p class="member-role">Director de Sostenibilidad</p>
                        <p class="member-bio">Carlos lidera nuestras iniciativas de sostenibilidad, asegurando que cada paso de nuestro proceso respete el medio ambiente.</p>
                    </div>
                    
                    <div class="team-member" data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
                        <div class="member-photo">
                        <img src="img/bg-img/blog4.jpg" alt="Directora de Producción">
                        <div class="member-social">
                            <a href="#" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            </a>
                            <a href="#" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                            </a>
                        </div>
                        </div>
                        <h3>Lerhy</h3>
                        <p class="member-role">Directora de Producción</p>
                        <p class="member-bio">Con su atención al detalle y su experiencia en producción textil, Laura garantiza la calidad de cada prenda que creamos.</p>
                    </div>
                    </div>
                </div>
                

                <!-- Equipo reducido -->
                <div class="about-team">
                <div class="team-grid">

                    <!-- Nuevo miembro 1 -->
                    <div class="team-member" data-aos="fade-up" data-aos-delay="400" data-aos-duration="800">
                    <div class="member-photo">
                        <img src="img/bg-img/blog4.jpg" alt="Diseñadora de Moda">
                        <div class="member-social">
                        <a href="#" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                        </a>
                        </div>
                    </div>
                    <h3>Luisa</h3>
                    <p class="member-role">Diseñadora de Moda</p>
                    <p class="member-bio">María aporta frescura e innovación a cada diseño, con una estética contemporánea y funcional.</p>
                    </div>

                    <!-- Nuevo miembro 2 -->
                    <div class="team-member" data-aos="fade-up" data-aos-delay="500" data-aos-duration="800">
                    <div class="member-photo">
                        <img src="img/bg-img/rp1.jpg" alt="Coordinador de Logística">
                        <div class="member-social">
                        <a href="#" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                        </a>
                        </div>
                    </div>
                    <h3>Yurleni</h3>
                    <p class="member-role">Coordinador de Logística</p>
                    <p class="member-bio">Juan asegura que cada prenda llegue a tiempo y en perfectas condiciones a nuestros clientes.</p>
                    </div>
                </div>
                </div>

                
                <!-- Filosofía -->
                <div class="about-philosophy">
                    <div class="philosophy-content" data-aos="fade-right" data-aos-delay="100" data-aos-duration="1000">
                    <h2>Nuestra Filosofía</h2>
                    <p>Creemos que la moda es una forma de expresión personal que va más allá de las tendencias pasajeras. Cada prenda que diseñamos está pensada para empoderar a quien la lleva, para hacerle sentir único y especial.</p>
                    <p>Nuestra filosofía se basa en tres pilares fundamentales: diseño innovador, calidad excepcional y responsabilidad social. Estos valores guían cada decisión que tomamos, desde la selección de materiales hasta el proceso de producción.</p>
                    <blockquote>
                    "Vestirse no es seguir reglas, es contar tu historia sin decir una palabra."
                    </blockquote>
                    </div>
                    <div class="philosophy-image" data-aos="fade-left" data-aos-delay="300" data-aos-duration="1000">
                    <div class="image-collage">
                        <img src="img/bg-img/rp3.jpg" alt="Filosofía de la marca" class="collage-main">
                        <img src="img/bg-img/rp4.jpg" alt="Detalle de diseño" class="collage-accent">
                    </div>
                    </div>
                </div>
                
                <!-- CTA -->
                <div class="about-cta" data-aos="zoom-in" data-aos-duration="1000">
                    <h2>Únete a Nuestra Historia</h2>
                    <p>Descubre nuestras colecciones y forma parte de una comunidad que valora la autenticidad, la calidad y la expresión personal.</p>
                    <div class="cta-buttons">
                    </div>
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
    <!-- Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwuyLRa1uKNtbgx6xAJVmWy-zADgegA2s"></script>
    <script src="js/map-active.js"></script>

</body>

</html>