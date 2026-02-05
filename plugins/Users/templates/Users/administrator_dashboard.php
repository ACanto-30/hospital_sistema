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
    <div class="summary-card summary-green">
      <strong class="section-title">Usuarios</strong>
      <p class="muted" style="margin-top:6px; margin-bottom:0;">Listado general de usuarios registrados</p>
    </div>

    <div class="summary-card summary-yellow">
      <strong class="section-title">Acceso</strong>
      <p class="muted" style="margin-top:6px; margin-bottom:0;">Solo administrador</p>
    </div>

    <div class="summary-card">
      <strong class="section-title">Registros</strong>
      <p class="muted" style="margin-top:6px; margin-bottom:0;"><?= $this->Paginator->counter('Total: {{count}}') ?></p>
    </div>
  </div>

  <div class="summary-card">
    <div class="card-header">
      <h3 class="section-title">Usuarios registrados</h3>
      <span class="muted"><?= $this->Paginator->counter('Página {{page}} de {{pages}}') ?></span>
    </div>

    <hr class="divider">

    <div class="table-responsive">
      <table class="dash-table">
        <thead>
          <tr>
            <th><?= $this->Paginator->sort('id', 'ID') ?></th>
            <th><?= $this->Paginator->sort('username', 'Usuario') ?></th>
            <th><?= $this->Paginator->sort('full_name', 'Nombre completo') ?></th>
            <th><?= $this->Paginator->sort('email', 'Correo') ?></th>
            <th>Rol</th>
            <th><?= $this->Paginator->sort('status', 'Estado') ?></th>
            <th><?= $this->Paginator->sort('created_at', 'Fecha creación') ?></th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= h($u->id) ?></td>
              <td><?= h($u->username) ?></td>
              <td><?= h($u->full_name) ?></td>
              <td><?= h($u->email) ?></td>
              <td><?= h($u->role->name ?? 'Sin rol') ?></td>
              <td>
                <?php if (($u->status ?? '') === 'activo'): ?>
                  <span class="dash-badge dash-badge--ok">activo</span>
                <?php else: ?>
                  <span class="dash-badge dash-badge--off"><?= h($u->status ?? 'N/A') ?></span>
                <?php endif; ?>
              </td>
              <td><?= $u->created_at ? $u->created_at->format('d/m/Y H:i') : 'N/A' ?></td>
            </tr>
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