<?php
$this->assign('title', 'Realizar Pago - ' . ($associate->first_name ?? '') . ' ' . ($associate->last_name ?? ''));

$user = $this->getRequest()->getAttribute('identity') ?? null;
$nombreUsuario = 'Usuario';
if (!empty($user)) {
    $nombreUsuario = $user->get('nombre_completo') ?? $user->get('nombre_usuario') ?? 'Usuario';
}
?>

<style>
/* =============================== */
/* DISEÑO VISUAL PREMIUM MODERNO   */
/* =============================== */

body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(180deg, #e7f9ed, #ffffff);
  color: #083d26;
  min-height: 100vh;
  margin: 0;
}

/* Contenedor principal */
.summary-card {
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.07);
  padding: 35px 40px;
  transition: all .3s ease;
  border: 1px solid #dcfce7;
  position: relative;
  overflow: hidden;
}
.summary-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 6px;
  background: linear-gradient(90deg, #16a34a, #22c55e, #65a30d);
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
}
.summary-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.1);
}

/* Título */
.section-title {
  font-size: 24px;
  font-weight: 700;
  color: #064e3b;
  margin-bottom: 12px;
}
.muted {
  color: #475569;
  font-size: 14px;
}

/* Inputs */
input, select {
  border-radius: 14px !important;
  padding: 14px 16px !important;
  border: 1px solid #d1d5db !important;
  background: #f9fafb;
  transition: .25s ease;
  font-size: 15px;
  width: 100%;
}
input:focus, select:focus {
  border-color: #16a34a !important;
  box-shadow: 0 0 0 4px rgba(22,163,74,.15);
  outline: none;
  background: #ffffff;
}

/* PASOS */
.step-block {
  opacity: 0;
  pointer-events: none;
  transform: translateY(15px);
  transition: all .5s ease;
  border-left: 5px solid #e2e8f0;
  padding-left: 20px;
  margin-top: 30px;
}
.step-block.active {
  opacity: 1;
  pointer-events: auto;
  transform: translateY(0);
  border-left-color: #16a34a;
}
.step-label {
  font-weight: 700;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
  color: #065f46;
  font-size: 15px;
}
.step-number {
  background: linear-gradient(145deg, #16a34a, #15803d);
  color: white;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

/* Caja deuda */
.debt-box {
  margin-top: 15px;
  padding: 18px;
  border-radius: 14px;
  background: linear-gradient(135deg, #ecfdf5, #d1fae5);
  border: 1px solid #bbf7d0;
  box-shadow: inset 0 0 8px rgba(0,0,0,0.03);
  display: none;
}
.debt-amount {
  font-size: 22px;
  font-weight: 700;
  color: #065f46;
}
.progress-bar-container {
  height: 10px;
  background: #e5e7eb;
  border-radius: 10px;
  margin-top: 12px;
  overflow: hidden;
}
.progress-bar {
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, #22c55e, #15803d);
  transition: width .4s ease;
}

/* Feedback */
.feedback-error {
  color: #dc2626;
  font-size: 13px;
  margin-top: 6px;
  display: none;
  font-weight: 600;
}
.feedback-success {
  color: #15803d;
  font-size: 13px;
  margin-top: 6px;
  display: none;
  font-weight: 600;
}

/* Archivo */
.file-preview {
  margin-top: 10px;
  font-size: 13px;
  color: #065f46;
  display: none;
  background: #f0fdf4;
  padding: 8px 10px;
  border-radius: 8px;
}

/* Botones */
.btn-primary {
  background: linear-gradient(135deg, #16a34a, #15803d);
  border: none;
  color: white;
  font-weight: 600;
  transition: all .25s ease;
  border-radius: 12px;
  padding: 13px 16px;
  font-size: 15px;
  box-shadow: 0 3px 10px rgba(21,128,61,0.25);
}
.btn-primary:hover {
  background: linear-gradient(135deg, #22c55e, #166534);
  transform: translateY(-1px);
  box-shadow: 0 5px 14px rgba(21,128,61,0.3);
}
.btn-logout {
  background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
}
.btn-logout:hover {
  background: linear-gradient(135deg, #ef4444, #991b1b) !important;
}
.btn-full {
  margin-top: 10px;
  font-size: 13px;
  background: #e2e8f0;
  border: none;
  padding: 8px 12px;
  border-radius: 8px;
  cursor: pointer;
  color: #1e293b;
  transition: all .2s ease;
}
.btn-full:hover {
  background: #cbd5e1;
  transform: translateY(-1px);
}

/* Caja de información */
.info-box {
  background: linear-gradient(135deg, #f0fdf4, #dcfce7);
  border-left: 6px solid #16a34a;
  padding: 12px 14px;
  margin-top: 18px;
  border-radius: 10px;
  font-size: 14px;
  color: #166534;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Encabezado */
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px;
}
.dashboard-title {
  color: #064e3b;
  font-size: 28px;
  font-weight: 800;
}
.dashboard-subtitle {
  color: #475569;
  font-size: 14px;
}
.dashboard-meta .btn {
  margin-left: 8px;
}
</style>

<div class="dashboard-page dashboard-cashier">
  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title">Realizar Abono</h2>
      <p class="dashboard-subtitle">
        Envío de comprobante para verificación manual • <?= h($nombreUsuario) ?>
      </p>
    </div>

    <div class="dashboard-meta">
      <?= $this->Html->link('Volver al Dashboard',
        ['plugin'=>'Associates','controller'=>'Associates','action'=>'dashboard'],
        ['class'=>'btn btn-primary']
      ) ?>

      <?= $this->Html->link('Cerrar sesión',
        ['plugin'=>'Users','controller'=>'Users','action'=>'logout'],
        ['class'=>'btn btn-primary btn-logout']
      ) ?>
    </div>
  </div>

  <hr class="divider">

  <div class="dashboard-content">
    <div class="summary-card" style="max-width:980px;width:100%;">
      <h3 class="section-title">Formulario de Pago</h3>

      <p class="muted">
        Asociado:
        <strong><?= h($associate->first_name . ' ' . $associate->last_name) ?></strong>
        (<?= h($associate->id_card) ?>)
      </p>

      <?= $this->Form->create($paymentDetail,['type'=>'file','autocomplete'=>'off','id'=>'paymentForm']) ?>

      <!-- PASO 1 -->
      <div id="step1" class="step-block active">
        <div class="step-label">
          <div class="step-number">1</div>
          Seleccione la deuda
        </div>

        <?= $this->Form->control('payment_id',[
          'label'=>false,
          'type'=>'select',
          'options'=>$pendingOptions,
          'empty'=>'Seleccione una opción...',
          'required'=>true,
          'id'=>'payment_select'
        ]) ?>

        <div id="debtBox" class="debt-box">
          <div>Deuda pendiente:</div>
          <div class="debt-amount" id="debtAmount"></div>

          <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
          </div>
        </div>
      </div>

      <!-- PASO 2 -->
      <div id="step2" class="step-block">
        <div class="step-label">
          <div class="step-number">2</div>
          Ingrese el monto
        </div>

        <?= $this->Form->control('amount',[
          'label'=>false,
          'type'=>'number',
          'step'=>'0.01',
          'min'=>'0.01',
          'required'=>true,
          'id'=>'amount_input',
          'placeholder'=>'0.00'
        ]) ?>

        <button type="button" id="payFullBtn" class="btn-full" style="display:none;">
          Pagar monto total
        </button>

        <div id="errorMsg" class="feedback-error"></div>
        <div id="successMsg" class="feedback-success"></div>
      </div>

      <!-- PASO 3 -->
      <div id="step3" class="step-block">
        <div class="step-label">
          <div class="step-number">3</div>
          Método de pago
        </div>

        <?= $this->Form->control('payment_method_id',[
          'label'=>false,
          'type'=>'select',
          'options'=>$paymentMethods,
          'empty'=>'Seleccione',
          'required'=>true,
          'id'=>'method_select'
        ]) ?>
      </div>

      <!-- PASO 4 -->
      <div id="step4" class="step-block">
        <div class="step-label">
          <div class="step-number">4</div>
          Adjunte el comprobante
        </div>

        <?= $this->Form->control('comprobante',[
          'label'=>false,
          'type'=>'file',
          'accept'=>'.jpg,.jpeg,.png,.pdf',
          'required'=>true,
          'id'=>'file_input'
        ]) ?>

        <div id="filePreview" class="file-preview"></div>
      </div>

      <div class="info-box">
        💡 Este pago será validado manualmente por administración.
      </div>

      <div style="margin-top:22px;">
        <?= $this->Form->button('Enviar comprobante',[
          'class'=>'btn btn-primary',
          'style'=>'width:100%;margin-bottom:12px;',
          'id'=>'submitBtn',
          'disabled'=>true
        ]) ?>

        <?= $this->Html->link('Cancelar',
          ['plugin'=>'Associates','controller'=>'Associates','action'=>'dashboard'],
          ['class'=>'btn btn-primary btn-logout','style'=>'width:100%;']
        ) ?>
      </div>

      <?= $this->Form->end() ?>
    </div>
  </div>
</div>

<?php if (!empty($debts)): ?>
<script>
const debts = <?= json_encode($debts) ?>;

const paymentSelect = document.getElementById('payment_select');
const amountInput = document.getElementById('amount_input');
const methodSelect = document.getElementById('method_select');
const fileInput = document.getElementById('file_input');

const step2 = document.getElementById('step2');
const step3 = document.getElementById('step3');
const step4 = document.getElementById('step4');

const debtBox = document.getElementById('debtBox');
const debtAmount = document.getElementById('debtAmount');
const progressBar = document.getElementById('progressBar');
const errorMsg = document.getElementById('errorMsg');
const successMsg = document.getElementById('successMsg');
const payFullBtn = document.getElementById('payFullBtn');
const submitBtn = document.getElementById('submitBtn');
const filePreview = document.getElementById('filePreview');

let currentMax = 0;

/* Paso 1 */
paymentSelect.addEventListener('change', function() {
  if (!this.value) return;
  currentMax = parseFloat(debts[this.value]);
  debtAmount.innerText = "B/. " + currentMax.toFixed(2);
  debtBox.style.display = 'block';
  payFullBtn.style.display = 'inline-block';
  step2.classList.add('active');
});

/* Paso 2 */
amountInput.addEventListener('input', function() {
  const value = parseFloat(this.value) || 0;
  if (value > currentMax) {
    errorMsg.innerText = "El monto no puede superar la deuda.";
    errorMsg.style.display = 'block';
    successMsg.style.display = 'none';
    submitBtn.disabled = true;
  } else if (value > 0) {
    errorMsg.style.display = 'none';
    successMsg.innerText = "Monto válido ✔";
    successMsg.style.display = 'block';
    progressBar.style.width = ((value/currentMax)*100)+"%";
    step3.classList.add('active');
  }
});

/* Botón pagar total */
payFullBtn.addEventListener('click', function() {
  amountInput.value = currentMax.toFixed(2);
  progressBar.style.width = "100%";
  successMsg.innerText = "Monto total ingresado ✔";
  successMsg.style.display = 'block';
  errorMsg.style.display = 'none';
  step3.classList.add('active');
});

/* Paso 3 */
methodSelect.addEventListener('change', function() {
  if (this.value) step4.classList.add('active');
});

/* Paso 4 */
fileInput.addEventListener('change', function() {
  if (this.files.length > 0) {
    filePreview.innerText = "📎 Archivo seleccionado: " + this.files[0].name;
    filePreview.style.display = 'block';
    submitBtn.disabled = false;
  }
});
</script>
<?php endif; ?>
