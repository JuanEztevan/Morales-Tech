<?php
require_once 'conexion.php';

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

    // Preguntas de seguridad
    $pregunta1  = trim($_POST['pregunta1']  ?? '');
    $respuesta1 = trim($_POST['respuesta1'] ?? '');
    $pregunta2  = trim($_POST['pregunta2']  ?? '');
    $respuesta2 = trim($_POST['respuesta2'] ?? '');
    $pregunta3  = trim($_POST['pregunta3']  ?? '');
    $respuesta3 = trim($_POST['respuesta3'] ?? '');

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
    } elseif (empty($pregunta1) || empty($respuesta1) || empty($pregunta2) || empty($respuesta2) || empty($pregunta3) || empty($respuesta3)) {
        $error = 'Completa las tres preguntas de seguridad y sus respuestas.';
    } else {
        // Verificar si el correo ya existe
        $stmt = $conn->prepare("SELECT idCliente FROM CLIENTE WHERE email = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {
            // Encriptar contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar cliente
            $stmt = $conn->prepare("
                INSERT INTO CLIENTE
                (nombres, apellidos, email, password, numTelefono, numDNI, numRUC,
                 pregunta1, respuesta1, pregunta2, respuesta2, pregunta3, respuesta3)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "sssssssssssss",
                $nombres,
                $apellidos,
                $correo,
                $passwordHash,
                $telefono,
                $dni,
                $ruc,
                $pregunta1,
                $respuesta1,
                $pregunta2,
                $respuesta2,
                $pregunta3,
                $respuesta3
            );

            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = "Error al registrar usuario.";
            }
        }
    }
}

// Valores anteriores para repoblar el formulario en caso de error
$old = [
    'nombres'   => $_POST['nombres']   ?? '',
    'apellidos' => $_POST['apellidos'] ?? '',
    'dni'       => $_POST['dni']       ?? '',
    'telefono'  => $_POST['telefono']  ?? '',
    'correo'    => $_POST['correo']    ?? '',
    'ruc'       => $_POST['ruc']       ?? '',
    'pregunta1' => $_POST['pregunta1'] ?? '',
    'respuesta1'=> $_POST['respuesta1']?? '',
    'pregunta2' => $_POST['pregunta2'] ?? '',
    'respuesta2'=> $_POST['respuesta2']?? '',
    'pregunta3' => $_POST['pregunta3'] ?? '',
    'respuesta3'=> $_POST['respuesta3']?? '',
];

// Determinar en qué paso re-aterrizar según dónde falló la validación
$startStep = 1;
if ($error && $old['nombres'] && $old['apellidos'] && $old['dni'] && $old['telefono'] && $old['correo']) {
    // Paso 1 OK → el error está en paso 2 o paso 3
    $securityOk = $old['pregunta1'] && $old['respuesta1'] && $old['pregunta2'] && $old['respuesta2'] && $old['pregunta3'] && $old['respuesta3'];
    $startStep  = $securityOk ? 3 : 2;
}

$preguntas = [
    '¿Cuál es el nombre de tu primera mascota?',
    '¿En qué ciudad naciste?',
    '¿Cuál es el nombre de tu madre?',
    '¿Nombre de tu escuela primaria?',
    '¿Cuál fue tu primer trabajo?',
];
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

    <!-- ══ Indicador de 3 pasos ══ -->
    <div class="ms-stepper" id="ms-stepper">

      <!-- Paso 1 -->
      <div class="ms-step ms-step--active" id="ms-dot-1">
        <div class="ms-step__dot">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <span class="ms-step__label">Datos personales</span>
      </div>

      <div class="ms-connector" id="ms-connector-1"></div>

      <!-- Paso 2 -->
      <div class="ms-step" id="ms-dot-2">
        <div class="ms-step__dot">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <span class="ms-step__label">Seguridad</span>
      </div>

      <div class="ms-connector" id="ms-connector-2"></div>

      <!-- Paso 3 -->
      <div class="ms-step" id="ms-dot-3">
        <div class="ms-step__dot">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
        <span class="ms-step__label">Contraseña</span>
      </div>

    </div>

    <?php if ($error): ?>
    <div class="auth-alert">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="registro.php" autocomplete="off" id="reg-form">

      <!-- ══════════ PASO 1: DATOS PERSONALES ══════════ -->
      <div class="ms-panel" id="ms-panel-1">

        <div class="section-divider">
          <div class="section-divider-line"></div>
          <span class="section-divider-label">Datos personales</span>
          <div class="section-divider-line"></div>
        </div>

        <div class="form-grid-2col">
          <!-- Nombres -->
          <div class="auth-form-group compact">
            <label for="nombres">Nombres <span class="label-required">*</span></label>
            <div class="auth-input-wrap">
              <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
              </svg>
              <input type="text" id="nombres" name="nombres"
                     placeholder="Ej. Juan Carlos"
                     value="<?= htmlspecialchars($old['nombres']) ?>"
                     required autocomplete="given-name">
            </div>
          </div>

          <!-- Apellidos -->
          <div class="auth-form-group compact">
            <label for="apellidos">Apellidos <span class="label-required">*</span></label>
            <div class="auth-input-wrap">
              <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
              </svg>
              <input type="text" id="apellidos" name="apellidos"
                     placeholder="Ej. García López"
                     value="<?= htmlspecialchars($old['apellidos']) ?>"
                     required autocomplete="family-name">
            </div>
          </div>

          <!-- DNI -->
          <div class="auth-form-group compact">
            <label for="dni">DNI <span class="label-required">*</span></label>
            <div class="auth-input-wrap">
              <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
              </svg>
              <input type="text" id="dni" name="dni"
                     placeholder="12345678" maxlength="8"
                     value="<?= htmlspecialchars($old['dni']) ?>"
                     required inputmode="numeric">
            </div>
          </div>

          <!-- Teléfono -->
          <div class="auth-form-group compact">
            <label for="telefono">Teléfono <span class="label-required">*</span></label>
            <div class="auth-input-wrap">
              <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.56 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.9-.9a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/>
              </svg>
              <input type="text" id="telefono" name="telefono"
                     placeholder="9XXXXXXXX" maxlength="9"
                     value="<?= htmlspecialchars($old['telefono']) ?>"
                     required inputmode="numeric">
            </div>
          </div>

          <!-- Correo -->
          <div class="auth-form-group compact span-2">
            <label for="correo">Correo electrónico <span class="label-required">*</span></label>
            <div class="auth-input-wrap">
              <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
              </svg>
              <input type="email" id="correo" name="correo"
                     placeholder="correo@ejemplo.com"
                     value="<?= htmlspecialchars($old['correo']) ?>"
                     required autocomplete="email">
            </div>
          </div>

          <!-- RUC (opcional) -->
          <div class="auth-form-group compact span-2">
            <label for="ruc">RUC <span class="label-opt">Opcional — solo si facturas a empresa</span></label>
            <div class="auth-input-wrap">
              <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
              </svg>
              <input type="text" id="ruc" name="ruc"
                     placeholder="20XXXXXXXXX" maxlength="11"
                     value="<?= htmlspecialchars($old['ruc']) ?>"
                     inputmode="numeric">
            </div>
          </div>
        </div>

        <!-- Botón siguiente -->
        <button type="button" class="btn-auth-submit auth-btn-submit-registro" id="ms-btn-next-1"
                onclick="msNext('reg-form', 1)">
          Siguiente
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
          </svg>
        </button>

      </div><!-- /ms-panel-1 -->


      <!-- ══════════ PASO 2: PREGUNTAS DE SEGURIDAD ══════════ -->
      <div class="ms-panel ms-panel--hidden" id="ms-panel-2">

        <div class="section-divider">
          <div class="section-divider-line"></div>
          <span class="section-divider-label">Preguntas de seguridad</span>
          <div class="section-divider-line"></div>
        </div>

        <p class="ms-security-hint">Estas preguntas te permitirán recuperar tu cuenta si olvidas tu contraseña.</p>

        <?php for ($i = 1; $i <= 3; $i++):
            $selP = $old["pregunta$i"] ?? '';
            $selR = $old["respuesta$i"] ?? '';
        ?>
        <div class="ms-sq-block">
          <div class="ms-sq-num"><?= $i ?></div>
          <div class="ms-sq-fields">
            <div class="auth-form-group compact">
              <label for="pregunta<?= $i ?>">Pregunta <?= $i ?> <span class="label-required">*</span></label>
              <div class="auth-input-wrap auth-input-wrap--select">
                <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <select id="pregunta<?= $i ?>" name="pregunta<?= $i ?>" class="auth-select" required>
                  <option value="">— Selecciona una pregunta —</option>
                  <?php foreach ($preguntas as $p): ?>
                  <option value="<?= htmlspecialchars($p) ?>"<?= $selP === $p ? ' selected' : '' ?>><?= htmlspecialchars($p) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="auth-form-group compact">
              <label for="respuesta<?= $i ?>">Respuesta <span class="label-required">*</span></label>
              <div class="auth-input-wrap">
                <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <input type="text" id="respuesta<?= $i ?>" name="respuesta<?= $i ?>"
                       placeholder="Tu respuesta secreta"
                       value="<?= htmlspecialchars($selR) ?>"
                       required autocomplete="off">
              </div>
            </div>
          </div>
        </div>
        <?php endfor; ?>

        <!-- Botones paso 2 -->
        <div class="ms-btn-row">
          <button type="button" class="ms-btn-back" onclick="msBack(1)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Anterior
          </button>
          <button type="button" class="btn-auth-submit auth-btn-submit-registro ms-btn-submit"
                  onclick="msNext('reg-form', 2)">
            Siguiente
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
          </button>
        </div>

      </div><!-- /ms-panel-2 -->


      <!-- ══════════ PASO 3: CONTRASEÑA ══════════ -->
      <div class="ms-panel ms-panel--hidden" id="ms-panel-3">

        <div class="section-divider">
          <div class="section-divider-line"></div>
          <span class="section-divider-label">Acceso a la cuenta</span>
          <div class="section-divider-line"></div>
        </div>

        <div class="form-grid-2col">
          <!-- Contraseña -->
          <div class="auth-form-group compact">
            <label for="password">Contraseña <span class="label-required">*</span></label>
            <div class="auth-input-wrap">
              <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              <input type="password" id="password" name="password"
                     placeholder="Mínimo 6 caracteres"
                     autocomplete="new-password"
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
            <label for="confirm">Confirmar contraseña <span class="label-required">*</span></label>
            <div class="auth-input-wrap">
              <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              <input type="password" id="confirm" name="confirm"
                     placeholder="Repite tu contraseña"
                     autocomplete="new-password"
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

        <!-- Botones paso 3 -->
        <div class="ms-btn-row">
          <button type="button" class="ms-btn-back" onclick="msBack(2)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Anterior
          </button>
          <button type="submit" class="btn-auth-submit auth-btn-submit-registro ms-btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="8.5" cy="7" r="4"/>
              <line x1="20" y1="8" x2="20" y2="14"/>
              <line x1="23" y1="11" x2="17" y2="11"/>
            </svg>
            Registrar
          </button>
        </div>

      </div><!-- /ms-panel-3 -->

    </form>

    <div class="auth-divider"><span>o</span></div>

    <div class="auth-card-footer">
      ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
    </div>
  </div>
</div>

<script>
  // Paso inicial según resultado del servidor
  var MS_START_STEP = <?= $startStep ?>;
  var MS_TOTAL_STEPS = 3;
</script>
<script src="script.js" defer></script>
</body>
</html>
