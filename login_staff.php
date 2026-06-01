<?php
// session_start();
session_start();
require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    // Validar dominio corporativo
    if (!str_ends_with($email, '@moralestechs.com')) {

        $error = 'Ingresa tu correo corporativo institucional para continuar.';

    } else {

        $stmt = $conn->prepare("
            SELECT idAdmin, nombres, apellidos, email, password
            FROM ADMIN
            WHERE email = ?
        ");

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $admin = $resultado->fetch_assoc();

            if (password_verify($pass, $admin['password'])) {

                $_SESSION['idAdmin']    = $admin['idAdmin'];
                $_SESSION['nombres']   = $admin['nombres'];
                $_SESSION['apellidos'] = $admin['apellidos'];
                $_SESSION['email']     = $admin['email'];

                header("Location: dashboard.php");
                exit;

            } else {

                $error = 'Credenciales incorrectas. Verifica tus datos e intenta de nuevo.';
            }

        } else {

            $error = 'Credenciales incorrectas. Verifica tus datos e intenta de nuevo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso Staff — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="admin-body">

<div class="admin-split">

  <!-- ══ PANEL IZQUIERDO — decorativo oscuro ══ -->
  <div class="admin-panel admin-panel--dark">
    <div class="admin-panel-dots"></div>
    <div class="admin-panel__brand">
      <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
           onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
      <span class="admin-brand-fallback">Morales Tech</span>
    </div>
    <div class="admin-panel__center">
      <div class="admin-panel-badge admin-panel-badge--green">Portal exclusivo de colaboradores</div>
      <h1 class="admin-panel__title">
        Panel de<br>gestión <em>interna</em><br>Morales Tech
      </h1>
      <p class="admin-panel__desc">
        Accede al sistema de tickets, inventario, ventas y atención al cliente desde tu cuenta corporativa. Solo para colaboradores autorizados.
      </p>
      <div class="admin-panel-stats">
        <div class="admin-panel-stat">
          <span class="admin-panel-stat__val">4</span>
          <span class="admin-panel-stat__lbl">Módulos</span>
        </div>
        <div class="admin-stat-sep"></div>
        <div class="admin-panel-stat">
          <span class="admin-panel-stat__val">24/7</span>
          <span class="admin-panel-stat__lbl">Disponible</span>
        </div>
      </div>
    </div>
    <div class="admin-panel__footer">
      © 2026 Morales Tech · Sistema interno · Acceso restringido
    </div>
  </div>

  <!-- ══ PANEL DERECHO — formulario claro ══ -->
  <div class="admin-panel admin-panel--light">
    <div class="admin-form-wrap">

      <div class="admin-form-isotipo">
        <a href="index.php" title="Ir al inicio">
          <img src="img/isotipo-color.png" alt="Morales Tech"
               onerror="this.style.display='none';this.parentElement.nextElementSibling.style.display='flex'">
        </a>
        <div class="admin-isotipo-fallback">MT</div>
      </div>

      <h1 class="admin-form-heading">Acceso para staff</h1>
      <p class="admin-form-sub">Ingresa con tu cuenta corporativa<br>asignada por Morales Tech</p>

      <?php if ($error): ?>
      <div class="admin-alert admin-alert--error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="login_staff.php" autocomplete="off">
        <div class="admin-form-group">
          <label for="email">Correo institucional</label>
          <div class="admin-input-wrap">
            <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input type="email" id="email" name="email"
                   placeholder="tucorreo@moralestechs.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   required autocomplete="username"
                   oninput="adminCheckDomain(this)">
          </div>
          <div class="admin-domain-hint" id="domain-hint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Usa tu correo corporativo institucional para ingresar.
          </div>
        </div>

        <div class="admin-form-group">
          <label for="password">Contraseña</label>
          <div class="admin-input-wrap">
            <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" required autocomplete="current-password">
            <button type="button" class="admin-pw-toggle" id="pw-toggle-staff" tabindex="-1" aria-label="Mostrar contraseña">
              <svg id="eye-show-staff" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg id="eye-hide-staff" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
        </div>

        <button type="submit" class="admin-btn-submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
          Ingresar al sistema
        </button>
      </form>

      <div class="admin-btn-divider"><span>¿Eres nuevo?</span></div>

      <a href="registro_staff.php" class="admin-btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
        Crear cuenta de colaborador
      </a>

      <div class="admin-ti-note">
        <div class="admin-ti-note__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
          <div class="admin-ti-note__title">¿Olvidaste tu contraseña?</div>
          <div class="admin-ti-note__text">
            Las credenciales son gestionadas por el administrador del sistema. Comunícate con el área de TI en
            <a href="mailto:ti@moralestechs.com">ti@moralestechs.com</a>
            o acércate a soporte interno.
          </div>
        </div>
      </div>

    </div>
  </div>

</div><!-- /admin-split -->

<script src="script.js" defer></script>
</body>
</html>
