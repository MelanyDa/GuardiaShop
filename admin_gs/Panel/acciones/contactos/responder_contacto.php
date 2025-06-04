<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../libs/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];
    $id_contacto = isset($_POST['id_contacto']) ? intval($_POST['id_contacto']) : 0; // <-- Añade esta línea

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Cambia por tu servidor SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gguardiashop@gmail.com'; // Tu correo
        $mail->Password   = 'dkgu cxev aksl ripl'; // Tu contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('gguardiashop@gmail.com', 'Soporte Guardiashop');
        $mail->addAddress($correo);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = nl2br($mensaje);

        $mail->send();
          // Cambiar el estado a "Respondido" en la base de datos
        if ($id_contacto > 0) {
            $conn = new mysqli("localhost", "root", "", "guardiashop");
            if (!$conn->connect_error) {
                $stmt = $conn->prepare("UPDATE contactanos SET estado = 'Respondido', respuesta_admin = ? WHERE id_contacto = ?");
                $stmt->bind_param("si", $mensaje, $id_contacto);
                $stmt->execute();
                $stmt->close();
                $conn->close();
            }
        }
        header("Location: /guardiashop/admin_gs/panel/g_contactos.php?respuesta=ok");
    } catch (Exception $e) {
        header("Location: /guardiashop/admin_gs/panel/g_contactos.php?respuesta=error");
    }
    exit;
}
?>

<?php if (!empty($msg['respuesta_admin'])): ?>
  <div style="background:#f1f8e9;padding:10px 15px;border-radius:6px;margin-top:8px;">
    <strong>Respuesta del equipo:</strong><br>
    <?= nl2br(htmlspecialchars($msg['respuesta_admin'])) ?>
  </div>
<?php endif; ?>