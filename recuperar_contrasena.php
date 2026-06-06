<?php
// ── Sesión SIEMPRE al inicio, antes de cualquier lógica ──
session_start();
require_once 'conexion.php';
$error     = '';
$success   = '';
$step      = intval($_POST['step'] ?? 1);
$startStep = 1;
// ════════════════════════════════════════
// PASO 1 → verificar correo o DNI
// ════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 1) {
    $identificador = trim($_POST['identificador'] ?? '');
    if (!$identificador) {
        $error     = 'Ingresa tu correo electrónico o DNI.';
        $startStep = 1;
    } else {
        $stmt = $conn->prepare(
            "SELECT idCliente, pregunta1, pregunta2, pregunta3
               FROM CLIENTE
              WHERE email = ? OR numDNI = ?
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
                $cliente = $res->fetch_assoc();
                // Limpiar datos anteriores por si se reintenta el flujo
                unset(
                    $_SESSION['recuperar_id'],
                    $_SESSION['recuperar_p1'],
                    $_SESSION['recuperar_p2'],
                    $_SESSION['recuperar_p3'],
                    $_SESSION['recuperar_ok']
                );
                $_SESSION['recuperar_id'] = (int) $cliente['idCliente'];
                $_SESSION['recuperar_p1'] = $cliente['pregunta1'];
                $_SESSION['recuperar_p2'] = $cliente['pregunta2'];
                $_SESSION['recuperar_p3'] = $cliente['pregunta3'];
                $startStep = 2;
            }
            $stmt->close();
        }
    }
}
// ════════════════════════════════════════
// PASO 2 → verificar respuestas
// ════════════════════════════════════════
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 2) {
    $r1 = trim($_POST['respuesta1'] ?? '');
    $r2 = trim($_POST['respuesta2'] ?? '');
    $r3 = trim($_POST['respuesta3'] ?? '');
    if (!$r1 || !$r2 || !$r3) {
        $error     = 'Responde las tres preguntas de seguridad.';
        $startStep = 2;
    } elseif (empty($_SESSION['recuperar_id'])) {
        $error     = 'Sesión expirada. Vuelve a empezar.';
        $startStep = 1;
    } else {
        $id   = (int) $_SESSION['recuperar_id'];
        $stmt = $conn->prepare(
            "SELECT respuesta1, respuesta2, respuesta3
               FROM CLIENTE WHERE idCliente = ? LIMIT 1"
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
                $_SESSION['recuperar_ok'] = true;
                $startStep = 3;
            }
        }
    }
}
// ════════════════════════════════════════
// PASO 3 → guardar nueva contraseña
// ════════════════════════════════════════
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 3) {
    $nueva   = $_POST['password'] ?? '';
    $confirm = $_POST['confirm']  ?? '';
    if (empty($_SESSION['recuperar_ok']) || empty($_SESSION['recuperar_id'])) {
        $error     = 'Sesión expirada. Vuelve a empezar.';
        $startStep = 1;
    } elseif (strlen($nueva) < 6) {
        $error     = 'La contraseña debe tener al menos 6 caracteres.';
        $startStep = 3;
    } elseif ($nueva !== $confirm) {
        $error     = 'Las contraseñas no coinciden.';
        $startStep = 3;
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $id   = (int) $_SESSION['recuperar_id'];
        $stmt = $conn->prepare("UPDATE CLIENTE SET password = ? WHERE idCliente = ?");
        if (!$stmt) {
            $error     = 'Error al guardar la contraseña. Inténtalo de nuevo.';
            $startStep = 3;
        } else {
            $stmt->bind_param("si", $hash, $id);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $stmt->close();
                // Limpiar datos de recuperación de la sesión
                unset(
                    $_SESSION['recuperar_id'],
                    $_SESSION['recuperar_p1'],
                    $_SESSION['recuperar_p2'],
                    $_SESSION['recuperar_p3'],
                    $_SESSION['recuperar_ok']
                );
                header("Location: login.php?recovered=1");
                exit;
            } else {
                $stmt->close();
                $error     = 'Error al guardar la contraseña. Inténtalo de nuevo.';
                $startStep = 3;
            }
        }
    }
}
// Leer preguntas de la sesión (disponibles desde el paso 2 en adelante)
$p1 = $_SESSION['recuperar_p1'] ?? '';
$p2 = $_SESSION['recuperar_p2'] ?? '';
$p3 = $_SESSION['recuperar_p3'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar contraseña — Morales Tech</title>
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
  <a href="login.php">Iniciar sesión</a>
  <a href="registro.php" class="mobile-cta">Crear cuenta →</a>
</div>
<!-- ══ CONTENIDO ══ -->
<div class="page-auth page-auth--registro">
  <div class="sphere-left"></div>
  <div class="sphere-right"></div>
  <div class="bg-noise"></div>
  <div class="auth-card auth-card--wide">
    <div class="auth-isotipo">
      <img src="img/isotipo-blanco.png" alt="Morales Tech"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
      <div class="auth-isotipo-fallback">M<span>T</span></div>
    </div>
    <h1 class="auth-heading">Recuperar contraseña</h1>
    <p class="auth-sub">Sigue los pasos para restablecer<br>el acceso a tu cuenta</p>
    <!-- ══ Stepper ══ -->
    <div class="ms-stepper" id="ms-stepper">
      <div class="ms-step ms-step--active" id="ms-dot-1">
        <div class="ms-step__dot">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
          </svg>
        </div>
        <span class="ms-step__label">Identificación</span>
      </div>
      <div class="ms-connector" id="ms-connector-1"></div>
      <div class="ms-step" id="ms-dot-2">
        <div class="ms-step__dot">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <span class="ms-step__label">Seguridad</span>
      </div>
      <div class="ms-connector" id="ms-connector-2"></div>
      <div class="ms-step" id="ms-dot-3">
        <div class="ms-step__dot">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
        <span class="ms-step__label">Nueva contraseña</span>
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
    <form method="POST" action="recuperar_contrasena.php" autocomplete="off"
          id="rec-form" onsubmit="recSyncStep()">
      <input type="hidden" name="step" id="rec-step-hidden" value="<?= $startStep ?>">
      <!-- ══════════ PASO 1 ══════════ -->
      <div class="ms-panel" id="ms-panel-1">
        <div class="section-divider">
          <div class="section-divider-line"></div>
          <span class="section-divider-label">Identifica tu cuenta</span>
          <div class="section-divider-line"></div>
        </div>
        <p class="ms-security-hint">Ingresa el correo electrónico o DNI asociado a tu cuenta.</p>
        <div class="auth-form-group compact">
          <label for="identificador">Correo electrónico o DNI <span class="label-required">*</span></label>
          <div class="auth-input-wrap">
            <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
            <input type="text" id="identificador" name="identificador"
                   placeholder="correo@ejemplo.com o 12345678"
                   value="<?= htmlspecialchars($_POST['identificador'] ?? '') ?>"
                   autocomplete="off">
          </div>
        </div>
        <button type="submit" class="btn-auth-submit auth-btn-submit-registro">
          Continuar
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
          </svg>
        </button>
      </div><!-- /ms-panel-1 -->
      <!-- ══════════ PASO 2 ══════════ -->
      <!--
        FIX: Los inputs NO llevan "required" porque el navegador intenta
        enfocarlos aunque el panel esté oculto (ms-panel--hidden), lo que
        lanza el error "invalid form control with name='respuestaX' is not
        focusable". La validación de que no estén vacíos la hace PHP en el
        servidor (bloque PASO 2 arriba).
      -->
      <div class="ms-panel ms-panel--hidden" id="ms-panel-2">
        <div class="section-divider">
          <div class="section-divider-line"></div>
          <span class="section-divider-label">Preguntas de seguridad</span>
          <div class="section-divider-line"></div>
        </div>
        <p class="ms-security-hint">Responde correctamente las tres preguntas que configuraste al registrarte.</p>
        <?php
        $pArr = [$p1, $p2, $p3];
        for ($i = 1; $i <= 3; $i++):
            $pregunta = $pArr[$i - 1] ?: "Pregunta de seguridad $i";
        ?>
        <div class="ms-sq-block">
          <div class="ms-sq-num"><?= $i ?></div>
          <div class="ms-sq-fields">
            <div class="auth-form-group compact">
              <label><?= htmlspecialchars($pregunta) ?></label>
              <div class="auth-input-wrap">
                <svg class="auth-input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <!-- SIN "required": evita el error de consola en paneles ocultos -->
                <input type="text" id="respuesta<?= $i ?>" name="respuesta<?= $i ?>"
                       placeholder="Tu respuesta secreta"
                       autocomplete="off">
              </div>
            </div>
          </div>
        </div>
        <?php endfor; ?>
        <div class="ms-btn-row">
          <button type="button" class="ms-btn-back" onclick="recBack(1)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Anterior
          </button>
          <button type="submit" class="btn-auth-submit auth-btn-submit-registro ms-btn-submit">
            Verificar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
          </button>
        </div>
      </div><!-- /ms-panel-2 -->
      <!-- ══════════ PASO 3 ══════════ -->
      <div class="ms-panel ms-panel--hidden" id="ms-panel-3">
        <div class="section-divider">
          <div class="section-divider-line"></div>
          <span class="section-divider-label">Nueva contraseña</span>
          <div class="section-divider-line"></div>
        </div>
        <div class="form-grid-2col">
          <!-- Nueva contraseña -->
          <div class="auth-form-group compact">
            <label for="password">Nueva contraseña <span class="label-required">*</span></label>
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
        <div class="ms-btn-row">
          <button type="button" class="ms-btn-back" onclick="recBack(2)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Anterior
          </button>
          <button type="submit" class="btn-auth-submit auth-btn-submit-registro ms-btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Guardar contraseña
          </button>
        </div>
      </div><!-- /ms-panel-3 -->
    </form>
    <div class="auth-divider"><span>o</span></div>
    <div class="auth-card-footer">
      ¿Recordaste tu contraseña? <a href="login.php">Inicia sesión aquí</a>
    </div>
  </div>
</div>
<script>
/* ─────────────────────────────────────────────────────────────
   RECUPERAR CONTRASEÑA — CLIENTES
   ─────────────────────────────────────────────────────────── */
var MS_START_STEP  = <?= (int)$startStep ?>;
var MS_TOTAL_STEPS = 3;

/* Panel actualmente visible (se mantiene sincronizado) */
var _recActivePanel = MS_START_STEP;

/* ── Sincroniza el campo hidden con el panel visible antes de enviar ── */
function recSyncStep() {
  document.getElementById('rec-step-hidden').value = _recActivePanel;
}

/* ── Muestra un panel y oculta los demás; actualiza stepper ── */
function recShowPanel(n) {
  for (var i = 1; i <= MS_TOTAL_STEPS; i++) {
    var panel = document.getElementById('ms-panel-' + i);
    if (panel) {
      if (i === n) {
        panel.classList.remove('ms-panel--hidden');
      } else {
        panel.classList.add('ms-panel--hidden');
      }
    }
    /* Actualizar dots del stepper */
    var dot = document.getElementById('ms-dot-' + i);
    if (dot) {
      dot.classList.toggle('ms-step--active',    i === n);
      dot.classList.toggle('ms-step--completed', i < n);
    }
    /* Actualizar conectores */
    if (i < MS_TOTAL_STEPS) {
      var conn = document.getElementById('ms-connector-' + i);
      if (conn) { conn.classList.toggle('ms-connector--active', i < n); }
    }
  }
  _recActivePanel = n;
  /* Mantener el hidden sincronizado por si el usuario usa Enter */
  document.getElementById('rec-step-hidden').value = n;
}

/* ── Botón "Anterior" ── */
function recBack(toStep) {
  recShowPanel(toStep);
}

/* ── Al cargar la página, muestra el panel que PHP indica ── */
document.addEventListener('DOMContentLoaded', function () {
  recShowPanel(MS_START_STEP);
});
</script>
<script src="script.js" defer></script>
</body>
</html>