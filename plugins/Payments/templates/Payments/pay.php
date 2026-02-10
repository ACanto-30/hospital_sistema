<?php
declare(strict_types=1);

$this->assign('title', 'Registrar pago - Envío de comprobante');

$user = $this->getRequest()->getAttribute('identity') ?? null;

$nombreUsuario = 'Usuario';
if (!empty($user)) {
    $nombreUsuario = $user->get('nombre_completo') ?? $user->get('nombre_usuario') ?? 'Usuario';
}
?>

<div class="dashboard-page dashboard-cashier">

  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title">Registrar pago</h2>
      <p class="dashboard-subtitle">
        Envío de comprobante para verificación manual • <?= h($nombreUsuario) ?>
      </p>
    </div>

    <div class="dashboard-meta">
      <?= $this->Html->link('Volver al dashboard', '/dashboard', ['class' => 'btn btn-primary']) ?>

      <?= $this->Html->link(
        'Cerrar sesión',
        ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
        ['class' => 'btn btn-primary btn-logout']
      ) ?>
    </div>
  </div>

  <hr class="divider">

  <div class="dashboard-content">

    <div class="summary-card pay-card">
      <div class="pay-card-head">
        <div>
          <h3 class="section-title">Envío de comprobante</h3>
          <p class="muted pay-lead">
            Asociado:
            <span class="pay-badge">
              <?= h($associate->first_name . ' ' . $associate->last_name) ?>
            </span>
            <span class="pay-badge pay-badge-soft">
              <?= h($associate->id_card) ?>
            </span>
          </p>
        </div>

        <div class="pay-card-mini">
          <div class="pay-mini-label">Estado</div>
          <div class="pay-mini-value">Pendiente</div>
        </div>
      </div>

      <?= $this->Form->create($payment, ['type' => 'file', 'autocomplete' => 'off']) ?>

      <div class="pay-grid">

        <div class="pay-field">
          <?= $this->Form->control('amount', [
            'label' => ['text' => 'Monto pagado (B/.)', 'class' => 'pay-label'],
            'type' => 'number',
            'step' => '0.01',
            'min' => '0',
            'required' => true,
            'class' => 'pay-input',
            'placeholder' => 'Ej: 25.00',
          ]) ?>
          <div class="pay-hint">Ingrese el monto exacto del comprobante.</div>
        </div>

        <div class="pay-field">
          <?= $this->Form->control('payment_method_id', [
            'label' => ['text' => 'Método de pago', 'class' => 'pay-label'],
            'type' => 'select',
            'options' => $paymentMethods,
            'empty' => 'Seleccione',
            'required' => true,
            'class' => 'pay-input',
          ]) ?>
          <div class="pay-hint">Seleccione el método utilizado.</div>
        </div>

        <div class="pay-field full">
          <?= $this->Form->control('comprobante', [
            'label' => ['text' => 'Adjuntar comprobante', 'class' => 'pay-label'],
            'type' => 'file',
            'accept' => '.jpg,.jpeg,.png,.pdf',
            'required' => true,
            'class' => 'pay-file',
          ]) ?>
          <div class="pay-hint">Formatos permitidos: JPG, PNG, PDF. Tamaño máximo: 5MB.</div>
        </div>

      </div>

      <div class="pay-info">
        Importante: el sistema solo recibe el comprobante. El estado (Pendiente / Pagado) se define manualmente.
      </div>

      <div class="pay-actions">
        <?= $this->Form->button('Enviar comprobante', ['class' => 'btn btn-primary']) ?>

        <?= $this->Html->link('Cancelar', '/dashboard', ['class' => 'btn btn-primary btn-logout']) ?>
      </div>

      <?= $this->Form->end() ?>
    </div>

  </div>
</div>
