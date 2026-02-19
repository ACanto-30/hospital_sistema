<?php
$this->assign('title', 'Dashboard - Cajero');

$nombreRol = 'Cajero';
$nombreUsuario = 'Usuario';

if (!empty($user)) {
    $nombreRol = (!empty($user->role) && !empty($user->role->nombre_rol))
        ? $user->role->nombre_rol
        : 'Cajero';

    $nombreUsuario = $user->nombre_completo ?? $user->nombre_usuario ?? 'Usuario';
}

$statusFilter = $statusFilter ?? 'pending';
?>

<div class="dashboard">
  <div class="dashboard-page dashboard-cashier">

      <div class="dashboard-header">
          <div>
              <h2 class="dashboard-title">Dashboard de <?= h($nombreRol) ?></h2>
              <p class="dashboard-subtitle">Bienvenido, <?= h($nombreUsuario) ?></p>
          </div>

          <div class="dashboard-meta">
              <!--<?= $this->Html->link(
                  'Registrar pago',
                  ['plugin' => 'Payments', 'controller' => 'Payments', 'action' => 'pay'],
                  ['class' => 'btn btn-primary']
              ) ?>-->

              <?= $this->Html->link(
                  'Cerrar sesión',
                  ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
                  ['class' => 'btn btn-primary btn-logout']
              ) ?>
          </div>
      </div>

      <hr class="divider">

      <!-- Summary Grid -->
      <div class="summary-grid">
          <div class="summary-card <?= $statusFilter === 'pending' ? 'active-tab' : '' ?>"
               onclick="window.location='?status=pending'" style="cursor: pointer;">
              <div class="card-content">
                  <div class="card-info">
                      <strong class="section-title">Pendientes</strong>
                      <p class="muted">Por aprobar (<?= (int) $resumen['pendientes_totales'] ?>)</p>
                  </div>
                  <div class="card-icon"><span>⏳</span></div>
              </div>
          </div>

          <div class="summary-card <?= $statusFilter === 'processed' ? 'active-tab' : '' ?>"
               onclick="window.location='?status=processed'" style="cursor: pointer;">
              <div class="card-content">
                  <div class="card-info">
                      <strong class="section-title">Procesados</strong>
                      <p class="muted">Hoy: <?= (int) $resumen['pagos_procesados_hoy'] ?></p>
                  </div>
                  <div class="card-icon"><span>✅</span></div>
              </div>
          </div>

          <div class="summary-card stats-card" onclick="window.location='?status=all'" style="cursor: pointer;">
              <div class="card-content">
                  <div class="card-info">
                      <strong class="section-title">Cobrado Hoy</strong>
                      <p class="muted">B/. <?= number_format((float) $resumen['cobrado_hoy'], 2) ?></p>
                  </div>
                  <div class="card-icon"><span>💰</span></div>
              </div>
          </div>

          <div class="summary-card stats-card">
              <div class="card-content">
                  <div class="card-info">
                      <strong class="section-title">Registros</strong>
                      <p class="muted"><?= $this->Paginator->counter('Total: {{count}}') ?></p>
                  </div>
                  <div class="card-icon"><span>📊</span></div>
              </div>
          </div>
      </div>

      <div class="summary-card">
          <div class="card-header">
              <h3 class="section-title">
                  <?php
                  if ($statusFilter === 'pending') echo "Pagos Pendientes de Aprobación";
                  elseif ($statusFilter === 'processed') echo "Pagos Procesados Hoy";
                  else echo "Listado General de Pagos";
                  ?>
              </h3>
              <span class="muted"><?= $this->Paginator->counter('Página {{page}} de {{pages}}') ?></span>
          </div>

          <hr class="divider">

          <div class="table-responsive">
              <table class="dash-table">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Miembro</th>
                          <th>Concepto</th>
                          <th>Monto</th>
                          <th>Método</th>
                          <th>Estado</th>
                          <th>Fecha</th>
                          <th>Acciones</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($pagos as $p): ?>
                          <tr>
                              <td>#<?= h($p->id) ?></td>
                              <td><?= h(($p->payment->associate->first_name ?? '-') . ' ' . ($p->payment->associate->last_name ?? '')) ?></td>
                              <td>Pago a Cuenta</td>
                              <td><strong>B/. <?= number_format((float) $p->amount, 2) ?></strong></td>
                              <td><?= h($p->payment_method->name ?? 'N/A') ?></td>
                              <td>
                                  <?php if ((int) $p->payment_status_id === 2): ?>
                                      <span class="dash-badge dash-badge--ok">Aprobado</span>
                                  <?php elseif ((int) $p->payment_status_id === 1): ?>
                                      <span class="dash-badge dash-badge--off">Pendiente</span>
                                  <?php else: ?>
                                      <span class="dash-badge" style="background:#fee2e2; color:#b91c1c;">Rechazado</span>
                                  <?php endif; ?>
                              </td>
                              <td><?= $p->payment_date ? $p->payment_date->format('d/m/Y H:i') : 'N/A' ?></td>
                              <td class="actions">
                                  <button type="button" class="action-icon" onclick="toggleEdit('edit-row-<?= $p->id ?>')" title="Procesar Pago">📝</button>
                                  <?php if ($p->proof_image): ?>
                                      <button type="button" class="action-icon" onclick="viewImage('image-modal-<?= $p->id ?>')" title="Ver Comprobante">👁️</button>
                                  <?php endif; ?>
                              </td>
                          </tr>

                          <tr id="edit-row-<?= $p->id ?>" class="edit-row" style="display: none;">
                              <td colspan="8">
                                  <div class="edit-container payment-edit">
                                      <div class="payment-grid">

                                          <div class="payment-image-section">
                                              <label class="muted small mb-2 d-block">Comprobante de Pago</label>
                                              <?php if ($p->proof_image):
                                                  $secureUrl = $this->Url->build([
                                                      '_name' => 'serve_receipt',
                                                      'id' => $p->id
                                                  ]);
                                              ?>
                                                  <div class="receipt-preview-container" onclick="openLightbox('<?= $secureUrl ?>')">
                                                      <img src="<?= $secureUrl ?>" alt="Comprobante Seguro" class="receipt-img-large">
                                                      <div class="overlay"><span>Haga clic para ampliar (Acceso Protegido)</span></div>
                                                  </div>
                                              <?php else: ?>
                                                  <div class="no-image">No hay comprobante disponible</div>
                                              <?php endif; ?>
                                          </div>

                                          <div class="payment-fields-section">
                                              <?= $this->Form->create(null, ['url' => ['_name' => 'process_payment', 'id' => $p->id]]) ?>

                                              <div class="form-group mb-4">
                                                  <label class="muted small">Monto Recibido</label>
                                                  <div class="input-with-symbol">
                                                      <span class="symbol">B/.</span>
                                                      <?= $this->Form->control('amount', [
                                                          'label' => false,
                                                          'value' => $p->amount,
                                                          'class' => 'form-input',
                                                          'type' => 'number',
                                                          'step' => '0.01'
                                                      ]) ?>
                                                  </div>
                                              </div>

                                              <div class="form-group mb-4">
                                                  <label class="muted small">Estado del Pago</label>
                                                  <?= $this->Form->control('payment_status_id', [
                                                      'label' => false,
                                                      'type' => 'select',
                                                      'options' => $paymentStatuses,
                                                      'value' => $p->payment_status_id,
                                                      'class' => 'form-select',
                                                      'required' => true,
                                                      'empty' => false
                                                  ]) ?>
                                              </div>

                                              <div class="edit-actions">
                                                  <?= $this->Form->button('Guardar Cambios', ['class' => 'btn-save']) ?>
                                                  <button type="button" class="btn-cancel" onclick="toggleEdit('edit-row-<?= $p->id ?>')">Cancelar</button>
                                              </div>

                                              <?= $this->Form->end() ?>
                                          </div>

                                      </div>
                                  </div>
                              </td>
                          </tr>
                      <?php endforeach; ?>

                      <?php if (count($pagos) === 0): ?>
                          <tr>
                              <td colspan="8" style="text-align:center; padding: 40px;">No hay pagos en esta categoría.</td>
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

<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="Ampliado">
</div>

<style>
   
    .dashboard-page.dashboard-cashier{
        padding: 20px 0;
        max-width: 100%;
    }

    .dashboard-page.dashboard-cashier .summary-grid{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }

    .dashboard-page.dashboard-cashier .summary-card{
        background: white !important;
        border-radius: 16px !important;
        padding: 25px !important;
        box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid #f1f5f9 !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
        transform: scale(1) !important;
        opacity: 0.9 !important;
        margin-top: 0 !important;
        width: 100%;
    }

    .dashboard-page.dashboard-cashier .summary-card:hover{
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
        opacity: 1 !important;
        z-index: 2;
    }

    .dashboard-page.dashboard-cashier .active-tab{
        border-color: #0b8f55 !important;
        box-shadow: 0 10px 15px -3px rgba(11, 143, 85, 0.2) !important;
        transform: scale(1.05) !important;
        opacity: 1 !important;
        z-index: 5 !important;
    }

    .dashboard-page.dashboard-cashier .active-tab .section-title{
        color: #0b8f55 !important;
    }

    .dashboard-page.dashboard-cashier .card-content{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dashboard-page.dashboard-cashier .card-info .section-title{
        color: #334155;
        font-size: 1.1rem;
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
    }

    .dashboard-page.dashboard-cashier .card-info .muted{
        color: #64748b;
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
    }

    .dashboard-page.dashboard-cashier .card-icon{
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

    .dashboard-page.dashboard-cashier .dash-table{
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 15px;
    }

    .dashboard-page.dashboard-cashier .dash-table th{
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

    .dashboard-page.dashboard-cashier .dash-table td{
        padding: 18px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
        color: #334155;
        vertical-align: middle;
    }

    .dashboard-page.dashboard-cashier .dash-table tr:hover td{
        background-color: #f8fafc;
    }

    .dashboard-page.dashboard-cashier .action-icon{
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        width: 38px;
        height: 38px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 5px;
        color: #475569;
    }

    .dashboard-page.dashboard-cashier .action-icon:hover{
        background: #e2e8f0;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        color: #0f172a;
    }

    .dashboard-page.dashboard-cashier .dash-badge{
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        letter-spacing: 0.02em;
    }

    .dashboard-page.dashboard-cashier .dash-badge--ok{
        background: #d1fae5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .dashboard-page.dashboard-cashier .dash-badge--off{
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }

    .dashboard-page.dashboard-cashier .payment-grid{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: start;
    }

    .dashboard-page.dashboard-cashier .edit-container{
        padding: 25px;
        background: #fff;
        border-radius: 12px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0;
        margin: 15px 0;
    }

    .dashboard-page.dashboard-cashier .receipt-preview-container{
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        cursor: zoom-in;
        max-height: 400px;
    }

    .dashboard-page.dashboard-cashier .receipt-img-large{
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s;
    }

    .dashboard-page.dashboard-cashier .receipt-preview-container:hover .receipt-img-large{
        transform: scale(1.02);
    }

    .dashboard-page.dashboard-cashier .overlay{
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 0.8rem;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .dashboard-page.dashboard-cashier .receipt-preview-container:hover .overlay{
        opacity: 1;
    }

    .dashboard-page.dashboard-cashier .input-with-symbol{
        display: flex;
        align-items: center;
        position: relative;
    }

    .dashboard-page.dashboard-cashier .input-with-symbol .symbol{
        position: absolute;
        left: 12px;
        color: #64748b;
        font-weight: 600;
        z-index: 2;
    }

    .dashboard-page.dashboard-cashier .input-with-symbol input{
        padding-left: 45px !important;
        font-size: 1.1rem;
        font-weight: 600;
        color: #0b8f55;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        height: 45px;
    }

    .dashboard-page.dashboard-cashier .form-select{
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        height: 45px;
        padding: 0 15px;
    }

    .dashboard-page.dashboard-cashier .edit-actions{
        display: flex;
        gap: 15px;
        margin-top: 25px;
    }

    .dashboard-page.dashboard-cashier .btn-save{
        background: #0b8f55;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
        transition: background 0.2s;
    }

    .dashboard-page.dashboard-cashier .btn-save:hover{
        background: #097d4a;
    }

    .dashboard-page.dashboard-cashier .btn-cancel{
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
    }

    .dashboard-page.dashboard-cashier .btn-cancel:hover{
        background: #f1f5f9;
        color: #334155;
    }

    /* Lightbox */
    .dashboard-page.dashboard-cashier ~ .lightbox,
    .lightbox{
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .lightbox img{
        max-width: 90%;
        max-height: 90%;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        border-radius: 4px;
    }

    /* Utilities */
    .dashboard-page.dashboard-cashier .d-block{ display:block; }
    .dashboard-page.dashboard-cashier .mb-2{ margin-bottom:0.5rem; }
    .dashboard-page.dashboard-cashier .mb-4{ margin-bottom:1.5rem; }
    .dashboard-page.dashboard-cashier .muted{ color:#64748b; }
    .dashboard-page.dashboard-cashier .small{ font-size:0.85rem; }
</style>

<script>
    function toggleEdit(id){
        let row = document.getElementById(id);
        let isVisible = row.style.display !== 'none';

        // Cerrar SOLO las filas del cajero
        document.querySelectorAll('.dashboard-page.dashboard-cashier .edit-row').forEach(r => r.style.display = 'none');

        if (!isVisible) row.style.display = 'table-row';
    }

    function openLightbox(src){
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').style.display = 'flex';
    }

    function closeLightbox(){
        document.getElementById('lightbox').style.display = 'none';
    }

    function viewImage(id){
        const part = id.split('-').pop();
        toggleEdit('edit-row-' + part);
    }
</script>
