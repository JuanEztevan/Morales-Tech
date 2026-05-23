<?php
// session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if (!str_ends_with($email, '@moralestechs.com')) {
        $error = 'Ingresa tu correo corporativo institucional para continuar.';
    } elseif ($email === 'demo@moralestechs.com' && $pass === 'demo') {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Credenciales incorrectas. Verifica tus datos e intenta de nuevo.';
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:    #000019;
            --azul:    #1746EA;
            --celeste: #1883ED;
            --white:   #ffffff;
            --gray-50: #f7f8ff;
            --gray-100:#eef0fb;
            --gray-400:#9aa2bf;
            --gray-600:#5a6380;
            --border:  #dde1f0;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--navy);
            min-height: 100vh;
            overflow-x: hidden;
        }
        a { text-decoration: none; color: inherit; }
        /* ══ LAYOUT 50-50 ══ */
        .page-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }
        /* ══════════════════════════════════════════
           PANEL IZQUIERDO
        ══════════════════════════════════════════ */
        .panel-left {
            background: linear-gradient(145deg, var(--navy) 0%, #060626 50%, #0c1660 100%);
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 52px 56px;
            position: relative; overflow: hidden;
        }
        .panel-left::before {
            content: '';
            position: absolute; bottom: -180px; left: -140px;
            width: 560px; height: 560px; border-radius: 50%;
            background: radial-gradient(circle,
                rgba(23,70,234,.55) 0%, rgba(24,131,237,.28) 40%, transparent 70%);
            filter: blur(44px); pointer-events: none;
        }
        .panel-left::after {
            content: '';
            position: absolute; top: -100px; right: -100px;
            width: 340px; height: 340px; border-radius: 50%;
            background: radial-gradient(circle, rgba(24,131,237,.32) 0%, transparent 68%);
            filter: blur(32px); pointer-events: none;
        }
        .panel-dots {
            position: absolute; inset: 0; pointer-events: none;
            background-image: radial-gradient(circle, rgba(255,255,255,.052) 1px, transparent 1px);
            background-size: 36px 36px;
        }
        .panel-brand { position: relative; z-index: 1; }
        .panel-brand img { height: 34px; width: auto; }
        .panel-brand-fallback {
            font-family: 'Montserrat', sans-serif;
            font-size: 20px; font-weight: 900; color: #fff; letter-spacing: -.3px;
            display: none;
        }
        .panel-center { position: relative; z-index: 1; }
        .panel-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.09); border: 1px solid rgba(255,255,255,.16);
            border-radius: 50px; padding: 7px 18px;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px; font-weight: 700; letter-spacing: .09em;
            text-transform: uppercase; color: rgba(255,255,255,.78);
            margin-bottom: 28px;
        }
        .panel-badge::before {
            content: ''; width: 7px; height: 7px; border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 0 3px rgba(74,222,128,.25);
            animation: pulse 2s ease-in-out infinite;
            flex-shrink: 0;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 3px rgba(74,222,128,.25); }
            50%       { box-shadow: 0 0 0 7px rgba(74,222,128,.09); }
        }
        .panel-title {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(26px, 2.4vw, 36px);
            font-weight: 600; line-height: 1.15;
            letter-spacing: -.5px; color: #fff;
            margin-bottom: 20px;
        }
        .panel-title em { font-style: normal; color: #7aaaff; }
        .panel-desc {
            font-size: 15px; color: rgba(255,255,255,.52);
            line-height: 1.8; margin-bottom: 44px; max-width: 380px;
        }
        .panel-stats { display: flex; gap: 28px; align-items: center; }
        .panel-stat { display: flex; flex-direction: column; gap: 4px; }
        .panel-stat__val {
            font-family: 'Montserrat', sans-serif;
            font-size: 24px; font-weight: 900; color: #fff; line-height: 1;
        }
        .panel-stat__lbl {
            font-size: 10px; font-weight: 700; color: rgba(255,255,255,.38);
            text-transform: uppercase; letter-spacing: .08em;
        }
        .stat-sep { width: 1px; height: 36px; background: rgba(255,255,255,.12); }
        .panel-footer {
            position: relative; z-index: 1;
            font-size: 12px; color: rgba(255,255,255,.26);
        }
        /* ══════════════════════════════════════════
           PANEL DERECHO
        ══════════════════════════════════════════ */
        .panel-right {
            background: var(--white);
            border-left: 1px solid var(--border);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 52px 48px;
            position: relative;
        }
        .form-wrap { width: 100%; max-width: 360px; }

        /* Isotipo — ahora es un link a index.php */
        .form-isotipo {
            display: flex; justify-content: center; margin-bottom: 26px;
        }
        .form-isotipo a {
            display: inline-flex;
            border-radius: 16px;
            transition: transform .18s, opacity .18s;
        }
        .form-isotipo a:hover { transform: scale(1.07); opacity: .85; }
        .form-isotipo img {
            height: 54px; width: auto;
            filter: drop-shadow(0 4px 18px rgba(23,70,234,.22));
        }
        .form-isotipo-fallback {
            width: 54px; height: 54px; border-radius: 14px;
            background: linear-gradient(135deg, var(--azul), var(--celeste));
            display: none; align-items: center; justify-content: center;
            font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 900; color: #fff;
        }
        .form-heading {
            font-family: 'Montserrat', sans-serif;
            font-size: 23px; font-weight: 800; letter-spacing: -.4px;
            color: var(--navy); text-align: center; margin-bottom: 6px;
        }
        .form-sub {
            font-size: 13px; color: var(--gray-400);
            text-align: center; line-height: 1.65; margin-bottom: 28px;
        }
        /* Alerta error */
        .alert-error {
            display: flex; align-items: flex-start; gap: 9px;
            background: #fff5f3; border: 1px solid #fbc8bd;
            border-radius: 12px; padding: 12px 16px;
            font-size: 12px; font-weight: 600; color: #b03020;
            margin-bottom: 16px; line-height: 1.5;
        }
        .alert-error svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }
        /* Alerta inline dominio */
        .domain-hint {
            display: none;
            align-items: center; gap: 7px;
            background: #fffbea; border: 1px solid #f5d97a;
            border-radius: 10px; padding: 9px 14px;
            font-size: 12px; font-weight: 600; color: #7a5c00;
            margin-top: 7px; line-height: 1.4;
        }
        .domain-hint.visible { display: flex; }
        .domain-hint svg { width: 13px; height: 13px; flex-shrink: 0; color: #c49a00; }
        /* Form groups */
        .form-group { display: flex; flex-direction: column; gap: 7px; margin-bottom: 14px; }
        .form-group label {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px; font-weight: 800; letter-spacing: .06em;
            text-transform: uppercase; color: var(--gray-600);
        }
        .input-wrap { position: relative; }
        .input-ico {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            width: 15px; height: 15px; pointer-events: none; z-index: 2;
            color: var(--gray-400); transition: color .2s;
        }
        .input-wrap:focus-within .input-ico { color: var(--azul); }
        .input-wrap input {
            font-family: 'Plus Jakarta Sans', sans-serif;
            width: 100%; padding: 13px 16px 13px 44px;
            background: var(--gray-50); border: 1.5px solid var(--border);
            border-radius: 50px;
            color: var(--navy); font-size: 14px; font-weight: 500;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
            -webkit-appearance: none; appearance: none;
        }
        .input-wrap input::placeholder { color: var(--gray-400); }
        .input-wrap input:focus {
            border-color: var(--azul); background: var(--white);
            box-shadow: 0 0 0 3px rgba(23,70,234,.10);
        }
        .input-wrap input.input-error {
            border-color: #e05040; background: #fff8f7;
            box-shadow: 0 0 0 3px rgba(224,80,64,.08);
        }
        /* Toggle password */
        .pw-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 4px;
            color: var(--gray-400); z-index: 3; transition: color .2s;
        }
        .pw-toggle:hover { color: var(--azul); }
        .pw-toggle svg { width: 15px; height: 15px; display: block; }
        /* Botón submit principal */
        .btn-submit {
            width: 100%; margin-top: 8px;
            padding: 14px; border-radius: 50px; border: none;
            background: linear-gradient(135deg, var(--azul), var(--celeste));
            color: #fff;
            font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 800;
            cursor: pointer; letter-spacing: .02em;
            box-shadow: 0 6px 24px rgba(23,70,234,.32);
            transition: transform .18s, box-shadow .18s;
            display: flex; align-items: center; justify-content: center; gap: 9px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(23,70,234,.45); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit svg { width: 15px; height: 15px; }

        /* ── Divisor entre botones ── */
        .btn-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 14px 0;
        }
        .btn-divider::before,
        .btn-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }
        .btn-divider span {
            font-size: 11px; font-weight: 700; color: var(--gray-400);
            letter-spacing: .06em; text-transform: uppercase; white-space: nowrap;
        }

        /* ── Botón registrarse (outline) ── */
        .btn-register {
            width: 100%;
            padding: 13px; border-radius: 50px;
            border: 1.5px solid var(--border);
            background: var(--white);
            color: var(--navy);
            font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700;
            cursor: pointer; letter-spacing: .02em;
            transition: border-color .18s, background .18s, transform .18s, color .18s;
            display: flex; align-items: center; justify-content: center; gap: 9px;
            text-decoration: none;
        }
        .btn-register:hover {
            border-color: var(--azul); color: var(--azul);
            background: rgba(23,70,234,.04);
            transform: translateY(-1px);
        }
        .btn-register:active { transform: translateY(0); }
        .btn-register svg { width: 15px; height: 15px; }

        /* Nota TI */
        .ti-note {
            margin-top: 20px;
            background: var(--gray-50); border: 1px solid var(--border);
            border-radius: 14px; padding: 16px 18px;
            display: flex; align-items: flex-start; gap: 12px;
        }
        .ti-note__icon {
            width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
            background: rgba(23,70,234,.08); display: grid; place-items: center;
        }
        .ti-note__icon svg { width: 14px; height: 14px; color: var(--azul); }
        .ti-note__body { flex: 1; }
        .ti-note__title {
            font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 800;
            color: var(--navy); letter-spacing: .02em; margin-bottom: 4px;
        }
        .ti-note__text { font-size: 12px; color: var(--gray-600); line-height: 1.65; }
        .ti-note__text a {
            color: var(--azul); font-weight: 700;
            border-bottom: 1px solid rgba(23,70,234,.20); transition: border-color .15s;
        }
        .ti-note__text a:hover { border-color: var(--azul); }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 900px) {
            .page-wrap { grid-template-columns: 1fr; }
            .panel-left { display: none; }
            .panel-right { border-left: none; background: var(--gray-50); padding: 48px 24px; }
        }
        @media (max-width: 480px) {
            .panel-right { padding: 36px 16px; }
            .form-wrap { max-width: 100%; }
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <!-- ══ PANEL IZQUIERDO ══ -->
    <div class="panel-left">
        <div class="panel-dots"></div>
        <div class="panel-brand">
            <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
            <span class="panel-brand-fallback">Morales Tech</span>
        </div>
        <div class="panel-center">
            <div class="panel-badge">Portal exclusivo de colaboradores</div>
            <h1 class="panel-title">
                Panel de<br>
                gestión <em>interna</em><br>
                Morales Tech
            </h1>
            <p class="panel-desc">
                Accede al sistema de tickets, inventario, ventas y atención al cliente desde tu cuenta corporativa. Solo para colaboradores autorizados.
            </p>
            <div class="panel-stats">
                <div class="panel-stat">
                    <span class="panel-stat__val">4</span>
                    <span class="panel-stat__lbl">Módulos</span>
                </div>
                <div class="stat-sep"></div>
                <div class="panel-stat">
                    <span class="panel-stat__val">24/7</span>
                    <span class="panel-stat__lbl">Disponible</span>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            © 2026 Morales Tech · Sistema interno · Acceso restringido
        </div>
    </div>

    <!-- ══ PANEL DERECHO ══ -->
    <div class="panel-right">
        <div class="form-wrap">
            <!-- Isotipo → link a index.php -->
            <div class="form-isotipo">
                <a href="index.php" title="Ir al inicio">
                    <img src="img/isotipo-color.png" alt="Morales Tech"
                         onerror="this.style.display='none';this.parentElement.nextElementSibling.style.display='flex'">
                </a>
                <div class="form-isotipo-fallback">MT</div>
            </div>

            <h1 class="form-heading">Acceso para staff</h1>
            <p class="form-sub">Ingresa con tu cuenta corporativa<br>asignada por Morales Tech</p>

            <!-- Error PHP -->
            <?php if ($error): ?>
            <div class="alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="login_staff.php" autocomplete="off" id="staff-form">
                <!-- Correo corporativo -->
                <div class="form-group">
                    <label for="email">Correo institucional</label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" id="email" name="email"
                               placeholder="tucorreo@empresa.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               required autocomplete="username"
                               oninput="checkDomain(this)">
                    </div>
                    <div class="domain-hint" id="domain-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Usa tu correo corporativo institucional para ingresar.
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               required autocomplete="current-password">
                        <button type="button" class="pw-toggle" id="pw-toggle" tabindex="-1" aria-label="Mostrar contraseña">
                            <svg id="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye-hide" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Ingresar al sistema
                </button>
            </form>

            <!-- Divisor -->
            <div class="btn-divider"><span>¿Eres nuevo?</span></div>

            <!-- Botón Registrarse -->
            <a href="registro_staff.php" class="btn-register">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Crear cuenta de colaborador
            </a>

            <!-- Nota TI -->
            <div class="ti-note">
                <div class="ti-note__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="ti-note__body">
                    <div class="ti-note__title">¿Olvidaste tu contraseña?</div>
                    <div class="ti-note__text">
                        Las credenciales son gestionadas por el administrador del sistema. Comunícate con el área de TI en
                        <a href="mailto:ti@moralestechs.com">ti@moralestechs.com</a>
                        o acércate a soporte interno.
                    </div>
                </div>
            </div>
            <!-- "Volver al portal de clientes" eliminado -->
        </div>
    </div>
</div>

<script>
    /* ── Toggle contraseña ── */
    const pwToggle = document.getElementById('pw-toggle');
    const pwInput  = document.getElementById('password');
    const eyeShow  = document.getElementById('eye-show');
    const eyeHide  = document.getElementById('eye-hide');
    pwToggle.addEventListener('click', () => {
        const show = pwInput.type === 'password';
        pwInput.type          = show ? 'text'  : 'password';
        eyeShow.style.display = show ? 'none'  : 'block';
        eyeHide.style.display = show ? 'block' : 'none';
    });

    /* ── Validación de dominio en tiempo real ── */
    const ALLOWED = 'moralestechs.com';
    const hint    = document.getElementById('domain-hint');
    const emailEl = document.getElementById('email');
    function checkDomain(input) {
        const val = input.value;
        if (!val.includes('@')) {
            hint.classList.remove('visible');
            input.classList.remove('input-error');
            return;
        }
        const domain = val.split('@')[1] || '';
        const ok = domain === '' || domain === ALLOWED || ALLOWED.startsWith(domain);
        hint.classList.toggle('visible', !ok && domain !== '');
        input.classList.toggle('input-error', !ok && domain !== '');
    }
    emailEl.addEventListener('blur', function() {
        if (!this.value.includes('@')) return;
        const domain = this.value.split('@')[1] || '';
        if (domain && domain !== ALLOWED) {
            hint.classList.add('visible');
            this.classList.add('input-error');
        }
    });
</script>
</body>
</html>