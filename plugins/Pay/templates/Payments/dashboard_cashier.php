<?php
$nombreRol = 'Cajero';
$nombreUsuario = 'kenneth';

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$pagos = [
    [
        'usuario' => 'Juan Pérez',
        'concepto' => 'Consulta médica',
        'monto' => 35.00,
        'fecha' => '27/09/2026',
        'estado' => 'Pagado'
    ],
    [
        'usuario' => 'María Gómez',
        'concepto' => 'Laboratorio clínico',
        'monto' => 60.00,
        'fecha' => '26/09/2026',
        'estado' => 'Pendiente'
    ],
    [
        'usuario' => 'Carlos Ruiz',
        'concepto' => 'Rayos X',
        'monto' => 120.00,
        'fecha' => '25/09/2026',
        'estado' => 'Pagado'
    ],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard Cajero</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #0f7c4a, #0a5d37);
}

/* HEADER */
.header {
    background: linear-gradient(90deg, #0f7c4a, #0a5d37);
    color: white;
    padding: 16px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* BOTÓN CERRAR SESIÓN (CORREGIDO) */
.header button {
    background: #0a5d37;
    border: none;
    color: #ffffff;
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.header button:hover {
    background: #094f30;
}

/* CARD PRINCIPAL */
.container {
    background: white;
    margin: 30px;
    border-radius: 16px;
    padding: 30px;
}

/* TITULO */
.dashboard-title {
    font-size: 24px;
    font-weight: bold;
    color: #0f7c4a;
}

.dashboard-subtitle {
    color: #555;
    margin-bottom: 25px;
}

/* CARDS SUPERIORES */
.stats {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    flex: 1;
    background: #f9f9f9;
    padding: 20px;
    border-radius: 12px;
    border-left: 6px solid #0f7c4a;
}

.stat-card.yellow {
    border-left-color: #f1c40f;
}

.stat-card h4 {
    margin: 0;
    color: #0f7c4a;
}

/* BANDEJA GMAIL */
.inbox {
    margin-top: 20px;
}

.inbox-item {
    display: flex;
    justify-content: space-between;
    padding: 14px 16px;
    border-bottom: 1px solid #eee;
    text-decoration: none;
    color: #000;
}

.inbox-item:hover {
    background: #f5f5f5;
}

.inbox-left strong {
    display: block;
}

.inbox-right {
    text-align: right;
    font-size: 14px;
}

.unread {
    background: #fff6f6;
    border-left: 5px solid #e74c3c;
}
</style>
</head>

<body>

<div class="header">
    <strong>🏥 Hospital Privado X</strong>
    <button>Cerrar sesión</button>
</div>

<div class="container">

    <div class="dashboard-title">Dashboard Cajero</div>
    <div class="dashboard-subtitle">
        Bienvenido, <?= h($nombreUsuario) ?> (<?= h($nombreRol) ?>)
    </div>

    <!-- CARDS -->
    <div class="stats">
        <div class="stat-card">
            <h4>Pagos</h4>
            Total del día: 3
        </div>
        <div class="stat-card yellow">
            <h4>Acceso</h4>
            Cajero
        </div>
        <div class="stat-card">
            <h4>Registros</h4>
            Pendientes: 1
        </div>
    </div>

    <!-- BANDEJA -->
    <div class="inbox">
        <h3 style="color:#0f7c4a;">Pagos recibidos</h3>

        <?php foreach ($pagos as $p): ?>
            <a href="detalle_pago.php" class="inbox-item <?= $p['estado'] === 'Pendiente' ? 'unread' : '' ?>">
                <div class="inbox-left">
                    <strong><?= h($p['usuario']) ?></strong>
                    <?= h($p['concepto']) ?>
                </div>

                <div class="inbox-right">
                    <strong>B/. <?= number_format($p['monto'],2) ?></strong><br>
                    <?= h($p['fecha']) ?>
                </div>
            </a>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>
