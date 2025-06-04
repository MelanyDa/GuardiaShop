<?php
@session_start();
if (isset($_SESSION['id'])) {
  exit();
}
include 'db.php'; // Incluye el archivo de conexión
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Buscar al usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar la contraseña ingresada con la almacenada en la base de datos
        if (password_verify($contraseña, $row['contraseña'])) {
            echo "Inicio de sesión exitoso";
            // Aquí puedes redirigir al usuario a una página de bienvenida
            // Ejemplo: header("Location: dashboard.php");
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    // Cerrar la conexión
    $conn->close();
}
?>


<!doctype html>
<html lang="en">
  <head>
    <title>GUARDIASHOP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Tu hoja de estilos personalizada -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Estilos adicionales -->
    <style>
      body {
        margin: 0;
        padding: 0;
        font-family: 'Lato', sans-serif;
        background-color: #ffffff;
        overflow-x: hidden;
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

      .text-center {
        text-align: center;
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
          width: 90%;
          margin: auto;
        }

        .img {
          width: 100%;
          height: 200px;
        }

        .login-wrap {
          width: 100%;
          padding: 20px;
        }

        .login-wrap h3 {
          text-align: center;
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

        .form-group .form-control {
          font-size: 16px;
        }

        .btn {
          font-size: 16px;
        }

        .form-group.d-md-flex {
          flex-direction: column;
          align-items: center;
        }

        .form-group.d-md-flex .w-50 {
          width: 100%;
          text-align: center;
          margin-top: 10px;
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
                  <h3>INICIO DE SESIÓN</h3>
                     <?php if (isset($_SESSION['error']['empty_user'])): ?>
              <div class="alert alert-danger w-100 text-center mb-0" role="alert">
                <small><?php echo $_SESSION['error']['empty_user']; ?></small>
              </div>
            <?php endif; if (isset($_SESSION['error']['empty_user'])) unset($_SESSION['error']['empty_user']); ?>
          </div>

                <form action="procesar_login.php" method="post" class="signin-form">
                  <div class="form-group">
                    
                    <label class="label">CORREO</label>
                    <input type="text" name="correo" class="form-control" placeholder="CORREO" required>
                  </div>

                  <div class="form-group">
                    <label class="label">CONTRASEÑA</label>
                    <input type="password" name="contraseña" class="form-control" placeholder="Contraseña" required>
                  </div>

                   <?php if (isset($_SESSION['error']['wrong_password'])): ?>
              <div class="mb-3">
                <p class="fw-bolder text-danger"><?php echo $_SESSION['error']['wrong_password']; ?></p>
              </div>
            <?php endif; if (isset($_SESSION['error']['empty_user'])) unset($_SESSION['error']['empty_user']); ?>
 

                  <div class="form-group">
                    <button type="submit" class="btn">Ingresar</button>
                  </div>

                  <div class="form-group d-md-flex">
                    <div class="w-50 text-left">
                      <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
                    </div>
                  </div>

                   <?php if ($redirect): ?>
    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
  <?php endif; ?>
                </form>
                <div class="form-group text-center" style="margin-top: 20px;">
                  <a href="google_login.php" class="btn d-flex align-items-center justify-content-center" style="background-color: white; color: #444242; border: 2px solid #ccc; text-decoration: none;">
                    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" width="24" height="24" style="margin-right: 10px;">
                    <span>Iniciar sesión con Google</span>
                  </a>
                </div>
<p class="text-center">
  ¿No eres miembro?
  <a href="../login/registro.php<?php echo $redirect ? '?redirect=' . urlencode($redirect) : ''; ?>" style="color: #B79F5E; font-weight:bold;">Regístrate</a>
</p>                <p class="text-center"><a href="../index.php" style="color: #B79F5E; font-weight:bold;">Regresa al inicio</a></p>

          
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Scripts opcionales -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>