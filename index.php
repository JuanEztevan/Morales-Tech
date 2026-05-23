<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Morales Tech — Soporte Técnico Inteligente</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --azul:       #1746EA;
      --celeste:    #1883ED;
      --negro:      #000019;
      --blanco:     #ffffff;
      --surface-1:  #05061a;
      --surface-2:  #0b0d22;
      --surface-3:  #12142e;
      --surface-4:  #1a1d3d;
      --borde:      rgba(255,255,255,.07);
      --borde-act:  rgba(23,70,234,.55);
      --txt-main:   #e8ebff;
      --txt-muted:  #7a83b0;
      --grad:       linear-gradient(135deg, #1746EA 0%, #1883ED 100%);
      --grad-soft:  linear-gradient(135deg, rgba(23,70,234,.20) 0%, rgba(24,131,237,.14) 100%);
      --glow-blue:  0 0 60px rgba(23,70,234,.25);
      --shadow-sm:  0 2px 16px rgba(0,0,0,.4);
      --shadow-md:  0 8px 40px rgba(0,0,0,.5);
      --shadow-lg:  0 24px 80px rgba(0,0,0,.7);
      --r:          16px;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--negro);
      color: var(--txt-main);
      overflow-x: hidden;
      line-height: 1.6;
    }
    img { display: block; max-width: 100%; }
    a { text-decoration: none; color: inherit; }
    .container { width: 100%; max-width: 1160px; margin-inline: auto; padding-inline: 24px; }
    .section    { padding-block: 100px; }
    .section--alt { background: var(--surface-1); }
    .tag {
      display: inline-flex; align-items: center; gap: 6px;
      font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
      background: rgba(23,70,234,.18); color: #8db4ff;
      border: 1px solid rgba(23,70,234,.40);
      padding: 5px 14px; border-radius: 50px;
    }
    .tag::before { content:''; width:6px; height:6px; border-radius:50%; background: var(--grad); flex-shrink:0; }
    .eyebrow {
      font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 800; letter-spacing: .14em;
      text-transform: uppercase; color: #6fa3ff; margin-bottom: 14px;
      display: flex; align-items: center; gap: 8px;
    }
    .eyebrow::before { content:''; width:24px; height:2px; background:var(--grad); border-radius:2px; flex-shrink:0; }
    h1,h2,h3 { font-family: 'Montserrat', sans-serif; line-height: 1.15; color: var(--txt-main); }
    /* Título hero menos grueso: 700 en lugar de 900 */
    h1 { font-size: clamp(2.4rem, 4.8vw, 3.6rem); font-weight: 700; letter-spacing: -.025em; }
    h2 { font-size: clamp(2rem, 3.5vw, 2.9rem); font-weight: 800; letter-spacing: -.025em; }
    h3 { font-size: 1rem; font-weight: 700; }
    p  { color: var(--txt-muted); font-size: 15px; line-height: 1.75; }
    .btn {
      display: inline-flex; align-items: center; gap: 8px;
      font-family: 'Montserrat', sans-serif;
      font-size: 13px; font-weight: 700;
      padding: 13px 28px; border-radius: 50px; border: none;
      cursor: pointer; transition: all .22s; white-space: nowrap;
      text-decoration: none;
    }
    .btn-primary {
      background: var(--grad); color: #fff;
      box-shadow: 0 4px 28px rgba(23,70,234,.50);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 40px rgba(23,70,234,.65); }
    .btn-outline {
      background: transparent; color: #8db4ff;
      border: 1.5px solid rgba(23,70,234,.55);
    }
    .btn-outline:hover { background: rgba(23,70,234,.14); border-color: var(--azul); transform: translateY(-2px); }
    .btn svg { width: 15px; height: 15px; flex-shrink: 0; }
    /* Botón WhatsApp verde */
    .btn-whatsapp {
      background: #25D366; color: #fff;
      box-shadow: 0 4px 28px rgba(37,211,102,.35);
      display: inline-flex; align-items: center; gap: 8px;
      font-family: 'Montserrat', sans-serif;
      font-size: 13px; font-weight: 700;
      padding: 13px 28px; border-radius: 50px; border: none;
      cursor: pointer; transition: all .22s; white-space: nowrap;
      text-decoration: none;
    }
    .btn-whatsapp:hover { transform: translateY(-2px); box-shadow: 0 8px 40px rgba(37,211,102,.50); background: #1ebe5d; }
    .btn-whatsapp svg { width: 15px; height: 15px; flex-shrink: 0; }

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
    .navbar.scrolled {
      background: rgba(0,0,20,.95);
      box-shadow: 0 4px 40px rgba(0,0,0,.6);
    }
    .nav-inner { display: flex; align-items: center; height: 68px; }
    .nav-left, .nav-center, .nav-right {
      flex: 1; display: flex; align-items: center;
    }
    .nav-center { justify-content: center; }
    .nav-right  { justify-content: flex-end; gap: 10px; }
    .nav-logo img { height: 30px; width: auto; }
    .nav-links {
      display: flex; align-items: center;
      background: rgba(255,255,255,.04);
      border-radius: 50px;
      padding: 4px 5px; gap: 2px;
      list-style: none; flex-shrink: 0;
      border: 1px solid var(--borde);
    }
    .nav-links li a {
      display: flex; align-items: center; gap: 6px;
      padding: 7px 16px; border-radius: 50px;
      font-family: 'Montserrat', sans-serif;
      font-size: 13px; font-weight: 600;
      color: var(--txt-muted); white-space: nowrap;
      transition: background .15s, color .15s;
    }
    .nav-links li a:hover { background: rgba(255,255,255,.07); color: #c5d4ff; }
    .nav-links li a.active {
      background: rgba(23,70,234,.28);
      color: #fff; font-weight: 700;
      box-shadow: 0 2px 12px rgba(23,70,234,.25);
    }
    .btn-login {
      display: flex; align-items: center; gap: 7px;
      font-family: 'Montserrat', sans-serif;
      font-size: 13px; font-weight: 700;
      padding: 9px 20px; border-radius: 50px;
      border: 1.5px solid rgba(23,70,234,.45);
      background: rgba(23,70,234,.12);
      color: #8db4ff; cursor: pointer;
      transition: all .18s; text-decoration: none; white-space: nowrap;
    }
    .btn-login:hover {
      border-color: var(--azul); background: rgba(23,70,234,.28);
      color: #fff; transform: translateY(-1px);
    }
    .btn-registro {
      display: flex; align-items: center; gap: 7px;
      font-family: 'Montserrat', sans-serif;
      font-size: 13px; font-weight: 700;
      padding: 9px 20px; border-radius: 50px;
      background: var(--grad); color: #fff;
      box-shadow: 0 4px 20px rgba(23,70,234,.4);
      cursor: pointer; transition: all .18s; text-decoration: none; white-space: nowrap;
    }
    .btn-registro:hover { transform: translateY(-1px); box-shadow: 0 6px 28px rgba(23,70,234,.55); }
    .nav-hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 6px; }
    .nav-hamburger span { display: block; width: 22px; height: 2px; background: var(--txt-main); border-radius: 2px; transition: all .25s; }

    /* ══ HERO ══ */
    .hero {
      padding-top: 150px;
      /* Sin padding-bottom fijo; dejamos que el circular-shade salga por abajo */
      padding-bottom: 0;
      position: relative; overflow: visible;
      background: var(--negro);
    }
    .hero-glow {
      position: absolute;
      top: 35%; left: 50%;
      transform: translate(-50%, -50%);
      width: 900px; height: 500px;
      border-radius: 50%;
      background: radial-gradient(ellipse, rgba(23,70,234,.18) 0%, rgba(24,131,237,.06) 40%, transparent 70%);
      pointer-events: none; z-index: 0;
    }
    .hero-glow-tr {
      position: absolute; top: -80px; right: -100px;
      width: 600px; height: 600px; border-radius: 50%;
      background: radial-gradient(circle, rgba(24,131,237,.10) 0%, transparent 65%);
      pointer-events: none; z-index: 0;
    }
    /* Hero centrado */
    .hero-inner {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      position: relative; z-index: 1;
      padding-bottom: 80px;
    }
    .hero-title { margin-bottom: 20px; max-width: 760px; }
    .hero-title em {
      font-style: normal;
      background: var(--grad);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .hero-sub {
      font-size: 16px; line-height: 1.75; color: var(--txt-muted);
      margin-bottom: 40px; max-width: 520px;
    }
    .hero-tag  { margin-bottom: 28px; }
    .hero-buttons {
      display: flex; align-items: center; justify-content: center;
      gap: 12px; flex-wrap: wrap; margin-bottom: 40px;
    }
    .hero-checks {
      display: flex; align-items: center; justify-content: center;
      gap: 22px; flex-wrap: wrap;
      margin-bottom: 0;
    }
    .hero-check {
      display: flex; align-items: center; gap: 8px;
      font-family: 'Montserrat', sans-serif;
      font-size: 12px; font-weight: 600; color: var(--txt-muted);
    }
    .check-dot {
      width: 18px; height: 18px; border-radius: 50%;
      background: var(--grad); display: grid; place-items: center; flex-shrink: 0;
    }
    .check-dot svg { width: 10px; height: 10px; color: #fff; }

    /* ══ CIRCULAR SHADE — separador hero / clientes ══ */
    .hero-shade-wrapper {
      position: relative;
      width: 100%;
      /* Empujamos hacia arriba para que el círculo quede a medias */
      margin-top: -120px;
      z-index: 2;
      display: flex;
      justify-content: center;
      /* Recorta solo la mitad inferior visible */
      overflow: hidden;
      /* Altura: muestra ~40% del círculo */
      height: 180px;
      pointer-events: none;
    }
    .hero-shade-wrapper img {
      width: 420px;
      max-width: 80vw;
      /* Empujamos la imagen hacia arriba para que sólo asome por abajo */
      margin-top: -100px;
      opacity: .85;
      filter: drop-shadow(0 0 60px rgba(23,70,234,.30));
      flex-shrink: 0;
    }

    /* ══ CLIENTES ══ */
    .clients {
      padding-block: 56px;
      border-top: 1px solid var(--borde);
      border-bottom: 1px solid var(--borde);
      background: var(--surface-1);
      position: relative; overflow: hidden;
    }
    .clients::before {
      content: '';
      position: absolute; top: 0; left: 20%; right: 20%; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(23,70,234,.5), transparent);
    }
    .clients-label {
      font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 700; letter-spacing: .12em;
      text-transform: uppercase; color: var(--txt-muted);
      text-align: center; margin-bottom: 40px;
    }
    .clients-logos {
      display: flex; align-items: center; justify-content: center;
      gap: 56px; flex-wrap: wrap;
    }
    /* Logos más grandes: 52px en lugar de 38px */
    .client-logo {
      display: block; height: 52px; width: auto; object-fit: contain;
      filter: brightness(0) invert(1);
      opacity: 1;
      transition: transform .3s cubic-bezier(.22,1,.36,1);
    }
    .client-logo:hover { transform: scale(1.10); }

    /* ══ BENEFICIOS ══ */
    .benefits-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 72px; align-items: center; }
    .benefits-title { margin-bottom: 18px; }
    .benefits-sub   { font-size: 15px; line-height: 1.8; margin-bottom: 36px; }
    .benefits-cards { display: flex; flex-direction: column; gap: 14px; }
    .benefit-card {
      display: flex; align-items: flex-start; gap: 16px;
      padding: 22px 24px; border-radius: var(--r);
      border: 1px solid var(--borde);
      background: var(--surface-2); transition: all .25s;
    }
    .benefit-card:hover {
      border-color: rgba(23,70,234,.4);
      box-shadow: 0 4px 32px rgba(23,70,234,.15);
      transform: translateX(6px);
      background: var(--surface-3);
    }
    .bc-icon {
      width: 44px; height: 44px; border-radius: 12px;
      background: var(--grad-soft); display: grid; place-items: center; flex-shrink: 0;
      border: 1px solid rgba(23,70,234,.2);
    }
    .bc-icon svg { width: 20px; height: 20px; color: #8db4ff; }
    .bc-title { font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700; color: var(--txt-main); margin-bottom: 5px; }
    .bc-text  { font-size: 13px; color: var(--txt-muted); line-height: 1.65; }
    .benefits-panel {
      position: relative;
      background: linear-gradient(145deg, #1746EA 0%, #0f33c8 50%, #1883ED 100%);
      border-radius: 24px;
      padding: 44px 40px; color: #fff; overflow: hidden;
      box-shadow: 0 20px 60px rgba(23,70,234,.45);
    }
    .benefits-panel::before {
      content: '';
      position: absolute; top: -80px; right: -80px;
      width: 280px; height: 280px; border-radius: 50%;
      background: rgba(255,255,255,.07);
    }
    .benefits-panel::after {
      content: '';
      position: absolute; bottom: -40px; left: -40px;
      width: 180px; height: 180px; border-radius: 50%;
      background: rgba(255,255,255,.04);
    }
    .bp-eyebrow { font-size: 10px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; opacity: .7; margin-bottom: 16px; font-family: 'Montserrat', sans-serif; }
    .bp-title   { font-size: 22px; font-weight: 800; font-family: 'Montserrat', sans-serif; margin-bottom: 32px; line-height: 1.3; color: #fff; }
    .bp-stats   { display: flex; flex-direction: column; gap: 18px; position: relative; z-index: 1; }
    .bp-stat    { display: flex; align-items: center; gap: 16px; }
    .bp-stat-val{ font-family: 'Montserrat', sans-serif; font-size: 36px; font-weight: 900; line-height: 1; min-width: 80px; color: #fff; }
    .bp-stat-txt{ font-size: 14px; opacity: .85; color: #fff; }
    .bp-divider { border: none; border-top: 1px solid rgba(255,255,255,.18); margin-block: 20px; }
    .bp-cta {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(255,255,255,.16); color: #fff;
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
      padding: 12px 24px; border-radius: 50px;
      backdrop-filter: blur(6px); transition: background .2s, transform .2s;
      text-decoration: none; border: 1px solid rgba(255,255,255,.2);
      position: relative; z-index: 1;
    }
    .bp-cta:hover { background: rgba(255,255,255,.26); transform: translateY(-2px); }
    .bp-cta svg { width: 14px; height: 14px; }

    /* ══ CÓMO FUNCIONA ══ */
    .how-header { text-align: center; margin-bottom: 72px; }
    .how-header h2 { margin-bottom: 14px; }
    .how-header .eyebrow { justify-content: center; }
    .how-header .eyebrow::before { display: none; }
    .how-header p  { max-width: 520px; margin-inline: auto; font-size: 15px; }
    .how-steps {
      display: grid; grid-template-columns: repeat(4,1fr);
      gap: 0; position: relative;
    }
    .how-steps::before {
      content: '';
      position: absolute; top: 36px; left: 12.5%; right: 12.5%; height: 2px;
      background: linear-gradient(90deg, var(--azul), var(--celeste));
      z-index: 0; opacity: .6;
    }
    .how-step { text-align: center; padding: 0 20px; position: relative; z-index: 1; }
    .hs-num-wrap {
      width: 72px; height: 72px; border-radius: 50%;
      background: var(--surface-2); border: 2px solid var(--azul);
      display: grid; place-items: center; margin-inline: auto; margin-bottom: 28px;
      box-shadow: 0 0 0 8px rgba(23,70,234,.10), 0 4px 20px rgba(23,70,234,.20);
    }
    .hs-num   { font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 900; color: var(--azul); }
    .hs-title { font-family: 'Montserrat', sans-serif; font-size: 15px; font-weight: 700; color: var(--txt-main); margin-bottom: 10px; }
    .hs-text  { font-size: 13px; color: var(--txt-muted); line-height: 1.7; }

    /* ══ SERVICIOS ══ */
    .services-header {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 32px; align-items: end; margin-bottom: 52px;
    }
    .services-header h2 { margin-bottom: 0; }
    .services-header p  { font-size: 15px; }
    .services-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 20px; margin-bottom: 44px; }
    .service-card {
      background: var(--surface-2);
      border: 1px solid var(--borde);
      border-radius: 20px; padding: 32px 28px;
      display: flex; align-items: flex-start; gap: 20px;
      transition: all .25s; position: relative; overflow: hidden;
    }
    .service-card::after {
      content: '';
      position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
      background: var(--grad); transform: scaleX(0); transform-origin: left;
      transition: transform .35s cubic-bezier(.22,1,.36,1);
    }
    .service-card:hover {
      border-color: rgba(23,70,234,.35);
      box-shadow: 0 8px 40px rgba(23,70,234,.18);
      transform: translateY(-5px);
      background: var(--surface-3);
    }
    .service-card:hover::after { transform: scaleX(1); }
    .sc-icon {
      width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
      background: var(--grad-soft); display: grid; place-items: center;
      border: 1px solid rgba(23,70,234,.2);
    }
    .sc-icon svg { width: 24px; height: 24px; color: #8db4ff; }
    .sc-title { font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 800; color: var(--txt-main); margin-bottom: 10px; }
    .sc-text  { font-size: 13px; color: var(--txt-muted); line-height: 1.75; }
    .services-cta { text-align: center; }

    /* ══ PORTAL MOCKUP ══ */
    .portal-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 72px; align-items: center; }
    .portal-title { margin-bottom: 16px; }
    .portal-sub   { font-size: 15px; line-height: 1.8; margin-bottom: 36px; }
    .portal-features { display: flex; flex-direction: column; gap: 14px; margin-bottom: 36px; }
    .pf-item { display: flex; align-items: center; gap: 14px; }
    .pf-icon {
      width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
      background: var(--grad-soft); display: grid; place-items: center;
      border: 1px solid rgba(23,70,234,.2);
    }
    .pf-icon svg { width: 16px; height: 16px; color: #8db4ff; }
    .pf-text { font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 600; color: var(--txt-main); }
    .portal-mockup {
      background: var(--surface-2); border-radius: 20px;
      border: 1px solid rgba(23,70,234,.20); box-shadow: var(--shadow-lg), 0 0 60px rgba(23,70,234,.10);
      overflow: hidden;
    }
    .pm-bar {
      background: var(--surface-3); padding: 12px 18px;
      display: flex; align-items: center; gap: 8px;
      border-bottom: 1px solid var(--borde);
    }
    .pm-dot { width: 10px; height: 10px; border-radius: 50%; }
    .pm-content { padding: 20px; }
    .pm-header {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 16px; padding-bottom: 14px;
      border-bottom: 1px solid var(--borde);
    }
    .pm-title { font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 800; color: var(--txt-main); }
    .pm-badge {
      padding: 4px 10px; border-radius: 50px;
      background: rgba(23,70,234,.2); color: #8db4ff;
      font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700;
    }
    .pm-tickets { display: flex; flex-direction: column; gap: 10px; }
    .pm-ticket {
      display: flex; align-items: center; gap: 12px;
      padding: 12px; border-radius: 10px;
      background: var(--surface-3); border: 1px solid var(--borde);
    }
    .pm-ticket-id   { font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 800; color: #8db4ff; min-width: 60px; }
    .pm-ticket-name { font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700; color: var(--txt-main); }
    .pm-ticket-svc  { font-size: 10px; color: var(--txt-muted); }
    .pm-ticket-info { flex: 1; }
    .pm-status { font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 50px; }
    .ps--diag { background: rgba(23,70,234,.22); color: #8db4ff; }
    .ps--rep  { background: rgba(201,74,0,.22);  color: #f5a07a; }
    .ps--done { background: rgba(26,122,74,.22); color: #5fc98a; }

    /* ══ DIFERENCIAL ══ */
    .diff-header { text-align: center; margin-bottom: 60px; }
    .diff-header h2 { margin-bottom: 14px; }
    .diff-header .eyebrow { justify-content: center; }
    .diff-header .eyebrow::before { display: none; }
    .diff-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; }
    .diff-card {
      text-align: center; padding: 40px 24px;
      border-radius: 20px; border: 1px solid var(--borde);
      background: var(--surface-2); transition: all .25s;
      position: relative; overflow: hidden;
    }
    .diff-card::before {
      content: '';
      position: absolute; top: 0; left: 50%; transform: translateX(-50%);
      width: 60%; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(23,70,234,.5), transparent);
    }
    .diff-card:hover {
      border-color: rgba(23,70,234,.35);
      box-shadow: 0 8px 40px rgba(23,70,234,.18);
      transform: translateY(-5px);
      background: var(--surface-3);
    }
    .diff-icon {
      width: 60px; height: 60px; border-radius: 50%;
      background: var(--grad-soft); display: grid; place-items: center;
      margin-inline: auto; margin-bottom: 20px;
      border: 1px solid rgba(23,70,234,.2);
    }
    .diff-icon svg { width: 26px; height: 26px; color: #8db4ff; }
    .diff-title { font-family: 'Montserrat', sans-serif; font-size: 15px; font-weight: 800; color: var(--txt-main); margin-bottom: 10px; }
    .diff-text  { font-size: 13px; color: var(--txt-muted); line-height: 1.7; }

    /* ══ CTA FINAL ══ */
    .cta-section {
      background: var(--negro); padding-block: 100px;
      position: relative; overflow: hidden;
    }
    .cta-section::before {
      content: '';
      position: absolute; top: -180px; left: -180px;
      width: 600px; height: 600px; border-radius: 50%;
      background: radial-gradient(circle, rgba(23,70,234,.30) 0%, transparent 70%);
    }
    .cta-section::after {
      content: '';
      position: absolute; bottom: -180px; right: -180px;
      width: 600px; height: 600px; border-radius: 50%;
      background: radial-gradient(circle, rgba(24,131,237,.20) 0%, transparent 70%);
    }
    .cta-inner { text-align: center; position: relative; z-index: 1; }
    .cta-tag { display: flex; justify-content: center; margin-bottom: 24px; }
    .cta-title { color: var(--txt-main); margin-bottom: 18px; }
    .cta-title em { font-style: normal; background: var(--grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .cta-sub { color: var(--txt-muted); font-size: 16px; max-width: 480px; margin-inline: auto; margin-bottom: 44px; }
    .cta-buttons { display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap; }
    .btn-white {
      background: #fff; color: var(--negro);
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
      padding: 14px 30px; border-radius: 50px; border: none; cursor: pointer;
      box-shadow: 0 4px 24px rgba(255,255,255,.15); transition: all .22s; text-decoration: none;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 36px rgba(255,255,255,.22); }
    .btn-ghost {
      background: rgba(255,255,255,.06); color: var(--txt-main);
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
      padding: 14px 30px; border-radius: 50px; border: 1.5px solid rgba(255,255,255,.15);
      cursor: pointer; transition: all .22s; text-decoration: none; backdrop-filter: blur(6px);
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-ghost:hover { background: rgba(255,255,255,.12); transform: translateY(-2px); }

    /* ══ FOOTER ══ */
    .footer { background: var(--surface-1); border-top: 1px solid var(--borde); padding-block: 68px 32px; }
    .footer-grid {
      display: grid; grid-template-columns: 2fr 1fr 1fr 1.4fr;
      gap: 48px; margin-bottom: 52px;
    }
    .footer-brand img { height: 34px; margin-bottom: 16px; }
    .footer-brand p { font-size: 13px; color: var(--txt-muted); line-height: 1.75; max-width: 240px; margin-bottom: 22px; }
    .footer-socials { display: flex; gap: 10px; }
    .social-btn {
      width: 36px; height: 36px; border-radius: 9px;
      border: 1px solid var(--borde); background: var(--surface-2);
      display: grid; place-items: center; cursor: pointer;
      transition: border-color .15s, box-shadow .15s; text-decoration: none;
    }
    .social-btn:hover { border-color: var(--azul); box-shadow: 0 0 16px rgba(23,70,234,.3); }
    .social-btn svg { width: 15px; height: 15px; color: var(--txt-muted); }
    .footer-col-title {
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 800;
      letter-spacing: .1em; text-transform: uppercase; color: var(--txt-main); margin-bottom: 18px;
    }
    .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .footer-links a { font-size: 13px; color: var(--txt-muted); transition: color .15s; }
    .footer-links a:hover { color: #8db4ff; }
    .footer-contact { display: flex; flex-direction: column; gap: 12px; }
    .fc-item { display: flex; align-items: flex-start; gap: 10px; }
    .fc-icon { color: #6fa3ff; flex-shrink: 0; margin-top: 2px; }
    .fc-icon svg { width: 14px; height: 14px; }
    .fc-text { font-size: 13px; color: var(--txt-muted); line-height: 1.55; }
    .footer-bottom {
      padding-top: 28px; border-top: 1px solid var(--borde);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 12px;
    }
    .footer-bottom p { font-size: 12px; color: var(--txt-muted); }
    .footer-legal { display: flex; gap: 20px; }
    .footer-legal a { font-size: 12px; color: var(--txt-muted); transition: color .15s; }
    .footer-legal a:hover { color: #8db4ff; }

    /* ══ MOBILE MENU ══ */
    .mobile-menu {
      display: none; position: fixed;
      top: 68px; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,20,.97);
      backdrop-filter: blur(24px); z-index: 99;
      flex-direction: column; padding: 24px; gap: 8px; overflow-y: auto;
    }
    .mobile-menu.open { display: flex; }
    .mobile-menu a {
      font-family: 'Montserrat', sans-serif;
      font-size: 16px; font-weight: 600; color: var(--txt-main);
      padding: 14px 18px; border-radius: 10px; border: 1px solid var(--borde);
      transition: all .15s;
    }
    .mobile-menu a:hover { background: rgba(23,70,234,.12); color: #8db4ff; border-color: rgba(23,70,234,.4); }
    .mobile-divider { border: none; border-top: 1px solid var(--borde); margin-block: 8px; }

    /* ══ ANIMATIONS ══ */
    .reveal { opacity: 0; transform: translateY(30px); transition: opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1); }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-delay-1 { transition-delay: .1s; }
    .reveal-delay-2 { transition-delay: .2s; }
    .reveal-delay-3 { transition-delay: .3s; }
    .reveal-delay-4 { transition-delay: .4s; }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 1024px) {
      .diff-grid { grid-template-columns: repeat(2,1fr); }
      .how-steps { grid-template-columns: repeat(2,1fr); gap: 40px; }
      .how-steps::before { display: none; }
    }
    @media (max-width: 900px) {
      .benefits-inner, .portal-inner { grid-template-columns: 1fr; gap: 40px; }
      .services-header { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 700px) {
      .section { padding-block: 64px; }
      .nav-center { display: none; }
      .nav-hamburger { display: flex; }
      .services-grid { grid-template-columns: 1fr; }
      .diff-grid { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr; gap: 32px; }
      .how-steps { grid-template-columns: 1fr; }
      .clients-logos { gap: 32px; }
      .client-logo { height: 40px; }
      .hero-shade-wrapper img { width: 300px; }
      .hero-shade-wrapper { height: 130px; }
    }
    @media (max-width: 480px) {
      h1 { font-size: 2.2rem; }
      h2 { font-size: 1.9rem; }
      .hero-buttons { flex-direction: column; align-items: center; }
      .cta-buttons { flex-direction: column; align-items: center; }
      .btn-registro { display: none; }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="container">
    <div class="nav-inner">
      <div class="nav-left">
        <a href="#" class="nav-logo">
          <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
               onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
          <span style="display:none;font-family:'Montserrat',sans-serif;font-weight:900;font-size:18px;color:#e8ebff">Morales<span style="color:#1746EA">Tech</span></span>
        </a>
      </div>
      <div class="nav-center">
        <ul class="nav-links">
          <li><a href="#inicio" class="active">Inicio</a></li>
          <li><a href="#servicios">Servicios</a></li>
          <li><a href="#como-funciona">Cómo funciona</a></li>
          <li><a href="#contacto">Soporte</a></li>
        </ul>
      </div>
      <div class="nav-right">
        <a href="login.php" class="btn-login">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Iniciar sesión
        </a>
        <a href="registro.php" class="btn-registro">
          Crear cuenta
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
  <a href="#inicio"        onclick="closeMobileMenu()">Inicio</a>
  <a href="#servicios"     onclick="closeMobileMenu()">Servicios</a>
  <a href="#como-funciona" onclick="closeMobileMenu()">Cómo funciona</a>
  <a href="#contacto"      onclick="closeMobileMenu()">Soporte</a>
  <hr class="mobile-divider">
  <a href="consulta_tickets.php">Consultar mi ticket</a>
  <a href="login.php">Iniciar sesión</a>
  <a href="registro.php" style="background:linear-gradient(135deg,#1746EA,#1883ED);color:#fff;border-color:transparent;text-align:center">
    Crear cuenta →
  </a>
</div>

<!-- HERO -->
<section class="hero" id="inicio">
  <div class="hero-glow"></div>
  <div class="hero-glow-tr"></div>
  <div class="container">
    <div class="hero-inner">

      <div class="hero-tag reveal">
        <span class="tag">Sistema de soporte técnico digital</span>
      </div>

      <h1 class="hero-title reveal reveal-delay-1">
        Soporte técnico <em>inteligente</em> para tu PC o laptop
      </h1>

      <p class="hero-sub reveal reveal-delay-2">
        Cotiza, solicita y da seguimiento a tu servicio en tiempo real desde un solo portal. Sin complicaciones, sin incertidumbre.
      </p>

      <div class="hero-buttons reveal reveal-delay-3">
        <!-- Botón principal: consultar ticket sin iniciar sesión -->
        <a href="consulta_tickets.php" class="btn btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/>
          </svg>
          Consultar estado de mi ticket
        </a>
        <!-- Botón secundario: ya tiene sesión / acceder al portal -->
        <a href="login.php" class="btn btn-outline">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Ya tengo cuenta
        </a>
      </div>

      <div class="hero-checks reveal reveal-delay-4">
        <div class="hero-check">
          <div class="check-dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          Ingresa tu número de ticket
        </div>
        <div class="hero-check">
          <div class="check-dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          Sin necesidad de iniciar sesión
        </div>
        <div class="hero-check">
          <div class="check-dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          Seguimiento en tiempo real
        </div>
      </div>

    </div><!-- /hero-inner -->
  </div><!-- /container -->

  <!-- Circular shade: asoma a medias desde abajo del hero -->
  <div class="hero-shade-wrapper">
    <img src="img/circular-shade.png" alt="" aria-hidden="true">
  </div>

</section>

<!-- CLIENTES -->
<div class="clients">
  <div class="container">
    <p class="clients-label">Empresas que han confiado en nosotros</p>
    <div class="clients-logos">
      <img class="client-logo" src="img/clientes/logo-redondos.png"         alt="Redondos">
      <img class="client-logo" src="img/clientes/logo-cordillera.png"       alt="La Cordillera Latam">
      <img class="client-logo" src="img/clientes/logo-patty-farma.png"      alt="Patty Farma">
      <img class="client-logo" src="img/clientes/logo-notaria.png"          alt="Notaría Vásquez">
      <img class="client-logo" src="img/clientes/logo-clinica-palomino.png" alt="Clínica Palomino">
    </div>
  </div>
</div>

<!-- BENEFICIOS -->
<section class="section" id="beneficios" style="background:var(--negro)">
  <div class="container">
    <div class="benefits-inner">
      <div class="benefits-left">
        <p class="eyebrow reveal">Por qué Morales Tech</p>
        <h2 class="benefits-title reveal reveal-delay-1">Una nueva forma de gestionar tu soporte técnico</h2>
        <p class="benefits-sub reveal reveal-delay-2">Olvídate de llamar y esperar sin saber qué pasa. Con nuestro sistema tienes visibilidad total en cada etapa.</p>
        <div class="benefits-cards">
          <div class="benefit-card reveal reveal-delay-1">
            <div class="bc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div>
            <div>
              <div class="bc-title">Diagnóstico preciso</div>
              <div class="bc-text">Identificamos el problema de tu equipo con análisis técnico detallado desde el primer momento.</div>
            </div>
          </div>
          <div class="benefit-card reveal reveal-delay-2">
            <div class="bc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
            <div>
              <div class="bc-title">Cotización transparente</div>
              <div class="bc-text">Conoce el costo exacto antes de confirmar el servicio, sin sorpresas ni cobros ocultos.</div>
            </div>
          </div>
          <div class="benefit-card reveal reveal-delay-3">
            <div class="bc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <div>
              <div class="bc-title">Seguimiento en tiempo real</div>
              <div class="bc-text">Consulta el estado de tu equipo en cada etapa: diagnóstico, reparación y entrega.</div>
            </div>
          </div>
        </div>
      </div>
      <div class="benefits-panel reveal reveal-delay-2">
        <div class="bp-eyebrow">Resultados</div>
        <div class="bp-title">Números que demuestran nuestra calidad</div>
        <div class="bp-stats">
          <div class="bp-stat">
            <div class="bp-stat-val">98%</div>
            <div class="bp-stat-txt">de clientes satisfechos con el servicio</div>
          </div>
          <hr class="bp-divider">
          <div class="bp-stat">
            <div class="bp-stat-val">24h</div>
            <div class="bp-stat-txt">tiempo promedio de resolución</div>
          </div>
          <hr class="bp-divider">
          <div class="bp-stat">
            <div class="bp-stat-val">500+</div>
            <div class="bp-stat-txt">equipos reparados en Ica</div>
          </div>
        </div>
        <hr class="bp-divider">
        <a href="registro.php" class="bp-cta">
          Solicitar servicio ahora
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- CÓMO FUNCIONA -->
<section class="section section--alt" id="como-funciona">
  <div class="container">
    <div class="how-header">
      <p class="eyebrow reveal">Proceso simple</p>
      <h2 class="reveal reveal-delay-1">Así de simple funciona Morales Tech</h2>
      <p class="reveal reveal-delay-2">En solo cuatro pasos tu equipo queda en manos de nuestros técnicos especializados.</p>
    </div>
    <div class="how-steps">
      <div class="how-step reveal reveal-delay-1">
        <div class="hs-num-wrap"><div class="hs-num">1</div></div>
        <div class="hs-title">Crea tu cuenta</div>
        <div class="hs-text">Regístrate en segundos y accede a tu panel personal desde cualquier dispositivo.</div>
      </div>
      <div class="how-step reveal reveal-delay-2">
        <div class="hs-num-wrap"><div class="hs-num">2</div></div>
        <div class="hs-title">Solicita tu servicio</div>
        <div class="hs-text">Selecciona el tipo de soporte y obtén una cotización inmediata y transparente.</div>
      </div>
      <div class="how-step reveal reveal-delay-3">
        <div class="hs-num-wrap"><div class="hs-num">3</div></div>
        <div class="hs-title">Genera tu ticket</div>
        <div class="hs-text">Confirma el servicio y tu solicitud quedará registrada automáticamente en el sistema.</div>
      </div>
      <div class="how-step reveal reveal-delay-4">
        <div class="hs-num-wrap"><div class="hs-num">4</div></div>
        <div class="hs-title">Sigue tu equipo</div>
        <div class="hs-text">Monitorea el estado de tu servicio en tiempo real desde el portal de clientes.</div>
      </div>
    </div>
  </div>
</section>

<!-- SERVICIOS -->
<section class="section" id="servicios" style="background:var(--negro)">
  <div class="container">
    <div class="services-header">
      <div>
        <p class="eyebrow reveal">Soluciones técnicas</p>
        <h2 class="reveal reveal-delay-1">Soluciones para tu equipo</h2>
      </div>
      <p class="reveal reveal-delay-2">Desde un simple mantenimiento hasta una reparación compleja, cubrimos todas las necesidades de tu PC o laptop.</p>
    </div>
    <div class="services-grid">
      <div class="service-card reveal reveal-delay-1">
        <div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div>
        <div>
          <div class="sc-title">Diagnóstico técnico</div>
          <div class="sc-text">Detectamos fallas en hardware y software con precisión usando herramientas especializadas para identificar la causa raíz del problema.</div>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-2">
        <div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
        <div>
          <div class="sc-title">Mantenimiento y reparación</div>
          <div class="sc-text">Prolonga la vida útil de tu equipo y corrige fallos críticos de hardware. Limpieza, reemplazo de piezas y más.</div>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-3">
        <div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg></div>
        <div>
          <div class="sc-title">Instalación y configuración</div>
          <div class="sc-text">Formateo, instalación de sistema operativo, drivers y software. Tu equipo listo para trabajar desde el primer día.</div>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-4">
        <div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <div>
          <div class="sc-title">Optimización del sistema</div>
          <div class="sc-text">Mejora el rendimiento y velocidad de tu equipo. Eliminamos procesos innecesarios, malware y optimizamos el arranque.</div>
        </div>
      </div>
    </div>
    <div class="services-cta reveal">
      <a href="registro.php" class="btn btn-primary">
        Cotizar ahora
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- PORTAL -->
<section class="section section--alt" id="portal">
  <div class="container">
    <div class="portal-inner">
      <div class="portal-left">
        <p class="eyebrow reveal">Portal de clientes</p>
        <h2 class="portal-title reveal reveal-delay-1">Todo el control en un solo lugar</h2>
        <p class="portal-sub reveal reveal-delay-2">Gestiona tus servicios, revisa tu historial y mantente informado en cada etapa del proceso desde tu panel personalizado.</p>
        <div class="portal-features reveal reveal-delay-3">
          <div class="pf-item">
            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg></div>
            <div class="pf-text">Gestión de tickets en tiempo real</div>
          </div>
          <div class="pf-item">
            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg></div>
            <div class="pf-text">Historial completo de servicios</div>
          </div>
          <div class="pf-item">
            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <div class="pf-text">Actualizaciones automáticas de estado</div>
          </div>
          <!-- Reemplazado "comunicación directa" por WhatsApp -->
          <div class="pf-item">
            <div class="pf-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
              </svg>
            </div>
            <div class="pf-text">Contacto con técnicos vía WhatsApp</div>
          </div>
        </div>
        <a href="registro.php" class="btn btn-primary reveal reveal-delay-4">
          Crear cuenta gratis
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
      <div class="portal-right reveal reveal-delay-2">
        <div class="portal-mockup">
          <div class="pm-bar">
            <div class="pm-dot" style="background:#ff5f57"></div>
            <div class="pm-dot" style="background:#febc2e"></div>
            <div class="pm-dot" style="background:#28c840"></div>
          </div>
          <div class="pm-content">
            <div class="pm-header">
              <div class="pm-title">Mis Tickets</div>
              <div class="pm-badge">3 activos</div>
            </div>
            <div class="pm-tickets">
              <div class="pm-ticket">
                <div class="pm-ticket-id">#MT-8842</div>
                <div class="pm-ticket-info">
                  <div class="pm-ticket-name">Laptop HP Pavilion</div>
                  <div class="pm-ticket-svc">Mantenimiento correctivo</div>
                </div>
                <div class="pm-status ps--diag">En diagnóstico</div>
              </div>
              <div class="pm-ticket">
                <div class="pm-ticket-id">#MT-8843</div>
                <div class="pm-ticket-info">
                  <div class="pm-ticket-name">PC Escritorio</div>
                  <div class="pm-ticket-svc">Repotenciación</div>
                </div>
                <div class="pm-status ps--rep">En reparación</div>
              </div>
              <div class="pm-ticket">
                <div class="pm-ticket-id">#MT-8844</div>
                <div class="pm-ticket-info">
                  <div class="pm-ticket-name">Laptop Apple MacBook</div>
                  <div class="pm-ticket-svc">Diagnóstico técnico</div>
                </div>
                <div class="pm-status ps--done">Completado ✓</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DIFERENCIAL -->
<section class="section" id="diferencial" style="background:var(--negro)">
  <div class="container">
    <div class="diff-header">
      <p class="eyebrow reveal">Nuestra ventaja</p>
      <h2 class="reveal reveal-delay-1">¿Por qué elegir Morales Tech?</h2>
      <p style="max-width:480px;margin:14px auto 0;font-size:15px" class="reveal reveal-delay-2">Más que un servicio técnico, una experiencia de soporte pensada para el usuario.</p>
    </div>
    <div class="diff-grid">
      <div class="diff-card reveal reveal-delay-1">
        <div class="diff-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <div class="diff-title">Atención rápida</div>
        <div class="diff-text">Reducimos tiempos de espera con procesos optimizados y diagnóstico ágil desde el primer contacto.</div>
      </div>
      <div class="diff-card reveal reveal-delay-2">
        <div class="diff-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="diff-title">Soporte transparente</div>
        <div class="diff-text">Información clara en cada etapa. Sabes exactamente qué se hace, cuánto cuesta y cuándo estará listo.</div>
      </div>
      <div class="diff-card reveal reveal-delay-3">
        <div class="diff-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="diff-title">Centrado en el usuario</div>
        <div class="diff-text">Plataforma diseñada para ser simple e intuitiva. Sin tecnicismos innecesarios, solo resultados reales.</div>
      </div>
      <div class="diff-card reveal reveal-delay-4">
        <div class="diff-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <div class="diff-title">Seguimiento constante</div>
        <div class="diff-text">Nunca pierdes de vista el estado de tu equipo. Notificaciones y actualizaciones en cada cambio de estado.</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA FINAL -->
<section class="cta-section">
  <div class="container">
    <div class="cta-inner">
      <div class="cta-tag reveal">
        <span class="tag">Ica, Perú · Atención inmediata</span>
      </div>
      <h2 class="cta-title reveal reveal-delay-1">¿Listo para solucionar tu <em>problema técnico?</em></h2>
      <p class="cta-sub reveal reveal-delay-2">Ingresa tu número de ticket y revisa el estado de tu equipo al instante, o crea una cuenta para cotizar y gestionar todos tus servicios.</p>
      <div class="cta-buttons reveal reveal-delay-3">
        <!-- Consultar ticket sin login -->
        <a href="consulta_tickets.php" class="btn-white">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/>
          </svg>
          Consultar mi ticket
        </a>
        <!-- WhatsApp -->
        <a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="btn-whatsapp">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
          </svg>
          Escribir al técnico
        </a>
        <!-- Crear cuenta / cotizar -->
        <a href="registro.php" class="btn-ghost">
          Crear cuenta para cotizar →
        </a>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer" id="contacto">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <img src="img/naming-logo-blanco.png" alt="Morales Tech"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <span style="display:none;font-family:'Montserrat',sans-serif;font-weight:900;font-size:18px;color:#e8ebff">Morales<span style="color:#1746EA">Tech</span></span>
        <p>Soluciones de soporte técnico digital para PC y laptops en Ica, Perú.</p>
        <div class="footer-socials">
          <a href="https://instagram.com/moralestech.pe" target="_blank" class="social-btn" title="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          <a href="https://facebook.com/moralestech.pe" target="_blank" class="social-btn" title="Facebook">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="https://wa.me/51903208170" target="_blank" class="social-btn" title="WhatsApp">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          </a>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Navegación</div>
        <ul class="footer-links">
          <li><a href="#inicio">Inicio</a></li>
          <li><a href="#servicios">Servicios</a></li>
          <li><a href="#como-funciona">Cómo funciona</a></li>
          <li><a href="consulta_tickets.php">Consultar ticket</a></li>
          <li><a href="#contacto">Contacto</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Legal</div>
        <ul class="footer-links">
          <li><a href="#">Privacidad</a></li>
          <li><a href="#">Términos de uso</a></li>
        </ul>
        <div style="margin-top:24px">
          <div class="footer-col-title">Otros servicios</div>
          <ul class="footer-links">
            <li><a href="#">Hosting</a></li>
            <li><a href="#">Redes y cableado</a></li>
            <li><a href="#">Diseño web</a></li>
            <li><a href="#">Identidad de marca</a></li>
          </ul>
        </div>
      </div>
      <div id="contacto-info">
        <div class="footer-col-title">Contacto</div>
        <div class="footer-contact">
          <div class="fc-item">
            <div class="fc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></div>
            <div class="fc-text">
              <a href="https://wa.me/51903208170" target="_blank" style="color:inherit;text-decoration:none">
                +51 903 208 170 (WhatsApp)
              </a>
            </div>
          </div>
          <div class="fc-item">
            <div class="fc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
            <div class="fc-text">moralestechsolutionss@gmail.com</div>
          </div>
          <div class="fc-item">
            <div class="fc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div class="fc-text">Urb. Jardines de Casablanca F-06, Ica, Perú</div>
          </div>
          <div class="fc-item">
            <div class="fc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></div>
            <div class="fc-text">@moralestech.pe</div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2026 Morales Tech · RUC 20613424238 · Todos los derechos reservados.</p>
      <div class="footer-legal">
        <a href="#">Privacidad</a>
        <a href="#">Términos</a>
      </div>
    </div>
  </div>
</footer>

<script>
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  });
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  let menuOpen = false;
  hamburger.addEventListener('click', () => {
    menuOpen = !menuOpen;
    mobileMenu.classList.toggle('open', menuOpen);
    const spans = hamburger.querySelectorAll('span');
    if (menuOpen) {
      spans[0].style.transform = 'translateY(7px) rotate(45deg)';
      spans[1].style.opacity = '0';
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
  const reveals = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
  }, { threshold: 0.10 });
  reveals.forEach(el => observer.observe(el));
  const navLinks = document.querySelectorAll('.nav-links a');
  window.addEventListener('scroll', () => {
    let current = 'inicio';
    document.querySelectorAll('section[id]').forEach(sec => {
      if (window.scrollY >= sec.offsetTop - 120) current = sec.getAttribute('id');
    });
    navLinks.forEach(a => {
      a.classList.toggle('active', a.getAttribute('href') === '#' + current);
    });
  });
</script>
</body>
</html>