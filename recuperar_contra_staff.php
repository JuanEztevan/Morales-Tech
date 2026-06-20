<?php
session_start();
require_once 'conexion.php';
$error     = '';
$step      = intval($_POST['step'] ?? 1);
$startStep = 1;
/* --- PASO 1: VERIFICAR CORREO O DNI --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 1) {
    $identificador = trim($_POST['identificador'] ?? '');
    if (!$identificador) {
        $error     = 'Ingresa tu correo institucional o DNI.';
        $startStep = 1;
    } else {
        $stmt = $conn->prepare(
            "SELECT idAdmin, pregunta1, pregunta2, pregunta3
               FROM ADMIN
              WHERE email = ? OR dni = ?
              LIMIT 1"
        );
        if (!$stmt) {
            $error     = 'Error interno. Intenta de nuevo.';
            $startStep = 1;
        } else {
            $stmt->bind_param("ss", $identificador, $identificador);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 0) {
                $error     = 'No encontramos ninguna cuenta con ese correo o DNI.';
                $startStep = 1;
            } else {
                $admin = $res->fetch_assoc();
                // limpia datos anteriores por si se reintenta el flujo
                unset(
                    $_SESSION['rec_staff_id'],
                    $_SESSION['rec_staff_p1'],
                    $_SESSION['rec_staff_p2'],
                    $_SESSION['rec_staff_p3'],
                    $_SESSION['rec_staff_ok']
                );
                $_SESSION['rec_staff_id'] = (int) $admin['idAdmin'];
                $_SESSION['rec_staff_p1'] = $admin['pregunta1'];
                $_SESSION['rec_staff_p2'] = $admin['pregunta2'];
                $_SESSION['rec_staff_p3'] = $admin['pregunta3'];
                $startStep = 2;
            }
            $stmt->close();
        }
    }
}
/* --- PASO 2: VERIFICAR RESPUESTAS --- */
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 2) {
    $r1 = trim($_POST['respuesta1'] ?? '');
    $r2 = trim($_POST['respuesta2'] ?? '');
    $r3 = trim($_POST['respuesta3'] ?? '');
    if (!$r1 || !$r2 || !$r3) {
        $error     = 'Responde las tres preguntas de seguridad.';
        $startStep = 2;
    } elseif (empty($_SESSION['rec_staff_id'])) {
        $error     = 'Sesión expirada. Vuelve a empezar.';
        $startStep = 1;
    } else {
        $id   = (int) $_SESSION['rec_staff_id'];
        $stmt = $conn->prepare(
            "SELECT respuesta1, respuesta2, respuesta3
               FROM ADMIN WHERE idAdmin = ? LIMIT 1"
        );
        if (!$stmt) {
            $error     = 'Error interno. Intenta de nuevo.';
            $startStep = 2;
        } else {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$row) {
                $error     = 'No se encontró la cuenta. Vuelve a empezar.';
                $startStep = 1;
            } elseif (
                strcasecmp(trim($row['respuesta1']), $r1) !== 0 ||
                strcasecmp(trim($row['respuesta2']), $r2) !== 0 ||
                strcasecmp(trim($row['respuesta3']), $r3) !== 0
            ) {
                $error     = 'Una o más respuestas son incorrectas. Intenta de nuevo.';
                $startStep = 2;
            } else {
                $_SESSION['rec_staff_ok'] = true;
                $startStep = 3;
            }
        }
    }
}
/* --- PASO 3: GUARDAR NUEVA CONTRASEÑA --- */
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 3) {
    $nueva   = $_POST['password']  ?? '';
    $confirm = $_POST['password2'] ?? '';
    if (empty($_SESSION['rec_staff_ok']) || empty($_SESSION['rec_staff_id'])) {
        $error     = 'Sesión expirada. Vuelve a empezar.';
        $startStep = 1;
    } elseif (strlen($nueva) < 8) {
        $error     = 'La contraseña debe tener al menos 8 caracteres.';
        $startStep = 3;
    } elseif ($nueva !== $confirm) {
        $error     = 'Las contraseñas no coinciden.';
        $startStep = 3;
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $id   = (int) $_SESSION['rec_staff_id'];
        $stmt = $conn->prepare("UPDATE ADMIN SET password = ? WHERE idAdmin = ?");
        if (!$stmt) {
            $error     = 'Error al guardar la contraseña. Inténtalo de nuevo.';
            $startStep = 3;
        } else {
            $stmt->bind_param("si", $hash, $id);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $stmt->close();
                unset(
                    $_SESSION['rec_staff_id'],
                    $_SESSION['rec_staff_p1'],
                    $_SESSION['rec_staff_p2'],
                    $_SESSION['rec_staff_p3'],
                    $_SESSION['rec_staff_ok']
                );
                header("Location: login_staff.php?recovered=1");
                exit;
            } else {
                $stmt->close();
                $error     = 'Error al guardar la contraseña. Inténtalo de nuevo.';
                $startStep = 3;
            }
        }
    }
}
$p1 = $_SESSION['rec_staff_p1'] ?? '';
$p2 = $_SESSION['rec_staff_p2'] ?? '';
$p3 = $_SESSION['rec_staff_p3'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar contraseña — Staff Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="img/isotipo-color.png" media="(prefers-color-scheme: light)">
  <link rel="icon" type="image/png" href="img/isotipo-blanco.png" media="(prefers-color-scheme: dark)">
</head>
<body class="admin-body">
<div class="admin-split">
  <div class="admin-panel admin-panel--light">
    <div class="admin-form-wrap admin-form-wrap--wide">
      <div class="admin-form-isotipo">
        <a href="index.php" title="Ir al inicio">
          <img src="img/isotipo-color.png" alt="Morales Tech"
               onerror="this.style.display='none';this.parentElement.nextElementSibling.style.display='flex'">
        </a>
        <div class="admin-isotipo-fallback">MT</div>
      </div>
      <h1 class="admin-form-heading">Recuperar contraseña</h1>
      <p class="admin-form-sub">Restablece el acceso a tu cuenta<br>de colaborador Morales Tech</p>
      <div class="ms-stepper ms-stepper--light" id="ms-stepper">
        <div class="ms-step ms-step--active ms-step--light" id="ms-dot-1">
          <div class="ms-step__dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
          </div>
          <span class="ms-step__label">Identificación</span>
        </div>
        <div class="ms-connector ms-connector--light" id="ms-connector-1"></div>
        <div class="ms-step ms-step--light" id="ms-dot-2">
          <div class="ms-step__dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
          </div>
          <span class="ms-step__label">Seguridad</span>
        </div>
        <div class="ms-connector ms-connector--light" id="ms-connector-2"></div>
        <div class="ms-step ms-step--light" id="ms-dot-3">
          <div class="ms-step__dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </div>
          <span class="ms-step__label">Nueva contraseña</span>
        </div>
      </div>
      <?php if ($error): ?>
      <div class="admin-alert admin-alert--error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>
      <form method="POST" action="recuperar_contra_staff.php" autocomplete="off"
            id="rec-form" onsubmit="recStaffSyncStep()">
        <input type="hidden" name="step" id="rec-step-hidden" value="<?= $startStep ?>">
        <div class="ms-panel" id="ms-panel-1">
          <div class="ms-sq-section-label" style="justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
            Identifica tu cuenta
          </div>
          <p class="ms-security-hint ms-security-hint--light">Ingresa tu correo institucional o DNI registrado en el sistema.</p>
          <div class="admin-form-group">
            <label for="identificador">Correo institucional o DNI</label>
            <div class="admin-input-wrap">
              <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
              </svg>
              <input type="text" id="identificador" name="identificador"
                     placeholder="tucorreo@moralestechs.com o 12345678"
                     value="<?= htmlspecialchars($_POST['identificador'] ?? '') ?>"
                     required autocomplete="off">
            </div>
          </div>
          <button type="submit" class="admin-btn-submit">
            Continuar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
          </button>
        </div>
        <div class="ms-panel ms-panel--hidden" id="ms-panel-2">
          <div class="ms-sq-section-label" style="justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Preguntas de seguridad
          </div>
          <p class="ms-security-hint ms-security-hint--light">Responde correctamente las tres preguntas que configuraste al registrarte.</p>
          <?php
          $pArr = [$p1, $p2, $p3];
          for ($i = 1; $i <= 3; $i++):
              $pregunta = $pArr[$i - 1] ?: "Pregunta de seguridad $i";
          ?>
          <div class="ms-sq-block ms-sq-block--light">
            <div class="ms-sq-num ms-sq-num--light"><?= $i ?></div>
            <div class="ms-sq-fields">
              <div class="admin-form-group">
                <label><?= htmlspecialchars($pregunta) ?></label>
                <div class="admin-input-wrap">
                  <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                  </svg>
                  <input type="text" id="respuesta<?= $i ?>" name="respuesta<?= $i ?>"
                         placeholder="Tu respuesta secreta"
                         autocomplete="off">
                </div>
              </div>
            </div>
          </div>
          <?php endfor; ?>
          <div class="ms-btn-row ms-btn-row--light">
            <button type="button" class="ms-btn-back ms-btn-back--light" onclick="recStaffBack(1)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
              </svg>
              Anterior
            </button>
            <button type="submit" class="admin-btn-submit ms-btn-submit">
              Verificar
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="ms-panel ms-panel--hidden" id="ms-panel-3">
          <div class="admin-form-group">
            <label for="password">Nueva contraseña</label>
            <div class="admin-input-wrap">
              <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              <input type="password" id="password" name="password"
                     placeholder="Mín. 8 caracteres"
                     autocomplete="new-password"
                     oninput="adminUpdateStrength(this.value)">
              <button type="button" class="admin-pw-toggle" id="pw-toggle-rec1" tabindex="-1" aria-label="Mostrar contraseña">
                <svg id="eye-show-rec1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg id="eye-hide-rec1" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
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
          <div class="admin-form-group">
            <label for="password2">Confirmar contraseña</label>
            <div class="admin-input-wrap">
              <svg class="admin-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              <input type="password" id="password2" name="password2"
                     placeholder="Repite tu contraseña"
                     autocomplete="new-password"
                     oninput="adminCheckMatch()">
              <button type="button" class="admin-pw-toggle" id="pw-toggle-rec2" tabindex="-1" aria-label="Mostrar contraseña">
                <svg id="eye-show-rec2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg id="eye-hide-rec2" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
              </button>
            </div>
          </div>
          <div class="ms-btn-row ms-btn-row--light">
            <button type="button" class="ms-btn-back ms-btn-back--light" onclick="recStaffBack(2)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
              </svg>
              Anterior
            </button>
            <button type="submit" class="admin-btn-submit ms-btn-submit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              Guardar contraseña
            </button>
          </div>
        </div>
      </form>
      <a href="login_staff.php" class="admin-back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Volver al inicio de sesión
      </a>
    </div>
  </div>
  <div class="admin-panel admin-panel--dark">
    <div class="admin-panel-dots"></div>
    <div class="admin-panel__brand">
      <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
           onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
      <span class="admin-brand-fallback">Morales Tech</span>
    </div>
    <div class="admin-panel__center">
      <div class="admin-panel-badge admin-panel-badge--green">Acceso seguro</div>
      <h2 class="admin-panel__title">
        Recupera<br>el acceso a tu<br>cuenta <em>interna</em>
      </h2>
      <p class="admin-panel__desc">
        Sigue los pasos para verificar tu identidad y establecer una nueva contraseña de forma segura.
      </p>
      <div class="admin-onboarding-steps">
        <div class="admin-onboarding-step">
          <div class="admin-step-num">1</div>
          <div>
            <div class="admin-step-title">Identifica tu cuenta</div>
            <div class="admin-step-desc">Ingresa tu correo institucional o DNI para localizar tu cuenta.</div>
          </div>
        </div>
        <div class="admin-onboarding-step">
          <div class="admin-step-num">2</div>
          <div>
            <div class="admin-step-title">Verifica tu identidad</div>
            <div class="admin-step-desc">Responde las preguntas de seguridad que configuraste al registrarte.</div>
          </div>
        </div>
        <div class="admin-onboarding-step">
          <div class="admin-step-num">3</div>
          <div>
            <div class="admin-step-title">Crea una nueva contraseña</div>
            <div class="admin-step-desc">Establece una contraseña segura de al menos 8 caracteres.</div>
          </div>
        </div>
      </div>
    </div>
    <div class="admin-panel__footer">
      © 2026 Morales Tech · Sistema interno · Acceso restringido
    </div>
  </div>
</div>
<script>
/* --- RECUPERAR CONTRASEÑA: STAFF --- */
var MS_START_STEP  = <?= (int)$startStep ?>;
var MS_TOTAL_STEPS = 3;
var _staffActivePanel = MS_START_STEP;

// recStaffSyncStep(): se llama desde el onsubmit del formulario
// recorre los paneles, detecta cuál no está oculto y guarda
// ese número en el campo hidden antes de enviarlo al servidor
function recStaffSyncStep() {
  for (var i = 1; i <= MS_TOTAL_STEPS; i++) {
    var panel = document.getElementById('ms-panel-' + i);
    if (panel && !panel.classList.contains('ms-panel--hidden')) {
      document.getElementById('rec-step-hidden').value = i;
      _staffActivePanel = i;
      break;
    }
  }
}

// muestra el panel anterior sin hacer submit
function recStaffBack(targetStep) {
  var panelPrev = document.getElementById('ms-panel-' + targetStep);
  var panelCurr = document.getElementById('ms-panel-' + (targetStep + 1));
  if (!panelPrev || !panelCurr) return;
  panelCurr.classList.add('ms-panel--hidden');
  panelPrev.classList.remove('ms-panel--hidden');
  _staffActivePanel = targetStep;
  recStaffUpdateStepper(targetStep);
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// actualiza los dots del stepper
function recStaffUpdateStepper(activeStep) {
  for (var i = 1; i <= MS_TOTAL_STEPS; i++) {
    var dot       = document.getElementById('ms-dot-' + i);
    var connector = document.getElementById('ms-connector-' + i);
    if (!dot) continue;
    dot.classList.remove('ms-step--active', 'ms-step--done');
    if (i < activeStep)        dot.classList.add('ms-step--done');
    else if (i === activeStep) dot.classList.add('ms-step--active');
    if (connector) connector.classList.toggle('ms-connector--done', i < activeStep);
  }
}

// al cargar, muestra el paso que indicó PHP y oculta los demás
(function initStaffRecPage() {
  for (var i = 1; i <= MS_TOTAL_STEPS; i++) {
    var p = document.getElementById('ms-panel-' + i);
    if (p) p.classList.add('ms-panel--hidden');
  }
  var target = document.getElementById('ms-panel-' + MS_START_STEP);
  if (target) target.classList.remove('ms-panel--hidden');
  _staffActivePanel = MS_START_STEP;
  recStaffUpdateStepper(MS_START_STEP);
  // sincroniza el hidden con el paso inicial de PHP
  document.getElementById('rec-step-hidden').value = MS_START_STEP;
})();
</script>
<script src="script.js" defer></script>
</body>
</html>
