<?php
$this->assign('title', 'Dashboard Médico');

$nombreUsuario = 'Usuario';
if (!empty($user)) {
    $nombreUsuario = $user->full_name ?? $user->username ?? 'Usuario';
}

// Intentar obtener el conteo total para las tarjetas de resumen
$totalAssociates = 0;
try {
    $totalAssociates = $this->Paginator->param('count');
} catch (\Exception $e) {
    $totalAssociates = count($associates);
}
?>

<div class="dashboard">
  <div class="dashboard-page dashboard-doctor">

      <div class="dashboard-header">
          <div>
              <h2 class="dashboard-title">Dashboard Médico</h2>
              <p class="dashboard-subtitle">Bienvenido, <?= h($doctorName ?? $nombreUsuario) ?></p>
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

      <!-- Summary Grid-->
      <div class="summary-grid">
          <div class="summary-card stats-card">
              <div class="card-content">
                  <div class="card-info">
                      <strong class="section-title">Total Pacientes</strong>
                      <p class="muted"><?= $totalAssociates ?></p>
                  </div>
                  <div class="card-icon">
                      <span>👥</span>
                  </div>
              </div>
          </div>
          <!--
          <div class="summary-card stats-card">
              <div class="card-content">
                  <div class="card-info">
                      <strong class="section-title">Consultas Hoy</strong>
                      <p class="muted">-</p>
                  </div>
                  <div class="card-icon">
                      <span>📅</span>
                  </div>
              </div>
          </div>

          <div class="summary-card stats-card">
              <div class="card-content">
                  <div class="card-info">
                      <strong class="section-title">Pendientes</strong>
                      <p class="muted">-</p>
                  </div>
                  <div class="card-icon">
                      <span>⏳</span>
                  </div>
              </div>
          </div>
          -->
      </div>

      <div class="summary-card main-card">
          <div class="card-header">
              <h3 class="section-title">Listado de Pacientes y Asociados</h3>
              <span class="muted"><?= $this->Paginator->counter('Página {{page}} de {{pages}}') ?></span>
          </div>

          <hr class="divider">

          <div class="table-responsive">
              <table class="dash-table">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Asociado</th>
                          <th>Cédula</th>
                          <th>Acciones</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($associates as $a): ?>
                          <tr>
                              <td>#<?= h($a->id) ?></td>
                              <td><?= h($a->first_name . ' ' . $a->last_name) ?></td>
                              <td><?= h($a->id_card) ?></td>
                              <td class="actions">
                                  <button type="button" class="action-icon" onclick="toggleDetail('detail-row-<?= $a->id ?>')" title="Ver condiciones y expediente">
                                      👁️ Ver Expediente
                                  </button>
                              </td>
                          </tr>

                          <!-- Fila expandible: Condiciones y Expediente Médico -->
                          <tr id="detail-row-<?= $a->id ?>" class="edit-row" style="display: none;">
                              <td colspan="4">
                                  <div class="edit-container payment-edit">
                                      <div class="payment-grid">
                                          <!-- Sección Condiciones -->
                                          <div class="payment-image-section">
                                              <div class="section-header-small">
                                                  <span class="icon">📋</span> Condiciones del Asociado
                                              </div>
                                              <?php if (!empty($a->associates_conditions)): ?>
                                                  <div class="conditions-list">
                                                      <?php foreach ($a->associates_conditions as $ac): ?>
                                                          <div class="condition-pill">
                                                              <?= h($ac->condition->condition ?? 'N/A') ?>
                                                          </div>
                                                      <?php endforeach; ?>
                                                  </div>
                                              <?php else: ?>
                                                  <div class="no-data-box">No hay condiciones registradas.</div>
                                              <?php endif; ?>
                                          </div>

                                          <!-- Sección Expediente Médico -->
                                          <div class="payment-fields-section">
                                              <div class="section-header-small">
                                                  <span class="icon">🩺</span> Historial Médico
                                              </div>
                                              <?php if (!empty($a->medical_records)): ?>
                                                  <div class="medical-records-table-wrap">
                                                      <table class="mini-table">
                                                          <thead>
                                                              <tr>
                                                                  <th>Fecha</th>
                                                                  <th>Diagnóstico</th>
                                                                  <th>Tratamiento</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                                              <?php foreach ($a->medical_records as $mr): ?>
                                                                  <tr>
                                                                      <td><?= $mr->visit_date ? $mr->visit_date->format('d/m/Y') : 'N/A' ?></td>
                                                                      <td><?= h($mr->diagnosis) ?></td>
                                                                      <td><?= h($mr->treatment ?? '-') ?></td>
                                                                  </tr>
                                                              <?php endforeach; ?>
                                                          </tbody>
                                                      </table>
                                                  </div>
                                              <?php else: ?>
                                                  <div class="no-data-box">No hay registros médicos recientes.</div>
                                              <?php endif; ?>
                                          </div>
                                      </div>
                                  </div>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                      <?php if (count($associates) === 0): ?>
                          <tr>
                              <td colspan="4" style="text-align:center; padding: 40px; color: #64748b;">No hay asociados registrados.</td>
                          </tr>
                      <?php endif; ?>
                  </tbody>
              </table>
          </div>

          <ul class="pagination">
              <?= $this->Paginator->first('<< primero') ?>
              <?= $this->Paginator->prev('< anterior') ?>
              <?= $this->Paginator->numbers(['modulus' => 5]) ?>
              <?= $this->Paginator->next('siguiente >') ?>
              <?= $this->Paginator->last('último >>') ?>
          </ul>
      </div>

  </div>
</div>

<style>
  
    .dashboard-page.dashboard-doctor{
        padding: 20px 0;
        max-width: 100%;
    }

    .dashboard-page.dashboard-doctor .summary-grid{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }

    .dashboard-page.dashboard-doctor .summary-card{
        background: white !important;
        border-radius: 16px !important;
        padding: 25px !important;
        box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid #f1f5f9 !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
        width: 100%;
    }

    .dashboard-page.dashboard-doctor .summary-card:hover{
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
    }

    .dashboard-page.dashboard-doctor .main-card{
        border-top: 4px solid #0b8f55 !important;
    }

    .dashboard-page.dashboard-doctor .card-content{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dashboard-page.dashboard-doctor .card-info .section-title{
        color: #334155;
        font-size: 1.1rem;
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
    }

    .dashboard-page.dashboard-doctor .card-info .muted{
        color: #64748b;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .dashboard-page.dashboard-doctor .card-icon{
        font-size: 2.2rem;
        background: #ecfdf5;
        color: #059669;
        padding: 15px;
        border-radius: 12px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dashboard-page.dashboard-doctor .dash-table{
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 15px;
    }

    .dashboard-page.dashboard-doctor .dash-table th{
        background: #f8fafc;
        padding: 16px;
        text-align: left;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
    }

    .dashboard-page.dashboard-doctor .dash-table td{
        padding: 18px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
        color: #334155;
        vertical-align: middle;
    }

    .dashboard-page.dashboard-doctor .dash-table tr:hover td{
        background-color: #f8fafc;
    }

    .dashboard-page.dashboard-doctor .action-icon{
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s;
        color: #475569;
        font-weight: 500;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .dashboard-page.dashboard-doctor .action-icon:hover{
        background: #e2e8f0;
        color: #0f172a;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .dashboard-page.dashboard-doctor .edit-row{
        background-color: #f8fafc;
    }

    .dashboard-page.dashboard-doctor .edit-container{
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0;
        margin: 10px 0;
    }

    .dashboard-page.dashboard-doctor .payment-grid{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: start;
    }

    .dashboard-page.dashboard-doctor .section-header-small{
        font-weight: 700;
        color: #334155;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1rem;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 8px;
    }

    .dashboard-page.dashboard-doctor .conditions-list{
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .dashboard-page.dashboard-doctor .condition-pill{
        background: #ecfdf5;
        color: #047857;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        border: 1px solid #d1fae5;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .dashboard-page.dashboard-doctor .no-data-box{
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        color: #94a3b8;
        font-style: italic;
    }

    .dashboard-page.dashboard-doctor .mini-table{
        width: 100%;
        font-size: 0.9rem;
        border-collapse: collapse;
    }

    .dashboard-page.dashboard-doctor .mini-table th,
    .dashboard-page.dashboard-doctor .mini-table td{
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .dashboard-page.dashboard-doctor .mini-table th{
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        border-radius: 4px 4px 0 0;
    }

    .dashboard-page.dashboard-doctor .medical-records-table-wrap{
        max-height: 350px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .dashboard-page.dashboard-doctor .pagination{
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin-top: 30px;
        gap: 5px;
    }

    .dashboard-page.dashboard-doctor .pagination li a,
    .dashboard-page.dashboard-doctor .pagination li span{
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s;
    }

    .dashboard-page.dashboard-doctor .pagination li.active a,
    .dashboard-page.dashboard-doctor .pagination li.active span{
        background: #0b8f55;
        color: white;
        border-color: #0b8f55;
    }

    .dashboard-page.dashboard-doctor .pagination li a:hover:not(.active){
        background: #f1f5f9;
        color: #0f172a;
    }

    @media (max-width: 900px){
        .dashboard-page.dashboard-doctor .payment-grid{
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    function toggleDetail(id) {
        let row = document.getElementById(id);
        let isVisible = row.style.display !== 'none';

        // Cerrar otros primero para limpieza visual
        document.querySelectorAll('.edit-row').forEach(r => {
            if (r.id !== id) r.style.display = 'none';
        });

        if (!isVisible) {
            row.style.display = 'table-row';
            // Pequeña animación de entrada
            row.style.opacity = '0';
            setTimeout(() => {
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '1';
            }, 10);
        } else {
            row.style.display = 'none';
        }
    }
</script>
