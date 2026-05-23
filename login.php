<?php
// session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if ($email === 'demo@gmail.com' && $pass === 'demo') {
        header('Location: inicio_clientes.php');
        exit;
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

        /* ══ NAVBAR ══ */
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
        .nav-links li a.active { background: rgba(23,70,234,.28); color: #fff; font-weight: 700; }

        .btn-nav-login {
            display: flex; align-items: center; gap: 7px;
            font-family: 'Montserrat',sans-serif; font-size: 13px; font-weight: 700;
            padding: 9px 20px; border-radius: 50px;
            border: 1.5px solid rgba(23,70,234,.45);
            background: rgba(23,70,234,.14); color: #8db4ff;
            cursor: pointer; transition: all .18s; white-space: nowrap;
        }
        .btn-nav-login.active-nav,
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
        .btn-nav-registro:hover { transform: translateY(-1px); box-shadow: 0 6px 28px rgba(23,70,234,.55); }
        .btn-nav-registro svg { width: 13px; height: 13px; }

        .nav-hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 6px; }
        .nav-hamburger span { display: block; width: 22px; height: 2px; background: var(--txt-main); border-radius: 2px; transition: all .25s; }

        /* ══ FONDO / ESFERAS ══ */
        .page-wrap {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding-top: 68px;
            position: relative; overflow: hidden;
        }

        /* Esfera izquierda — solo relleno difuminado, sin borde visible */
        .sphere-left {
            position: absolute;
            width: 640px; height: 640px;
            border-radius: 50%;
            /* Relleno de color con difuminado radial desde el centro */
            background: radial-gradient(
                circle at 60% 50%,
                rgba(23,70,234,.52)  0%,
                rgba(23,70,234,.38) 25%,
                rgba(24,131,237,.22) 50%,
                rgba(24,131,237,.07) 70%,
                transparent 85%
            );
            filter: blur(32px);
            pointer-events: none; z-index: 0;
            left: -280px; top: 50%; transform: translateY(-55%);
        }

        /* Esfera derecha — más pequeña, tono complementario */
        .sphere-right {
            position: absolute;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: radial-gradient(
                circle at 40% 45%,
                rgba(24,131,237,.40)  0%,
                rgba(23,70,234,.25)  30%,
                rgba(23,70,234,.10)  60%,
                transparent 80%
            );
            filter: blur(28px);
            pointer-events: none; z-index: 0;
            right: -160px; bottom: 5%; 
        }

        /* Ruido de fondo muy sutil */
        .bg-noise {
            position: absolute; inset: 0; z-index: 0; pointer-events: none;
            background-image: radial-gradient(circle, rgba(23,70,234,.06) 1px, transparent 1px);
            background-size: 48px 48px; opacity: .4;
        }

        /* ══ CARD ══ */
        .login-card {
            position: relative; z-index: 1;
            width: 100%; max-width: 410px;
            background: var(--azul-card);   /* azul tec #184BEB como base */
            border-radius: 26px;
            padding: 42px 38px 38px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,.10),
                0 28px 80px rgba(0,0,0,.65),
                0 0 80px rgba(23,70,234,.25);
            /* Glassmorphism encima del azul */
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        /* Línea superior luminosa */
        .login-card::before {
            content: '';
            position: absolute; top: 0; left: 12%; right: 12%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), rgba(255,255,255,.55), rgba(255,255,255,.35), transparent);
            border-radius: 50%;
        }

        /* ══ ISOTIPO ══ */
        .login-isotipo { display: flex; justify-content: center; margin-bottom: 18px; }
        .login-isotipo img {
            height: 50px; width: auto;
            filter: drop-shadow(0 0 20px rgba(255,255,255,.30));
        }
        .login-isotipo-fallback {
            height: 50px; display: none; align-items: center;
            font-family: 'Montserrat',sans-serif; font-weight: 900; font-size: 26px; color: #fff;
        }
        .login-isotipo-fallback span { color: rgba(255,255,255,.6); }

        /* ══ TEXTOS ══ */
        .login-heading {
            text-align: center; margin-bottom: 6px;
            font-family: 'Montserrat',sans-serif;
            font-size: 21px; font-weight: 800; color: #fff; letter-spacing: -.3px;
        }
        .login-sub {
            text-align: center; font-size: 13px; font-weight: 500;
            color: rgba(255,255,255,.60); margin-bottom: 28px; line-height: 1.55;
        }

        /* ══ ERROR ══ */
        .login-error {
            display: flex; align-items: center; gap: 9px;
            background: rgba(255,80,50,.18); border: 1px solid rgba(255,110,80,.35);
            border-radius: 50px; padding: 10px 16px; margin-bottom: 18px;
            font-size: 12px; font-weight: 600; color: #ffc2b0;
        }
        .login-error svg { width: 14px; height: 14px; flex-shrink: 0; }

        /* ══ FORM ══ */
        .form-group { display: flex; flex-direction: column; gap: 7px; margin-bottom: 14px; }
        .form-group label {
            font-family: 'Montserrat',sans-serif;
            font-size: 11px; font-weight: 700; letter-spacing: .05em;
            text-transform: uppercase; color: rgba(255,255,255,.55);
        }
        .input-wrap { position: relative; }
        .input-ico {
            position: absolute; left: 17px; top: 50%; transform: translateY(-50%);
            width: 15px; height: 15px; pointer-events: none; z-index: 2;
            color: rgba(255,255,255,.45); transition: color .2s;
        }
        .input-wrap:focus-within .input-ico { color: rgba(255,255,255,.85); }
        .input-wrap input {
            font-family: 'Plus Jakarta Sans',sans-serif;
            width: 100%; padding: 13px 18px 13px 44px;
            background: rgba(255,255,255,.10);
            border: 1.5px solid rgba(255,255,255,.15);
            border-radius: 50px;
            color: #fff; font-size: 14px; font-weight: 500;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
            -webkit-appearance: none; appearance: none;
        }
        .input-wrap input::placeholder { color: rgba(255,255,255,.35); }
        .input-wrap input:focus {
            border-color: rgba(255,255,255,.45);
            background: rgba(255,255,255,.16);
            box-shadow: 0 0 0 3px rgba(255,255,255,.10);
        }

        /* Toggle password */
        .pw-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 4px;
            color: rgba(255,255,255,.40); z-index: 3; transition: color .2s;
        }
        .pw-toggle:hover { color: rgba(255,255,255,.80); }
        .pw-toggle svg { width: 15px; height: 15px; display: block; }

        /* ══ BTN SUBMIT ══ */
        .btn-submit {
            width: 100%; margin-top: 10px;
            padding: 14px; border-radius: 50px; border: none;
            /* Blanco semitransparente sobre el azul de la card — se aprecia bien */
            background: #fff;
            color: var(--azul-card);
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

        /* ══ DIVIDER + FOOTER ══ */
        .login-divider {
            display: flex; align-items: center; gap: 12px; margin-block: 20px;
        }
        .login-divider::before, .login-divider::after {
            content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.14);
        }
        .login-divider span { font-size: 11px; color: rgba(255,255,255,.35); white-space: nowrap; }

        .login-card-footer {
            text-align: center; font-size: 12px; color: rgba(255,255,255,.50);
            line-height: 1.8;
        }
        .login-card-footer a {
            color: rgba(255,255,255,.85); font-weight: 700;
            transition: color .15s;
            border-bottom: 1px solid rgba(255,255,255,.20);
        }
        .login-card-footer a:hover { color: #fff; border-color: rgba(255,255,255,.5); }

        /* ══ MOBILE ══ */
        .mobile-menu { display: none; position: fixed; top: 68px; left: 0; right: 0; bottom: 0; background: rgba(0,0,20,.97); backdrop-filter: blur(24px); z-index: 99; flex-direction: column; padding: 24px; gap: 8px; overflow-y: auto; }
        .mobile-menu.open { display: flex; }
        .mobile-menu a { font-family: 'Montserrat',sans-serif; font-size: 16px; font-weight: 600; color: var(--txt-main); padding: 14px 18px; border-radius: 10px; border: 1px solid var(--borde); transition: all .15s; }
        .mobile-menu a:hover { background: rgba(23,70,234,.12); color: #8db4ff; border-color: rgba(23,70,234,.4); }
        .mobile-divider { border: none; border-top: 1px solid var(--borde); margin-block: 8px; }

        @media(max-width:700px){
            .nav-center { display: none; }
            .nav-hamburger { display: flex; }
            .btn-nav-registro { display: none; }
            .login-card { padding: 34px 22px 30px; margin: 0 16px; }
            .sphere-left { width: 380px; height: 380px; left: -180px; }
            .sphere-right { width: 260px; height: 260px; }
        }
        @media(max-width:420px){
            .login-card { padding: 28px 18px 26px; border-radius: 20px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="nav-inner">
            <div class="nav-left">
                <a href="index.php" class="nav-logo" style="display:flex;align-items:center;gap:0;">
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
    <a href="consulta_tickets.php">Consultar mi ticket</a>
    <a href="login.php" style="background:rgba(23,70,234,.18);color:#8db4ff;border-color:rgba(23,70,234,.4);">Iniciar sesión</a>
    <a href="registro.php" style="background:linear-gradient(135deg,#1746EA,#1883ED);color:#fff;border-color:transparent;text-align:center">Crear cuenta →</a>
</div>

<!-- CONTENIDO -->
<div class="page-wrap">

    <!-- Esferas de fondo — solo relleno difuminado -->
    <div class="sphere-left"></div>
    <div class="sphere-right"></div>
    <div class="bg-noise"></div>

    <!-- Card -->
    <div class="login-card">

        <!-- Isotipo -->
        <div class="login-isotipo">
            <img src="img/isotipo-blanco.png" alt="Morales Tech"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div class="login-isotipo-fallback">M<span>T</span></div>
        </div>

        <h1 class="login-heading">Bienvenido de nuevo</h1>
        <p class="login-sub">Ingresa tus credenciales para<br>acceder a tu portal</p>

        <?php if ($error): ?>
        <div class="login-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off">

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <div class="input-wrap">
                    <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input type="email" id="email" name="email"
                           placeholder="correo@ejemplo.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-wrap">
                    <svg class="input-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••"
                           required autocomplete="current-password">
                    <button type="button" class="pw-toggle" id="pw-toggle" tabindex="-1" aria-label="Ver contraseña">
                        <svg id="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg id="eye-hide" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Iniciar sesión
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            </button>

        </form>

        <div class="login-divider"><span>o</span></div>

        <div class="login-card-footer">
            ¿Aún no tienes cuenta? <a href="registro.php">Regístrate aquí</a><br>
            <a href="consulta_tickets.php">Consultar estado de mi ticket</a>
        </div>

    </div>
</div>

<script>
    /* Navbar scroll */
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 10);
    });

    /* Hamburger */
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
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

    /* Toggle password */
    const pwToggle = document.getElementById('pw-toggle');
    const pwInput  = document.getElementById('password');
    const eyeShow  = document.getElementById('eye-show');
    const eyeHide  = document.getElementById('eye-hide');
    pwToggle.addEventListener('click', () => {
        const show = pwInput.type === 'password';
        pwInput.type     = show ? 'text'  : 'password';
        eyeShow.style.display = show ? 'none'  : 'block';
        eyeHide.style.display = show ? 'block' : 'none';
    });
</script>
</body>
</html>