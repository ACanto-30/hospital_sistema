<?php
$this->assign('title', 'Dashboard - Administrador');

$adminNombre = $currentUser->full_name ?? $currentUser->username ?? 'Administrador';
?>

<div class="dashboard-page">

  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title">Dashboard Administrador</h2>
      <p class="dashboard-subtitle">
        Bienvenido, <?= h($adminNombre) ?> (<?= h($currentUser->role->name ?? 'Administrador') ?>)
      </p>
    </div>
  </div>

  <hr class="divider">

  <div class="summary-grid">
    <div class="summary-card <?= $listType === 'users' ? 'active-tab' : '' ?>" onclick="window.location='?type=users'" style="cursor: pointer;">
      <div class="card-content">
        <div class="card-info">
          <strong class="section-title">Usuarios</strong>
          <p class="muted">Listado general de usuarios</p>
        </div>
        <div class="card-icon">
          <span>👥</span>
        </div>
      </div>
    </div>

    <div class="summary-card <?= $listType === 'associates' ? 'active-tab' : '' ?>" onclick="window.location='?type=associates'" style="cursor: pointer;">
      <div class="card-content">
        <div class="card-info">
          <strong class="section-title">Asociados</strong>
          <p class="muted">Listado de asociados y planes</p>
        </div>
        <div class="card-icon">
          <span>🤝</span>
        </div>
      </div>
    </div>

    <div class="summary-card stats-card">
      <div class="card-content">
        <div class="card-info">
          <strong class="section-title">Registros</strong>
          <p class="muted"><?= $this->Paginator->counter('Total: {{count}}') ?></p>
        </div>
        <div class="card-icon">
          <span>📊</span>
        </div>
      </div>
    </div>
  </div>

  <div class="summary-card">
    <div class="card-header">
      <h3 class="section-title"><?= $listType === 'associates' ? 'Asociados Miembros' : 'Usuarios del Sistema' ?></h3>
      <span class="muted"><?= $this->Paginator->counter('Página {{page}} de {{pages}}') ?></span>
    </div>

    <hr class="divider">

    <div class="table-responsive">
      <table class="dash-table">
        <thead>
          <tr>
            <?php if ($listType === 'users'): ?>
              <th><?= $this->Paginator->sort('id', 'ID') ?></th>
              <th><?= $this->Paginator->sort('username', 'Usuario') ?></th>
              <th><?= $this->Paginator->sort('full_name', 'Nombre') ?></th>
              <th><?= $this->Paginator->sort('email', 'Correo') ?></th>
              <th>Rol</th>
              <th><?= $this->Paginator->sort('status', 'Estado') ?></th>
              <th>Acciones</th>
            <?php else: ?>
              <th>ID</th>
              <th>Cédula</th>
              <th>Asociado</th>
              <th>Plan Actual</th>
              <th>Estado</th>
              <th>Acciones</th>
            <?php endif; ?>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($data as $item): ?>
            <?php if ($listType === 'users'): ?>
              <tr>
                <td><?= h($item->id) ?></td>
                <td><?= h($item->username) ?></td>
                <td><?= h($item->full_name) ?></td>
                <td><?= h($item->email) ?></td>
                <td><?= h($item->role->name ?? 'Sin rol') ?></td>
                <td>
                  <?php if (($item->status ?? '') === 'activo'): ?>
                    <span class="dash-badge dash-badge--ok">activo</span>
                  <?php else: ?>
                    <span class="dash-badge dash-badge--off"><?= h($item->status ?? 'inactivo') ?></span>
                  <?php endif; ?>
                </td>
                <td class="actions">
                  <?php if (($item->status ?? '') === 'activo'): ?>
                    <?= $this->Form->postLink(
                      '<span title="Desactivar">🚫</span>',
                      ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'toggleUserStatus', $item->id],
                      ['confirm' => '¿Estás seguro de desactivar este usuario?', 'escape' => false, 'class' => 'action-icon']
                    ) ?>
                  <?php else: ?>
                    <?= $this->Form->postLink(
                      '<span title="Activar">✔️</span>',
                      ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'toggleUserStatus', $item->id],
                      ['confirm' => '¿Estás seguro de activar este usuario?', 'escape' => false, 'class' => 'action-icon active']
                    ) ?>
                  <?php endif; ?>
                </td>
              </tr>
            <?php else: ?>
              <tr>
                <td><?= h($item->id) ?></td>
                <td><?= h($item->id_card) ?></td>
                <td><?= h($item->first_name . ' ' . $item->last_name) ?></td>
                <td>
                  <span class="dash-badge dash-badge--ok" style="background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe;">
                    <?= h($item->insurance_plan->name ?? 'Sin Plan') ?>
                  </span>
                </td>
                <td>
                  <span class="dash-badge <?= ($item->member_status ?? '') === 'active' ? 'dash-badge--ok' : 'dash-badge--off' ?>">
                    <?= h($item->member_status ?? 'inactivo') ?>
                  </span>
                </td>
                <td class="actions">
                  <button type="button" class="action-icon" onclick="toggleEdit('edit-row-<?= $item->id ?>')" title="Editar Plan">
                    📝
                  </button>
                  <?php if (($item->user->status ?? '') === 'activo'): ?>
                    <?= $this->Form->postLink(
                      '<span title="Desactivar Usuario">🚫</span>',
                      ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'toggleUserStatus', $item->user_id],
                      ['confirm' => '¿Estás seguro de desactivar este usuario?', 'escape' => false, 'class' => 'action-icon']
                    ) ?>
                  <?php else: ?>
                    <?= $this->Form->postLink(
                      '<span title="Activar Usuario">✔️</span>',
                      ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'toggleUserStatus', $item->user_id],
                      ['confirm' => '¿Estás seguro de activar este usuario?', 'escape' => false, 'class' => 'action-icon active']
                    ) ?>
                  <?php endif; ?>
                </td>
              </tr>
              <!-- Row expandible para edición -->
              <tr id="edit-row-<?= $item->id ?>" class="edit-row" style="display: none;">
                <td colspan="6">
                  <div class="edit-container">
                    <?= $this->Form->create(null, ['url' => ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'editAssociatePlan', $item->id]]) ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                      <div>
                        <label class="muted small">Nombre(s)</label>
                        <?= $this->Form->control('first_name', ['label' => false, 'value' => $item->first_name, 'class' => 'form-input']) ?>
                      </div>
                      <div>
                        <label class="muted small">Apellido(s)</label>
                        <?= $this->Form->control('last_name', ['label' => false, 'value' => $item->last_name, 'class' => 'form-input']) ?>
                      </div>
                      <div>
                        <label class="muted small">Teléfono</label>
                        <?= $this->Form->control('phone', ['label' => false, 'value' => $item->phone, 'class' => 'form-input']) ?>
                      </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px; margin-bottom: 15px;">
                      <div>
                        <label class="muted small">Dirección</label>
                        <?= $this->Form->control('address', ['label' => false, 'value' => $item->address, 'class' => 'form-input']) ?>
                      </div>
                      <div>
                        <label class="muted small">Nuevo Plan de Seguro</label>
                        <?= $this->Form->control('plan_id', [
                          'label' => false,
                          'type' => 'select',
                          'options' => $insurancePlans,
                          'default' => $item->plan_id,
                          'class' => 'form-select'
                        ]) ?>
                      </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 15px; align-items: end;">
                      <div>
                        <label class="muted small">Motivo del cambio de plan (opcional)</label>
                        <?= $this->Form->control('reason', [
                          'label' => false,
                          'type' => 'text',
                          'placeholder' => 'Ej: Solicitud por mejor cobertura o actualización de datos',
                          'class' => 'form-input'
                        ]) ?>
                      </div>
                      <div style="display: flex; gap: 5px;">
                        <?= $this->Form->button('Guardar', ['class' => 'btn-save', 'style' => 'flex: 1;']) ?>
                        <button type="button" class="btn-cancel" onclick="toggleEdit('edit-row-<?= $item->id ?>')" style="flex: 1;">X</button>
                      </div>
                    </div>
                    <?= $this->Form->end() ?>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <ul class="pagination">
      <?= $this->Paginator->first('<< Primero') ?>
      <?= $this->Paginator->prev('< Anterior') ?>
      <?= $this->Paginator->numbers(['modulus' => 5]) ?>
      <?= $this->Paginator->next('Siguiente >') ?>
      <?= $this->Paginator->last('Último >>') ?>
    </ul>

  </div>

</div>

<style>
  .btn-deactivate {
    color: #ef4444;
    text-decoration: none;
    font-size: 0.85em;
    font-weight: 600;
  }

  .btn-edit {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    padding: 5px 10px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85em;
    transition: all 0.2s;
  }

  .btn-edit:hover {
    background: #e5e7eb;
  }

  .action-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    transition: all 0.2s;
    text-decoration: none;
    line-height: 1;
    padding: 0;
  }

  .action-icon:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
  }

  .action-icon.active {
    color: #059669;
  }

  .action-icon.active:hover {
    background: #ecfdf5;
    border-color: #10b981;
  }

  .edit-row {
    background-color: #f9fafb;
    transition: all 0.3s ease;
  }

  .edit-container {
    padding: 20px;
    border-left: 4px solid #4f46e5;
    margin: 10px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .form-select,
  .form-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.9em;
  }

  .btn-save {
    background: #4f46e5;
    color: white;
    border: none;
    padding: 9px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
  }

  .btn-cancel {
    background: white;
    color: #6b7280;
    border: 1px solid #d1d5db;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    margin-left: 5px;
  }

  .actions {
    white-space: nowrap;
  }

  /* Estilos para el active-tab y normalización */
  .summary-grid .summary-card {
    position: relative;
    overflow: visible;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 2px solid transparent;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 110px;
    margin-top: 0 !important;
    padding: 20px !important;
    background: #fff;
    box-sizing: border-box;
  }

  .summary-grid .card-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }

  .summary-grid .card-info {
    flex: 1;
  }

  .summary-grid .card-icon {
    font-size: 2rem;
    opacity: 0.6;
    margin-left: 15px;
  }

  /* Tarjeta SELECCIONADA: Se hace más grande */
  .summary-grid .summary-card.active-tab {
    background: #ffffff;
    border-color: #157347;
    box-shadow: 0 20px 35px rgba(21, 115, 71, 0.2);
    transform: scale(1.1);
    /* Efecto de crecimiento notable */
    z-index: 5;
  }

  /* Tarjetas NO seleccionadas: Se hacen un poco más pequeñas y se opacan */
  .summary-grid .summary-card:not(.active-tab):not(.stats-card) {
    opacity: 0.8;
    transform: scale(0.94);
  }

  .summary-grid .summary-card.active-tab::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: linear-gradient(90deg, #157347, #34d399);
  }

  .summary-grid .summary-card.active-tab .section-title {
    color: #157347;
    font-size: 1.1em;
  }

  .summary-grid .summary-card:hover:not(.stats-card):not(.active-tab) {
    opacity: 1;
    transform: scale(1);
    border-color: rgba(21, 115, 71, 0.3);
  }

  .stats-card {
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    opacity: 0.9;
  }

  .summary-grid .summary-card .muted {
    font-size: 0.85rem;
    margin-top: 4px;
    margin-bottom: 0;
  }
</style>

<script>
  function toggleEdit(rowId) {
    const row = document.getElementById(rowId);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
  }
</script>

</div>