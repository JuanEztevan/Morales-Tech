<?php
require_once 'admin_protect.php';
require_once 'conexion.php';

/* --- AJAX: BÚSQUEDA DE CLIENTE POR DNI --- */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'buscar_dni') {
    header('Content-Type: application/json; charset=utf-8');
    $dni = trim($_GET['dni'] ?? '');
    if (strlen($dni) !== 8 || !ctype_digit($dni)) {
        echo json_encode(['found' => false]);
        exit;
    }
    $stmt = $conn->prepare(
        "SELECT idCliente, nombres, apellidos, numRUC FROM CLIENTE WHERE numDNI = ? LIMIT 1"
    );
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($row) {
        echo json_encode([
            'found'     => true,
            'idCliente' => (int) $row['idCliente'],
            'nombres'   => $row['nombres'],
            'apellidos' => $row['apellidos'],
            'ruc'       => $row['numRUC'],
        ]);
    } else {
        echo json_encode(['found' => false]);
    }
    exit;
}

/* --- AJAX: ACTUALIZAR DATOS DEL EQUIPO --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'actualizar_equipo') {
    header('Content-Type: application/json; charset=utf-8');
    $datos = json_decode(file_get_contents('php://input'), true);
    if (!is_array($datos)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    $idEquipo = (int) ($datos['idEquipo'] ?? 0);
    $tipo     = trim($datos['tipo']   ?? '');
    $esLaptop = $tipo === 'Laptop';
    $marca    = $esLaptop ? trim($datos['marca']  ?? '') : '';
    $modelo   = $esLaptop ? trim($datos['modelo'] ?? '') : '';
    $serie    = $esLaptop ? trim($datos['serie']  ?? '') : '';
    $so       = trim($datos['so'] ?? '');

    if ($idEquipo <= 0) {
        echo json_encode(['success' => false, 'message' => 'Equipo no válido.']);
        exit;
    }

    $stmt = $conn->prepare(
        "UPDATE EQUIPO SET marca = ?, modelo = ?, numSerie = ?, sistemaOperativo = ? WHERE idEquipo = ?"
    );
    $stmt->bind_param("ssssi", $marca, $modelo, $serie, $so, $idEquipo);
    $ok = $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => (bool) $ok]);
    exit;
}

/* --- POST: REGISTRAR VENTA --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $datos = json_decode(file_get_contents('php://input'), true);
    if (!is_array($datos)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    $idAdmin     = (int) $_SESSION['idAdmin'];
    $tipoVenta   = trim($datos['tipoVenta']  ?? ''); // 'ticket' | 'producto'
    $nombres     = trim($datos['nombres']    ?? '');
    $apellidos   = trim($datos['apellidos']  ?? '');
    $dni         = trim($datos['dni']        ?? '');
    $ruc         = trim($datos['ruc']        ?? '');
    $idTicketRef = (int) ($datos['idTicket'] ?? 0);
    $metodoPago  = trim($datos['metodoPago'] ?? '');
    $productos   = is_array($datos['productos'] ?? null) ? $datos['productos'] : [];

    $nombreCompleto = trim("$nombres $apellidos");

    if ($tipoVenta !== 'ticket' && $tipoVenta !== 'producto') {
        echo json_encode(['success' => false, 'message' => 'Tipo de venta no especificado.']);
        exit;
    }
    if ($metodoPago === '') {
        echo json_encode(['success' => false, 'message' => 'Selecciona un método de pago.']);
        exit;
    }
    if ($tipoVenta === 'ticket' && $idTicketRef <= 0) {
        echo json_encode(['success' => false, 'message' => 'Selecciona un ticket válido.']);
        exit;
    }
    if ($tipoVenta === 'producto' && ($nombreCompleto === '' || $dni === '')) {
        echo json_encode(['success' => false, 'message' => 'Completa los datos del cliente.']);
        exit;
    }
    if ($tipoVenta === 'producto' && !$productos) {
        echo json_encode(['success' => false, 'message' => 'Añade al menos un producto.']);
        exit;
    }

    $conn->begin_transaction();
    $ok = true;
    $message = '';
    // valida el stock disponible y calcula el subtotal
    $subtotalProd = 0.0;
    $componentesValidados = [];

    if ($ok && $productos) {
        foreach ($productos as $p) {
            $idComponente = (int) ($p['idComponente'] ?? 0);
            $cantidad     = (int) ($p['cantidad'] ?? 0);
            if ($idComponente <= 0 || $cantidad <= 0) { continue; }

            $stmtComp = $conn->prepare(
                "SELECT idComponente, nombre, precioUnitario, stockActual FROM COMPONENTE WHERE idComponente = ? LIMIT 1"
            );
            $stmtComp->bind_param("i", $idComponente);
            $stmtComp->execute();
            $comp = $stmtComp->get_result()->fetch_assoc();
            $stmtComp->close();

            if (!$comp) { $ok = false; $message = 'Uno de los productos seleccionados ya no existe.'; break; }
            if ((int) $comp['stockActual'] < $cantidad) {
                $ok = false;
                $message = 'Stock insuficiente para "' . $comp['nombre'] . '" (disponible: ' . $comp['stockActual'] . ').';
                break;
            }

            $precio = (float) $comp['precioUnitario'];
            $subtotalProd += $precio * $cantidad;
            $componentesValidados[] = [
                'idComponente' => $idComponente,
                'cantidad'     => $cantidad,
                'precio'       => $precio,
                'nombre'       => $comp['nombre'],
            ];
        }
    }

    if (!$ok) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $message ?: 'No se pudo validar el stock.']);
        exit;
    }
    // busca al cliente por DNI o lo crea si no existe
    $idClienteVenta = 0;
    if ($tipoVenta === 'producto') {
        $stmtC = $conn->prepare("SELECT idCliente FROM CLIENTE WHERE numDNI = ? LIMIT 1");
        $stmtC->bind_param("s", $dni);
        $stmtC->execute();
        $rowC = $stmtC->get_result()->fetch_assoc();
        $stmtC->close();

        if ($rowC) {
            $idClienteVenta = (int) $rowC['idCliente'];
        } else {
            $stmtIns = $conn->prepare(
                "INSERT INTO CLIENTE (nombres, apellidos, email, password, numTelefono, numDNI, numRUC,
                 pregunta1, respuesta1, pregunta2, respuesta2, pregunta3, respuesta3)
                 VALUES (?, ?, '', '', '', ?, ?, '', '', '', '', '', '')"
            );
            $stmtIns->bind_param("ssss", $nombres, $apellidos, $dni, $ruc);
            $ok = $stmtIns->execute();
            $idClienteVenta = (int) $stmtIns->insert_id;
            $stmtIns->close();
        }
    }
    // trae los datos del ticket y verifica que no esté ya facturado
    $idTicketFinal    = 0;
    $servicioSubtotal = 0.0;
    $nombresFinal     = $nombres;
    $apellidosFinal   = $apellidos;
    $dniFinal         = $dni;
    $rucFinal         = $ruc;
    $serviciosTicket  = [];

    if ($ok && $tipoVenta === 'ticket') {
        $stmtT = $conn->prepare(
            "SELECT t.idTicket, t.codigo, co.idCotizacion, co.subtotal,
                    c.nombres, c.apellidos, c.numDNI, c.numRUC
             FROM TICKET t
             JOIN COTIZACION co ON co.idCotizacion = t.idCotizacion
             JOIN CLIENTE c     ON c.idCliente     = co.idCliente
             WHERE t.idTicket = ? LIMIT 1"
        );
        $stmtT->bind_param("i", $idTicketRef);
        $stmtT->execute();
        $rowT = $stmtT->get_result()->fetch_assoc();
        $stmtT->close();

        if (!$rowT) {
            $ok = false;
            $message = 'El ticket seleccionado no existe.';
        } else {
            // Evitar doble facturación del mismo ticket
            $stmtChk = $conn->prepare("SELECT idVenta FROM VENTA WHERE idTicket = ? LIMIT 1");
            $stmtChk->bind_param("i", $rowT['idTicket']);
            $stmtChk->execute();
            $yaFacturado = $stmtChk->get_result()->fetch_assoc();
            $stmtChk->close();

            if ($yaFacturado) {
                $ok = false;
                $message = 'Este ticket ya tiene una venta registrada.';
            } else {
                $idTicketFinal    = (int) $rowT['idTicket'];
                $servicioSubtotal = (float) $rowT['subtotal'];
                $nombresFinal     = $rowT['nombres'];
                $apellidosFinal   = $rowT['apellidos'];
                $dniFinal         = $rowT['numDNI'];
                $rucFinal         = $rowT['numRUC'];

                $stmtSrv = $conn->prepare(
                    "SELECT s.nomServicio, s.precio FROM COTIZACION_SERVICIO cs
                     JOIN SERVICIO s ON s.idServicio = cs.idServicio
                     WHERE cs.idCotizacion = ?"
                );
                $stmtSrv->bind_param("i", $rowT['idCotizacion']);
                $stmtSrv->execute();
                $serviciosTicket = $stmtSrv->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmtSrv->close();
            }
        }
    }

    if (!$ok) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $message ?: 'No se pudo procesar la venta.']);
        exit;
    }
    // calcula subtotal, IGV y total; inserta el registro en VENTA
    // nota: para venta de producto, idTicket admite NULL
    $subtotal = round(($tipoVenta === 'ticket' ? $servicioSubtotal : 0) + $subtotalProd, 2);
    $igv      = round($subtotal * 0.18, 2);
    $total    = round($subtotal + $igv, 2);

    $nombreClienteVenta = trim($nombresFinal . ' ' . $apellidosFinal);
    $dniClienteVenta     = $dniFinal;
    $rucClienteVenta     = $rucFinal;

    if ($tipoVenta === 'ticket') {
        $stmtV = $conn->prepare(
            "INSERT INTO VENTA (idTicket, idAdmin, nombreCliente, dniCliente, rucCliente, tipo, metodoPago, subtotal, igv, total)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmtV->bind_param("iisssssddd", $idTicketFinal, $idAdmin, $nombreClienteVenta, $dniClienteVenta, $rucClienteVenta, $tipoVenta, $metodoPago, $subtotal, $igv, $total);
    } else {
        $stmtV = $conn->prepare(
            "INSERT INTO VENTA (idTicket, idAdmin, nombreCliente, dniCliente, rucCliente, tipo, metodoPago, subtotal, igv, total)
             VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmtV->bind_param("isssssddd", $idAdmin, $nombreClienteVenta, $dniClienteVenta, $rucClienteVenta, $tipoVenta, $metodoPago, $subtotal, $igv, $total);
    }
    $ok = $stmtV->execute();
    $idVenta = (int) $stmtV->insert_id;
    $stmtV->close();
    // registra cada producto en DETALLE_VENTA y descuenta el stock
    if ($ok && $componentesValidados) {
        $stmtDet   = $conn->prepare("INSERT INTO DETALLE_VENTA (idVenta, idComponente, cantidad) VALUES (?, ?, ?)");
        $stmtStock = $conn->prepare("UPDATE COMPONENTE SET stockActual = stockActual - ? WHERE idComponente = ? AND stockActual >= ?");
        foreach ($componentesValidados as $cv) {
            $stmtDet->bind_param("iii", $idVenta, $cv['idComponente'], $cv['cantidad']);
            if (!$stmtDet->execute()) { $ok = false; break; }

            $stmtStock->bind_param("iii", $cv['cantidad'], $cv['idComponente'], $cv['cantidad']);
            if (!$stmtStock->execute() || $stmtStock->affected_rows < 1) { $ok = false; break; }
        }
        $stmtDet->close();
        $stmtStock->close();
    }
    // marca el ticket como "Completado"
    if ($ok && $tipoVenta === 'ticket') {
        $stmtEst = $conn->prepare("UPDATE TICKET SET estado = 'Completado' WHERE idTicket = ?");
        $stmtEst->bind_param("i", $idTicketFinal);
        $stmtEst->execute();
        $stmtEst->close();
    }

    if ($ok) {
        $conn->commit();

        // Items para el PDF (servicios del ticket + productos vendidos)
        $itemsPdf = [];
        foreach ($serviciosTicket as $s) {
            $itemsPdf[] = ['nombre' => $s['nomServicio'], 'precio' => (float) $s['precio'], 'cantidad' => 1];
        }
        foreach ($componentesValidados as $cv) {
            $itemsPdf[] = ['nombre' => $cv['nombre'], 'precio' => $cv['precio'], 'cantidad' => $cv['cantidad']];
        }

        echo json_encode([
            'success'    => true,
            'idVenta'    => $idVenta,
            'cliente'    => $nombreClienteVenta,
            'dni'        => $dniClienteVenta,
            'ruc'        => $rucClienteVenta,
            'metodoPago' => $metodoPago,
            'subtotal'   => $subtotal,
            'igv'        => $igv,
            'total'      => $total,
            'items'      => $itemsPdf,
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'No se pudo registrar la venta. Verifica el stock e inténtalo de nuevo.']);
    }
    exit;
}

/* --- CARGA DE LA PÁGINA --- */
$stmt = $conn->prepare("SELECT nombres, apellidos FROM ADMIN WHERE idAdmin = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION['idAdmin']);
$stmt->execute();
$adm = $stmt->get_result()->fetch_assoc();
$stmt->close();

$nombre_usuario = $adm ? $adm['nombres'] . ' ' . $adm['apellidos'] : 'Admin';
$rol_usuario    = 'Administrador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];

// Tickets con servicio completado, listos para facturar (sin venta registrada aún)
$tickets_disponibles = [];
$resTickets = $conn->query(
    "SELECT
        t.idTicket, t.codigo,
        c.nombres, c.apellidos, c.numDNI, c.numRUC,
        e.idEquipo, e.tipoEquipo, e.marca, e.modelo, e.numSerie, e.sistemaOperativo,
        co.idCotizacion, co.subtotal
     FROM TICKET t
     JOIN COTIZACION co ON co.idCotizacion = t.idCotizacion
     JOIN CLIENTE c     ON c.idCliente     = co.idCliente
     JOIN EQUIPO e      ON e.idEquipo      = co.idEquipo
     LEFT JOIN VENTA v  ON v.idTicket      = t.idTicket
     WHERE v.idVenta IS NULL
     ORDER BY t.idTicket DESC
     LIMIT 50"
);
if ($resTickets) {
    while ($row = $resTickets->fetch_assoc()) {
        $stmtSrv = $conn->prepare(
            "SELECT s.nomServicio, s.precio FROM COTIZACION_SERVICIO cs
             JOIN SERVICIO s ON s.idServicio = cs.idServicio
             WHERE cs.idCotizacion = ?"
        );
        $stmtSrv->bind_param("i", $row['idCotizacion']);
        $stmtSrv->execute();
        $serviciosRow = $stmtSrv->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtSrv->close();

        $serviciosArr = array_map(function ($s) {
            return ['nombre' => $s['nomServicio'], 'precio' => (float) $s['precio']];
        }, $serviciosRow);

        $tickets_disponibles[] = [
            "idTicket"    => (int) $row['idTicket'],
            "id"          => $row['codigo'],
            "nombres"     => $row['nombres'],
            "apellidos"   => $row['apellidos'],
            "dni"         => $row['numDNI'],
            "ruc"         => $row['numRUC'],
            "servicio"    => implode(', ', array_column($serviciosArr, 'nombre')),
            "servicios"   => $serviciosArr,
            "subtotal"    => (float) $row['subtotal'],
            "idEquipo"    => (int) $row['idEquipo'],
            "tipo_equipo" => $row['tipoEquipo'],
            "marca"       => $row['marca'],
            "modelo"      => $row['modelo'],
            "serie"       => $row['numSerie'],
            "so"          => $row['sistemaOperativo'],
        ];
    }
}

// Productos del inventario con stock disponible
$productos = [];
$resProd = $conn->query(
    "SELECT idComponente, nombre, categoria, precioUnitario, stockActual
     FROM COMPONENTE WHERE stockActual > 0 ORDER BY categoria, nombre"
);
if ($resProd) {
    while ($row = $resProd->fetch_assoc()) {
        $productos[] = [
            "id"        => (int) $row['idComponente'],
            "nombre"    => $row['nombre'],
            "categoria" => $row['categoria'],
            "precio"    => (float) $row['precioUnitario'],
            "stock"     => (int) $row['stockActual'],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Venta — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <link rel="icon" type="image/png" href="img/isotipo-color.png" media="(prefers-color-scheme: light)">
  <link rel="icon" type="image/png" href="img/isotipo-blanco.png" media="(prefers-color-scheme: dark)">
</head>
<body class="admin-body admin-body--dashboard">

<div class="dash-shell">

  <aside class="dash-sidebar">
    <div class="dash-sidebar__logo">
      <img src="img/isotipo-color.png" alt="Morales Tech" class="dash-sidebar__isotipo"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
      <div class="dash-sidebar__isotipo-fallback">MT</div>
    </div>
    <nav class="dash-sidebar__nav">
      <a href="dashboard.php" class="dash-nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="tickets.php" class="dash-nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
        <span>Tickets</span>
      </a>
      <a href="inventario.php" class="dash-nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        <span>Inventario</span>
      </a>
      <a href="ventas.php" class="dash-nav-link dash-nav-link--active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <span>Ventas</span>
      </a>
    </nav>
    <div class="dash-sidebar__footer">
      <a href="logout.php" class="dash-sidebar__logout">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        <span>Salir</span>
      </a>
    </div>
  </aside>

  <div class="dash-main">
    <header class="dash-header">
      <button class="dash-header__hamburger" id="dash-hamburger" aria-label="Menú">
        <span></span><span></span><span></span>
      </button>
      <div class="dash-header__breadcrumb">
        Panel / <a href="ventas.php" class="dash-header__breadcrumb-link">Ventas</a> / <span>Nueva Venta</span>
      </div>
      <div class="dash-header__user">
        <div class="dash-header__avatar"><?= $inicial ?></div>
        <div class="dash-header__user-info">
          <span class="dash-header__username"><?= htmlspecialchars($nombre_corto) ?></span>
          <span class="dash-header__role"><?= htmlspecialchars($rol_usuario) ?></span>
        </div>
      </div>
    </header>

    <main class="dash-page-content">

      <div class="tk-page-header">
        <div>
          <h1 class="tk-page-title">Nueva Venta</h1>
          <p class="tk-page-subtitle">Registra una venta de servicio técnico o de productos</p>
        </div>
        <a href="ventas.php" class="dash-btn-back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Volver
        </a>
      </div>

      <div class="nv-content-grid">

        <div>

          <div class="nv-card">
            <div class="nv-card__header">
              <div class="ntk-step-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              </div>
              <div>
                <div class="nv-card__title">Tipo de venta</div>
                <div class="nv-card__sub">¿Qué vas a registrar?</div>
              </div>
            </div>
            <div class="nv-vtype-grid">
              <div class="nv-vtype-opt selected" id="vopt-ticket" onclick="nvSelTipo('ticket',this)">
                <div class="nv-vtype-opt__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                </div>
                <div class="nv-vtype-opt__title">Venta por ticket</div>
                <div class="nv-vtype-opt__desc">Servicio técnico concretado, con opción de añadir productos</div>
              </div>
              <div class="nv-vtype-opt" id="vopt-producto" onclick="nvSelTipo('producto',this)">
                <div class="nv-vtype-opt__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <div class="nv-vtype-opt__title">Venta de producto</div>
                <div class="nv-vtype-opt__desc">Venta directa de productos del inventario a un cliente</div>
              </div>
            </div>
          </div>

          <div class="nv-card" id="nv-bloque-ticket">
            <div class="nv-card__header">
              <div class="ntk-step-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
              </div>
              <div>
                <div class="nv-card__title">Ticket asociado</div>
                <div class="nv-card__sub">Selecciona el ticket del servicio completado</div>
              </div>
            </div>
            <div class="ntk-form-group">
              <label>Número de ticket <span class="ntk-req">*</span></label>
              <select id="nv-sel-ticket" class="ntk-input ntk-select" onchange="nvOnTicketChange()">
                <option value="">Seleccionar ticket…</option>
                <?php foreach ($tickets_disponibles as $t): ?>
                <option value="<?= (int) $t['idTicket'] ?>"
                        data-codigo="<?= htmlspecialchars($t['id']) ?>"
                        data-nombres="<?= htmlspecialchars($t['nombres']) ?>"
                        data-apellidos="<?= htmlspecialchars($t['apellidos']) ?>"
                        data-dni="<?= htmlspecialchars($t['dni']) ?>"
                        data-ruc="<?= htmlspecialchars($t['ruc'] ?? '') ?>"
                        data-servicio="<?= htmlspecialchars($t['servicio']) ?>"
                        data-subtotal="<?= $t['subtotal'] ?>"
                        data-idequipo="<?= (int) $t['idEquipo'] ?>"
                        data-tipo="<?= htmlspecialchars($t['tipo_equipo']) ?>"
                        data-marca="<?= htmlspecialchars($t['marca'] ?? '') ?>"
                        data-modelo="<?= htmlspecialchars($t['modelo'] ?? '') ?>"
                        data-serie="<?= htmlspecialchars($t['serie'] ?? '') ?>"
                        data-so="<?= htmlspecialchars($t['so'] ?? '') ?>">
                  <?= htmlspecialchars($t['id']) ?>
                </option>
                <?php endforeach; ?>
              </select>
              <?php if (!$tickets_disponibles): ?>
              <span class="ntk-hint">No hay tickets pendientes de facturar por el momento.</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="nv-card nv-hidden" id="nv-bloque-dispositivo">

            <div class="nv-device-section">
              <div class="nv-device-section__title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                Datos del Ticket
              </div>
              <div class="nv-device-info-grid">
                <div class="nv-device-info-item">
                  <span class="nv-device-info-item__label">N° de Ticket</span>
                  <span class="nv-device-info-item__value" id="nv-di-ticket">—</span>
                </div>
                <div class="nv-device-info-item">
                  <span class="nv-device-info-item__label">Cliente</span>
                  <span class="nv-device-info-item__value" id="nv-di-cliente">—</span>
                </div>
                <div class="nv-device-info-item">
                  <span class="nv-device-info-item__label">Servicio solicitado</span>
                  <span class="nv-device-info-item__value" id="nv-di-servicio">—</span>
                </div>
                <div class="nv-device-info-item">
                  <span class="nv-device-info-item__label">Subtotal</span>
                  <span class="nv-device-info-item__value nv-device-info-item__value--price" id="nv-di-subtotal">S/ —</span>
                </div>
              </div>
            </div>

            <div class="nv-device-divider"></div>

            <div class="nv-device-section">
              <div class="nv-device-section__head">
                <div class="nv-device-section__title">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg>
                  Detalles del Equipo
                </div>

                <div class="nv-equipo-edit-btns" id="nv-equipo-edit-btns">
                  <button class="nv-equipo-btn nv-equipo-btn--edit" id="nv-btn-editar" onclick="nvEquipoEditar()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Editar
                  </button>
                  <button class="nv-equipo-btn nv-equipo-btn--save nv-hidden" id="nv-btn-guardar" onclick="nvEquipoGuardar()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Guardar
                  </button>
                  <button class="nv-equipo-btn nv-equipo-btn--cancel nv-hidden" id="nv-btn-cancelar" onclick="nvEquipoCancelar()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Cancelar
                  </button>
                </div>
              </div>

              <div id="nv-equipo-view">
                <div class="nv-device-info-grid" id="nv-equipo-view-grid">
                  <div class="nv-device-info-item nv-field-laptop-only" id="nv-view-marca-wrap">
                    <span class="nv-device-info-item__label">Marca</span>
                    <span class="nv-device-info-item__value" id="nv-view-marca">—</span>
                  </div>
                  <div class="nv-device-info-item nv-field-laptop-only" id="nv-view-modelo-wrap">
                    <span class="nv-device-info-item__label">Modelo</span>
                    <span class="nv-device-info-item__value nv-device-info-item__value--empty" id="nv-view-modelo">—</span>
                  </div>
                  <div class="nv-device-info-item nv-field-laptop-only" id="nv-view-serie-wrap">
                    <span class="nv-device-info-item__label">N° de Serie</span>
                    <span class="nv-device-info-item__value nv-device-info-item__value--empty" id="nv-view-serie">—</span>
                  </div>
                  <div class="nv-device-info-item">
                    <span class="nv-device-info-item__label">Sistema Operativo</span>
                    <span class="nv-device-info-item__value" id="nv-view-so">—</span>
                  </div>
                </div>
                <div class="nv-equipo-type-badge" id="nv-equipo-type-badge">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg>
                  <span id="nv-equipo-type-text">—</span>
                </div>
              </div>

              <div id="nv-equipo-edit" class="nv-hidden">
                <div class="ntk-form-group nv-field-laptop-only" id="nv-edit-marca-wrap">
                  <label>Marca</label>
                  <input type="text" id="nv-edit-marca" class="ntk-input" placeholder="Ej: HP, Lenovo, Dell…">
                </div>
                <div class="ntk-form-group nv-field-laptop-only" id="nv-edit-modelo-wrap">
                  <label>Modelo <span class="ntk-label-opt">opcional</span></label>
                  <input type="text" id="nv-edit-modelo" class="ntk-input" placeholder="Ej: Pavilion 15-eh2">
                </div>
                <div class="ntk-form-group nv-field-laptop-only" id="nv-edit-serie-wrap">
                  <label>N° de Serie <span class="ntk-label-opt">opcional</span></label>
                  <input type="text" id="nv-edit-serie" class="ntk-input" placeholder="Ej: 5CD2345XYZ">
                </div>
                <div class="ntk-form-group">
                  <label>Sistema Operativo</label>
                  <input type="text" id="nv-edit-so" class="ntk-input" placeholder="Ej: Windows 11 Home">
                </div>
                <p class="nv-edit-hint">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                  Los campos opcionales pueden quedar vacíos y completarse después.
                </p>
              </div>
            </div>

          </div>

          <div class="nv-card nv-hidden" id="nv-bloque-cliente">
            <div class="nv-card__header">
              <div class="ntk-step-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
              <div>
                <div class="nv-card__title">Datos del cliente</div>
                <div class="nv-card__sub">Ingresa el DNI para autocompletar o rellena manualmente</div>
              </div>
            </div>
            <div class="ntk-form-grid">
              <div class="ntk-form-group">
                <label>DNI <span class="ntk-req">*</span></label>
                <div class="ntk-input-wrap">
                  <input type="text" id="nv-cli-dni" placeholder="8 dígitos" maxlength="8" class="ntk-input" autocomplete="off">
                  <span class="ntk-dni-status" id="nv-cli-dni-status"></span>
                </div>
              </div>
              <div class="ntk-form-group">
                <label>RUC <span class="ntk-label-opt">opcional</span></label>
                <input type="text" id="nv-cli-ruc" placeholder="11 dígitos" maxlength="11" class="ntk-input"
                       oninput="this.value=this.value.replace(/\D/g,'')">
              </div>
              <div class="ntk-form-group">
                <label>Nombres <span class="ntk-req">*</span></label>
                <input type="text" id="nv-cli-nombres" placeholder="Ej. Juan Carlos" class="ntk-input"
                       autocomplete="off" oninput="nvUpdateQuote()">
              </div>
              <div class="ntk-form-group">
                <label>Apellidos <span class="ntk-req">*</span></label>
                <input type="text" id="nv-cli-apellidos" placeholder="Ej. García López" class="ntk-input"
                       autocomplete="off" oninput="nvUpdateQuote()">
              </div>
            </div>
          </div>

          <div class="nv-card" id="nv-bloque-productos">
            <div class="nv-card__header">
              <div class="ntk-step-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
              </div>
              <div>
                <div class="nv-card__title" id="nv-prod-card-title">Productos adicionales</div>
                <div class="nv-card__sub" id="nv-prod-card-sub">Añade repuestos o materiales usados en el servicio</div>
              </div>
            </div>
            <div class="nv-prod-list" id="nv-prod-list"></div>
            <?php if (!$productos): ?>
            <span class="ntk-hint">No hay productos con stock disponible en el inventario.</span>
            <?php endif; ?>
            <button class="nv-btn-add-prod" onclick="nvAgregarProd()" <?= !$productos ? 'disabled' : '' ?>>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Añadir producto
            </button>
          </div>

        </div>

        <div class="nv-sticky-right">
          <div class="nv-quote-card">
            <div class="nv-quote-card__title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
              Resumen de Venta
            </div>

            <div class="nv-quote-client nv-hidden" id="nv-q-client">
              <span class="nv-quote-client__name" id="nv-q-client-name">—</span>
            </div>

            <div id="nv-q-empty" class="nv-quote-empty">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
              Selecciona un ticket o<br>añade productos para<br>ver el resumen
            </div>

            <div id="nv-q-details" class="nv-hidden">
              <div class="nv-quote-items" id="nv-q-items"></div>
              <hr class="nv-quote-divider">
              <div class="nv-quote-subtotal"><span>Subtotal</span><span>S/ <span id="nv-q-subtotal">0.00</span></span></div>
              <div class="nv-quote-igv"><span>IGV (18%)</span><span>S/ <span id="nv-q-igv">0.00</span></span></div>
              <hr class="nv-quote-divider">
              <div class="nv-quote-total">
                <span class="nv-quote-total__label">Total</span>
                <span class="nv-quote-total__amount">S/ <span id="nv-q-total">0.00</span></span>
              </div>
            </div>

            <div class="nv-metodo-title">Método de pago</div>
            <div class="nv-metodo-opts">
              <div class="nv-metodo-opt selected" onclick="nvSelMetodo('Yape',this)">
                <div class="nv-metodo-opt__label">Yape</div>
              </div>
              <div class="nv-metodo-opt" onclick="nvSelMetodo('Transferencia',this)">
                <div class="nv-metodo-opt__label">Transferencia</div>
              </div>
              <div class="nv-metodo-opt" onclick="nvSelMetodo('Efectivo',this)">
                <div class="nv-metodo-opt__label">Efectivo</div>
              </div>
            </div>

            <button class="nv-btn-create" id="nv-btn-registrar" onclick="nvGuardarVenta()">Registrar venta</button>
          </div>
        </div>

      </div>

    </main>
  </div>

<div class="dash-admin-mob" id="admin-mob-menu">
  <a href="dashboard.php">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a href="tickets.php">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
    Tickets
  </a>
  <a href="inventario.php">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
    Inventario
  </a>
  <a href="ventas.php" class="dash-admin-mob--active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    Ventas
  </a>
  <hr class="dash-admin-mob__divider">
  <a href="logout.php">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
    Cerrar sesión
  </a>
</div>

</div>

<div class="nv-modal-overlay" id="nv-modal-success">
  <div class="ntk-modal-box" style="max-width:400px;">
    <div class="ntk-modal-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="ntk-modal-title">¡Venta registrada!</div>
    <div class="ntk-modal-sub">La venta ha sido registrada correctamente en el sistema.</div>
    <div class="nv-modal-amount" id="nv-modal-amount">S/ 0.00</div>
    <div class="nv-modal-method" id="nv-modal-method">—</div>
    <button class="nv-modal-btn nv-modal-btn--primary" onclick="nvGenerarPDF()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Descargar boleta PDF
    </button>
    <button class="nv-modal-btn nv-modal-btn--secondary" onclick="window.location.href='ventas.php'">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      Ver ventas
    </button>
  </div>
</div>

<script>
  window.NV_TICKETS   = <?= json_encode($tickets_disponibles, JSON_UNESCAPED_UNICODE) ?>;
  window.NV_PRODUCTOS = <?= json_encode($productos, JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="script.js" defer></script>
</body>
</html>
