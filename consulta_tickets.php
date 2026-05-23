<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consulta tu Ticket — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --azul:      #1746EA;
      --celeste:   #1883ED;
      --negro:     #000019;
      --surface-1: #05061a;
      --surface-2: #0b0d22;
      --surface-3: #12142e;
      --surface-4: #1a1d3d;
      --borde:     rgba(255,255,255,.07);
      --txt-main:  #e8ebff;
      --txt-muted: #7a83b0;
      --grad:      linear-gradient(135deg, #1746EA 0%, #1883ED 100%);
      --grad-soft: linear-gradient(135deg, rgba(23,70,234,.20) 0%, rgba(24,131,237,.14) 100%);
      --r:         16px;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--negro);
      color: var(--txt-main);
      overflow-x: hidden;
      line-height: 1.6;
      min-height: 100vh;
    }
    img { display: block; max-width: 100%; }
    a { text-decoration: none; color: inherit; }
    .container { width: 100%; max-width: 1160px; margin-inline: auto; padding-inline: 24px; }

    /* ══ NAVBAR (idéntica a index) ══ */
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
    .nav-inner { display: flex; align-items: center; height: 68px; }
    .nav-left, .nav-center, .nav-right { flex: 1; display: flex; align-items: center; }
    .nav-center { justify-content: center; }
    .nav-right  { justify-content: flex-end; gap: 10px; }
    .nav-logo img { height: 30px; width: auto; }
    .nav-links {
      display: flex; align-items: center;
      background: rgba(255,255,255,.04);
      border-radius: 50px; padding: 4px 5px; gap: 2px;
      list-style: none; flex-shrink: 0;
      border: 1px solid var(--borde);
    }
    .nav-links li a {
      display: flex; align-items: center; gap: 6px;
      padding: 7px 16px; border-radius: 50px;
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 600;
      color: var(--txt-muted); white-space: nowrap;
      transition: background .15s, color .15s;
    }
    .nav-links li a:hover { background: rgba(255,255,255,.07); color: #c5d4ff; }
    .nav-links li a.active { background: rgba(23,70,234,.28); color: #fff; font-weight: 700; box-shadow: 0 2px 12px rgba(23,70,234,.25); }
    .btn-login {
      display: flex; align-items: center; gap: 7px;
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
      padding: 9px 20px; border-radius: 50px;
      border: 1.5px solid rgba(23,70,234,.45);
      background: rgba(23,70,234,.12); color: #8db4ff;
      cursor: pointer; transition: all .18s; text-decoration: none; white-space: nowrap;
    }
    .btn-login:hover { border-color: var(--azul); background: rgba(23,70,234,.28); color: #fff; transform: translateY(-1px); }
    .btn-registro {
      display: flex; align-items: center; gap: 7px;
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
      padding: 9px 20px; border-radius: 50px;
      background: var(--grad); color: #fff;
      box-shadow: 0 4px 20px rgba(23,70,234,.4);
      cursor: pointer; transition: all .18s; text-decoration: none; white-space: nowrap;
    }
    .btn-registro:hover { transform: translateY(-1px); box-shadow: 0 6px 28px rgba(23,70,234,.55); }
    .nav-hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 6px; }
    .nav-hamburger span { display: block; width: 22px; height: 2px; background: var(--txt-main); border-radius: 2px; transition: all .25s; }
    .mobile-menu {
      display: none; position: fixed;
      top: 68px; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,20,.97); backdrop-filter: blur(24px);
      z-index: 99; flex-direction: column; padding: 24px; gap: 8px; overflow-y: auto;
    }
    .mobile-menu.open { display: flex; }
    .mobile-menu a {
      font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 600;
      color: var(--txt-main); padding: 14px 18px; border-radius: 10px;
      border: 1px solid var(--borde); transition: all .15s;
    }
    .mobile-menu a:hover { background: rgba(23,70,234,.12); color: #8db4ff; border-color: rgba(23,70,234,.4); }
    .mobile-divider { border: none; border-top: 1px solid var(--borde); margin-block: 8px; }

    /* ══ PAGE HEADER ══ */
    .page-header {
      padding-top: 130px; padding-bottom: 56px;
      text-align: center; position: relative; overflow: hidden;
      background: var(--negro);
    }
    .page-header::before {
      content: '';
      position: absolute; top: 0; left: 50%; transform: translateX(-50%);
      width: 900px; height: 400px; border-radius: 50%;
      background: radial-gradient(ellipse, rgba(23,70,234,.16) 0%, rgba(24,131,237,.05) 40%, transparent 70%);
      pointer-events: none;
    }
    .ph-tag {
      display: inline-flex; align-items: center; gap: 6px;
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700;
      letter-spacing: .07em; text-transform: uppercase;
      background: rgba(23,70,234,.18); color: #8db4ff;
      border: 1px solid rgba(23,70,234,.40);
      padding: 5px 14px; border-radius: 50px;
      margin-bottom: 22px;
    }
    .ph-tag::before { content:''; width:6px; height:6px; border-radius:50%; background: var(--grad); flex-shrink:0; }
    .page-header h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: clamp(2rem, 4vw, 3rem); font-weight: 700;
      letter-spacing: -.025em; color: var(--txt-main);
      margin-bottom: 14px;
    }
    .page-header h1 em {
      font-style: normal;
      background: var(--grad);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .page-header p {
      font-size: 15px; color: var(--txt-muted); max-width: 480px; margin-inline: auto;
    }

    /* ══ MAIN CONTENT ══ */
    .main-content {
      padding-bottom: 80px;
      background: var(--negro);
    }

    /* Grid: izquierda (formulario) + derecha (resultado) */
    .ct-grid {
      display: grid;
      grid-template-columns: 380px 1fr;
      gap: 24px;
      align-items: start;
    }

    /* ── Panel izquierdo: input ── */
    .ct-input-panel {
      background: var(--surface-2);
      border: 1px solid var(--borde);
      border-radius: 20px;
      padding: 32px 28px;
      position: sticky;
      top: 88px;
    }
    .cip-label {
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 800;
      letter-spacing: .12em; text-transform: uppercase;
      color: #6fa3ff; margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }
    .cip-label::before { content:''; width:20px; height:2px; background:var(--grad); border-radius:2px; }
    .cip-title {
      font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 800;
      color: var(--txt-main); margin-bottom: 6px; line-height: 1.2;
    }
    .cip-sub { font-size: 13px; color: var(--txt-muted); margin-bottom: 28px; line-height: 1.6; }

    .field-label {
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700;
      letter-spacing: .08em; text-transform: uppercase;
      color: var(--txt-muted); margin-bottom: 10px; display: block;
    }
    .field-wrap { position: relative; margin-bottom: 14px; }
    .field-wrap svg {
      position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
      width: 16px; height: 16px; color: var(--txt-muted); pointer-events: none;
    }
    .ct-input {
      width: 100%; padding: 14px 16px 14px 44px;
      background: var(--surface-3); border: 1.5px solid var(--borde);
      border-radius: 12px; color: var(--txt-main);
      font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 600;
      outline: none; transition: border-color .2s, box-shadow .2s;
      letter-spacing: .04em;
    }
    .ct-input::placeholder { color: var(--txt-muted); font-weight: 500; letter-spacing: 0; }
    .ct-input:focus {
      border-color: var(--azul);
      box-shadow: 0 0 0 3px rgba(23,70,234,.18);
    }
    .btn-consultar {
      width: 100%; padding: 14px;
      background: var(--grad); color: #fff;
      font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700;
      border: none; border-radius: 12px; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 4px 28px rgba(23,70,234,.45);
      transition: transform .2s, box-shadow .2s;
    }
    .btn-consultar:hover { transform: translateY(-2px); box-shadow: 0 8px 36px rgba(23,70,234,.60); }
    .btn-consultar svg { width: 16px; height: 16px; }

    .cip-help {
      margin-top: 22px; padding: 16px;
      background: rgba(23,70,234,.08); border: 1px solid rgba(23,70,234,.18);
      border-radius: 10px;
    }
    .cip-help p {
      font-size: 12px; color: var(--txt-muted); line-height: 1.65;
    }
    .cip-help a {
      color: #8db4ff; font-weight: 600;
      text-decoration: underline; text-underline-offset: 3px;
    }
    .cip-help a:hover { color: #c5d4ff; }

    /* Ejemplos de tickets rápidos */
    .quick-tickets {
      margin-top: 20px;
    }
    .qt-label {
      font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700;
      letter-spacing: .1em; text-transform: uppercase; color: var(--txt-muted);
      margin-bottom: 10px;
    }
    .qt-chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .qt-chip {
      padding: 6px 14px; border-radius: 50px;
      border: 1px solid rgba(23,70,234,.30);
      background: rgba(23,70,234,.08);
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700;
      color: #8db4ff; cursor: pointer;
      transition: background .15s, border-color .15s;
    }
    .qt-chip:hover { background: rgba(23,70,234,.18); border-color: var(--azul); }

    /* ── Panel derecho: resultado ── */
    .ct-result-panel {
      display: flex; flex-direction: column; gap: 20px;
    }

    /* Estado vacío */
    .result-empty {
      background: var(--surface-2);
      border: 1.5px dashed rgba(23,70,234,.25);
      border-radius: 20px; padding: 60px 40px;
      text-align: center;
    }
    .re-icon {
      width: 72px; height: 72px; border-radius: 50%;
      background: var(--grad-soft); border: 1px solid rgba(23,70,234,.2);
      display: grid; place-items: center; margin-inline: auto; margin-bottom: 24px;
    }
    .re-icon svg { width: 32px; height: 32px; color: #8db4ff; }
    .re-title { font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 800; color: var(--txt-main); margin-bottom: 10px; }
    .re-text  { font-size: 14px; color: var(--txt-muted); max-width: 360px; margin-inline: auto; }

    /* Card de resultado del ticket */
    .result-card {
      background: var(--surface-2);
      border: 1px solid rgba(23,70,234,.20);
      border-radius: 20px; overflow: hidden;
      display: none; /* visible via JS */
    }
    .result-card.visible { display: block; }

    /* Cabecera de resultado */
    .rc-header {
      padding: 24px 28px;
      border-bottom: 1px solid var(--borde);
      display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
      position: relative; overflow: hidden;
    }
    .rc-header::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; height: 3px;
      background: var(--grad);
    }
    .rc-header-label {
      font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 800;
      letter-spacing: .12em; text-transform: uppercase; color: #6fa3ff; margin-bottom: 8px;
    }
    .rc-ticket-id {
      font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 900;
      color: var(--txt-main); letter-spacing: -.01em;
    }
    .rc-device {
      font-size: 13px; color: var(--txt-muted); margin-top: 4px;
    }
    /* Badge de estado */
    .status-badge {
      font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700;
      padding: 7px 16px; border-radius: 50px; white-space: nowrap; flex-shrink: 0;
    }
    .status-badge.recibido     { background: rgba(23,70,234,.20);  color: #8db4ff;  border: 1px solid rgba(23,70,234,.35); }
    .status-badge.diagnostico  { background: rgba(201,150,0,.20);  color: #f5c842;  border: 1px solid rgba(201,150,0,.35); }
    .status-badge.reparacion   { background: rgba(201,74,0,.22);   color: #f5a07a;  border: 1px solid rgba(201,74,0,.35); }
    .status-badge.completado   { background: rgba(26,122,74,.22);  color: #5fc98a;  border: 1px solid rgba(26,122,74,.35); }

    /* Barra de progreso */
    .rc-progress { padding: 28px 28px 24px; border-bottom: 1px solid var(--borde); }
    .rcp-bar-wrap {
      height: 6px; background: var(--surface-4); border-radius: 99px;
      margin-bottom: 28px; overflow: hidden;
    }
    .rcp-bar-fill {
      height: 100%; border-radius: 99px;
      background: var(--grad);
      transition: width .8s cubic-bezier(.22,1,.36,1);
    }
    /* Etapas */
    .rcp-steps {
      display: grid; grid-template-columns: repeat(4, 1fr);
      gap: 8px;
    }
    .rcp-step { display: flex; flex-direction: column; align-items: center; gap: 8px; }
    .rcp-step-icon {
      width: 44px; height: 44px; border-radius: 50%;
      display: grid; place-items: center; flex-shrink: 0;
      border: 2px solid var(--borde);
      background: var(--surface-3);
      transition: all .3s;
    }
    .rcp-step-icon svg { width: 18px; height: 18px; color: var(--txt-muted); }
    .rcp-step-name {
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700;
      color: var(--txt-muted); text-align: center; line-height: 1.3;
    }
    /* Estados de cada paso */
    .rcp-step.done .rcp-step-icon {
      background: rgba(23,70,234,.18); border-color: var(--azul);
    }
    .rcp-step.done .rcp-step-icon svg { color: #8db4ff; }
    .rcp-step.done .rcp-step-name { color: #8db4ff; }
    .rcp-step.active .rcp-step-icon {
      background: var(--grad); border-color: var(--celeste);
      box-shadow: 0 0 0 5px rgba(23,70,234,.18), 0 4px 20px rgba(23,70,234,.35);
    }
    .rcp-step.active .rcp-step-icon svg { color: #fff; }
    .rcp-step.active .rcp-step-name { color: var(--txt-main); font-weight: 800; }

    /* Caja de actualización */
    .rc-update { padding: 22px 28px; border-bottom: 1px solid var(--borde); }
    .rcu-inner {
      background: var(--surface-3);
      border: 1px solid rgba(23,70,234,.18);
      border-radius: 12px; padding: 18px 20px;
      display: flex; gap: 16px; align-items: flex-start;
    }
    .rcu-dot-wrap {
      width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
      background: rgba(23,70,234,.18); display: grid; place-items: center;
    }
    .rcu-dot-wrap svg { width: 16px; height: 16px; color: #8db4ff; }
    .rcu-title {
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 800;
      color: var(--txt-main); margin-bottom: 6px;
    }
    .rcu-text { font-size: 13px; color: var(--txt-muted); line-height: 1.7; }
    .rcu-eta {
      margin-top: 10px; display: inline-flex; align-items: center; gap: 6px;
      background: rgba(26,122,74,.18); color: #5fc98a;
      border: 1px solid rgba(26,122,74,.28);
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700;
      padding: 5px 12px; border-radius: 50px;
    }
    .rcu-eta svg { width: 12px; height: 12px; }

    /* Detalles técnicos */
    .rc-details { padding: 22px 28px; }
    .rcd-title {
      font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 800;
      letter-spacing: .1em; text-transform: uppercase; color: var(--txt-muted);
      margin-bottom: 14px;
    }
    .rcd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .rcd-item {
      background: var(--surface-3); border: 1px solid var(--borde);
      border-radius: 10px; padding: 12px 16px;
    }
    .rcd-key  { font-size: 11px; color: var(--txt-muted); margin-bottom: 4px; }
    .rcd-val  { font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700; color: var(--txt-main); }

    /* ══ FEATURES STRIP ══ */
    .features-strip {
      background: var(--surface-1);
      border-top: 1px solid var(--borde);
      border-bottom: 1px solid var(--borde);
      padding: 40px 0;
      margin-top: 56px;
    }
    .fs-grid {
      display: grid; grid-template-columns: repeat(3,1fr); gap: 28px;
    }
    .fs-item { display: flex; align-items: flex-start; gap: 16px; }
    .fs-icon {
      width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
      background: var(--grad-soft); display: grid; place-items: center;
      border: 1px solid rgba(23,70,234,.2);
    }
    .fs-icon svg { width: 20px; height: 20px; color: #8db4ff; }
    .fs-title { font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700; color: var(--txt-main); margin-bottom: 4px; }
    .fs-text  { font-size: 13px; color: var(--txt-muted); line-height: 1.6; }

    /* ══ SOPORTE CTA ══ */
    .support-cta {
      background: var(--negro); padding: 72px 0;
      position: relative; overflow: hidden;
    }
    .support-cta::before {
      content: '';
      position: absolute; top: -160px; left: 50%; transform: translateX(-50%);
      width: 700px; height: 400px; border-radius: 50%;
      background: radial-gradient(ellipse, rgba(23,70,234,.14) 0%, transparent 70%);
      pointer-events: none;
    }
    .sc-inner { text-align: center; position: relative; z-index: 1; }
    .sc-icon-wrap {
      width: 64px; height: 64px; border-radius: 50%;
      background: var(--grad-soft); border: 1px solid rgba(23,70,234,.25);
      display: grid; place-items: center; margin-inline: auto; margin-bottom: 20px;
    }
    .sc-icon-wrap svg { width: 28px; height: 28px; color: #8db4ff; }
    .sc-title {
      font-family: 'Montserrat', sans-serif; font-size: 24px; font-weight: 800;
      color: var(--txt-main); margin-bottom: 10px;
    }
    .sc-sub { font-size: 15px; color: var(--txt-muted); max-width: 400px; margin-inline: auto; margin-bottom: 32px; }
    .sc-buttons { display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap; }
    .btn-wa {
      display: inline-flex; align-items: center; gap: 8px;
      background: #25D366; color: #fff;
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
      padding: 13px 26px; border-radius: 50px; border: none; cursor: pointer;
      box-shadow: 0 4px 28px rgba(37,211,102,.35);
      transition: all .22s; text-decoration: none;
    }
    .btn-wa:hover { transform: translateY(-2px); box-shadow: 0 8px 36px rgba(37,211,102,.50); background: #1ebe5d; }
    .btn-wa svg { width: 16px; height: 16px; }
    .btn-call {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(23,70,234,.14); color: #8db4ff;
      font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
      padding: 13px 26px; border-radius: 50px;
      border: 1.5px solid rgba(23,70,234,.40);
      cursor: pointer; transition: all .22s; text-decoration: none;
    }
    .btn-call:hover { background: rgba(23,70,234,.26); border-color: var(--azul); color: #fff; transform: translateY(-2px); }
    .btn-call svg { width: 16px; height: 16px; }

    /* ══ FOOTER (idéntico a index) ══ */
    .footer { background: var(--surface-1); border-top: 1px solid var(--borde); padding-block: 68px 32px; }
    .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1.4fr; gap: 48px; margin-bottom: 52px; }
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
      display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
    }
    .footer-bottom p { font-size: 12px; color: var(--txt-muted); }
    .footer-legal { display: flex; gap: 20px; }
    .footer-legal a { font-size: 12px; color: var(--txt-muted); transition: color .15s; }
    .footer-legal a:hover { color: #8db4ff; }

    /* ══ ANIMATIONS ══ */
    .reveal { opacity: 0; transform: translateY(24px); transition: opacity .6s cubic-bezier(.22,1,.36,1), transform .6s cubic-bezier(.22,1,.36,1); }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-delay-1 { transition-delay: .1s; }
    .reveal-delay-2 { transition-delay: .2s; }
    .reveal-delay-3 { transition-delay: .3s; }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 960px) {
      .ct-grid { grid-template-columns: 1fr; }
      .ct-input-panel { position: static; }
      .fs-grid { grid-template-columns: 1fr 1fr; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 700px) {
      .nav-center { display: none; }
      .nav-hamburger { display: flex; }
      .rcp-steps { grid-template-columns: repeat(2,1fr); gap: 14px; }
      .rcd-grid  { grid-template-columns: 1fr; }
      .fs-grid   { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr; gap: 32px; }
      .sc-buttons { flex-direction: column; align-items: center; }
    }
    @media (max-width: 480px) {
      .btn-registro { display: none; }
    }
  </style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="navbar" id="navbar">
  <div class="container">
    <div class="nav-inner">
      <div class="nav-left">
        <a href="index.php" class="nav-logo">
          <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
               onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
          <span style="display:none;font-family:'Montserrat',sans-serif;font-weight:900;font-size:18px;color:#e8ebff">Morales<span style="color:#1746EA">Tech</span></span>
        </a>
      </div>
      <div class="nav-center">
        <ul class="nav-links">
          <li><a href="index.php">Inicio</a></li>
          <li><a href="index.php#servicios">Servicios</a></li>
          <li><a href="index.php#como-funciona">Cómo funciona</a></li>
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
  <a href="index.php"           onclick="closeMobileMenu()">Inicio</a>
  <a href="index.php#servicios" onclick="closeMobileMenu()">Servicios</a>
  <a href="index.php#como-funciona" onclick="closeMobileMenu()">Cómo funciona</a>
  <a href="#contacto"           onclick="closeMobileMenu()">Soporte</a>
  <hr class="mobile-divider">
  <a href="consulta_tickets.php" style="background:rgba(23,70,234,.12);color:#8db4ff;border-color:rgba(23,70,234,.4)">Consultar ticket</a>
  <a href="login.php">Iniciar sesión</a>
  <a href="registro.php" style="background:linear-gradient(135deg,#1746EA,#1883ED);color:#fff;border-color:transparent;text-align:center">Crear cuenta →</a>
</div>

<!-- ══ PAGE HEADER ══ -->
<div class="page-header">
  <div class="container">
    <div class="ph-tag reveal">Seguimiento de servicio</div>
    <h1 class="reveal reveal-delay-1">Consulta tu <em>Estado</em></h1>
    <p class="reveal reveal-delay-2">Ingresa tu código único de seguimiento y ve el progreso de tu solicitud en tiempo real. No necesitas iniciar sesión.</p>
  </div>
</div>

<!-- ══ MAIN CONTENT ══ -->
<div class="main-content">
  <div class="container">
    <div class="ct-grid reveal reveal-delay-2">

      <!-- ─ PANEL IZQUIERDO: formulario ─ -->
      <div class="ct-input-panel">
        <div class="cip-label">Código de Ticket</div>
        <div class="cip-title">Busca tu ticket</div>
        <p class="cip-sub">Ingresa el código que recibiste al registrar tu equipo.</p>

        <label class="field-label" for="ticketInput">Número de ticket</label>
        <div class="field-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/>
          </svg>
          <input
            id="ticketInput"
            class="ct-input"
            type="text"
            placeholder="Ej: MT-1234"
            maxlength="12"
            autocomplete="off"
            spellcheck="false"
          >
        </div>

        <button class="btn-consultar" id="btnConsultar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          Consultar estado
        </button>

        <div class="quick-tickets">
          <div class="qt-label">Prueba un ejemplo</div>
          <div class="qt-chips">
            <div class="qt-chip" data-ticket="MT-8842">MT-8842</div>
            <div class="qt-chip" data-ticket="MT-8843">MT-8843</div>
            <div class="qt-chip" data-ticket="MT-8844">MT-8844</div>
          </div>
        </div>

        <div class="cip-help">
          <p>¿No recuerdas tu código de ticket? Escríbenos directamente y te ayudamos a recuperarlo. <a href="https://wa.me/51903208170" target="_blank" rel="noopener">Contactar soporte →</a></p>
        </div>
      </div>

      <!-- ─ PANEL DERECHO: resultado ─ -->
      <div class="ct-result-panel">

        <!-- Estado vacío -->
        <div class="result-empty" id="resultEmpty">
          <div class="re-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/>
            </svg>
          </div>
          <div class="re-title">Resultado de búsqueda</div>
          <p class="re-text">Ingresa tu código de ticket en el formulario de la izquierda y presiona <strong style="color:var(--txt-main)">"Consultar estado"</strong> para ver el progreso de tu equipo aquí.</p>
        </div>

        <!-- Resultado del ticket -->
        <div class="result-card" id="resultCard">

          <!-- Cabecera -->
          <div class="rc-header">
            <div>
              <div class="rc-header-label">Resultado de búsqueda</div>
              <div class="rc-ticket-id" id="rcTicketId">Ticket #MT-8844</div>
              <div class="rc-device" id="rcDevice">Laptop Apple MacBook · Diagnóstico técnico</div>
            </div>
            <div class="status-badge reparacion" id="rcStatusBadge">En reparación</div>
          </div>

          <!-- Barra de progreso + etapas -->
          <div class="rc-progress">
            <div class="rcp-bar-wrap">
              <div class="rcp-bar-fill" id="rcBarFill" style="width: 0%"></div>
            </div>
            <div class="rcp-steps">
              <!-- Paso 1 -->
              <div class="rcp-step" id="step1">
                <div class="rcp-step-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="rcp-step-name">Recibido</div>
              </div>
              <!-- Paso 2 -->
              <div class="rcp-step" id="step2">
                <div class="rcp-step-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <div class="rcp-step-name">En diagnóstico</div>
              </div>
              <!-- Paso 3 -->
              <div class="rcp-step" id="step3">
                <div class="rcp-step-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div class="rcp-step-name">En reparación</div>
              </div>
              <!-- Paso 4 -->
              <div class="rcp-step" id="step4">
                <div class="rcp-step-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="rcp-step-name">Completado</div>
              </div>
            </div>
          </div>

          <!-- Actualización del sistema -->
          <div class="rc-update">
            <div class="rcu-inner">
              <div class="rcu-dot-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
              <div>
                <div class="rcu-title">Actualización del sistema</div>
                <div class="rcu-text" id="rcUpdateText">
                  Tu equipo completó el diagnóstico satisfactoriamente. Actualmente se encuentra en el área de reparación con nuestro técnico asignado, quien está trabajando en el reemplazo de componentes identificados.
                </div>
                <div class="rcu-eta" id="rcEta">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Tiempo estimado: 24–48 horas
                </div>
              </div>
            </div>
          </div>

          <!-- Detalles técnicos -->
          <div class="rc-details">
            <div class="rcd-title">Información del servicio</div>
            <div class="rcd-grid" id="rcdGrid">
              <div class="rcd-item">
                <div class="rcd-key">Técnico asignado</div>
                <div class="rcd-val">Ángel Morales</div>
              </div>
              <div class="rcd-item">
                <div class="rcd-key">Fecha de ingreso</div>
                <div class="rcd-val" id="rcdFecha">—</div>
              </div>
              <div class="rcd-item">
                <div class="rcd-key">Tipo de servicio</div>
                <div class="rcd-val" id="rcdServicio">Diagnóstico técnico</div>
              </div>
              <div class="rcd-item">
                <div class="rcd-key">Estado actual</div>
                <div class="rcd-val" id="rcdEstado">En reparación</div>
              </div>
            </div>
          </div>

        </div><!-- /result-card -->
      </div><!-- /ct-result-panel -->
    </div><!-- /ct-grid -->
  </div><!-- /container -->
</div><!-- /main-content -->

<!-- ══ FEATURES STRIP ══ -->
<div class="features-strip">
  <div class="container">
    <div class="fs-grid">
      <div class="fs-item reveal">
        <div class="fs-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
          <div class="fs-title">Seguimiento 24/7</div>
          <div class="fs-text">Consulta el estado de tu equipo en cualquier momento, desde cualquier dispositivo, sin necesidad de llamar.</div>
        </div>
      </div>
      <div class="fs-item reveal reveal-delay-1">
        <div class="fs-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
          <div class="fs-title">Garantía certificada</div>
          <div class="fs-text">Todos nuestros servicios incluyen garantía sobre el trabajo realizado. Tu equipo en buenas manos.</div>
        </div>
      </div>
      <div class="fs-item reveal reveal-delay-2">
        <div class="fs-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div>
          <div class="fs-title">Actualizaciones en tiempo real</div>
          <div class="fs-text">Cada avance en tu equipo se registra al instante. Siempre sabrás en qué etapa se encuentra tu servicio.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ══ SOPORTE CTA ══ -->
<div class="support-cta" id="contacto">
  <div class="container">
    <div class="sc-inner reveal">
      <div class="sc-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
        </svg>
      </div>
      <div class="sc-title">¿Necesitas más información?</div>
      <p class="sc-sub">Si tienes dudas sobre el estado de tu equipo o necesitas hablar con un técnico, escríbenos directamente.</p>
      <div class="sc-buttons">
        <a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="btn-wa">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          Chatear ahora
        </a>
        <a href="tel:+51903208170" class="btn-call">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.77 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 5.9 5.9l.92-.92a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          Llamar a soporte
        </a>
      </div>
    </div>
  </div>
</div>

<!-- ══ FOOTER ══ -->
<footer class="footer">
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
          <li><a href="index.php">Inicio</a></li>
          <li><a href="index.php#servicios">Servicios</a></li>
          <li><a href="index.php#como-funciona">Cómo funciona</a></li>
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
      <div>
        <div class="footer-col-title">Contacto</div>
        <div class="footer-contact">
          <div class="fc-item">
            <div class="fc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></div>
            <div class="fc-text"><a href="https://wa.me/51903208170" target="_blank" style="color:inherit">+51 903 208 170 (WhatsApp)</a></div>
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
/* ══ Navbar scroll ══ */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 20);
});

/* ══ Hamburger ══ */
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

/* ══ Reveal ══ */
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
}, { threshold: 0.10 });
reveals.forEach(el => observer.observe(el));

/* ══ Datos de demo ══ */
const TICKETS = {
  'MT-8842': {
    device: 'Laptop HP Pavilion',
    service: 'Mantenimiento correctivo',
    status: 1, // 1=recibido 2=diagnostico 3=reparacion 4=completado
    statusLabel: 'Recibido',
    statusClass: 'recibido',
    updateText: 'Tu equipo acaba de ser recibido y registrado en nuestro sistema. Un técnico ha sido asignado y comenzará el diagnóstico en breve.',
    eta: 'Inicio de diagnóstico: dentro de 2–4 horas',
    tecnico: 'Ángel Morales',
    fecha: '20/05/2026',
  },
  'MT-8843': {
    device: 'PC Escritorio',
    service: 'Repotenciación',
    status: 2,
    statusLabel: 'En diagnóstico',
    statusClass: 'diagnostico',
    updateText: 'Tu equipo está siendo analizado por nuestro técnico. Estamos identificando los componentes a mejorar y calculando el alcance del servicio de repotenciación.',
    eta: 'Diagnóstico estimado: 4–6 horas',
    tecnico: 'Ángel Morales',
    fecha: '19/05/2026',
  },
  'MT-8844': {
    device: 'Laptop Apple MacBook',
    service: 'Diagnóstico técnico',
    status: 3,
    statusLabel: 'En reparación',
    statusClass: 'reparacion',
    updateText: 'Tu equipo completó el diagnóstico satisfactoriamente. Actualmente se encuentra en el área de reparación con nuestro técnico asignado, quien está trabajando en el reemplazo de componentes identificados.',
    eta: 'Tiempo estimado: 24–48 horas',
    tecnico: 'Ángel Morales',
    fecha: '18/05/2026',
  },
};

/* Porcentaje de progreso por estado */
const PROGRESS = { 1: 12, 2: 40, 3: 72, 4: 100 };

function normalize(raw) {
  return raw.trim().toUpperCase().replace(/^MT-?/i, 'MT-');
}

function renderTicket(key) {
  const data = TICKETS[key];
  if (!data) return false;

  /* Cabecera */
  document.getElementById('rcTicketId').textContent = 'Ticket #' + key;
  document.getElementById('rcDevice').textContent = data.device + ' · ' + data.service;

  /* Badge */
  const badge = document.getElementById('rcStatusBadge');
  badge.textContent = data.statusLabel;
  badge.className = 'status-badge ' + data.statusClass;

  /* Barra de progreso */
  setTimeout(() => {
    document.getElementById('rcBarFill').style.width = PROGRESS[data.status] + '%';
  }, 100);

  /* Pasos */
  for (let i = 1; i <= 4; i++) {
    const el = document.getElementById('step' + i);
    el.className = 'rcp-step';
    if (i < data.status)  el.classList.add('done');
    if (i === data.status) el.classList.add('active');
  }

  /* Actualización */
  document.getElementById('rcUpdateText').textContent = data.updateText;
  document.getElementById('rcEta').innerHTML =
    `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>${data.eta}`;

  /* Detalles */
  document.getElementById('rcdFecha').textContent    = data.fecha;
  document.getElementById('rcdServicio').textContent = data.service;
  document.getElementById('rcdEstado').textContent   = data.statusLabel;

  return true;
}

function doSearch() {
  const raw   = document.getElementById('ticketInput').value;
  const key   = normalize(raw);
  const empty = document.getElementById('resultEmpty');
  const card  = document.getElementById('resultCard');

  /* Reset barra */
  document.getElementById('rcBarFill').style.width = '0%';

  if (renderTicket(key)) {
    empty.style.display = 'none';
    card.classList.add('visible');
    card.scrollIntoView({ behavior: 'smooth', block: 'start' });
  } else {
    card.classList.remove('visible');
    empty.style.display = '';
    empty.querySelector('.re-title').textContent = 'Ticket no encontrado';
    empty.querySelector('.re-text').innerHTML =
      'No encontramos un ticket con el código <strong style="color:var(--txt-main)">' + (raw || '—') + '</strong>. Verifica el código e inténtalo de nuevo, o <a href="https://wa.me/51903208170" target="_blank" style="color:#8db4ff;font-weight:600">contáctanos por WhatsApp</a>.';
  }
}

/* Botón consultar */
document.getElementById('btnConsultar').addEventListener('click', doSearch);

/* Enter en el input */
document.getElementById('ticketInput').addEventListener('keydown', e => {
  if (e.key === 'Enter') doSearch();
});

/* Chips de ejemplo */
document.querySelectorAll('.qt-chip').forEach(chip => {
  chip.addEventListener('click', () => {
    document.getElementById('ticketInput').value = chip.dataset.ticket;
    doSearch();
  });
});
</script>
</body>
</html>