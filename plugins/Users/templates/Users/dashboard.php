<?php

$nombreRol = (!empty($user->role) && !empty($user->role->nombre_rol))
    ? $user->role->nombre_rol
    : 'Usuario';

$nombreUsuario = $user->nombre_completo ?? $user->nombre_usuario ?? 'Usuario';

$this->assign('title', 'Dashboard - ' . $nombreRol);
?>

<div class="dashboard-page">

    <div class="dashboard-header">
        <div>
            <h2 class="dashboard-title">Dashboard de <?= h($nombreRol) ?></h2>
            <p class="dashboard-subtitle">Bienvenido, <?= h($nombreUsuario) ?></p>
        </div>

        <div class="dashboard-meta">
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
            <h3 class="section-title">Panel en construcción</h3>
            <p class="muted">Estoy trabajando en las funcionalidades específicas para este rol.</p>

            <div class="dashboard-actions">
                <span class="status-active">Activo</span>
            </div>

            <div class="benefits-box">
                <strong class="benefits-title">Próximos módulos</strong>
                <ul class="benefits-list">
                    <li>Pagos (Usuario)</li>
                    <li>Pagos (Cajero)</li>
                    <li>Pacientes (Médico)</li>
                </ul>
            </div>
        </div>

        <div class="side-col">
            <div class="notification">
                <strong>Nota</strong>
                <div class="muted notification-text">
                    Este dashboard es general. El middleware se encarga de redirigir cada rol a su dashboard específico.
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
