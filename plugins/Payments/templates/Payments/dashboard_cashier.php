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
                ['plugin' => 'Payments', 'controller' => 'Payments', 'action' => 'pay'],
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
                                <td>#<?= h($p->id) ?></td>
                                <td><?= h(($p->associate->first_name ?? '-') . ' ' . ($p->associate->last_name ?? '')) ?></td>
                                <td>Pago de Cuota</td>
                                <td>B/. <?= number_format((float) $p->amount, 2) ?></td>
                                <td><?= h($p->payment_method->name ?? 'N/A') ?></td>
                                <td>
                                    <?php if ((int) $p->payment_status_id === 2): ?>
                                        <span class="status-active"><?= h($p->payment_status->name) ?></span>
                                    <?php elseif ((int) $p->payment_status_id === 1): ?>
                                        <span class="dash-badge dash-badge--off">Pendiente</span>
                                    <?php else: ?>
                                        <span class="muted"><?= h($p->payment_status->name ?? 'N/A') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $p->payment_date ? $p->payment_date->format('d/m/Y H:i') : 'N/A' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($pagos->isEmpty()): ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">No hay pagos registrados hoy.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="side-col">

            <div class="notification">
                <strong>Resumen</strong>
                <div class="muted notification-text">
                    Total cobrado hoy: <strong>B/. <?= number_format((float) $resumen['cobrado_hoy'], 2) ?></strong><br>
                    Pagos procesados hoy: <strong><?= (int) $resumen['pagos_hoy'] ?></strong><br>
                    Pendientes: <strong><?= (int) $resumen['pendientes'] ?></strong>
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