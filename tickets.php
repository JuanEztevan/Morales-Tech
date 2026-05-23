<?php
// include("includes/auth.php");
$filtro_activo = isset($_GET['estado']) ? $_GET['estado'] : 'Todos';
$tickets = [
    ["id" => "MT-8842", "cliente" => "Valeria Ramírez",  "tipo" => "Laptop", "marca" => "HP",    "servicio" => "Reparación",              "estado" => "En diagnóstico"],
    ["id" => "MT-8843", "cliente" => "Andrés Ochante",   "tipo" => "PC",     "marca" => "Apple", "servicio" => "Repotenciación",          "estado" => "Recibido"],
    ["id" => "MT-8844", "cliente" => "Diana Calderón",   "tipo" => "Laptop", "marca" => "Apple", "servicio" => "Mantenimiento correctivo","estado" => "En reparación"],
    ["id" => "MT-8845", "cliente" => "Brenda Benites",   "tipo" => "Laptop", "marca" => "ASUS",  "servicio" => "Limpieza preventiva",     "estado" => "Completado"],
];
if ($filtro_activo !== 'Todos') {
    $tickets = array_filter($tickets, function($t) use ($filtro_activo) {
        return $t['estado'] === $filtro_activo;
    });
}
$estados = ['Todos', 'Recibido', 'En diagnóstico', 'En reparación', 'Completado'];
function clase_estado($estado) {
    $mapa = [
        'Recibido'       => 'badge--recibido',
        'En diagnóstico' => 'badge--diagnostico',
        'En reparación'  => 'badge--reparacion',
        'Completado'     => 'badge--completado',
    ];
    return $mapa[$estado] ?? 'badge--default';
}
function clase_filtro($estado) {
    $mapa = [
        'Recibido'       => 'filter--recibido',
        'En diagnóstico' => 'filter--diagnostico',
        'En reparación'  => 'filter--reparacion',
        'Completado'     => 'filter--completado',
        'Todos'          => 'filter--todos',
    ];
    return $mapa[$estado] ?? '';
}
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets — Morales Tech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ══════════════════════════════════════════════
           VARIABLES — idénticas a ventas.php
        ══════════════════════════════════════════════ */
        :root {
            --azul-prof: #000019;
            --azul-tec:  #1746EA;
            --celeste:   #1883ED;
            --border:    #e6e9f0;
            --bg:        #ffffff;
            --surface:   #ffffff;
            --surface2:  #f4f6fb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg);          /* ← blanco #ffffff igual que ventas */
            color: var(--azul-prof);
            min-height: 100vh;
        }

        /* ══ LAYOUT ══ */
        .app-shell { display: flex; min-height: 100vh; }
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; width: 0; background: var(--bg); }

        /* ══════════════════════════════════════════════
           SIDEBAR — copiado exacto de ventas.php
        ══════════════════════════════════════════════ */
        .sidebar {
            width: 80px; min-width: 80px;
            background: linear-gradient(180deg, #fff 0%, #edf1fd 100%);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column; align-items: center;
            height: 100vh; position: sticky; top: 0; flex-shrink: 0;
        }
        .sidebar__logo { width: 100%; display: flex; justify-content: center; padding: 18px 0 14px; }
        .sidebar__isotipo { width: 38px; height: auto; }
        .sidebar__nav {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 2px; width: 100%; padding: 12px 6px;
        }
        .nav-link {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; gap: 4px;
            width: 62px; height: 62px; border-radius: 12px;
            text-decoration: none; color: #7a8096;
            font-size: 10px; font-weight: 600;
            transition: all .18s; text-align: center;
        }
        .nav-link svg { width: 20px; height: 20px; flex-shrink: 0; }
        .nav-link:hover { background: rgba(255,255,255,.8); color: var(--azul-prof); }
        .nav-link--active { background: #fff; color: var(--azul-tec); font-weight: 700; box-shadow: 0 3px 10px rgba(23,70,234,.10); }
        .sidebar__footer { width: 100%; display: flex; flex-direction: column; align-items: center; padding: 12px 6px 24px; }
        .sidebar__logout {
            display: flex; flex-direction: column; align-items: center;
            gap: 3px; text-decoration: none; color: #a0a8bb;
            font-size: 10px; font-weight: 600;
            padding: 6px 10px; border-radius: 10px;
            transition: background .15s, color .15s;
            width: 62px; text-align: center;
        }
        .sidebar__logout:hover { background: rgba(255,255,255,.8); color: #c94a00; }
        .sidebar__logout svg { width: 18px; height: 18px; }

        /* ══════════════════════════════════════════════
           HEADER — copiado exacto de ventas.php
        ══════════════════════════════════════════════ */
        .header {
            background: #fff; border-bottom: 1px solid var(--border);
            padding: 0 28px; height: 65px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 10; flex-shrink: 0; width: 100%;
        }
        .header__breadcrumb { font-size: 13px; font-weight: 500; color: #a0a8bb; }
        .header__breadcrumb span { color: var(--azul-prof); font-weight: 600; }
        .header__user { display: flex; align-items: center; gap: 10px; }
        .header__user-info { display: flex; flex-direction: column; align-items: flex-start; }
        .header__avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--azul-tec), var(--celeste));
            border-radius: 50%; display: grid; place-items: center;
            font-size: 13px; font-weight: 700; color: #fff;
        }
        .header__username { font-size: 13px; font-weight: 700; color: var(--azul-prof); line-height: 1.1; }
        .header__user-role { font-size: 11px; font-weight: 500; color: #a0a8bb; }

        /* ══ PAGE ══ */
        .page { padding: 28px; flex: 1; background: var(--bg); }
        .page__header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 24px; gap: 16px; flex-wrap: wrap;
        }
        .page__title { font-size: 28px; font-weight: 800; letter-spacing: -.5px; color: var(--azul-prof); margin-bottom: 4px; }
        .page__subtitle { font-size: 13px; color: #a0a8bb; }

        /* ══ BOTÓN REGISTRAR TICKET — igual estilo que "Registrar venta" en ventas.php ══ */
        .btn-new-ticket {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--azul-prof); color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px; font-weight: 700;
            padding: 10px 20px; border-radius: 50px; border: none;
            cursor: pointer; text-decoration: none; white-space: nowrap;
            box-shadow: 0 3px 10px rgba(0,0,25,.22);
            transition: background .15s, transform .1s;
        }
        .btn-new-ticket:hover { background: var(--azul-tec); transform: translateY(-1px); }
        .btn-new-ticket svg { width: 14px; height: 14px; }

        /* ══ FILTROS ══ */
        .filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
        .filter-btn {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px; font-weight: 600;
            padding: 7px 16px; border-radius: 50px;
            border: 1.5px solid var(--border);
            background: #fff; color: #7a8096;
            text-decoration: none; transition: all .15s; cursor: pointer;
        }
        /* Todos */
        .filter--todos:hover                   { border-color: var(--azul-tec); color: var(--azul-tec); }
        .filter--todos.filter-btn--active      { background: var(--azul-tec); border-color: var(--azul-tec); color: #fff; }
        /* Recibido */
        .filter--recibido:hover                { border-color: #e09a00; color: #9a6c00; }
        .filter--recibido.filter-btn--active   { background: #f5a623; border-color: #f5a623; color: #fff; }
        /* En diagnóstico */
        .filter--diagnostico:hover             { border-color: var(--azul-tec); color: var(--azul-tec); }
        .filter--diagnostico.filter-btn--active{ background: var(--azul-tec); border-color: var(--azul-tec); color: #fff; }
        /* En reparación */
        .filter--reparacion:hover              { border-color: #c94a00; color: #c94a00; }
        .filter--reparacion.filter-btn--active { background: #e85d04; border-color: #e85d04; color: #fff; }
        /* Completado */
        .filter--completado:hover              { border-color: #1a7a4a; color: #1a7a4a; }
        .filter--completado.filter-btn--active { background: #1a7a4a; border-color: #1a7a4a; color: #fff; }

        /* ══ TABLA ══ */
        .table-card {
            background: #fff; border: 1px solid var(--border);
            border-radius: 14px; overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,25,.05);
        }
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 560px; }
        thead th {
            padding: 12px 18px; font-size: 10px; font-weight: 700;
            letter-spacing: .09em; text-transform: uppercase;
            color: #a0a8bb; text-align: left;
            border-bottom: 1px solid var(--border);
            white-space: nowrap; background: #fafbff;
        }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f7f8fd; }
        tbody td { padding: 13px 18px; font-size: 13px; vertical-align: middle; }

        .ticket-id { font-size: 13px; font-weight: 800; color: var(--azul-prof); }
        .cliente-cell { display: flex; align-items: center; gap: 9px; }
        .cliente-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: #edf1fd; color: var(--azul-tec);
            font-size: 10px; font-weight: 700;
            display: grid; place-items: center; flex-shrink: 0;
        }
        .cliente-nombre { font-weight: 500; }
        .marca-tipo  { font-size: 11px; color: #a0a8bb; }
        .marca-nombre { font-weight: 700; color: var(--azul-prof); }

        /* ══ SELECT DE ESTADO — colores por texto, actualizables via JS ══ */
        .estado-select {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px; font-weight: 600;
            padding: 6px 28px 6px 11px;
            border-radius: 20px; border: 1.5px solid transparent;
            cursor: pointer; appearance: none; -webkit-appearance: none;
            outline: none;
            /* Chevron único, tamaño fijo, sin repetición */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%23a0a8bb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 9px center;
            background-size: 11px 11px;
            transition: background-color .2s, color .2s, border-color .2s;
        }
        /* Clases de color — SOLO color de fondo, texto y borde. Sin background-image adicional. */
        .badge--recibido    { background-color: #fff8e6; color: #9a6c00; border-color: #f0dfa0; }
        .badge--diagnostico { background-color: #edf1fd; color: #1746EA; border-color: #c4d0f8; }
        .badge--reparacion  { background-color: #fff0eb; color: #c94a00; border-color: #f5c4a8; }
        .badge--completado  { background-color: #e6f8f0; color: #1a7a4a; border-color: #a8dfc4; }
        .badge--default     { background-color: var(--surface2); color: #7a8096; border-color: var(--border); }

        .empty-state { padding: 50px 24px; text-align: center; color: #a0a8bb; }
        .empty-state svg { width: 36px; height: 36px; margin-bottom: 10px; opacity: .3; display: block; margin-inline: auto; }
        .empty-state p { font-size: 14px; font-weight: 600; margin-bottom: 4px; }
        .empty-state small { font-size: 12px; }
        .table-footer { padding: 12px 18px; border-top: 1px solid var(--border); font-size: 12px; color: #a0a8bb; }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 700px) {
            .sidebar { width: 60px; min-width: 60px; }
            .nav-link { width: 46px; height: 50px; font-size: 0; }
            .sidebar__logout span { display: none; }
            .header__user-info { display: none; }
            .page { padding: 18px 14px; }
            .header { padding: 0 14px; }
        }
        @media (max-width: 480px) { .sidebar { display: none; } }
    </style>
</head>
<body>
<div class="app-shell">

    <!-- ══ SIDEBAR ══ -->
    <aside class="sidebar">
        <div class="sidebar__logo">
            <img src="img/isotipo-color.png" alt="Morales Tech" class="sidebar__isotipo">
        </div>
        <nav class="sidebar__nav">
            <a href="dashboard.php" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="tickets.php" class="nav-link nav-link--active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                Tickets
            </a>
            <a href="inventario.php" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                Inventario
            </a>
            <a href="ventas.php" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Ventas
            </a>
        </nav>
        <div class="sidebar__footer">
            <a href="logout.php" class="sidebar__logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Salir</span>
            </a>
        </div>
    </aside>

    <!-- ══ ÁREA PRINCIPAL ══ -->
    <div class="main">
        <header class="header">
            <div class="header__breadcrumb">Panel / <span>Tickets</span></div>
            <div class="header__user">
                <div class="header__avatar"><?= $inicial ?></div>
                <div class="header__user-info">
                    <span class="header__username"><?= htmlspecialchars($nombre_corto) ?></span>
                    <span class="header__user-role"><?= htmlspecialchars($rol_usuario) ?></span>
                </div>
            </div>
        </header>

        <main class="page">
            <div class="page__header">
                <div>
                    <h1 class="page__title">Tickets</h1>
                    <p class="page__subtitle">Gestiona los tickets de soporte técnico</p>
                </div>
                <a href="nuevo_ticket.php" class="btn-new-ticket">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Registrar ticket
                </a>
            </div>

            <!-- Filtros -->
            <div class="filters">
                <?php foreach ($estados as $estado):
                    $url_filtro = ($estado === 'Todos') ? 'tickets.php' : 'tickets.php?estado=' . urlencode($estado);
                    $es_activo  = ($filtro_activo === $estado);
                    $clase_f    = clase_filtro($estado);
                ?>
                    <a href="<?= $url_filtro ?>"
                       class="filter-btn <?= $clase_f ?> <?= $es_activo ? 'filter-btn--active' : '' ?>">
                        <?= htmlspecialchars($estado) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Tabla -->
            <div class="table-card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID del ticket</th>
                                <th>Nombre del cliente</th>
                                <th>Marca</th>
                                <th>Servicio solicitado</th>
                                <th>Estado del ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($tickets)): ?>
                            <tr><td colspan="5">
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <p>No hay tickets con este estado</p>
                                    <small>Prueba otro filtro o registra un nuevo ticket</small>
                                </div>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($tickets as $t): ?>
                            <tr>
                                <td><span class="ticket-id">#<?= htmlspecialchars($t['id']) ?></span></td>
                                <td>
                                    <div class="cliente-cell">
                                        <div class="cliente-avatar">
                                            <?php
                                                $p = explode(' ', $t['cliente']);
                                                echo strtoupper(substr($p[0],0,1)) . (isset($p[1]) ? strtoupper(substr($p[1],0,1)) : '');
                                            ?>
                                        </div>
                                        <span class="cliente-nombre"><?= htmlspecialchars($t['cliente']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="marca-tipo"><?= htmlspecialchars($t['tipo']) ?> · </span>
                                    <span class="marca-nombre"><?= htmlspecialchars($t['marca']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($t['servicio']) ?></td>
                                <td>
                                    <select class="estado-select <?= clase_estado($t['estado']) ?>"
                                            onchange="actualizarEstado(this)">
                                        <option <?= $t['estado']==='Recibido'       ? 'selected' : '' ?>>Recibido</option>
                                        <option <?= $t['estado']==='En diagnóstico' ? 'selected' : '' ?>>En diagnóstico</option>
                                        <option <?= $t['estado']==='En reparación'  ? 'selected' : '' ?>>En reparación</option>
                                        <option <?= $t['estado']==='Completado'     ? 'selected' : '' ?>>Completado</option>
                                    </select>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <?php
                        $total = count($tickets);
                        echo $total . ' ticket' . ($total !== 1 ? 's' : '');
                        if ($filtro_activo !== 'Todos')
                            echo ' &middot; filtro: <strong>' . htmlspecialchars($filtro_activo) . '</strong>';
                    ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
/* Mapa texto → clase CSS */
const BADGE_MAP = {
    'Recibido':       'badge--recibido',
    'En diagnóstico': 'badge--diagnostico',
    'En reparación':  'badge--reparacion',
    'Completado':     'badge--completado',
};
const ALL_BADGES = Object.values(BADGE_MAP).concat(['badge--default']);

function actualizarEstado(select) {
    /* Quitar todas las clases de estado anteriores */
    ALL_BADGES.forEach(cls => select.classList.remove(cls));
    /* Agregar la clase correspondiente al nuevo valor */
    const nuevaClase = BADGE_MAP[select.value] || 'badge--default';
    select.classList.add(nuevaClase);
}
</script>
</body>
</html>