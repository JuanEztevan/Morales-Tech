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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --azul:     #1746EA;
            --azul-card:#184BEB;
            --celeste:  #1883ED;
            --negro:    #000019;
            --blanco:   #ffffff;
            --surface1: #05061a;
            --surface2: #0b0d22;
            --borde:    rgba(255,255,255,.07);
            --txt-main: #e8ebff;
            --txt-muted:#7a83b0;
            --grad:     linear-gradient(135deg, #1746EA 0%, #1883ED 100%);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--negro);
            color: var(--txt-main);
            min-height: 100vh;
            overflow-x: hidden;
        }
        a { text-decoration: none; color: inherit; }

        /* ══ NAVBAR — idéntico a login.php ══ */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(0,0,20,.80);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--borde);
            height: 68px;
            transition: box-shadow .25s, background .25s;
        }
        .navbar.scrolled { background: rgba(0,0,20,.95); box-shadow: 0 4px 40px rgba(0,0,0,.6); }
        .container { width: 100%; max-width: 1160px; margin-inline: auto; padding-inline: 24px; }
        .nav-inner { display: flex; align-items: center; height: 68px; }
        .nav-left, .nav-center, .nav-right { flex: 1; display: flex; align-items: center; }
        .nav-center { justify-content: center; }
        .nav-right  { justify-content: flex-end; gap: 10px; }
        .nav-logo img { height: 30px; width: auto; }
        .nav-logo-text { font-family: 'Montserrat',sans-serif; font-weight: 900; font-size: 18px; color: var(--txt-main); }
        .nav-logo-text span { color: var(--azul); }
        .nav-links {
            display: flex; align-items: center;
            background: rgba(255,255,255,.04); border-radius: 50px;
            padding: 4px 5px; gap: 2px; list-style: none; flex-shrink: 0;
            border: 1px solid var(--borde);
        }
        .nav-links li a {
            display: flex; align-items: center; gap: 6px; padding: 7px 16px;
            border-radius: 50px; font-family: 'Montserrat',sans-serif;
            font-size: 13px; font-weight: 600; color: var(--txt-muted);
            white-space: nowrap; transition: background .15s, color .15s;
        }
        .nav-links li a:hover { background: rgba(255,255,255,.07); color: #c5d4ff; }
        .btn-nav-login {
            display: flex; align-items: center; gap: 7px;
            font-family: 'Montserrat',sans-serif; font-size: 13px; font-weight: 700;
            padding: 9px 20px; border-radius: 50px;
            border: 1.5px solid rgba(23,70,234,.45);
            background: rgba(23,70,234,.14); color: #8db4ff;
            cursor: pointer; transition: all .18s; white-space: nowrap;
        }
        .btn-nav-login:hover { border-color: var(--azul); background: rgba(23,70,234,.30); color: #fff; transform: translateY(-1px); }
        .btn-nav-login svg { width: 14px; height: 14px; }
        .btn-nav-registro {
            display: flex; align-items: center; gap: 7px;
            font-family: 'Montserrat',sans-serif; font-size: 13px; font-weight: 700;
            padding: 9px 20px; border-radius: 50px;
            background: var(--grad); color: #fff;
            box-shadow: 0 4px 20px rgba(23,70,234,.4);
            cursor: pointer; transition: all .18s; white-space: nowrap;
        }
        .btn-nav-registro.active-nav,
        .btn-nav-registro:hover { transform: translateY(-1px); box-shadow: 0 6px 28px rgba(23,70,234,.55); }
        .btn-nav-registro svg { width: 13px; height: 13px; }
        .nav-hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 6px; }
        .nav-hamburger span { display: block; width: 22px; height: 2px; background: var(--txt-main); border-radius: 2px; transition: all .25s; }

        /* ══ FONDO ══ */
        .page-wrap {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding-top: 88px; padding-bottom: 40px;
            position: relative; overflow: hidden;
        }
        .sphere-left {
            position: absolute;
            width: 640px; height: 640px; border-radius: 50%;
            background: radial-gradient(circle at 60% 50%,
                rgba(23,70,234,.52) 0%, rgba(23,70,234,.38) 25%,
                rgba(24,131,237,.22) 50%, rgba(24,131,237,.07) 70%, transparent 85%);
            filter: blur(32px); pointer-events: none; z-index: 0;
            left: -280px; top: 50%; transform: translateY(-55%);
        }
        .sphere-right {
            position: absolute;
            width: 420px; height: 420px; border-radius: 50%;
            background: radial-gradient(circle at 40% 45%,
                rgba(24,131,237,.40) 0%, rgba(23,70,234,.25) 30%,
                rgba(23,70,234,.10) 60%, transparent 80%);
            filter: blur(28px); pointer-events: none; z-index: 0;
            right: -160px; bottom: 5%;
        }
        .bg-noise {
            position: absolute; inset: 0; z-index: 0; pointer-events: none;
            background-image: radial-gradient(circle, rgba(23,70,234,.06) 1px, transparent 1px);
            background-size: 48px 48px; opacity: .4;
        }

        /* ══ CARD ══ */
        .reg-card {
            position: relative; z-index: 1;
            width: 100%; max-width: 520px;
            background: var(--azul-card);
            border-radius: 26px;
            padding: 42px 40px 38px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,.10),
                0 28px 80px rgba(0,0,0,.65),
                0 0 80px rgba(23,70,234,.25);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        .reg-card::before {
            content: '';
            position: absolute; top: 0; left: 12%; right: 12%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), rgba(255,255,255,.55), rgba(255,255,255,.35), transparent);
            border-radius: 50%;
        }

        /* ══ ISOTIPO ══ */
        .card-isotipo { display: flex; justify-content: center; margin-bottom: 18px; }
        .card-isotipo img {
            height: 46px; width: auto;
            filter: drop-shadow(0 0 20px rgba(255,255,255,.30));
        }
        .card-isotipo-fallback {
            height: 46px; display: none; align-items: center;
            font-family: 'Montserrat',sans-serif; font-weight: 900; font-size: 24px; color: #fff;
        }
        .card-isotipo-fallback span { color: rgba(255,255,255,.6); }

        /* ══ ENCABEZADO ══ */
        .card-heading {
            text-align: center; margin-bottom: 4px;
            font-family: 'Montserrat',sans-serif;
            font-size: 21px; font-weight: 800; color: #fff; letter-spacing: -.3px;
        }
        .card-sub {
            text-align: center; font-size: 13px; font-weight: 500;
            color: rgba(255,255,255,.60); margin-bottom: 26px; line-height: 1.55;
        }

        /* ══ ERROR / ÉXITO ══ */
        .alert {
            display: flex; align-items: flex-start; gap: 9px;
            border-radius: 14px; padding: 12px 16px; margin-bottom: 18px;
            font-size: 12px; font-weight: 600; line-height: 1.5;
        }
        .alert svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }
        .alert--error {
            background: rgba(255,80,50,.18); border: 1px solid rgba(255,110,80,.35);
            color: #ffc2b0;
        }

        /* ══ SECCIÓN TÍTULO ══ */
        .section-divider {
            display: flex; align-items: center; gap: 10px; margin: 20px 0 16px;
        }
        .section-divider__line { flex: 1; height: 1px; background: rgba(255,255,255,.12); }
        .section-divider__label {
            font-family: 'Montserrat',sans-serif; font-size: 10px; font-weight: 800;
            letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.40);
            white-space: nowrap;
        }

        /* ══ GRID DE CAMPOS ══ */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 14px;
        }
        .span-2 { grid-column: span 2; }

        /* ══ FORM GROUP ══ */
        .form-group { display: flex; flex-direction: column; gap: 7px; }
        .form-group label {
            font-family: 'Montserrat',sans-serif;
            font-size: 10px; font-weight: 800; letter-spacing: .06em;
            text-transform: uppercase; color: rgba(255,255,255,.52);
            display: flex; align-items: center; gap: 5px;
        }
        .label-opt {
            font-size: 9px; font-weight: 600; letter-spacing: .02em;
            text-transform: none; color: rgba(255,255,255,.30);
            background: rgba(255,255,255,.08); border-radius: 50px;
            padding: 1px 7px;
        }
        .input-wrap { position: relative; }
        .input-ico {
            position: absolute; left: 17px; top: 50%; transform: translateY(-50%);
            width: 14px; height: 14px; pointer-events: none; z-index: 2;
            color: rgba(255,255,255,.40); transition: color .2s;
        }
        .input-wrap:focus-within .input-ico { color: rgba(255,255,255,.80); }
        .input-wrap input {
            font-family: 'Plus Jakarta Sans',sans-serif;
            width: 100%; padding: 12px 16px 12px 42px;
            background: rgba(255,255,255,.10);
            border: 1.5px solid rgba(255,255,255,.14);
            border-radius: 50px;
            color: #fff; font-size: 13px; font-weight: 500;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
            -webkit-appearance: none; appearance: none;
        }
        .input-wrap input::placeholder { color: rgba(255,255,255,.30); }
        .input-wrap input:focus {
            border-color: rgba(255,255,255,.40);
            background: rgba(255,255,255,.15);
            box-shadow: 0 0 0 3px rgba(255,255,255,.08);
        }
        /* Campo inválido */
        .input-wrap input.invalid {
            border-color: rgba(255,100,80,.55);
            background: rgba(255,80,50,.10);
        }

        /* Toggle password */
        .pw-toggle {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 4px;
            color: rgba(255,255,255,.35); z-index: 3; transition: color .2s;
        }
        .pw-toggle:hover { color: rgba(255,255,255,.75); }
        .pw-toggle svg { width: 14px; height: 14px; display: block; }

        /* Indicador fuerza de contraseña */
        .pw-strength {
            display: flex; gap: 5px; margin-top: 6px; padding: 0 4px;
        }
        .pw-strength__bar {
            flex: 1; height: 3px; border-radius: 3px;
            background: rgba(255,255,255,.12);
            transition: background .3s;
        }
        .pw-strength__bar.active-1 { background: #ff5f57; }
        .pw-strength__bar.active-2 { background: #f5a623; }
        .pw-strength__bar.active-3 { background: #28c840; }
        .pw-strength__bar.active-4 { background: #28c840; }
        .pw-strength__label {
            font-size: 10px; font-weight: 700; color: rgba(255,255,255,.35);
            padding: 0 4px; text-align: right; margin-top: 4px;
            font-family: 'Montserrat',sans-serif;
            transition: color .3s;
        }

        /* ══ TÉRMINOS ══ */
        .terms-row {
            display: flex; align-items: flex-start; gap: 10px;
            margin-top: 18px; margin-bottom: 6px;
        }
        .terms-row input[type="checkbox"] {
            width: 16px; height: 16px; min-width: 16px;
            margin-top: 1px; accent-color: #fff;
            cursor: pointer;
        }
        .terms-row label {
            font-size: 12px; color: rgba(255,255,255,.50); line-height: 1.55; cursor: pointer;
        }
        .terms-row label a { color: rgba(255,255,255,.80); font-weight: 700; border-bottom: 1px solid rgba(255,255,255,.20); }
        .terms-row label a:hover { color: #fff; }

        /* ══ BTN SUBMIT ══ */
        .btn-submit {
            width: 100%; margin-top: 18px;
            padding: 14px; border-radius: 50px; border: none;
            background: #fff; color: var(--azul-card);
            font-family: 'Montserrat',sans-serif; font-size: 14px; font-weight: 800;
            cursor: pointer; letter-spacing: .02em;
            box-shadow: 0 4px 24px rgba(0,0,0,.25), 0 0 0 1px rgba(255,255,255,.20);
            transition: transform .18s, box-shadow .18s, background .15s;
            display: flex; align-items: center; justify-content: center; gap: 9px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            background: #eef2ff;
            box-shadow: 0 8px 36px rgba(0,0,0,.30), 0 0 0 1px rgba(255,255,255,.30);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit svg { width: 15px; height: 15px; }

        /* ══ FOOTER CARD ══ */
        .login-divider {
            display: flex; align-items: center; gap: 12px; margin-block: 20px;
        }
        .login-divider::before, .login-divider::after {
            content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.14);
        }
        .login-divider span { font-size: 11px; color: rgba(255,255,255,.35); white-space: nowrap; }
        .card-footer {
            text-align: center; font-size: 12px;
            color: rgba(255,255,255,.50); line-height: 1.8;
        }
        .card-footer a {
            color: rgba(255,255,255,.85); font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,.20);
            transition: color .15s;
        }
        .card-footer a:hover { color: #fff; border-color: rgba(255,255,255,.5); }

        /* ══ MOBILE MENU ══ */
        .mobile-menu {
            display: none; position: fixed;
            top: 68px; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,20,.97); backdrop-filter: blur(24px);
            z-index: 99; flex-direction: column;
            padding: 24px; gap: 8px; overflow-y: auto;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            font-family: 'Montserrat',sans-serif; font-size: 16px; font-weight: 600;
            color: var(--txt-main); padding: 14px 18px; border-radius: 10px;
            border: 1px solid var(--borde); transition: all .15s;
        }
        .mobile-menu a:hover { background: rgba(23,70,234,.12); color: #8db4ff; }
        .mobile-divider { border: none; border-top: 1px solid var(--borde); margin-block: 8px; }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 700px) {
            .nav-center  { display: none; }
            .nav-hamburger { display: flex; }
            .btn-nav-registro { display: none; }
            .reg-card { padding: 32px 20px 28px; margin: 0 14px; }
            .form-grid { grid-template-columns: 1fr; }
            .span-2 { grid-column: span 1; }
            .sphere-left  { width: 380px; height: 380px; left: -180px; }
            .sphere-right { width: 260px; height: 260px; }
        }
        @media (max-width: 420px) {
            .reg-card { padding: 26px 16px 24px; border-radius: 20px; }
        }
    </style>
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
                    <div style="display:none;" class="nav-logo-text">Morales<span>Tech</span></div>
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
                <button class="nav-hamburger" id="hamburger" aria-label="Menú" style="margin-left:4px">
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
    <a href="registro.php" style="background:linear-gradient(135deg,#1746EA,#1883ED);color:#fff;border-color:transparent;text-align:center">Crear cuenta →</a>
</div>

<!-- ══ CONTENIDO ══ -->
<div class="page-wrap">
    <div class="sphere-left"></div>
    <div class="sphere-right"></div>
    <div class="bg-noise"></div>

    <div class="reg-card">

        <!-- Isotipo -->
        <div class="card-isotipo">
            <img src="img/isotipo-blanco.png" alt="Morales Tech"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div class="card-isotipo-fallback">M<span>T</span></div>
        </div>

        <h1 class="card-heading">Crea tu cuenta</h1>
        <p class="card-sub">Accede a tu portal de soporte técnico<br>en segundos, es gratuito</p>

        <?php if ($error): ?>
        <div class="alert alert--error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="registro.php" autocomplete="off" id="reg-form">

            <!-- ─── Datos personales ─── -->
            <div class="section-divider">
                <div class="section-divider__line"></div>
                <span class="section-divider__label">Datos personales</span>
                <div class="section-divider__line"></div>
            </div>

            <div class="form-grid">

                <!-- Nombres -->
                <div class="form-group">
                    <label for="nombres">
                        Nombres
                        <span style="color:rgba(255,180,160,.8)">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" id="nombres" name="nombres"
                               placeholder="Ej. Juan Carlos"
                               value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>"
                               required autocomplete="given-name">
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="form-group">
                    <label for="apellidos">
                        Apellidos
                        <span style="color:rgba(255,180,160,.8)">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" id="apellidos" name="apellidos"
                               placeholder="Ej. García López"
                               value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>"
                               required autocomplete="family-name">
                    </div>
                </div>

                <!-- DNI -->
                <div class="form-group">
                    <label for="dni">
                        DNI
                        <span style="color:rgba(255,180,160,.8)">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        <input type="text" id="dni" name="dni"
                               placeholder="12345678" maxlength="8"
                               value="<?= htmlspecialchars($_POST['dni'] ?? '') ?>"
                               required inputmode="numeric">
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="form-group">
                    <label for="telefono">
                        Teléfono
                        <span style="color:rgba(255,180,160,.8)">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.56 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.9-.9a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                        <input type="text" id="telefono" name="telefono"
                               placeholder="9XXXXXXXX" maxlength="9"
                               value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                               required inputmode="numeric">
                    </div>
                </div>

                <!-- RUC (opcional) -->
                <div class="form-group span-2">
                    <label for="ruc">
                        RUC
                        <span class="label-opt">Opcional — solo si facturas a empresa</span>
                    </label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                        <input type="text" id="ruc" name="ruc"
                               placeholder="20XXXXXXXXX" maxlength="11"
                               value="<?= htmlspecialchars($_POST['ruc'] ?? '') ?>"
                               inputmode="numeric">
                    </div>
                </div>

            </div><!-- /form-grid -->

            <!-- ─── Acceso a la cuenta ─── -->
            <div class="section-divider">
                <div class="section-divider__line"></div>
                <span class="section-divider__label">Acceso a la cuenta</span>
                <div class="section-divider__line"></div>
            </div>

            <div class="form-grid">

                <!-- Correo -->
                <div class="form-group span-2">
                    <label for="correo">
                        Correo electrónico
                        <span style="color:rgba(255,180,160,.8)">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" id="correo" name="correo"
                               placeholder="correo@ejemplo.com"
                               value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                               required autocomplete="email">
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password">
                        Contraseña
                        <span style="color:rgba(255,180,160,.8)">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="password" name="password"
                               placeholder="Mínimo 6 caracteres"
                               required autocomplete="new-password"
                               oninput="checkStrength(this.value)">
                        <button type="button" class="pw-toggle" id="pw-toggle-1" tabindex="-1" aria-label="Ver contraseña">
                            <svg id="eye1-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye1-hide" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <!-- Indicador de fuerza -->
                    <div class="pw-strength" id="pw-bars">
                        <div class="pw-strength__bar" id="bar1"></div>
                        <div class="pw-strength__bar" id="bar2"></div>
                        <div class="pw-strength__bar" id="bar3"></div>
                        <div class="pw-strength__bar" id="bar4"></div>
                    </div>
                    <div class="pw-strength__label" id="pw-label"></div>
                </div>

                <!-- Confirmar contraseña -->
                <div class="form-group">
                    <label for="confirm">
                        Confirmar contraseña
                        <span style="color:rgba(255,180,160,.8)">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="confirm" name="confirm"
                               placeholder="Repite tu contraseña"
                               required autocomplete="new-password"
                               oninput="checkMatch()">
                        <button type="button" class="pw-toggle" id="pw-toggle-2" tabindex="-1" aria-label="Ver contraseña">
                            <svg id="eye2-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye2-hide" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <div class="pw-strength__label" id="match-label" style="text-align:left;padding:0 4px;"></div>
                </div>

            </div><!-- /form-grid acceso -->

            <!-- Términos -->
            <div class="terms-row">
                <input type="checkbox" id="terminos" name="terminos" required>
                <label for="terminos">
                    Acepto los <a href="#">términos y condiciones</a> y la
                    <a href="#">política de privacidad</a> de Morales Tech.
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Crear mi cuenta
            </button>

        </form>

        <div class="login-divider"><span>o</span></div>

        <div class="card-footer">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>

    </div><!-- /reg-card -->
</div>

<script>
    /* Navbar scroll */
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 10);
    });

    /* Hamburger */
    const hamburger   = document.getElementById('hamburger');
    const mobileMenu  = document.getElementById('mobile-menu');
    let menuOpen = false;
    hamburger.addEventListener('click', () => {
        menuOpen = !menuOpen;
        mobileMenu.classList.toggle('open', menuOpen);
        const spans = hamburger.querySelectorAll('span');
        if (menuOpen) {
            spans[0].style.transform = 'translateY(7px) rotate(45deg)';
            spans[1].style.opacity   = '0';
            spans[2].style.transform = 'translateY(-7px) rotate(-45deg)';
        } else {
            spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
        }
    });
    function closeMobileMenu() {
        menuOpen = false;
        mobileMenu.classList.remove('open');
        hamburger.querySelectorAll('span').forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
    }

    /* Solo números en DNI, Teléfono y RUC */
    ['dni','telefono','ruc'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });

    /* Toggle password — campo 1 */
    setupToggle('pw-toggle-1', 'password', 'eye1-show', 'eye1-hide');
    setupToggle('pw-toggle-2', 'confirm',  'eye2-show', 'eye2-hide');

    function setupToggle(btnId, inputId, showId, hideId) {
        const btn   = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        const show  = document.getElementById(showId);
        const hide  = document.getElementById(hideId);
        btn.addEventListener('click', () => {
            const visible = input.type === 'password';
            input.type        = visible ? 'text'    : 'password';
            show.style.display = visible ? 'none'   : 'block';
            hide.style.display = visible ? 'block'  : 'none';
        });
    }

    /* Fuerza de contraseña */
    const bars   = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
    const labels = ['', 'Débil', 'Regular', 'Buena', 'Fuerte'];
    const colors = ['', '#ff5f57', '#f5a623', '#28c840', '#28c840'];

    function checkStrength(val) {
        let score = 0;
        if (val.length >= 6)                          score++;
        if (val.length >= 10)                         score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val))  score++;
        if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score++;

        bars.forEach((b, i) => {
            b.className = 'pw-strength__bar';
            if (val.length > 0 && i < score) b.classList.add('active-' + score);
        });

        const lbl = document.getElementById('pw-label');
        lbl.textContent = val.length > 0 ? labels[score] : '';
        lbl.style.color = colors[score];

        checkMatch(); // re-evaluar si coinciden
    }

    /* Coincidencia contraseñas */
    function checkMatch() {
        const pw  = document.getElementById('password').value;
        const cf  = document.getElementById('confirm').value;
        const lbl = document.getElementById('match-label');
        if (!cf) { lbl.textContent = ''; return; }
        if (pw === cf) {
            lbl.textContent = '✓ Contraseñas coinciden';
            lbl.style.color = '#28c840';
        } else {
            lbl.textContent = '✗ No coinciden';
            lbl.style.color = '#ff5f57';
        }
    }
</script>
</body>
</html>