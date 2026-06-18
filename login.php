<?php
// session_start();
session_start();
require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = $conn->prepare("
        SELECT idCliente, nombres, apellidos, email, password
        FROM CLIENTE
        WHERE email = ?
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $cliente = $resultado->fetch_assoc();

        if (password_verify($pass, $cliente['password'])) {

            $_SESSION['idCliente'] = $cliente['idCliente'];
            $_SESSION['nombres'] = $cliente['nombres'];
            $_SESSION['apellidos'] = $cliente['apellidos'];
            $_SESSION['email'] = $cliente['email'];

            header("Location: inicio_clientes.php");
            exit;

        } else {
            $error = 'Correo o contraseña incorrectos. Verifica tus datos.';
        }

    } else {
        $error = 'Correo o contraseña incorrectos. Verifica tus datos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="img/isotipo-color.png" media="(prefers-color-scheme: light)">
  <link rel="icon" type="image/png" href="img/isotipo-blanco.png" media="(prefers-color-scheme: dark)">
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
        <a href="login.php" class="btn-nav-login active-nav">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="14" height="14">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Iniciar sesión
        </a>
        <a href="registro.php" class="btn-nav-registro">
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
  <a href="consulta_tickets.php">Consultar mi ticket</a>
  <a href="login.php" class="mobile-active">Iniciar sesión</a>
  <a href="registro.php" class="mobile-cta">Crear cuenta →</a>
</div>

<!-- ══ CONTENIDO ══ -->
<div class="page-auth">
  <div class="sphere-left"></div>
  <div class="sphere-right"></div>
  <div class="bg-noise"></div>

  <div class="auth-card">
    <!-- Isotipo -->
    <div class="auth-isotipo">
      <img src="img/isotipo-blanco.png" alt="Morales Tech"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
      <div class="auth-isotipo-fallback">M<span>T</span></div>
    </div>

    <h1 class="auth-heading">Bienvenido de nuevo</h1>
    <p class="auth-sub">Ingresa tus credenciales para<br>acceder a tu portal</p>

    <?php if ($error): ?>
    <div class="auth-alert">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="login.php" autocomplete="off">
      <div class="auth-form-group">
        <label for="email">Correo electrónico</label>
        <div class="auth-input-wrap">
          <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
          </svg>
          <input type="email" id="email" name="email"
                 placeholder="correo@ejemplo.com"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                 required autocomplete="section-cliente email"
                 readonly>
        </div>
      </div>

      <div class="auth-form-group">
        <label for="password">Contraseña</label>
        <div class="auth-input-wrap">
          <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          <input type="password" id="password" name="password"
                 placeholder="••••••••"
                 required autocomplete="section-cliente current-password">
          <button type="button" class="pw-toggle" id="pw-toggle" tabindex="-1" aria-label="Ver contraseña">
            <svg id="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
            <svg id="eye-hide" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
              <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
              <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
          </button>
        </div>
      </div>
      <a href="recuperar_contrasena.php" class="forgot-link">
        ¿Olvidaste tu contraseña? <!-- Botón nuevo para redirigir a Recuperar contrasela -->
      </a>
      <button type="submit" class="btn-auth-submit">
        Iniciar sesión
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
          <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
      </button>
    </form>

    <div class="auth-divider"><span>o</span></div>
    <div class="auth-card-footer">
      ¿Aún no tienes cuenta? <a href="registro.php">Regístrate aquí</a><br>
      <a href="consulta_tickets.php">Consultar estado de mi ticket</a>
    </div>
  </div>
</div>

<script src="script.js" defer></script>
<script>
  window.addEventListener('load', function() {
    setTimeout(function() {
      var f = document.getElementById('email');
      if (!f) return;
      f.removeAttribute('readonly');
      if (f.value && f.value.endsWith('@moralestechs.com')) {
        f.value = '';
        var p = document.getElementById('password');
        if (p) p.value = '';
      }
    }, 600);
  });
</script>
</body>
</html>
