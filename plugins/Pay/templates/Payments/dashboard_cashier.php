<?php
$nombreRol = 'Cajero';
$nombreUsuario = 'Usuario';

if (!empty($user)) {
    $nombreRol = (!empty($user->role) && !empty($user->role->nombre_rol))
        ? $user->role->nombre_rol
        : 'Cajero';

    $nombreUsuario = $user->nombre_completo ?? $user->nombre_usuario ?? 'Usuario';
}

$this->assign('title', 'Dashboard - Cajero');
?>

<div class="dashboard-page dashboard-cashier">

    <div class="dashboard-header">
        <div>
            <h2 class="dashboard-title">Dashboard de <?= h($nombreRol) ?></h2>
            <p class="dashboard-subtitle">Bienvenido, <?= h($nombreUsuario) ?></p>
        </div>

        <div class="dashboard-meta">
            <?= $this->Html->link(
                'Registrar pago',
                '#',
                ['class' => 'btn btn-primary']
            ) ?>

            <?= $this->Html->link(
                'Cerrar sesión',
                ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
                ['class' => 'btn btn-primary btn-logout']
            ) ?>
        </div>
    </div>

    <hr class="divider">

    <div class="dashboard-content">

        <div class="card summary-card">
            <h3 class="section-title">Pagos recientes</h3>
            <p class="muted">Vista preliminar (datos estáticos) mientras se corrige la DB.</p>

            <div style="overflow:auto; margin-top:14px;">
                <table class="table table-hover" style="min-width:900px;">
                    <thead>
                        <tr>
                            <th>Recibo</th>
                            <th>Miembro</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagos as $p): ?>
                            <tr>
                                <td><?= h($p['recibo']) ?></td>
                                <td><?= h($p['miembro']) ?></td>
                                <td><?= h($p['concepto']) ?></td>
                                <td>B/. <?= number_format((float)$p['monto'], 2) ?></td>
                                <td><?= h($p['metodo']) ?></td>
                                <td>
                                    <?php if ($p['estado'] === 'Pagado'): ?>
                                        <span class="status-active">Pagado</span>
                                    <?php else: ?>
                                        <span class="muted">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($p['fecha']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="side-col">

            <div class="notification">
                <strong>Resumen</strong>
                <div class="muted notification-text">
                    Total cobrado hoy: <strong>B/. <?= number_format((float)$resumen['cobrado_hoy'], 2) ?></strong><br>
                    Pagos procesados hoy: <strong><?= (int)$resumen['pagos_hoy'] ?></strong><br>
                    Pendientes: <strong><?= (int)$resumen['pendientes'] ?></strong>
                </div>
            </div>

            <div class="card summary-card summary-green">
                <div class="card-header">
                    <strong class="section-title">Acceso</strong>
                    <span class="muted"><?= h($nombreRol) ?></span>
                </div>

                <div class="progress-wrap">
                    <div class="muted">Progreso de implementación</div>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
