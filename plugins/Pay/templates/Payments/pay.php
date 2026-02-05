<?php
declare(strict_types=1);

$this->assign('title', 'Registrar pago - Envío de comprobante');

$user = $this->getRequest()->getAttribute('identity') ?? null;

$nombreRol = 'Cajero';
$nombreUsuario = 'Usuario';

if (!empty($user)) {
    $nombreUsuario = $user->get('nombre_completo') ?? $user->get('nombre_usuario') ?? 'Usuario';
}
?>

<style>
/* Solo para esta vista*/
.pay-grid{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:16px;
  margin-top: 14px;
}
.pay-grid .full{ grid-column: 1 / -1; }

.pay-actions{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
  margin-top: 16px;
}

.pay-hint{
  font-size:12px;
  color:#6b7280;
  margin-top:6px;
}

.pay-info{
  margin-top: 14px;
  background:#f3faf6;
  border:1px solid #d7efe2;
  color:#157347;
  padding: 12px 14px;
  border-radius: 14px;
  font-size: 13px;
}

@media (max-width: 768px){
  .pay-grid{ grid-template-columns: 1fr; }
}
</style>

<div class="dashboard-page dashboard-cashier">

  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title">Registrar pago</h2>
      <p class="dashboard-subtitle">
        Envío de comprobante para verificación manual • <?= h($nombreUsuario) ?>
      </p>
    </div>

    <div class="dashboard-meta">
      <?= $this->Html->link(
          'Volver al dashboard',
          ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'dashboardCashier'],
          ['class' => 'btn btn-primary']
      ) ?>

      <?= $this->Html->link(
          'Cerrar sesión',
          ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
          ['class' => 'btn btn-primary btn-logout']
      ) ?>
    </div>
  </div>

  <hr class="divider">

  <div class="dashboard-content">

    <div class="summary-card" style="max-width:980px;width:100%;">
      <h3 class="section-title">Envío de comprobante</h3>
      <p class="muted" style="margin-top:6px;">
        Complete el formulario y adjunte el comprobante. Este pago será verificado manualmente por el cajero.
      </p>

      <?= $this->Form->create(null, ['type' => 'file', 'autocomplete' => 'off']) ?>

      <div class="pay-grid">

        <div class="full">
          <?= $this->Form->control('nombre_completo', [
              'label' => 'Nombre completo',
              'required' => true
          ]) ?>
        </div>

        <div>
          <?= $this->Form->control('cedula', [
              'label' => 'Cédula',
              'placeholder' => '8-888-888',
              'required' => true
          ]) ?>
        </div>

        <div>
          <?= $this->Form->control('celular', [
              'label' => 'Teléfono',
              'placeholder' => '6XXX-XXXX',
              'required' => true
          ]) ?>
        </div>

        <div class="full">
          <?= $this->Form->control('correo', [
              'label' => 'Correo electrónico',
              'type' => 'email',
              'required' => true
          ]) ?>
        </div>

        <div class="full">
          <?= $this->Form->control('concepto', [
              'label' => 'Concepto del pago',
              'type' => 'textarea',
              'rows' => 3,
              'required' => true
          ]) ?>
        </div>

        <div>
          <?= $this->Form->control('monto', [
              'label' => 'Monto pagado (B/.)',
              'type' => 'number',
              'step' => '0.01',
              'min' => '0',
              'required' => true
          ]) ?>
        </div>

        <div>
          <?= $this->Form->control('metodo', [
              'label' => 'Método de pago',
              'type' => 'select',
              'options' => [
                  'Transferencia' => 'Transferencia',
                  'Depósito' => 'Depósito',
                  'Yappy' => 'Yappy',
                  'Efectivo (caja)' => 'Efectivo (caja)',
              ],
              'empty' => 'Seleccione',
              'required' => true
          ]) ?>
          <div class="pay-hint">No se aceptan pagos con tarjeta.</div>
        </div>

        <div class="full">
          <?= $this->Form->control('comprobante', [
              'label' => 'Adjuntar comprobante',
              'type' => 'file',
              'accept' => '.jpg,.jpeg,.png',
              'required' => true
          ]) ?>
          <div class="pay-hint">Formatos permitidos: JPG / PNG. Tamaño máximo: 5MB.</div>
        </div>

      </div>

      <div class="pay-info">
        Importante: este sistema solo recibe el comprobante. El estado (Pendiente / Pagado) se define manualmente.
      </div>

      <div class="pay-actions">
        <?= $this->Form->button('Enviar comprobante', ['class' => 'btn btn-primary']) ?>

        <?= $this->Html->link(
            'Cancelar',
            ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'dashboardCashier'],
            ['class' => 'btn btn-primary btn-logout']
        ) ?>
      </div>

      <?= $this->Form->end() ?>

    </div>

  </div>
</div>
