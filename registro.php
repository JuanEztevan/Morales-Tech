<?php
// session_start();
$error   = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres   = trim($_POST['nombres']   ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $dni       = trim($_POST['dni']       ?? '');
    $telefono  = trim($_POST['telefono']  ?? '');
    $correo    = trim($_POST['correo']    ?? '');
    $ruc       = trim($_POST['ruc']       ?? '');
    $password  = $_POST['password']       ?? '';
    $confirm   = $_POST['confirm']        ?? '';

    if (!$nombres || !$apellidos || !$dni || !$telefono || !$correo || !$password) {
        $error = 'Completa todos los campos obligatorios.';
    } elseif (strlen($dni) !== 8) {
        $error = 'El DNI debe tener exactamente 8 dígitos.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ingresa un correo electrónico válido.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $confirm) {
        $error = 'Las contraseñas no coinciden.';
    } elseif ($ruc && strlen($ruc) !== 11) {
        $error = 'El RUC debe tener exactamente 11 dígitos.';
    } else {
        // TODO: guardar en BD
        header('Location: login.php?registered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="navbar" id="navbar">
  <div class="container">
    <div class="nav-inner">
      <div class="nav-left">
        <a href="index.php" class="nav-logo" style="display:flex;align-items:center;">
          <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
          <div class="nav-logo-fallback">Morales<span>Tech</span></div>
        </a>
      </div>
      <div class="nav-center">
        <ul class="nav-links">
          <li><a href="index.php#inicio">Inicio</a></li>
          <li><a href="index.php#servicios">Servicios</a></li>
          <li><a href="index.php#como-funciona">Cómo funciona</a></li>
          <li><a href="index.php#contacto">Soporte</a></li>
        </ul>
      </div>
      <div class="nav-right">
        <a href="login.php" class="btn-nav-login">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="14" height="14">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Iniciar sesión
        </a>
        <a href="registro.php" class="btn-nav-registro active-nav">
          Crear cuenta
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
          </svg>
        </a>
        <button class="nav-hamburger" id="hamburger" aria-label="Menú">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu">
  <a href="index.php#inicio"        onclick="closeMobileMenu()">Inicio</a>
  <a href="index.php#servicios"     onclick="closeMobileMenu()">Servicios</a>
  <a href="index.php#como-funciona" onclick="closeMobileMenu()">Cómo funciona</a>
  <a href="index.php#contacto"      onclick="closeMobileMenu()">Soporte</a>
  <hr class="mobile-divider">
  <a href="login.php">Iniciar sesión</a>
  <a href="registro.php" class="mobile-cta">Crear cuenta →</a>
</div>

<!-- ══ CONTENIDO ══ -->
<div class="page-auth page-auth--registro">
  <div class="sphere-left"></div>
  <div class="sphere-right"></div>
  <div class="bg-noise"></div>

  <div class="auth-card auth-card--wide">
    <!-- Isotipo -->
    <div class="auth-isotipo">
      <img src="img/isotipo-blanco.png" alt="Morales Tech"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
      <div class="auth-isotipo-fallback">M<span>T</span></div>
    </div>

    <h1 class="auth-heading">Crea tu cuenta</h1>
    <p class="auth-sub">Accede a tu portal de soporte técnico<br>en segundos, es gratuito</p>

    <?php if ($error): ?>
    <div class="auth-alert">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="registro.php" autocomplete="off" id="reg-form">

      <!-- ─── Datos personales ─── -->
      <div class="section-divider">
        <div class="section-divider-line"></div>
        <span class="section-divider-label">Datos personales</span>
        <div class="section-divider-line"></div>
      </div>

      <div class="form-grid-2col">
        <!-- Nombres -->
        <div class="auth-form-group compact">
          <label for="nombres">
            Nombres <span class="label-required">*</span>
          </label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            <input type="text" id="nombres" name="nombres"
                   placeholder="Ej. Juan Carlos"
                   value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>"
                   required autocomplete="given-name">
          </div>
        </div>

        <!-- Apellidos -->
        <div class="auth-form-group compact">
          <label for="apellidos">
            Apellidos <span class="label-required">*</span>
          </label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            <input type="text" id="apellidos" name="apellidos"
                   placeholder="Ej. García López"
                   value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>"
                   required autocomplete="family-name">
          </div>
        </div>

        <!-- DNI -->
        <div class="auth-form-group compact">
          <label for="dni">
            DNI <span class="label-required">*</span>
          </label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
            </svg>
            <input type="text" id="dni" name="dni"
                   placeholder="12345678" maxlength="8"
                   value="<?= htmlspecialchars($_POST['dni'] ?? '') ?>"
                   required inputmode="numeric">
          </div>
        </div>

        <!-- Teléfono -->
        <div class="auth-form-group compact">
          <label for="telefono">
            Teléfono <span class="label-required">*</span>
          </label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.56 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.9-.9a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/>
            </svg>
            <input type="text" id="telefono" name="telefono"
                   placeholder="9XXXXXXXX" maxlength="9"
                   value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                   required inputmode="numeric">
          </div>
        </div>

        <!-- RUC (opcional) -->
        <div class="auth-form-group compact span-2">
          <label for="ruc">
            RUC <span class="label-opt">Opcional — solo si facturas a empresa</span>
          </label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
              <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
            </svg>
            <input type="text" id="ruc" name="ruc"
                   placeholder="20XXXXXXXXX" maxlength="11"
                   value="<?= htmlspecialchars($_POST['ruc'] ?? '') ?>"
                   inputmode="numeric">
          </div>
        </div>
      </div>

      <!-- ─── Acceso a la cuenta ─── -->
      <div class="section-divider">
        <div class="section-divider-line"></div>
        <span class="section-divider-label">Acceso a la cuenta</span>
        <div class="section-divider-line"></div>
      </div>

      <div class="form-grid-2col">
        <!-- Correo -->
        <div class="auth-form-group compact span-2">
          <label for="correo">
            Correo electrónico <span class="label-required">*</span>
          </label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
            <input type="email" id="correo" name="correo"
                   placeholder="correo@ejemplo.com"
                   value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                   required autocomplete="email">
          </div>
        </div>

        <!-- Contraseña -->
        <div class="auth-form-group compact">
          <label for="password">
            Contraseña <span class="label-required">*</span>
          </label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input type="password" id="password" name="password"
                   placeholder="Mínimo 6 caracteres"
                   required autocomplete="new-password"
                   oninput="checkStrength(this.value)">
            <button type="button" class="pw-toggle" id="pw-toggle-1" tabindex="-1" aria-label="Ver contraseña">
              <svg id="eye1-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
              <svg id="eye1-hide" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
          <div class="pw-strength" id="pw-bars">
            <div class="pw-strength-bar" id="bar1"></div>
            <div class="pw-strength-bar" id="bar2"></div>
            <div class="pw-strength-bar" id="bar3"></div>
            <div class="pw-strength-bar" id="bar4"></div>
          </div>
          <div class="pw-strength-label" id="pw-label"></div>
        </div>

        <!-- Confirmar contraseña -->
        <div class="auth-form-group compact">
          <label for="confirm">
            Confirmar contraseña <span class="label-required">*</span>
          </label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input type="password" id="confirm" name="confirm"
                   placeholder="Repite tu contraseña"
                   required autocomplete="new-password"
                   oninput="checkMatch()">
            <button type="button" class="pw-toggle" id="pw-toggle-2" tabindex="-1" aria-label="Ver contraseña">
              <svg id="eye2-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
              <svg id="eye2-hide" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
          <div class="pw-strength-label" id="match-label" style="text-align:left;padding:0 4px;"></div>
        </div>
      </div>

      <!-- Términos -->
      <div class="terms-row">
        <input type="checkbox" id="terminos" name="terminos" required>
        <label for="terminos">
          Acepto los <a href="#">términos y condiciones</a> y la
          <a href="#">política de privacidad</a> de Morales Tech.
        </label>
      </div>

      <button type="submit" class="btn-auth-submit auth-btn-submit-registro">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="8.5" cy="7" r="4"/>
          <line x1="20" y1="8" x2="20" y2="14"/>
          <line x1="23" y1="11" x2="17" y2="11"/>
        </svg>
        Crear mi cuenta
      </button>
    </form>

    <div class="auth-divider"><span>o</span></div>

    <div class="auth-card-footer">
      ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
    </div>
  </div>
</div>

<script src="script.js" defer></script>
</body>
</html>
