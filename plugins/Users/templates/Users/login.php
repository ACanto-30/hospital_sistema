<?php
$this->assign('title', 'Hospital Privado X | Iniciar Sesión');
?>

<div class="wrapper">

  <div class="logo">
    <svg viewBox="0 0 64 64" width="34" height="34" aria-hidden="true">
      <rect x="20" y="4" width="24" height="56" rx="6" fill="#fff"/>
      <rect x="4" y="20" width="56" height="24" rx="6" fill="#fff"/>
      <path d="M18 34h6l4-8 6 16 4-8h8" fill="none" stroke="#0b8f55" stroke-width="3"/>
      <path d="M32 44c-8-6-12-10-12-15a7 7 0 0 1 12-5a7 7 0 0 1 12 5c0 5-4 9-12 15z" fill="#0b8f55"/>
    </svg>
    Hospital Privado X
  </div>

  <div class="card">
    <h1>Bienvenido</h1>
    <p>Acceso seguro al sistema médico</p>

    <?= $this->Flash->render() ?>

    <?= $this->Form->create(null, ['autocomplete' => 'off']) ?>

      <div class="input-group">
        <span class="icon left" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="7" r="4"/>
            <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
          </svg>
        </span>

        <?= $this->Form->control('correo', [
          'label' => false,
          'required' => true,
          'placeholder' => 'Correo',
          'type' => 'email',
        ]) ?>
      </div>

      <div class="input-group">
        <span class="icon left" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="10" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </span>

        <?= $this->Form->control('contrasena_hash', [
          'label' => false,
          'required' => true,
          'placeholder' => 'Contraseña',
          'type' => 'password',
        ]) ?>

        <span class="icon right" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
        </span>
      </div>

      <?= $this->Form->button('Iniciar Sesión', ['class' => 'btn btn-primary']) ?>

    <?= $this->Form->end() ?>

    <div class="forgot">
      <?= $this->Html->link('¿Olvidó su contraseña?', '#') ?>
    </div>

    <div class="register">
      ¿No tiene una cuenta?
      <?= $this->Html->link('Regístrese', ['action' => 'register']) ?>
    </div>
  </div>
</div>
