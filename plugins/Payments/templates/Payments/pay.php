<?php
$this->assign('title', 'Realizar Pago - ' . ($associate->first_name ?? '') . ' ' . ($associate->last_name ?? ''));

$user = $this->getRequest()->getAttribute('identity') ?? null;
$nombreUsuario = 'Usuario';
if (!empty($user)) {
    $nombreUsuario = $user->get('nombre_completo') ?? $user->get('nombre_usuario') ?? 'Usuario';
}
?>

<style>

/* ==== Mantiene tu diseño base ==== */

.summary-card{
    transition:.25s ease;
}
.summary-card:hover{
    transform:translateY(-2px);
}

/* Inputs */
input, select{
    border-radius:10px !important;
    padding:10px 12px !important;
    border:1px solid #d1d5db !important;
    transition:.2s ease;
    font-size:14px;
}

input:focus, select:focus{
    border-color:#15803d !important;
    box-shadow:0 0 0 3px rgba(21,128,61,.15);
}

/* PASOS */
.step-block{
    opacity:.5;
    pointer-events:none;
    transition:.3s ease;
}

.step-block.active{
    opacity:1;
    pointer-events:auto;
}

.step-label{
    font-weight:700;
    margin-top:18px;
    margin-bottom:6px;
    display:flex;
    align-items:center;
    gap:8px;
}

.step-number{
    background:#15803d;
    color:white;
    width:26px;
    height:26px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
}

/* Caja deuda */
.debt-box{
    margin-top:10px;
    padding:14px;
    border-radius:14px;
    background:#ecfdf5;
    border:1px solid #bbf7d0;
    display:none;
}

.debt-amount{
    font-size:20px;
    font-weight:700;
    color:#065f46;
}

.progress-bar-container{
    height:8px;
    background:#e5e7eb;
    border-radius:10px;
    margin-top:10px;
    overflow:hidden;
}

.progress-bar{
    height:100%;
    width:0%;
    background:#15803d;
    transition:.3s ease;
}

/* Feedback */
.feedback-error{
    color:#dc2626;
    font-size:12px;
    margin-top:5px;
    display:none;
}

.feedback-success{
    color:#15803d;
    font-size:12px;
    margin-top:5px;
    display:none;
}

/* Archivo */
.file-preview{
    margin-top:8px;
    font-size:12px;
    color:#065f46;
    display:none;
}

.btn-full{
    margin-top:8px;
    font-size:12px;
    background:#e2e8f0;
    border:none;
    padding:6px 10px;
    border-radius:8px;
    cursor:pointer;
}

.btn-full:hover{
    background:#cbd5e1;
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
      <?= $this->Html->link(
        'Volver al Dashboard',
        ['plugin'=>'Associates','controller'=>'Associates','action'=>'dashboard'],
        ['class'=>'btn btn-primary']
      ) ?>

      <?= $this->Html->link(
        'Cerrar sesión',
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

      <div style="margin-top:14px;background:#f3faf6;border:1px solid #d7efe2;color:#157347;padding:12px;border-radius:14px;font-size:13px;">
        Este pago será validado manualmente por administración.
      </div>

      <div style="margin-top:18px;">
        <?= $this->Form->button('Enviar comprobante',[
          'class'=>'btn btn-primary',
          'style'=>'width:100%;margin-bottom:10px;',
          'id'=>'submitBtn',
          'disabled'=>true
        ]) ?>

        <?= $this->Html->link(
          'Cancelar',
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
paymentSelect.addEventListener('change', function(){
    if(!this.value) return;

    currentMax = parseFloat(debts[this.value]);
    debtAmount.innerText = "B/. " + currentMax.toFixed(2);
    debtBox.style.display='block';
    payFullBtn.style.display='inline-block';

    step2.classList.add('active');
});

/* Paso 2 */
amountInput.addEventListener('input', function(){
    const value = parseFloat(this.value) || 0;

    if(value > currentMax){
        errorMsg.innerText="El monto no puede superar la deuda.";
        errorMsg.style.display='block';
        successMsg.style.display='none';
        submitBtn.disabled=true;
    }
    else if(value > 0){
        errorMsg.style.display='none';
        successMsg.innerText="Monto válido ✔";
        successMsg.style.display='block';
        progressBar.style.width = ((value/currentMax)*100)+"%";
        step3.classList.add('active');
    }
});

/* Paso 3 */
methodSelect.addEventListener('change', function(){
    if(this.value){
        step4.classList.add('active');
    }
});

/* Paso 4 */
fileInput.addEventListener('change', function(){
    if(this.files.length > 0){
        filePreview.innerText = "Archivo seleccionado: " + this.files[0].name;
        filePreview.style.display='block';
        submitBtn.disabled=false;
    }
});

</script>
<?php endif; ?>
