<?php
/**
 * User Dashboard – Life Insurance
 */
$this->assign('title', 'Dashboard de Usuario');
?>

<div class="dashboard-page">

  <!-- Header -->
  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title">Panel de Control</h2>
      <p class="dashboard-subtitle">Bienvenido, <?= h(($associate->first_name ?? 'Usuario') . ' ' . ($associate->last_name ?? '')) ?></p>
    </div>

    <!-- <div class="dashboard-meta">
      <?= $this->Html->link(
        '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px; vertical-align: middle;">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
         </svg>Cerrar Sesión',
        ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
        [
          'class' => 'btn',
          'escape' => false,
          'style' => 'background-color: #ff4757; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.3s ease; border: none; box-shadow: 0 4px 12px rgba(255, 71, 87, 0.2);'
        ]
      ) ?>
    </div> -->
  </div>

  <!-- Summary Cards -->
  <div class="summary-grid">

    <div class="summary-card summary-green">
      <small class="muted">Plan de Seguro</small>
      <h3 class="summary-value" style="font-size: 1.2rem;"><?= h($associate->insurance_plan->name ?? 'Sin Plan') ?></h3>
    </div>

    <div class="summary-card summary-yellow">
      <small class="muted">Cuota Quincenal</small>
      <h3 class="summary-value">$<?= number_format((float) ($associate->insurance_plan->biweekly_fee ?? 0), 2) ?></h3>
    </div>

    <div class="summary-card summary-green">
      <small class="muted">Miembro desde</small>
      <h3 class="summary-value"><?= $associate->registered_at ? $associate->registered_at->format('d/m/Y') : 'N/A' ?></h3>
    </div>

  </div>

  <!-- Main Content -->
  <div class="dashboard-content">

    <!-- Insurance Status -->
    <div class="card">

      <div class="card-header">
        <div>
          <h3 class="section-title">Estado del Seguro</h3>
          <p class="section-subtitle">Detalles de tu cuenta hospitalaria</p>
        </div>
        <span class="status-active"><?= ucfirst(h($associate->member_status ?? 'Desconocido')) ?></span>
      </div>

      <hr class="divider">

      <div class="info-grid">
        <div>
          <small class="muted">Número de Cédula</small>
          <p><strong><?= h($associate->id_card ?? 'N/A') ?></strong></p>
        </div>

        <div>
          <small class="muted">Titular</small>
          <p><strong><?= h(($associate->first_name ?? 'Usuario') . ' ' . ($associate->last_name ?? '')) ?></strong></p>
        </div>

        <div>
          <small class="muted">Correo Electrónico</small>
          <p><strong><?= h($associate->user->email ?? $associate->email ?? 'N/A') ?></strong></p>
        </div>

        <div>
          <small class="muted">Teléfono</small>
          <p><strong><?= h($associate->phone ?? 'Sin teléfono') ?></strong></p>
        </div>
      </div>

      <!-- Coverage Progress (Placeholder con lógica dinámica) -->
      <div class="progress-wrap">
        <small class="muted">Uso del Servicio</small>

        <div class="progress-bar">
          <div class="progress-fill" style="width:10%"></div>
        </div>

        <small class="muted">Perfil completado al 100%</small>
      </div>

      <hr class="divider">

      <p>📅 <strong>Inscrito el:</strong> <?= $associate->registered_at ? $associate->registered_at->format('d \d\e F \d\e Y') : 'N/A' ?></p>
      <p>📍 <strong>Dirección:</strong> <?= h($associate->address ?? 'No registrada') ?></p>

      <!-- Benefits -->
      <div class="benefits-box">
        <strong class="benefits-title">Beneficios incluidos</strong>
        <ul class="benefits-list">
          <li>✔ Cobertura por muerte accidental</li>
          <li>✔ Enfermedades graves</li>
          <li>✔ Asistencia médica 24/7</li>
          <li>✔ Protección familiar</li>
        </ul>
      </div>
    </div>

    <!-- Payments & Notifications -->
    <div class="side-col">

      <!-- Upcoming Payments -->
      <div class="card">
        <h4 class="section-title">Próximos Pagos</h4>

        <div class="pay-box">
          <strong>Prima Mensual</strong>
          <?php $monthlyFee = ($associate->insurance_plan->biweekly_fee ?? 0) * 2; ?>
          <p>$<?= number_format((float) $monthlyFee, 2) ?></p>
          <small class="muted">Vence: <?= (new \DateTime())->format('15/m/Y') ?></small>

          <?= $this->Html->link('Pagar ahora', ['plugin' => 'Payments', 'controller' => 'Payments', 'action' => 'pay'], ['class' => 'btn btn-primary', 'style' => 'text-decoration: none; display: block; text-align: center; margin-top: 10px;']) ?>
        </div>

        <!-- <button class="btn btn-outline" type="button">Ver calendario de pagos</button> -->
      </div>

      <!-- Notifications -->
      <div class="card">
        <h4 class="section-title">Notificaciones</h4>

        <!-- <div class="notification">
          <strong>Tu pago de febrero está próximo a vencer</strong>
          <p class="muted" style="margin:4px 0 0;">Hace 2 días</p>
        </div> -->
      </div>

    </div>
  </div>
</div>