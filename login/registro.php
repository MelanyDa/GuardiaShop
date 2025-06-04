
<!doctype html>
<html lang="en">
<head>
  <title>GUARDIASHOP - Registro</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="../assets/css/style.css">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Lato', sans-serif;
      background-color: #ffffff;
      overflow-x: hidden; /* permite scroll vertical */
    }

    .ftco-section {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .wrap {
      display: flex;
      flex-direction: row;
      background-color: #EFD9AB;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
      flex-wrap: wrap;
      width: 100%;
      max-width: 900px;
    }

    .img {
      background-image: url('images/12.png');
      background-size: cover;
      background-position: center;
      width: 50%;
    }

    .login-wrap {
      padding: 40px;
      width: 50%;
    }

    .login-wrap h3 {
      font-size: 21px;
      color: #444242;
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-control {
      background-color: #EFD9AB;
      color: #444242;
      border: 2px solid #B79F5E;
      padding: 10px;
      width: 100%;
      border-radius: 4px;
    }

    .btn {
      background-color: #B79F5E;
      color: #444242;
      font-weight: bold;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #a0894c;
    }

    a {
      color: #444242;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .social-media a {
      margin-left: 10px;
      color: #444242;
    }

    .back-link {
      position: absolute;
      top: 20px;
      left: 20px;
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #444242;
      z-index: 10;
    }

    .back-link img {
      width: 55px;
      height: 55px;
      margin-right: 10px;
    }

    .back-link span {
      font-size: 18px;
    }
    @media (max-width: 768px) {
      .wrap {
        flex-direction: column;
        width: 95%;
        margin: auto;
      }

      .img {
        width: 100%;
        height: 100px;
        background-size: cover;
        background-position: center;
      }

      .login-wrap {
        width: 100%;
        padding: 20px 15px;
      }

      .login-wrap h3 {
        text-align: center;
        font-size: 24px;
      }

      .form-control,
      .btn {
        font-size: 16px;
      }

      .btn {
        margin-top: 10px;
      }

      .form-group.text-center {
        margin-top: 15px;
      }

      .back-link {
        top: 10px;
        left: 10px;
        font-size: 14px;
      }

      .back-link img {
        width: 40px;
        height: 40px;
      }
    }
  </style>
</head>

<body>
  <section class="ftco-section">

    <!-- ENLACE DE REGRESAR 
    <a href="../index.php" class="back-link">
      <img src="../img/core-img/atras.png" alt="Regresar">
      <span>Regresar</span>
    </a>-->

     <div class="container" style="margin-top: -85px;">
      <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10">
          <div class="wrap d-md-flex">
            <div class="img"></div>
            <div class="login-wrap p-4 p-md-5">
              <div class="d-flex justify-content-between align-items-center">
                <h3>REGISTRO</h3>
              </div>

              <form action="procesar_registro.php" method="post" class="signin-form">
                <div class="form-group">
                  <label class="label">Primer Nombre</label>
                  <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
                </div><div class="form-group">
                  <label class="label">segundo Nombre</label>
                  <input type="text" name="nombre2" class="form-control" placeholder="Nombre completo" >
                </div><div class="form-group">
                  <label class="label">Primer apellido</label>
                  <input type="text" name="apellido" class="form-control" placeholder="Nombre completo" required>
                </div><div class="form-group">
                  <label class="label">segundo apellido</label>
                  <input type="text" name="apellido2" class="form-control" placeholder="Nombre completo" >
                </div>

                <div class="form-group">
                  <label class="label">CORREO ELECTRÓNICO</label>
                  <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
                </div>

               <div class="form-group">
  <label class="label">CONTRASEÑA</label>
  <input type="password" name="contraseña" id="contraseña" class="form-control" placeholder="Contraseña" required>
</div>

<div class="form-group">
  <label class="label">CONFIRMAR CONTRASEÑA</label>
  <input type="password" name="confirmar" id="confirmar" class="form-control" placeholder="Confirmar contraseña" required>
  <small id="mensaje-contrasena" style="color: red; display: block; margin-top: 5px;"></small>
</div>

                <div class="form-group">
                  <button type="submit" class="btn">Registrarse</button>
                </div>
<?php
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
if ($redirect):
?>
  <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
<?php endif; ?>

              </form> 

              <div class="form-group text-center" style="margin-top: 20px;">
                  <a href="google_login.php" class="btn d-flex align-items-center justify-content-center" style="background-color: white; color: #444242; border: 2px solid #ccc; text-decoration: none;">
                    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" width="24" height="24" style="margin-right: 10px;">
                    <span>Iniciar sesión con Google</span>
                  </a>
              </div>
              
              <p class="text-center">¿Ya tienes cuenta? <a href="../login/login.php" style="color: #B79F5E; font-weight:bold;">Inicia sesión</a></p>
                            <p class="text-center"><a href="../index.php" style="color: #B79F5E; font-weight:bold;">Regresa al inicio</a></p>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
  // filepath: c:\xampp\htdocs\guardiashop4\guardiashop\login\registro.php
  const contraseña = document.getElementById('contraseña');
  const confirmar = document.getElementById('confirmar');
  const mensaje = document.getElementById('mensaje-contrasena');

  function validarCoincidencia() {
    if (confirmar.value.length === 0) {
      mensaje.textContent = '';
      return;
    }
    if (contraseña.value === confirmar.value) {
      mensaje.style.color = 'green';
      mensaje.textContent = 'Las contraseñas coinciden';
    } else {
      mensaje.style.color = 'red';
      mensaje.textContent = 'Las contraseñas no coinciden';
    }
  }

  contraseña.addEventListener('input', validarCoincidencia);
  confirmar.addEventListener('input', validarCoincidencia);
</script>
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>