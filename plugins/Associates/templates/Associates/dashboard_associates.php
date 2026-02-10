<?php
/**
 * User Dashboard – Life Insurance (Diseño Mejorado)
 */
$this->assign('title', 'Dashboard de Usuario Mejorado');
?>

<?php $this->append('css'); ?>
<style>
  .dashboard {
    margin: 0;
    min-height: 100vh;
    background: linear-gradient(135deg, #0f6b3f 0%, #1b8a5a 70%) !important;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
  }

  .dashboard-page {
    max-width: 1200px !important;
    margin: 40px auto;
    background: #ffffff;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  }
</style>
<?php $this->end(); ?>

<div class="dashboard-page">

  <!-- Header -->
  <div class="dashboard-header" style="display:flex; justify-content: space-between; align-items:center; margin-bottom:20px;">
    <div>
      <h2 class="dashboard-title" style="margin:0; font-size:1.8rem; font-weight:600;">Panel de Control</h2>
      <p class="dashboard-subtitle" style="margin:4px 0 0; color:#555;">
        Bienvenido, <?= h(($associate->first_name ?? 'Usuario') . ' ' . ($associate->last_name ?? '')) ?>
      </p>
    </div>
    <div>
      <?= $this->Html->link(
        'Cerrar sesión',
        ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
        [
          'class' => 'btn-logout',
          'style' => 'background-color:#2d6a4f; color:white; padding:10px 20px; border-radius:8px; font-weight:600; text-decoration:none; transition:0.3s;'
        ]
      ) ?>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="summary-grid" style="display:flex; gap:20px; margin-bottom:30px; flex-wrap:wrap;">
    <div class="summary-card" style="flex:1; min-width:200px; background:#d9f0e3; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
      <small style="color:#555;">Plan de Seguro</small>
      <h3 style="margin-top:5px; font-size:1.3rem; font-weight:600;">
        <?= h($associate->insurance_plan->name ?? 'Sin Plan') ?>
      </h3>
    </div>

    <div class="summary-card" style="flex:1; min-width:200px; background:#fff4cc; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
      <small style="color:#555;">Cuota Quincenal</small>
      <h3 style="margin-top:5px; font-size:1.3rem; font-weight:600;">
        $<?= number_format((float) ($associate->insurance_plan->biweekly_fee ?? 0), 2) ?>
      </h3>
    </div>

    <div class="summary-card" style="flex:1; min-width:200px; background:#d9f0e3; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
      <small style="color:#555;">Miembro desde</small>
      <h3 style="margin-top:5px; font-size:1.3rem; font-weight:600;">
        <?= $associate->registered_at ? $associate->registered_at->format('d/m/Y') : 'N/A' ?>
      </h3>
    </div>
  </div>

  <!-- Main Content -->
  <div class="dashboard-content" style="display:flex; gap:30px; flex-wrap:wrap;">

    <!-- Insurance & Benefits -->
    <div class="main-col" style="flex:2; min-width:300px;">

      <div class="card" style="background:white; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.05); margin-bottom:30px;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
          <div>
            <h3 style="margin:0; font-weight:600;">Estado del Seguro</h3>
            <p style="margin:4px 0 0; color:#555;">Detalles de tu cuenta hospitalaria</p>
          </div>
          <span style="background:#2d6a4f; color:white; padding:5px 12px; border-radius:20px; font-size:0.85rem;">
            <?= ucfirst(h($associate->member_status ?? 'Desconocido')) ?>
          </span>
        </div>

        <hr style="border:none; border-bottom:1px solid #eee; margin:10px 0;">

        <div class="info-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
          <div><small style="color:#555;">Número de Cédula</small>
            <p><strong><?= h($associate->id_card ?? 'N/A') ?></strong></p>
          </div>
          <div><small style="color:#555;">Titular</small>
            <p><strong><?= h(($associate->first_name ?? 'Usuario') . ' ' . ($associate->last_name ?? '')) ?></strong></p>
          </div>
          <div><small style="color:#555;">Correo Electrónico</small>
            <p><strong><?= h($associate->user->email ?? $associate->email ?? 'N/A') ?></strong></p>
          </div>
          <div><small style="color:#555;">Teléfono</small>
            <p><strong><?= h($associate->phone ?? 'Sin teléfono') ?></strong></p>
          </div>
        </div>

        <div class="progress-wrap" style="margin-bottom:15px;">
          <small style="color:#555;">Uso del Servicio</small>
          <div style="background:#eee; border-radius:10px; overflow:hidden; margin:5px 0; height:12px;">
            <div style="width:100%; background:#2d6a4f; height:100%;"></div>
          </div>
          <small style="color:#555;">Perfil completado al 100%</small>
        </div>

        <hr style="border:none; border-bottom:1px solid #eee; margin:10px 0;">

        <p>📅 <strong>Inscrito el:</strong> <?= $associate->registered_at ? $associate->registered_at->format('d \d\e F \d\e Y') : 'N/A' ?></p>
        <p>📍 <strong>Dirección:</strong> <?= h($associate->address ?? 'No registrada') ?></p>

        <div class="benefits-box" style="margin-top:15px;">
          <strong style="display:block; margin-bottom:8px;">Beneficios incluidos</strong>
          <ul style="list-style:none; padding-left:0; line-height:1.6;">
            <li>✔ Cobertura por muerte accidental</li>
            <li>✔ Enfermedades graves</li>
            <li>✔ Asistencia médica 24/7</li>
            <li>✔ Protección familiar</li>
          </ul>
        </div>
      </div>

    </div>

    <!-- Sidebar -->
    <div class="side-col" style="flex:1; min-width:250px; display:flex; flex-direction:column; gap:20px;">

      <div class="card" style="background:white; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
        <h4 style="margin:0 0 10px; font-weight:600;">Próximos Pagos</h4>
        <?php $monthlyFee = ($associate->insurance_plan->biweekly_fee ?? 0) * 2; ?>
        <p style="margin:5px 0; font-size:1.2rem; font-weight:600;">
          $<?= number_format((float) $monthlyFee, 2) ?>
        </p>

        <?= $this->Html->link(
          'Pagar ahora',
          ['plugin' => 'Payments', 'controller' => 'Payments', 'action' => 'pay'],
          [
            'style' => 'display:block; text-align:center; background:#2d6a4f; color:white; padding:10px; border-radius:8px; margin-top:10px; text-decoration:none; font-weight:600;'
          ]
        ) ?>
      </div>

      <div class="card" style="background:white; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
        <h4 style="margin:0 0 10px; font-weight:600;">Notificaciones</h4>
        <p style="color:#555;">No hay nuevas notificaciones</p>
      </div>

    </div>

  </div>
</div>