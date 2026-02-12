<?php
/**
 * @var \Payments\Model\Entity\PaymentDetail $paymentDetail
 * @var \Cake\ORM\ResultSet $paymentMethods
 * @var $associate
 * @var array $pendingOptions
 * @var array $debts
 */

$this->assign('title', 'Realizar Pago - ' . ($associate->first_name ?? '') . ' ' . ($associate->last_name ?? ''));

$user = $this->getRequest()->getAttribute('identity') ?? null;
$nombreUsuario = 'Usuario';
if (!empty($user)) {
    $nombreUsuario = $user->get('nombre_completo') ?? $user->get('nombre_usuario') ?? 'Usuario';
}
?>

<style>
  /* Solo para esta vista */
  .pay-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 14px;
  }

  .pay-grid .full {
    grid-column: 1 / -1;
  }

  .pay-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 16px;
  }

  .pay-hint {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
  }

  .pay-info {
    margin-top: 14px;
    background: #f3faf6;
    border: 1px solid #d7efe2;
    color: #157347;
    padding: 12px 14px;
    border-radius: 14px;
    font-size: 13px;
  }

  .pay-feedback {
    color: #dc2626;
    font-size: 12px;
    margin-top: 6px;
    display: none;
  }

  @media (max-width: 768px) {
    .pay-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="dashboard-page dashboard-cashier">

  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title"><?= empty($pendingOptions) ? 'Realizar Pago' : 'Realizar Abono' ?></h2>
      <p class="dashboard-subtitle">
        <?= empty($pendingOptions) ? 'Sin deudas pendientes' : 'Envío de comprobante para verificación manual' ?> • <?= h($nombreUsuario) ?>
      </p>
    </div>

    <div class="dashboard-meta">
      <?= $this->Html->link(
        'Volver al Dashboard',
        ['plugin' => 'Associates', 'controller' => 'Associates', 'action' => 'dashboard'],
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

    <?php if (empty($pendingOptions)): ?>
      <!-- Caso Paz y Salvo -->
      <div class="summary-card" style="max-width: 600px; margin: 0 auto; text-align: center; padding: 40px;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
        <h3 class="section-title" style="color: #065f46;">¡Estás Paz y Salvo!</h3>
        <p class="muted" style="margin-top: 1rem;">No tienes deudas pendientes por pagar en este momento.</p>
        <?= $this->Html->link(
          'Volver al Dashboard',
          ['plugin' => 'Associates', 'controller' => 'Associates', 'action' => 'dashboard'],
          ['class' => 'btn btn-primary', 'style' => 'margin-top: 20px;']
        ) ?>
      </div>

    <?php else: ?>
      <!-- Caso con Deudas -->
      <div class="summary-card" style="max-width: 980px; width: 100%;">
        <h3 class="section-title">Realizar Abono</h3>
        <p class="muted" style="margin-top: 6px;">
          Asociado: <strong><?= h($associate->first_name . ' ' . $associate->last_name) ?></strong> (<?= h($associate->id_card) ?>)<br>
          Complete el formulario y adjunte el comprobante.
        </p>

        <?= $this->Form->create($paymentDetail, ['type' => 'file', 'autocomplete' => 'off', 'id' => 'paymentForm']) ?>

        <div class="pay-grid">

          <div class="full">
            <?= $this->Form->control('payment_id', [
              'label' => 'Seleccione la Deuda a Abonar',
              'type' => 'select',
              'options' => $pendingOptions,
              'empty' => 'Seleccione una opción...',
              'required' => true,
              'id' => 'payment_select'
            ]) ?>
            <div class="pay-hint">Se muestra el monto original y lo restante.</div>
          </div>

          <div>
            <?= $this->Form->control('amount', [
              'label' => 'Monto a Pagar (B/.)',
              'type' => 'number',
              'step' => '0.01',
              'min' => '0.01',
              'required' => true,
              'id' => 'amount_input',
              'placeholder' => '0.00'
            ]) ?>
            <div id="amount-feedback" class="pay-feedback"></div>
          </div>

          <div>
            <?= $this->Form->control('payment_method_id', [
              'label' => 'Método de pago',
              'type' => 'select',
              'options' => $paymentMethods,
              'empty' => 'Seleccione',
              'required' => true
            ]) ?>
            <div class="pay-hint">Seleccione el método utilizado.</div>
          </div>

          <div class="full">
            <?= $this->Form->control('comprobante', [
              'label' => 'Adjuntar comprobante',
              'type' => 'file',
              'accept' => '.jpg,.jpeg,.png,.pdf',
              'required' => true
            ]) ?>
            <div class="pay-hint">Formatos permitidos: JPG, PNG, PDF. Tamaño máximo: 5MB.</div>
          </div>

        </div>

        <div class="pay-info">
          Importante: este sistema solo recibe el comprobante. El estado (Pendiente / Pagado) se define manualmente.
        </div>

        <div class="pay-actions">
          <?= $this->Form->button('Enviar comprobante', ['class' => 'btn btn-primary']) ?>

          <?= $this->Html->link(
            'Cancelar',
            ['plugin' => 'Associates', 'controller' => 'Associates', 'action' => 'dashboard'],
            ['class' => 'btn btn-primary btn-logout']
          ) ?>
        </div>

        <?= $this->Form->end() ?>

      </div>
    <?php endif; ?>

  </div>
</div>

<?php if (!empty($debts)): ?>
<script>
    const debts = <?= json_encode($debts) ?>;
    const paymentSelect = document.getElementById('payment_select');
    const amountInput = document.getElementById('amount_input');
    const feedback = document.getElementById('amount-feedback');

    if (paymentSelect && amountInput && feedback) {
        paymentSelect.addEventListener('change', validateAmount);
        amountInput.addEventListener('input', validateAmount);

        function validateAmount() {
            const selectedId = paymentSelect.value;
            const currentAmount = parseFloat(amountInput.value) || 0;

            if (!selectedId) {
                feedback.style.display = 'none';
                feedback.textContent = '';
                amountInput.style.borderColor = '';
                return;
            }

            const maxDebt = parseFloat(debts[selectedId]);

            if (currentAmount > (maxDebt + 0.01)) {
                feedback.textContent = 'El monto no puede superar la deuda restante de B/. ' + maxDebt.toFixed(2);
                feedback.style.display = 'block';
                amountInput.style.borderColor = '#dc2626';
            } else {
                feedback.style.display = 'none';
                feedback.textContent = '';
                amountInput.style.borderColor = '';
            }
        }
    }
</script>
<?php endif; ?>
