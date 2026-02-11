<?php
$this->assign('title', 'Dashboard Médico');
?>

<div class="dashboard-page">

  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title">Dashboard Médico</h2>
      <p class="dashboard-subtitle">Bienvenido, <?= h($doctorName) ?> • Panel (estático)</p>
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

  <div class="summary-grid">
    <div class="summary-card summary-green">
      <div class="muted">Pacientes hoy</div>
      <h3 class="section-title"><?= (int)$stats['pacientes_hoy'] ?></h3>
    </div>

    <div class="summary-card summary-yellow">
      <div class="muted">Citas pendientes</div>
      <h3 class="section-title"><?= (int)$stats['citas_pendientes'] ?></h3>
    </div>

    <div class="summary-card summary-green">
      <div class="muted">Emergencias</div>
      <h3 class="section-title"><?= (int)$stats['emergencias'] ?></h3>
    </div>
  </div>

  <div class="dashboard-content" style="gap:18px; display:grid; grid-template-columns:2fr 1fr;">
    <div class="summary-card">
      <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3 class="section-title">Agenda de hoy</h3>
        <span class="muted"><?= date('d/m/Y') ?></span>
      </div>

      <table class="dash-table" style="margin-top:12px;">
        <thead>
          <tr>
            <th>Hora</th>
            <th>Paciente</th>
            <th>Motivo</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($agendaHoy as $cita): ?>
          <tr>
            <td><?= h($cita['hora']) ?></td>
            <td><?= h($cita['paciente']) ?></td>
            <td><?= h($cita['motivo']) ?></td>
            <td><?= h($cita['estado']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="summary-card">
      <h3 class="section-title">Pacientes en espera</h3>
      <div class="muted" style="margin-top:6px;">Demo</div>

      <div style="margin-top:12px;display:flex;flex-direction:column;gap:10px;">
        <?php foreach ($enEspera as $p): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;border-radius:14px;border:1px solid #eef5f0;background:#f8fcfa;">
            <div>
              <strong><?= h($p['paciente']) ?></strong>
              <div class="muted">Tiempo: <?= h($p['tiempo']) ?></div>
            </div>
            <span style="display:inline-flex;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:900;background:#ecfdf3;color:#157347;border:1px solid #d7efe2;">
              <?= h($p['prioridad']) ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>
