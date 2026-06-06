<?php
require_once 'conexion.php';

$error   = '';
$success = false;
$old     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'nombres'    => trim($_POST['nombres']    ?? ''),
        'apellidos'  => trim($_POST['apellidos']  ?? ''),
        'email'      => trim($_POST['email']      ?? ''),
        'dni'        => trim($_POST['dni']        ?? ''),
        'pregunta1'  => trim($_POST['pregunta1']  ?? ''),
        'respuesta1' => trim($_POST['respuesta1'] ?? ''),
        'pregunta2'  => trim($_POST['pregunta2']  ?? ''),
        'respuesta2' => trim($_POST['respuesta2'] ?? ''),
        'pregunta3'  => trim($_POST['pregunta3']  ?? ''),
        'respuesta3' => trim($_POST['respuesta3'] ?? ''),
    ];
    $pass  = $_POST['password']  ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if (empty($old['nombres']) || empty($old['apellidos']) || empty($old['email']) || empty($old['dni']) || empty($pass)) {
        $error = 'Completa todos los campos para continuar.';
    } elseif (!str_ends_with($old['email'], '@moralestechs.com')) {
        $error = 'Ingresa tu correo corporativo institucional para continuar.';
    } elseif (!preg_match('/^\d{8}$/', $old['dni'])) {
        $error = 'El DNI debe contener exactamente 8 dígitos.';
    } elseif (strlen($pass) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($pass !== $pass2) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (empty($old['pregunta1']) || empty($old['respuesta1']) || empty($old['pregunta2']) || empty($old['respuesta2']) || empty($old['pregunta3']) || empty($old['respuesta3'])) {
        $error = 'Completa las tres preguntas de seguridad y sus respuestas.';
    } else {
        // Verificar si el correo ya existe
        $stmt = $conn->prepare("SELECT idAdmin FROM ADMIN WHERE email = ?");
        $stmt->bind_param("s", $old['email']);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $error = 'Ya existe una cuenta registrada con ese correo.';
        } else {
            // Encriptar contraseña
            $passwordHash = password_hash($pass, PASSWORD_DEFAULT);

            // Registrar administrador
            $stmt = $conn->prepare("
                INSERT INTO ADMIN
                (nombres, apellidos, email, password, dni,
                 pregunta1, respuesta1, pregunta2, respuesta2, pregunta3, respuesta3)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "sssssssssss",
                $old['nombres'],
                $old['apellidos'],
                $old['email'],
                $passwordHash,
                $old['dni'],
                $old['pregunta1'],
                $old['respuesta1'],
                $old['pregunta2'],
                $old['respuesta2'],
                $old['pregunta3'],
                $old['respuesta3']
            );

            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = 'Ocurrió un error al registrar la cuenta.';
            }
        }
    }
}

// Determinar en qué paso re-aterrizar según dónde falló la validación
$startStep = 1;
if ($error && !empty($old['nombres']) && !empty($old['apellidos']) && !empty($old['email']) && !empty($old['dni'])) {
    // Paso 1 OK → el error está en paso 2 o paso 3
    $securityOk = !empty($old['pregunta1']) && !empty($old['respuesta1']) &&
                  !empty($old['pregunta2']) && !empty($old['respuesta2']) &&
                  !empty($old['pregunta3']) && !empty($old['respuesta3']);
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
  <title>Registro de colaborador — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="admin-body">

<div class="admin-split">

  <!-- ══ PANEL IZQUIERDO — formulario claro ══ -->
  <div class="admin-panel admin-panel--light">
    <div class="admin-form-wrap admin-form-wrap--wide">

      <div class="admin-form-isotipo">
        <a href="index.php" title="Ir al inicio">
          <img src="img/isotipo-color.png" alt="Morales Tech"
               onerror="this.style.display='none';this.parentElement.nextElementSibling.style.display='flex'">
        </a>
        <div class="admin-isotipo-fallback">MT</div>
      </div>

      <h1 class="admin-form-heading">Crear cuenta de colaborador</h1>
      <p class="admin-form-sub">Completa los datos para solicitar acceso<br>al sistema interno de Morales Tech</p>

      <!-- ══ Indicador de 3 pasos (variante light) ══ -->
      <div class="ms-stepper ms-stepper--light" id="ms-stepper">

        <!-- Paso 1 -->
        <div class="ms-step ms-step--active ms-step--light" id="ms-dot-1">
          <div class="ms-step__dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <span class="ms-step__label">Datos personales</span>
        </div>

        <div class="ms-connector ms-connector--light" id="ms-connector-1"></div>

        <!-- Paso 2 -->
        <div class="ms-step ms-step--light" id="ms-dot-2">
          <div class="ms-step__dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
          </div>
          <span class="ms-step__label">Seguridad</span>
        </div>

        <div class="ms-connector ms-connector--light" id="ms-connector-2"></div>

        <!-- Paso 3 -->
        <div class="ms-step ms-step--light" id="ms-dot-3">
          <div class="ms-step__dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </div>
          <span class="ms-step__label">Contraseña</span>
        </div>

      </div>

      <?php if ($success): ?>
      <div class="admin-alert admin-alert--success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Solicitud enviada correctamente. El administrador activará tu cuenta en breve.
      </div>
      <?php elseif ($error): ?>
      <div class="admin-alert admin-alert--error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <?php if (!$success): ?>
      <form method="POST" action="registro_staff.php" autocomplete="off" id="reg-form">

        <!-- ══════════ PASO 1: DATOS PERSONALES ══════════ -->
        <div class="ms-panel" id="ms-panel-1">

          <!-- Nombres y Apellidos -->
          <div class="admin-form-row">
            <div class="admin-form-group">
              <label for="nombres">Nombres</label>
              <div class="admin-input-wrap">
                <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" id="nombres" name="nombres"
                       placeholder="Juan"
                       value="<?= htmlspecialchars($old['nombres'] ?? '') ?>"
                       required autocomplete="given-name">
              </div>
            </div>
            <div class="admin-form-group">
              <label for="apellidos">Apellidos</label>
              <div class="admin-input-wrap">
                <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" id="apellidos" name="apellidos"
                       placeholder="Pérez García"
                       value="<?= htmlspecialchars($old['apellidos'] ?? '') ?>"
                       required autocomplete="family-name">
              </div>
            </div>
          </div>

          <!-- Correo corporativo -->
          <div class="admin-form-group">
            <label for="email">Correo institucional</label>
            <div class="admin-input-wrap">
              <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <input type="email" id="email" name="email"
                     placeholder="tucorreo@moralestechs.com"
                     value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                     required autocomplete="email"
                     oninput="adminCheckDomain(this)">
            </div>
            <div class="admin-domain-hint" id="domain-hint">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              Usa tu correo corporativo institucional para registrarte.
            </div>
          </div>

          <!-- DNI -->
          <div class="admin-form-group">
            <label for="dni">DNI</label>
            <div class="admin-input-wrap">
              <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
              <input type="text" id="dni" name="dni"
                     placeholder="12345678"
                     value="<?= htmlspecialchars($old['dni'] ?? '') ?>"
                     required maxlength="8" inputmode="numeric" pattern="\d{8}"
                     oninput="this.value=this.value.replace(/\D/g,'').slice(0,8)">
            </div>
          </div>

          <!-- Botón siguiente paso 1 -->
          <button type="button" class="admin-btn-submit" id="ms-btn-next-1"
                  onclick="msNext('reg-form', 1)">
            Siguiente
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
          </button>

        </div><!-- /ms-panel-1 -->


        <!-- ══════════ PASO 2: PREGUNTAS DE SEGURIDAD ══════════ -->
        <div class="ms-panel ms-panel--hidden" id="ms-panel-2">

          <!-- Etiqueta de sección -->
          <div class="ms-sq-section-label">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Preguntas de seguridad
          </div>
          <p class="ms-security-hint ms-security-hint--light">Estas preguntas te permitirán recuperar tu cuenta si olvidas tu contraseña.</p>

          <?php for ($i = 1; $i <= 3; $i++):
            $selP = $old["pregunta$i"] ?? '';
            $selR = $old["respuesta$i"] ?? '';
          ?>
          <div class="ms-sq-block ms-sq-block--light">
            <div class="ms-sq-num ms-sq-num--light"><?= $i ?></div>
            <div class="ms-sq-fields">
              <div class="admin-form-group">
                <label for="pregunta<?= $i ?>">Pregunta <?= $i ?></label>
                <div class="admin-input-wrap admin-input-wrap--select">
                  <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                  <select id="pregunta<?= $i ?>" name="pregunta<?= $i ?>" class="admin-select" required>
                    <option value="">— Selecciona una pregunta —</option>
                    <?php foreach ($preguntas as $p): ?>
                    <option value="<?= htmlspecialchars($p) ?>"<?= $selP === $p ? ' selected' : '' ?>><?= htmlspecialchars($p) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="admin-form-group">
                <label for="respuesta<?= $i ?>">Respuesta</label>
                <div class="admin-input-wrap">
                  <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
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
          <div class="ms-btn-row ms-btn-row--light">
            <button type="button" class="ms-btn-back ms-btn-back--light" onclick="msBack(1)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
              </svg>
              Anterior
            </button>
            <button type="button" class="admin-btn-submit ms-btn-submit"
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

          <!-- Contraseña -->
          <div class="admin-form-group">
            <label for="password">Contraseña</label>
            <div class="admin-input-wrap">
              <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" id="password" name="password"
                     placeholder="Mín. 8 caracteres"
                     autocomplete="new-password"
                     oninput="adminUpdateStrength(this.value)">
              <button type="button" class="admin-pw-toggle" id="pw-toggle-reg1" tabindex="-1" aria-label="Mostrar contraseña">
                <svg id="eye-show-reg1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg id="eye-hide-reg1" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
              </button>
            </div>
            <div class="admin-pw-strength" id="admin-pw-strength" style="display:none">
              <div class="admin-pw-strength__bars">
                <div class="admin-pw-strength__bar"></div>
                <div class="admin-pw-strength__bar"></div>
                <div class="admin-pw-strength__bar"></div>
                <div class="admin-pw-strength__bar"></div>
              </div>
              <span class="admin-pw-strength__label" id="admin-pw-strength-label">Débil</span>
            </div>
          </div>

          <!-- Confirmar contraseña -->
          <div class="admin-form-group">
            <label for="password2">Confirmar contraseña</label>
            <div class="admin-input-wrap">
              <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" id="password2" name="password2"
                     placeholder="Repite tu contraseña"
                     autocomplete="new-password"
                     oninput="adminCheckMatch()">
              <button type="button" class="admin-pw-toggle" id="pw-toggle-reg2" tabindex="-1" aria-label="Mostrar contraseña">
                <svg id="eye-show-reg2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg id="eye-hide-reg2" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
              </button>
            </div>
          </div>

          <!-- Botones paso 3 -->
          <div class="ms-btn-row ms-btn-row--light">
            <button type="button" class="ms-btn-back ms-btn-back--light" onclick="msBack(2)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
              </svg>
              Anterior
            </button>
            <button type="submit" class="admin-btn-submit ms-btn-submit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
              Registrar
            </button>
          </div>

        </div><!-- /ms-panel-3 -->

      </form>
      <?php endif; ?>

      <a href="login_staff.php" class="admin-back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver al inicio de sesión
      </a>

    </div>
  </div>

  <!-- ══ PANEL DERECHO — decorativo oscuro ══ -->
  <div class="admin-panel admin-panel--dark">
    <div class="admin-panel-dots"></div>
    <div class="admin-panel__brand">
      <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
           onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
      <span class="admin-brand-fallback">Morales Tech</span>
    </div>
    <div class="admin-panel__center">
      <div class="admin-panel-badge admin-panel-badge--blue">Nuevo colaborador</div>
      <h2 class="admin-panel__title">
        Únete al<br>equipo <em>interno</em><br>Morales Tech
      </h2>
      <p class="admin-panel__desc">
        Tu cuenta será revisada por el administrador. Una vez aprobada, tendrás acceso al sistema de gestión según tu rol asignado.
      </p>
      <div class="admin-onboarding-steps">
        <div class="admin-onboarding-step">
          <div class="admin-step-num">1</div>
          <div>
            <div class="admin-step-title">Completa el formulario</div>
            <div class="admin-step-desc">Ingresa tus datos personales y tu correo corporativo asignado.</div>
          </div>
        </div>
        <div class="admin-onboarding-step">
          <div class="admin-step-num">2</div>
          <div>
            <div class="admin-step-title">Revisión del administrador</div>
            <div class="admin-step-desc">El área de TI verificará tus datos y activará tu acceso.</div>
          </div>
        </div>
        <div class="admin-onboarding-step">
          <div class="admin-step-num">3</div>
          <div>
            <div class="admin-step-title">Accede al sistema</div>
            <div class="admin-step-desc">Recibirás confirmación y podrás ingresar con tus credenciales.</div>
          </div>
        </div>
      </div>
    </div>
    <div class="admin-panel__footer">
      © 2026 Morales Tech · Sistema interno · Acceso restringido
    </div>
  </div>

</div><!-- /admin-split -->

<script>
  var MS_START_STEP  = <?= $startStep ?>;
  var MS_TOTAL_STEPS = 3;
</script>
<script src="script.js" defer></script>
</body>
</html>
