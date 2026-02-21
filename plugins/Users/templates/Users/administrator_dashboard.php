<?php
$this->assign('title', 'Dashboard - Administrador');

$adminNombre = $currentUser->full_name ?? $currentUser->username ?? 'Administrador';
?>

<div class="dashboard">
  <div class="dashboard-page">

    <div class="dashboard-header">
      <div>
        <h2 class="dashboard-title">Dashboard Administrador</h2>
        <p class="dashboard-subtitle">
          Bienvenido, <?= h($adminNombre) ?> (<?= h($currentUser->role->name ?? 'Administrador') ?>)
        </p>
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
      <div class="summary-card <?= $listType === 'users' ? 'active-tab' : '' ?>"
           onclick="window.location='?type=users'" style="cursor:pointer;">
        <div class="card-content">
          <div class="card-info">
            <strong class="section-title">Usuarios</strong>
            <p class="muted">Listado general de usuarios</p>
          </div>
          <div class="card-icon"><span>👥</span></div>
        </div>
      </div>

      <div class="summary-card <?= $listType === 'associates' ? 'active-tab' : '' ?>"
           onclick="window.location='?type=associates'" style="cursor:pointer;">
        <div class="card-content">
          <div class="card-info">
            <strong class="section-title">Asociados</strong>
            <p class="muted">Listado de asociados y planes</p>
          </div>
          <div class="card-icon"><span>🤝</span></div>
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

    <!-- Card principal -->
    <div class="summary-card">
      <div class="card-header">
        <h3 class="section-title"><?= $listType === 'associates' ? 'Asociados Miembros' : 'Usuarios del Sistema' ?></h3>
        <span class="muted"><?= $this->Paginator->counter('Página {{page}} de {{pages}}') ?></span>
      </div>

      <hr class="divider">

<!-- ===================== -->
<!-- 🔹 FILTROS ASOCIADOS -->
<!-- ===================== -->
<?php if ($listType === 'associates'): ?>

  <h3 style="margin-bottom:12px;font-weight:600;color:#222;">🎯 Panel de Asociados</h3>

  <?php
    $selectedMonth = $this->request->getQuery('birth_month');
    $showAllMonths = $this->request->getQuery('all_birthdays');
    $monthNames = [
      '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
      '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
      '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
    ];
    $currentMonth = date('m');
  ?>

  <!-- 💬 Mensaje informativo -->
  <?php if (!empty($selectedMonth)): ?>
    <div style="background:#e7f3ff;border-left:4px solid #1e90ff;padding:10px 15px;border-radius:6px;margin-bottom:15px;">
      🎉 Mostrando los cumpleañeros del mes de <strong><?= $monthNames[$selectedMonth] ?></strong>.
    </div>
  <?php elseif ($showAllMonths): ?>
    <div style="background:#fff4e1;border-left:4px solid #f2b94b;padding:10px 15px;border-radius:6px;margin-bottom:15px;">
      🎂 Mostrando los cumpleañeros de todos los meses del año.
    </div>
  <?php else: ?>
    <div style="background:#f9f9f9;border-left:4px solid #aaa;padding:10px 15px;border-radius:6px;margin-bottom:15px;">
      🎂 Este mes hay <strong><?= $birthdayCount ?></strong> cumpleañero<?= $birthdayCount == 1 ? '' : 's' ?> registrados.
    </div>
  <?php endif; ?>


  <!-- ===================== -->
  <!-- 🔹 FILTRO DE BÚSQUEDA -->
  <!-- ===================== -->
  <div class="search-container" style="margin-bottom:25px;background:#fafafa;border:1px solid #ddd;border-radius:10px;padding:20px;">
    <?= $this->Form->create(null, ['type' => 'get', 'id' => 'filterForm']) ?>
      <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:15px;align-items:end;">
        <!-- Campos -->
        <div>
          <label style="font-weight:600;">🔍 Buscar</label>
          <?= $this->Form->control('search', ['label' => false,'placeholder' => 'Nombre, cédula o teléfono...','value' => $this->request->getQuery('search'),'class' => 'form-input','style' => 'border-radius:6px;border:1px solid #ccc;padding:6px;width:100%;']) ?>
        </div>

        <div>
          <label style="font-weight:600;">Plan</label>
          <?= $this->Form->control('plan', ['label' => false,'type' => 'select','empty' => 'Todos','options' => $insurancePlans,'value' => $this->request->getQuery('plan'),'class' => 'form-select','style' => 'border-radius:6px;border:1px solid #ccc;padding:6px;width:100%;']) ?>
        </div>

        <div>
          <label style="font-weight:600;">Estado</label>
          <?= $this->Form->control('status', ['label' => false,'type' => 'select','empty' => 'Todos','options' => ['active' => 'Activo','inactive' => 'Inactivo'],'value' => $this->request->getQuery('status'),'class' => 'form-select','style' => 'border-radius:6px;border:1px solid #ccc;padding:6px;width:100%;']) ?>
        </div>

        <div>
          <label style="font-weight:600;">Condición</label>
          <?= $this->Form->control('condition', ['label' => false,'type' => 'select','empty' => 'Todas','options' => $conditionsList,'value' => $this->request->getQuery('condition'),'class' => 'form-select','style' => 'border-radius:6px;border:1px solid #ccc;padding:6px;width:100%;']) ?>
        </div>

        <div>
          <label style="font-weight:600;">Mes de cumpleaños</label>
          <?= $this->Form->control('birth_month', ['label' => false,'type' => 'select','empty' => 'Todos','options' => $monthNames,'value' => $this->request->getQuery('birth_month'),'class' => 'form-select','style' => 'border-radius:6px;border:1px solid #ccc;padding:6px;width:100%;']) ?>
        </div>

        <!-- Botones -->
        <div style="display:flex;gap:5px;">
          <?= $this->Form->button('Buscar', ['class' => 'btn-save','style' => 'background:#1e90ff;color:#fff;border:none;border-radius:6px;padding:7px 12px;cursor:pointer;']) ?>
          <?= $this->Html->link('Limpiar', ['?' => ['type' => 'associates']], ['class' => 'btn-cancel','style' => 'background:#ddd;color:#333;border:none;border-radius:6px;padding:7px 12px;text-decoration:none;display:inline-block;']) ?>
        </div>
      </div>

      <!-- 🎂 Mostrar todos -->
      <div style="margin-top:20px;display:flex;align-items:center;gap:10px;padding:10px 15px;background:#fff7e6;border:1px solid #ffd89c;border-radius:8px;">
        <?= $this->Form->checkbox('all_birthdays', ['hiddenField' => false,'checked' => (bool)$this->request->getQuery('all_birthdays'),'label' => false,'id' => 'showAllBirthdaysCheckbox']) ?>
        <label for="showAllBirthdaysCheckbox" style="cursor:pointer;font-weight:600;color:#b36b00;">
          🎂 Mostrar cumpleaños de todos los asociados de todo el año
        </label>
      </div>
    <?= $this->Form->end() ?>
  </div>


 <!-- ============================== -->
<!-- 🧩 SUBSECCIÓN 1: CHECKLIST DE MESES -->
<!-- ============================== -->
<h3 style="color:#1e90ff;margin-top:30px;">📅 Cumpleaños por mes</h3>
<hr style="border:1px dashed #1e90ff;margin-bottom:15px;">

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:10px;margin-bottom:25px;">
  <?php foreach ($monthNames as $mKey => $mName): ?>
    <?php
      $isChecked = (!empty($selectedMonth) && $selectedMonth === $mKey);
    ?>
    <label style="display:flex;align-items:center;gap:6px;background:<?= $isChecked ? '#e7f3ff' : '#f9f9f9' ?>;border:1px solid #ccc;border-radius:8px;padding:6px 10px;cursor:pointer;">
      <input type="checkbox" class="monthCheckbox"
        data-month="<?= $mKey ?>"
        <?= $isChecked ? 'checked' : '' ?>>
      <span style="font-weight:600;color:#333;"><?= $mName ?></span>
    </label>
  <?php endforeach; ?>
</div>

<!-- ============================== -->
<!-- 🧩 SUBSECCIÓN 2: INDIVIDUAL POR MES -->
<!-- ============================== -->
<?php if (!empty($selectedMonth)): ?>
  <h3 style="color:#1e90ff;margin-top:30px;">🎉 Cumpleañeros de <?= $monthNames[$selectedMonth] ?></h3>
  <hr style="border:1px dashed #1e90ff;margin-bottom:15px;">

  <div style="background:#f0f8ff;border-radius:12px;padding:15px 20px;margin-bottom:30px;">
    <ul style="list-style:none;padding:0;">
      <?php
        $found = false;
        foreach ($data as $associate):
          if (!empty($associate->birth_date) && date('m', strtotime($associate->birth_date)) == $selectedMonth):
            $found = true;
      ?>
        <li style="padding:6px 0;border-bottom:1px dashed #1e90ff;">
          <strong><?= h($associate->first_name . ' ' . $associate->last_name) ?></strong>
          — <span style="color:#555;"><?= date('d/m', strtotime($associate->birth_date)) ?></span>
        </li>
      <?php
          endif;
        endforeach;
        if (!$found):
      ?>
        <li style="color:#777;">No hay cumpleañeros registrados para este mes.</li>
      <?php endif; ?>
    </ul>
  </div>
<?php endif; ?>


<!-- ============================== -->
<!-- 🧩 SUBSECCIÓN 3: AGRUPADA POR TODOS LOS MESES -->
<!-- ============================== -->
<?php if ($showAllMonths): ?>
  <h3 style="color:#b36b00;margin-top:30px;">🎂 Cumpleañeros agrupados por mes</h3>
  <hr style="border:1px dashed #b36b00;margin-bottom:20px;">

  <?php foreach ($monthNames as $mKey => $mName): ?>
    <div style="background:linear-gradient(90deg,#fff4e1,#ffeec2);border-radius:12px;padding:15px 20px;margin-bottom:25px;box-shadow:0 2px 5px rgba(0,0,0,0.08);">
      <h4 style="margin:0 0 10px 0;color:#b36b00;font-weight:700;">🎉 <?= $mName ?></h4>
      <ul style="list-style:none;padding-left:10px;margin:0;font-size:15px;">
        <?php
          $found = false;
          foreach ($data as $associate):
            if (!empty($associate->birth_date) && date('m', strtotime($associate->birth_date)) == $mKey):
              $found = true;
        ?>
          <li style="padding:6px 0;border-bottom:1px dashed #f2b94b;">
            <strong style="color:#333;"><?= h($associate->first_name . ' ' . $associate->last_name) ?></strong>
            — <span style="color:#555;"><?= date('d/m', strtotime($associate->birth_date)) ?></span>
          </li>
        <?php
            endif;
          endforeach;
          if (!$found):
        ?>
          <li style="color:#777;">No hay cumpleañeros registrados en <?= $mName ?>.</li>
        <?php endif; ?>
      </ul>
    </div>
  <?php endforeach; ?>
<?php endif; ?>


<!-- ===================== -->
<!-- 🔹 SCRIPT PARA CHECKLIST -->
<!-- ===================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const allCheckbox = document.getElementById('showAllBirthdaysCheckbox');
  const monthCheckboxes = document.querySelectorAll('.monthCheckbox');

  // ✅ Activar/desactivar modo "todos los meses"
  allCheckbox.addEventListener('change', function() {
    const url = new URL(window.location.href);
    if (allCheckbox.checked) {
      url.searchParams.set('all_birthdays', '1');
      url.searchParams.delete('birth_month');
    } else {
      url.searchParams.delete('all_birthdays');
    }
    window.location.href = url.toString();
  });

  // ✅ Checklist individual de meses
  monthCheckboxes.forEach(cb => {
    cb.addEventListener('change', function() {
      const url = new URL(window.location.href);
      // Limpiar todos los meses previos y todos
      url.searchParams.delete('all_birthdays');
      url.searchParams.delete('birth_month');

      // Si está activado, filtrar ese mes
      if (cb.checked) {
        url.searchParams.set('birth_month', cb.dataset.month);
      }
      window.location.href = url.toString();
    });
  });
});
</script>

  <!-- ===================== -->
  <!-- 🔹 TABLA GENERAL -->
  <!-- ===================== -->
  <div class="card" style="border-radius:10px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.06);margin-top:40px;">
    <div class="card-header" style="background:#f1f1f1;padding:10px 15px;font-weight:600;">
      📋 Lista General de Asociados
    </div>
    <div class="card-body" style="padding:0;">
      <table class="table table-striped" style="width:100%;border-collapse:collapse;">
        <thead style="background:#f1f1f1;">
          <tr>
            <th style="padding:10px;">Nombre</th>
            <th>Cédula</th>
            <th>Teléfono</th>
            <th>Plan</th>
            <th>Fecha de Nacimiento</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data) && $data->count() > 0): ?>
            <?php foreach ($data as $associate): ?>
              <tr>
                <td style="padding:8px 10px;"><?= h($associate->first_name . ' ' . $associate->last_name) ?></td>
                <td><?= h($associate->id_card ?? '-') ?></td>
                <td><?= h($associate->phone ?? '-') ?></td>
                <td><?= h($associate->insurance_plan->name ?? '-') ?></td>
                <td><?= $associate->birth_date ? $associate->birth_date->format('d/m/Y') : '-' ?></td>
                <td><?= $associate->member_status === 'active' ? 'Activo' : 'Inactivo' ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center muted small" style="padding:15px;">No se encontraron asociados.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>


  <!-- ===================== -->
  <!-- 🔹 SCRIPT -->
  <!-- ===================== -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const allCheckbox = document.getElementById('showAllBirthdaysCheckbox');
    allCheckbox.addEventListener('change', function() {
      const url = new URL(window.location.href);
      if (allCheckbox.checked) {
        url.searchParams.set('all_birthdays', '1');
        url.searchParams.delete('birth_month');
      } else {
        url.searchParams.delete('all_birthdays');
      }
      window.location.href = url.toString();
    });
  });
  </script>

<?php endif; ?>

<!-- ===================== -->
<!-- 🔹 LISTA DE USUARIOS -->
<!-- ===================== -->
<?php if ($listType === 'users'): ?>
  <div class="card">
    <div class="card-body">
      <h5>Lista de usuarios del sistema</h5>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Fecha de creación</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data as $user): ?>
            <tr>
              <td><?= h($user->nombre_completo ?? $user->username) ?></td>
              <td><?= h($user->email) ?></td>
              <td><?= h($user->role->name ?? '-') ?></td>
              <td><?= $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

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
                    <span class="dash-badge dash-badge--ok" style="background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe;">
                      <?= h($item->insurance_plan->name ?? 'Sin Plan') ?>
                    </span>
                  </td>
                  <td>
                    <span class="dash-badge <?= ($item->member_status ?? '') === 'active' ? 'dash-badge--ok' : 'dash-badge--off' ?>">
                      <?= h($item->member_status ?? 'inactivo') ?>
                    </span>
                  </td>
                  <td class="actions">
                    <button type="button" class="action-icon" onclick="toggleEdit('edit-row-<?= $item->id ?>')" title="Editar Plan">📝</button>
                    <button type="button" class="action-icon" onclick="toggleEdit('charge-row-<?= $item->id ?>')" title="Generar Cobro Manual">💵</button>

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
                <tr id="edit-row-<?= $item->id ?>" class="edit-row" style="display:none;">
                  <td colspan="6">
                    <div class="edit-container">
                      <?= $this->Form->create(null, ['url' => ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'editAssociatePlan', $item->id]]) ?>

                      <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:15px; margin-bottom:15px;">
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

                      <div style="display:grid; grid-template-columns:2fr 1fr; gap:15px; margin-bottom:15px;">
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

                      <div style="display:grid; grid-template-columns:3fr 1fr; gap:15px; align-items:end;">
                        <div>
                          <label class="muted small">Motivo del cambio de plan (opcional)</label>
                          <?= $this->Form->control('reason', [
                            'label' => false,
                            'type' => 'text',
                            'placeholder' => 'Ej: Solicitud por mejor cobertura o actualización de datos',
                            'class' => 'form-input'
                          ]) ?>
                        </div>
                        <div style="display:flex; gap:5px;">
                          <?= $this->Form->button('Guardar', ['class' => 'btn-save', 'style' => 'flex: 1;']) ?>
                          <button type="button" class="btn-cancel" onclick="toggleEdit('edit-row-<?= $item->id ?>')" style="flex: 1;">X</button>
                        </div>
                      </div>

                      <?= $this->Form->end() ?>
                    </div>
                  </td>
                </tr>

                <!-- Row expandible para COBRO MANUAL -->
                <tr id="charge-row-<?= $item->id ?>" class="edit-row" style="display:none; background-color:#f0fdf4;">
                  <td colspan="6">
                    <div class="edit-container" style="border-left-color:#059669;">
                      <h4 style="margin-top:0; color:#059669; font-size:1rem; margin-bottom:15px;">💰 Generar Cobro Manual (Simulación Mensualidad)</h4>

                      <?= $this->Form->create(null, ['url' => ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'createDebt']]) ?>
                      <?= $this->Form->hidden('associate_id', ['value' => $item->id]) ?>

                      <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; align-items:end;">
                        <div>
                          <label class="muted small">Monto a Cobrar (B/.)</label>
                          <?= $this->Form->control('amount', [
                            'label' => false,
                            'class' => 'form-input',
                            'type' => 'number',
                            'step' => '0.01',
                            'required' => true,
                            'placeholder' => '0.00'
                          ]) ?>
                        </div>
                        <div style="display:flex; gap:5px;">
                          <?= $this->Form->button('Generar Deuda', ['class' => 'btn-save', 'style' => 'flex: 1; background: #059669;']) ?>
                          <button type="button" class="btn-cancel" onclick="toggleEdit('charge-row-<?= $item->id ?>')" style="flex: 1;">Cancelar</button>
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

      <hr class="divider" style="margin:40px 0;">

      <!-- Gestión Administrativa -->
      <div style="padding:30px; background:#f9fafb; border-radius:10px; border:1px solid #e5e7eb;">
        <h3 class="section-title" style="margin-bottom:10px;">Gestión Administrativa de Usuarios</h3>
        <p class="muted" style="margin-bottom:25px;">Edición completa con control total</p>

        <?= $this->Form->create(null, [
          'url' => ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'adminUpdateUser']
        ]) ?>

        <div style="margin-bottom:30px;">
          <h4 style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; font-weight:700; color:#4f46e5; margin-bottom:15px;">
            Identificación
          </h4>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <?= $this->Form->control('id', [
              'label' => 'ID del Usuario',
              'class' => 'form-input',
              'required' => true
            ]) ?>

            <?= $this->Form->control('role', [
              'label' => 'Rol',
              'type' => 'select',
              'options' => [
                'admin' => 'Administrador',
                'asociado' => 'Asociado',
                'usuario' => 'Usuario'
              ],
              'default' => 'admin',
              'class' => 'form-select'
            ]) ?>
          </div>
        </div>

        <div style="margin-bottom:30px;">
          <h4 style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; font-weight:700; color:#4f46e5; margin-bottom:15px;">
            Información Personal
          </h4>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <?= $this->Form->control('name', [
              'label' => 'Nombre Completo',
              'class' => 'form-input'
            ]) ?>

            <?= $this->Form->control('email', [
              'label' => 'Correo Electrónico',
              'class' => 'form-input'
            ]) ?>
          </div>
        </div>

        <div style="margin-bottom:30px;">
          <h4 style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; font-weight:700; color:#4f46e5; margin-bottom:15px;">
            Configuración de Cuenta
          </h4>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <?= $this->Form->control('status', [
              'label' => 'Estado',
              'type' => 'select',
              'options' => [
                'activo' => 'Activo',
                'inactivo' => 'Inactivo',
                'suspendido' => 'Suspendido'
              ],
              'default' => 'activo',
              'class' => 'form-select'
            ]) ?>

            <?= $this->Form->control('password', [
              'label' => 'Nueva Contraseña',
              'type' => 'password',
              'class' => 'form-input'
            ]) ?>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end;">
          <?= $this->Form->button('Actualizar Usuario', ['class' => 'btn-save']) ?>
        </div>

        <?= $this->Form->end() ?>
      </div>
    </div>

  </div>
</div>

<style>
  .btn-deactivate{
    color:#ef4444;
    text-decoration:none;
    font-size:0.85em;
    font-weight:600;
  }

  .btn-edit{
    background:#f3f4f6;
    border:1px solid #d1d5db;
    padding:5px 10px;
    border-radius:6px;
    cursor:pointer;
    font-size:0.85em;
    transition: all 0.2s;
  }

  .btn-edit:hover{
    background:#e5e7eb;
  }

  .action-icon{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:32px;
    height:32px;
    background:#f3f4f6;
    border:1px solid #d1d5db;
    border-radius:6px;
    cursor:pointer;
    font-size:1.1em;
    transition: all 0.2s;
    text-decoration:none;
    line-height:1;
    padding:0;
  }

  .action-icon:hover{
    background:#e5e7eb;
    border-color:#9ca3af;
  }

  .action-icon.active{
    color:#059669;
  }

  .action-icon.active:hover{
    background:#ecfdf5;
    border-color:#10b981;
  }

  .edit-row{
    background-color:#f9fafb;
    transition: all 0.3s ease;
  }

  .edit-container{
    padding:20px;
    border-left:4px solid #4f46e5;
    margin:10px;
    background:white;
    border-radius:8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }

  .form-select,
  .form-input{
    width:100%;
    padding:8px 12px;
    border:1px solid #d1d5db;
    border-radius:6px;
    font-size:0.9em;
  }

  .btn-save{
    background:#4f46e5;
    color:white;
    border:none;
    padding:9px 15px;
    border-radius:6px;
    cursor:pointer;
    font-weight:600;
  }

  .btn-cancel{
    background:white;
    color:#6b7280;
    border:1px solid #d1d5db;
    padding:8px 15px;
    border-radius:6px;
    cursor:pointer;
    margin-left:5px;
  }

  .actions{
    white-space:nowrap;
  }

  /* Active-tab */
  .summary-grid .summary-card{
    position: relative;
    overflow: visible;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border:2px solid transparent;
    display:flex;
    flex-direction:column;
    justify-content:center;
    min-height:110px;
    margin-top:0 !important;
    padding:20px !important;
    background:#fff;
    box-sizing:border-box;
  }

  .summary-grid .card-content{
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:100%;
  }

  .summary-grid .card-info{ flex: 1; }

  .summary-grid .card-icon{
    font-size:2rem;
    opacity:0.6;
    margin-left:15px;
  }

  .summary-grid .summary-card.active-tab{
    background:#ffffff;
    border-color:#157347;
    box-shadow: 0 20px 35px rgba(21, 115, 71, 0.2);
    transform: scale(1.1);
    z-index: 5;
  }

  .summary-grid .summary-card:not(.active-tab):not(.stats-card){
    opacity:0.8;
    transform: scale(0.94);
  }

  .summary-grid .summary-card.active-tab::after{
    content:"";
    position:absolute;
    top:0; left:0;
    width:100%;
    height:6px;
    background: linear-gradient(90deg, #157347, #34d399);
  }

  .summary-grid .summary-card.active-tab .section-title{
    color:#157347;
    font-size:1.1em;
  }

  .summary-grid .summary-card:hover:not(.stats-card):not(.active-tab){
    opacity:1;
    transform: scale(1);
    border-color: rgba(21, 115, 71, 0.3);
  }

  .stats-card{
    background:#f8fafc !important;
    border:1px solid #e2e8f0 !important;
    opacity:0.9;
  }

  .summary-grid .summary-card .muted{
    font-size:0.85rem;
    margin-top:4px;
    margin-bottom:0;
  }
</style>

<script>
  function toggleEdit(rowId){
    const row = document.getElementById(rowId);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
  }
</script>