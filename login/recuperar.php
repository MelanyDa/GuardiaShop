<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar contraseña</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', Arial, sans-serif;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9e4d5 100%);
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .recuperar-container {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(183, 135, 50, 0.15), 0 1.5px 8px rgba(0,0,0,0.07);
      padding: 38px 32px 28px 32px;
      max-width: 400px;
      width: 100%;
      text-align: center;
      border: 1.5px solid #e6e2c3;
    }
    .recuperar-container h2 {
      color: #b78732;
      font-weight: 600;
      margin-bottom: 18px;
      font-size: 2rem;
      letter-spacing: 1px;
    }
    .recuperar-container label {
      display: block;
      color: #6c757d;
      font-size: 1rem;
      margin-bottom: 8px;
      text-align: center; /* centrado */
      font-weight: 500;
    }
    .recuperar-container input[type="email"] {
      border: 1.5px solid #b78732;
      border-radius: 7px;
      padding: 12px;
      font-size: 1rem;
      margin-bottom: 18px;
      outline: none;
      transition: border-color 0.2s;
      background: #f8f9fa;
    }
    .recuperar-container input[type="email"]:focus {
      border-color: #8d6a1e;
      background: #fffbe7;
    }
    .recuperar-container button {
      background: linear-gradient(90deg, #b78732 60%, #8d6a1e 100%);
      color: #fff;
      border: none;
      border-radius: 7px;
      padding: 12px 0;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(183, 135, 50, 0.08);
      transition: background 0.2s, box-shadow 0.2s;
      margin-top: 8px;
      width: 100%;
      white-space: normal;
      word-break: break-word;
      text-align: center;
      box-sizing: border-box;
    }
    .recuperar-container button:hover {
      background: linear-gradient(90deg, #8d6a1e 60%, #b78732 100%);
      box-shadow: 0 4px 16px rgba(183, 135, 50, 0.13);
    }
    .recuperar-container .volver {
      display: block;
      margin-top: 22px;
      color: #b78732;
      text-decoration: none;
      font-size: 0.98rem;
      font-weight: 500;
      transition: color 0.2s;
    }
    .recuperar-container .volver:hover {
      color: #8d6a1e;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="recuperar-container">
    <h2>Recuperar contraseña</h2>
    <form action="enviar_correo.php" method="POST" autocomplete="off">
      <label for="correo">Correo electrónico registrado:</label>
      <input type="email" name="correo" id="correo" required placeholder="Ej: usuario@correo.com">
      <button type="submit">Enviar enlace de recuperación</button>
    </form>
    <a href="login.php" class="volver">Volver al inicio de sesión</a>
  </div>
</body>
</html>
