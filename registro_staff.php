<?php
// session_start();
$error   = '';
$success = false;
$old     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'nombres'   => trim($_POST['nombres']   ?? ''),
        'apellidos' => trim($_POST['apellidos'] ?? ''),
        'email'     => trim($_POST['email']     ?? ''),
        'dni'       => trim($_POST['dni']       ?? ''),
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
    } else {
        // Aquí iría la inserción en BD
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de colaborador — Morales Tech</title>
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

        /* ══ LAYOUT 50-50 — formulario a la IZQUIERDA ══ */
        .page-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════════
           PANEL IZQUIERDO — blanco, formulario
        ══════════════════════════════════════════ */
        .panel-left {
            background: var(--white);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 48px 48px;
            position: relative;
        }
        .form-wrap { width: 100%; max-width: 380px; }

        /* Isotipo → index.php */
        .form-isotipo {
            display: flex; justify-content: center; margin-bottom: 22px;
        }
        .form-isotipo a {
            display: inline-flex; border-radius: 16px;
            transition: transform .18s, opacity .18s;
        }
        .form-isotipo a:hover { transform: scale(1.07); opacity: .85; }
        .form-isotipo img {
            height: 48px; width: auto;
            filter: drop-shadow(0 4px 18px rgba(23,70,234,.22));
        }
        .form-isotipo-fallback {
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(135deg, var(--azul), var(--celeste));
            display: none; align-items: center; justify-content: center;
            font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 900; color: #fff;
        }

        .form-heading {
            font-family: 'Montserrat', sans-serif;
            font-size: 22px; font-weight: 800; letter-spacing: -.4px;
            color: var(--navy); text-align: center; margin-bottom: 5px;
        }
        .form-sub {
            font-size: 13px; color: var(--gray-400);
            text-align: center; line-height: 1.65; margin-bottom: 24px;
        }

        /* Alertas */
        .alert-error {
            display: flex; align-items: flex-start; gap: 9px;
            background: #fff5f3; border: 1px solid #fbc8bd;
            border-radius: 12px; padding: 12px 16px;
            font-size: 12px; font-weight: 600; color: #b03020;
            margin-bottom: 16px; line-height: 1.5;
        }
        .alert-error svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }
        .alert-success {
            display: flex; align-items: flex-start; gap: 9px;
            background: #f0fdf4; border: 1px solid #86efac;
            border-radius: 12px; padding: 12px 16px;
            font-size: 12px; font-weight: 600; color: #166534;
            margin-bottom: 16px; line-height: 1.5;
        }
        .alert-success svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }

        /* Fila de dos campos */
        .form-row {
            display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
            margin-bottom: 14px;
        }
        .form-group { display: flex; flex-direction: column; gap: 7px; margin-bottom: 14px; }
        .form-group.no-mb { margin-bottom: 0; }
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
            width: 100%; padding: 12px 16px 12px 44px;
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

        /* Hint dominio */
        .domain-hint {
            display: none; align-items: center; gap: 7px;
            background: #fffbea; border: 1px solid #f5d97a;
            border-radius: 10px; padding: 9px 14px;
            font-size: 12px; font-weight: 600; color: #7a5c00;
            margin-top: 7px; line-height: 1.4;
        }
        .domain-hint.visible { display: flex; }
        .domain-hint svg { width: 13px; height: 13px; flex-shrink: 0; color: #c49a00; }

        /* Toggle password */
        .pw-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 4px;
            color: var(--gray-400); z-index: 3; transition: color .2s;
        }
        .pw-toggle:hover { color: var(--azul); }
        .pw-toggle svg { width: 15px; height: 15px; display: block; }

        /* Indicador de fuerza de contraseña */
        .pw-strength {
            display: flex; gap: 5px; margin-top: 8px; align-items: center;
        }
        .pw-strength__bars { display: flex; gap: 4px; flex: 1; }
        .pw-strength__bar {
            flex: 1; height: 3px; border-radius: 4px;
            background: var(--border);
            transition: background .25s;
        }
        .pw-strength__label {
            font-size: 11px; font-weight: 700; color: var(--gray-400);
            white-space: nowrap; min-width: 60px; text-align: right;
            transition: color .25s;
        }
        .strength-1 .pw-strength__bar:nth-child(1) { background: #e05040; }
        .strength-2 .pw-strength__bar:nth-child(-n+2) { background: #f59e0b; }
        .strength-3 .pw-strength__bar:nth-child(-n+3) { background: #3b82f6; }
        .strength-4 .pw-strength__bar { background: #22c55e; }

        /* Botón submit */
        .btn-submit {
            width: 100%; margin-top: 6px;
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

        /* Link volver al login */
        .back-link {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            margin-top: 18px; font-size: 12px; color: var(--gray-400);
            font-weight: 600; transition: color .15s;
        }
        .back-link svg { width: 13px; height: 13px; }
        .back-link:hover { color: var(--azul); }

        /* ══════════════════════════════════════════
           PANEL DERECHO — decorativo oscuro
        ══════════════════════════════════════════ */
        .panel-right {
            background: linear-gradient(145deg, var(--navy) 0%, #060626 50%, #0c1660 100%);
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 52px 56px;
            position: relative; overflow: hidden;
        }
        .panel-right::before {
            content: '';
            position: absolute; bottom: -180px; right: -140px;
            width: 560px; height: 560px; border-radius: 50%;
            background: radial-gradient(circle,
                rgba(23,70,234,.55) 0%, rgba(24,131,237,.28) 40%, transparent 70%);
            filter: blur(44px); pointer-events: none;
        }
        .panel-right::after {
            content: '';
            position: absolute; top: -100px; left: -100px;
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
            background: #7aaaff;
            box-shadow: 0 0 0 3px rgba(122,170,255,.25);
            animation: pulse 2s ease-in-out infinite;
            flex-shrink: 0;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 3px rgba(122,170,255,.25); }
            50%       { box-shadow: 0 0 0 7px rgba(122,170,255,.09); }
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

        /* Pasos / checklist de onboarding */
        .onboarding-steps { display: flex; flex-direction: column; gap: 16px; }
        .onboarding-step {
            display: flex; align-items: flex-start; gap: 14px;
        }
        .step-num {
            width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14);
            display: grid; place-items: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px; font-weight: 800; color: #7aaaff;
        }
        .step-body { flex: 1; }
        .step-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px; font-weight: 700; color: #fff;
            margin-bottom: 3px;
        }
        .step-desc { font-size: 12px; color: rgba(255,255,255,.44); line-height: 1.6; }

        .panel-footer {
            position: relative; z-index: 1;
            font-size: 12px; color: rgba(255,255,255,.26);
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 960px) {
            .page-wrap { grid-template-columns: 1fr; }
            .panel-right { display: none; }
            .panel-left { border-right: none; background: var(--gray-50); padding: 48px 24px; }
        }
        @media (max-width: 480px) {
            .panel-left { padding: 36px 16px; }
            .form-wrap { max-width: 100%; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="page-wrap">

    <!-- ══ PANEL IZQUIERDO — FORMULARIO ══ -->
    <div class="panel-left">
        <div class="form-wrap">

            <!-- Isotipo → index.php -->
            <div class="form-isotipo">
                <a href="index.php" title="Ir al inicio">
                    <img src="img/isotipo-color.png" alt="Morales Tech"
                         onerror="this.style.display='none';this.parentElement.nextElementSibling.style.display='flex'">
                </a>
                <div class="form-isotipo-fallback">MT</div>
            </div>

            <h1 class="form-heading">Crear cuenta de colaborador</h1>
            <p class="form-sub">Completa los datos para solicitar acceso<br>al sistema interno de Morales Tech</p>

            <?php if ($success): ?>
            <div class="alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Solicitud enviada correctamente. El administrador activará tu cuenta en breve.
            </div>
            <?php elseif ($error): ?>
            <div class="alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" action="registro_staff.php" autocomplete="off" id="reg-form">

                <!-- Nombres y Apellidos en fila -->
                <div class="form-row">
                    <div class="form-group no-mb">
                        <label for="nombres">Nombres</label>
                        <div class="input-wrap">
                            <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" id="nombres" name="nombres"
                                   placeholder="Juan"
                                   value="<?= htmlspecialchars($old['nombres'] ?? '') ?>"
                                   required autocomplete="given-name">
                        </div>
                    </div>
                    <div class="form-group no-mb">
                        <label for="apellidos">Apellidos</label>
                        <div class="input-wrap">
                            <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" id="apellidos" name="apellidos"
                                   placeholder="Pérez García"
                                   value="<?= htmlspecialchars($old['apellidos'] ?? '') ?>"
                                   required autocomplete="family-name">
                        </div>
                    </div>
                </div>

                <!-- Correo corporativo -->
                <div class="form-group">
                    <label for="email">Correo institucional</label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" id="email" name="email"
                               placeholder="tucorreo@moralestechs.com"
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                               required autocomplete="email"
                               oninput="checkDomain(this)">
                    </div>
                    <div class="domain-hint" id="domain-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Usa tu correo corporativo institucional para registrarte.
                    </div>
                </div>

                <!-- DNI -->
                <div class="form-group">
                    <label for="dni">DNI</label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        <input type="text" id="dni" name="dni"
                               placeholder="12345678"
                               value="<?= htmlspecialchars($old['dni'] ?? '') ?>"
                               required maxlength="8"
                               inputmode="numeric" pattern="\d{8}"
                               oninput="this.value=this.value.replace(/\D/g,'').slice(0,8)">
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="password" name="password"
                               placeholder="Mín. 8 caracteres"
                               required autocomplete="new-password"
                               oninput="updateStrength(this.value)">
                        <button type="button" class="pw-toggle" id="pw-toggle" tabindex="-1" aria-label="Mostrar contraseña">
                            <svg id="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye-hide" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <!-- Indicador fuerza -->
                    <div class="pw-strength" id="pw-strength" style="display:none">
                        <div class="pw-strength__bars">
                            <div class="pw-strength__bar"></div>
                            <div class="pw-strength__bar"></div>
                            <div class="pw-strength__bar"></div>
                            <div class="pw-strength__bar"></div>
                        </div>
                        <span class="pw-strength__label" id="pw-strength-label">Débil</span>
                    </div>
                </div>

                <!-- Confirmar contraseña -->
                <div class="form-group">
                    <label for="password2">Confirmar contraseña</label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="password2" name="password2"
                               placeholder="Repite tu contraseña"
                               required autocomplete="new-password"
                               oninput="checkMatch()">
                        <button type="button" class="pw-toggle" id="pw-toggle2" tabindex="-1" aria-label="Mostrar contraseña">
                            <svg id="eye-show2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye-hide2" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    Crear cuenta
                </button>
            </form>
            <?php endif; ?>

            <!-- Volver al login -->
            <a href="login_staff.php" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver al inicio de sesión
            </a>
        </div>
    </div>

    <!-- ══ PANEL DERECHO — decorativo ══ -->
    <div class="panel-right">
        <div class="panel-dots"></div>

        <!-- Logo blanco -->
        <div class="panel-brand" style="position:relative;z-index:1">
            <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
            <span class="panel-brand-fallback">Morales Tech</span>
        </div>

        <!-- Centro -->
        <div class="panel-center">
            <div class="panel-badge">Nuevo colaborador</div>
            <h2 class="panel-title">
                Únete al<br>
                equipo <em>interno</em><br>
                Morales Tech
            </h2>
            <p class="panel-desc">
                Tu cuenta será revisada por el administrador. Una vez aprobada, tendrás acceso al sistema de gestión según tu rol asignado.
            </p>

            <!-- Pasos de onboarding -->
            <div class="onboarding-steps">
                <div class="onboarding-step">
                    <div class="step-num">1</div>
                    <div class="step-body">
                        <div class="step-title">Completa el formulario</div>
                        <div class="step-desc">Ingresa tus datos personales y tu correo corporativo asignado.</div>
                    </div>
                </div>
                <div class="onboarding-step">
                    <div class="step-num">2</div>
                    <div class="step-body">
                        <div class="step-title">Revisión del administrador</div>
                        <div class="step-desc">El área de TI verificará tus datos y activará tu acceso.</div>
                    </div>
                </div>
                <div class="onboarding-step">
                    <div class="step-num">3</div>
                    <div class="step-body">
                        <div class="step-title">Accede al sistema</div>
                        <div class="step-desc">Recibirás confirmación y podrás ingresar con tus credenciales.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer" style="position:relative;z-index:1">
            © 2026 Morales Tech · Sistema interno · Acceso restringido
        </div>
    </div>

</div><!-- /page-wrap -->

<script>
    /* ── Toggle contraseña #1 ── */
    const pwToggle  = document.getElementById('pw-toggle');
    const pwInput   = document.getElementById('password');
    const eyeShow   = document.getElementById('eye-show');
    const eyeHide   = document.getElementById('eye-hide');
    pwToggle.addEventListener('click', () => {
        const show = pwInput.type === 'password';
        pwInput.type          = show ? 'text'  : 'password';
        eyeShow.style.display = show ? 'none'  : 'block';
        eyeHide.style.display = show ? 'block' : 'none';
    });

    /* ── Toggle contraseña #2 ── */
    const pwToggle2  = document.getElementById('pw-toggle2');
    const pwInput2   = document.getElementById('password2');
    const eyeShow2   = document.getElementById('eye-show2');
    const eyeHide2   = document.getElementById('eye-hide2');
    pwToggle2.addEventListener('click', () => {
        const show = pwInput2.type === 'password';
        pwInput2.type          = show ? 'text'  : 'password';
        eyeShow2.style.display = show ? 'none'  : 'block';
        eyeHide2.style.display = show ? 'block' : 'none';
    });

    /* ── Validación de dominio ── */
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
    emailEl.addEventListener('blur', function () {
        if (!this.value.includes('@')) return;
        const domain = this.value.split('@')[1] || '';
        if (domain && domain !== ALLOWED) {
            hint.classList.add('visible');
            this.classList.add('input-error');
        }
    });

    /* ── Indicador fuerza de contraseña ── */
    const strengthWrap  = document.getElementById('pw-strength');
    const strengthLabel = document.getElementById('pw-strength-label');
    const strengthLevels = ['', 'Débil', 'Regular', 'Buena', 'Segura'];
    function updateStrength(val) {
        if (!val) { strengthWrap.style.display = 'none'; return; }
        strengthWrap.style.display = 'flex';
        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        strengthWrap.className = 'pw-strength strength-' + score;
        strengthLabel.textContent = strengthLevels[score] || 'Débil';
        const colors = ['', '#e05040', '#f59e0b', '#3b82f6', '#22c55e'];
        strengthLabel.style.color = colors[score] || '#9aa2bf';
    }

    /* ── Coincidencia de contraseñas ── */
    function checkMatch() {
        const p1 = pwInput.value;
        const p2 = pwInput2.value;
        if (p2.length === 0) { pwInput2.classList.remove('input-error'); return; }
        pwInput2.classList.toggle('input-error', p1 !== p2);
    }
</script>
</body>
</html>