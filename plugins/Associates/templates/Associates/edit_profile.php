<?php
$this->assign('title', 'Editar Perfil');
?>

<?php $this->append('css'); ?>
<style>
  :root{
    --verde-oscuro:#0b6b3a;
    --verde:#1b8a5a;
    --verde-claro:#e6f6ee;
    --gris:#555;
    --gris-claro:#7a7a7a;
    --sombra: rgba(0,0,0,.18);
    --radius: 20px;
    --transition: .25s;
    --font-main: 'Segoe UI', Roboto, sans-serif;
  }

  /* Wrapper*/
  .profile-wrap{
    max-width: 1200px;
    margin: 0 auto;
    padding: 26px 28px 40px;
  }

  /* Head*/
  .profile-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:18px;
    margin-bottom:18px;
  }
  .profile-title{
    margin:0;
    font-family: var(--font-main);
    color:#ffffff;
    font-weight: 900;
    font-size: 2rem;
    letter-spacing: .2px;
  }

  .profile-sub{
    margin:8px 0 0;
    color:#ffffffcc; /* blanco suave */
    font-size: 1rem;
  }

  .btn-pro{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding: 12px 18px;
    border-radius: 16px;
    text-decoration:none;
    font-weight: 800;
    color:#fff;
    background: linear-gradient(145deg, var(--verde), var(--verde-oscuro));
    box-shadow: 0 12px 28px var(--sombra);
    transition: transform var(--transition), box-shadow var(--transition);
    white-space: nowrap;
  }
  .btn-pro:hover{
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 16px 36px var(--sombra);
  }

  /* Card glass */
  .profile-card{
    background: rgba(255,255,255,.92);
    border-radius: var(--radius);
    padding: 26px;
    box-shadow: 0 20px 60px rgba(0,0,0,.15);
    border: 1px solid rgba(11,107,58,.12);
  }

  /* Grid */
  .grid{
    display:grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px 18px;
  }
  @media (max-width: 900px){
    .grid{ grid-template-columns: 1fr; }
    .profile-head{ align-items:flex-start; flex-direction:column; }
  }

  /* Inputs*/
  .profile-card .input,
  .profile-card input[type="text"],
  .profile-card input[type="email"],
  .profile-card input[type="date"],
  .profile-card textarea,
  .profile-card select{
    width: 100%;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,.12);
    background: rgba(255,255,255,.98);
    outline: none;
    transition: border-color var(--transition), box-shadow var(--transition), transform var(--transition);
    font-family: var(--font-main);
    font-size: 1rem;
  }
  .profile-card textarea{ resize: vertical; min-height: 92px; }

  .profile-card input:focus,
  .profile-card textarea:focus,
  .profile-card select:focus{
    border-color: rgba(27,138,90,.55);
    box-shadow: 0 0 0 4px rgba(27,138,90,.12);
    transform: translateY(-1px);
  }

  /* Labels */
  .profile-card label{
    display:block;
    margin-bottom: 6px;
    color: #2f2f2f;
    font-weight: 800;
    font-size: .92rem;
  }

  .profile-card .input{ margin:0; }
  .profile-card .input + .input{ margin-top:0; }

  /* ✅ Campo bloqueado (solo visual) */
  .profile-card input:disabled,
  .profile-card textarea:disabled,
  .profile-card select:disabled{
    background: #f3f4f6;
    color: #6b7280;
    border-color: rgba(0,0,0,.10);
    cursor: not-allowed;
    opacity: 1; /* mantiene legible */
  }

  /* Info box */
  .info-box{
    margin-top: 16px;
    padding: 14px 16px;
    border-radius: 16px;
    background: linear-gradient(145deg, #f3fdf7, #e6f6ee);
    border: 1px solid rgba(11,107,58,.18);
  }
  .info-box .t{
    color: var(--verde-oscuro);
    font-weight: 900;
    margin: 0 0 6px 0;
  }
  .info-box .d{
    color: #5f6b66;
    margin: 0;
  }

  /* Footer actions */
  .actions{
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top: 18px;
    flex-wrap: wrap;
  }

  .btn-cancel{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 12px 16px;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.12);
    background: #fff;
    color: #222;
    text-decoration:none;
    font-weight: 800;
    transition: transform var(--transition), box-shadow var(--transition);
  }
  .btn-cancel:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(0,0,0,.10);
  }

  .btn-save{
    padding: 12px 18px;
    border-radius: 16px;
    border: 0;
    color:#fff;
    font-weight: 900;
    cursor:pointer;
    background: linear-gradient(145deg, var(--verde), var(--verde-oscuro));
    box-shadow: 0 12px 28px var(--sombra);
    transition: transform var(--transition), box-shadow var(--transition);
  }
  .btn-save:hover{
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 16px 36px var(--sombra);
  }

  /* Helpers */
  .divider{
    height:1px;
    background: rgba(0,0,0,.08);
    margin: 14px 0 18px;
    border:0;
  }
</style>
<?php $this->end(); ?>

<div class="profile-wrap">
  <div class="profile-head">
    <div>
      <h2 class="profile-title">Editar perfil</h2>
      <p class="profile-sub">Actualiza tu información personal.</p>
    </div>

    <?= $this->Html->link(
      '← Volver al dashboard',
      ['plugin' => 'Associates', 'controller' => 'Associates', 'action' => 'dashboard'],
      ['class' => 'btn-pro']
    ) ?>
  </div>

  <div class="profile-card">
    <?= $this->Form->create($associate) ?>

    <hr class="divider">

    <div class="grid">
      <?= $this->Form->control('id_card', [
        'label' => 'Cédula / ID',
        'class' => 'form-control',
        'disabled' => true
      ]) ?>

      <?= $this->Form->control('birth_date', [
        'label' => 'Fecha de nacimiento',
        'type' => 'date',
        'class' => 'form-control',
        'disabled' => true
      ]) ?>

      <?= $this->Form->control('first_name', [
        'label' => 'Nombres',
        'class' => 'form-control',
        'disabled' => true
      ]) ?>

      <?= $this->Form->control('last_name', [
        'label' => 'Apellidos',
        'class' => 'form-control',
        'disabled' => true
      ]) ?>

      <?= $this->Form->control('phone', [
        'label' => 'Teléfono',
        'class' => 'form-control',
        'required' => false
      ]) ?>

      <?= $this->Form->control('email', [
        'label' => 'Correo',
        'class' => 'form-control',
        'disabled' => true
      ]) ?>
    </div>

    <div style="margin-top:16px;">
      <?= $this->Form->control('address', [
        'label' => 'Dirección',
        'type' => 'textarea',
        'rows' => 3,
        'class' => 'form-control',
        'required' => false
      ]) ?>
    </div>

    <div class="info-box">
      <p class="t">Datos no editables aquí</p>
      <p class="d">
        Plan: <strong><?= h($associate->insurance_plan->plan_name ?? $associate->insurance_plan->name ?? 'N/D') ?></strong>
        &nbsp;|&nbsp;
        Estado: <strong><?= h($associate->member_status ?? 'N/D') ?></strong>
      </p>
    </div>

    <div class="actions">
      <?= $this->Html->link(
        'Cancelar',
        ['plugin' => 'Associates', 'controller' => 'Associates', 'action' => 'dashboard'],
        ['class' => 'btn-cancel']
      ) ?>

      <?= $this->Form->button('Guardar cambios', ['class' => 'btn-save']) ?>
    </div>

    <?= $this->Form->end() ?>
  </div>
</div>