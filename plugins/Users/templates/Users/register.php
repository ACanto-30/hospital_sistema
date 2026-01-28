<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $roles
 */

$this->assign('title', 'Hospital Privado X | Crear Cuenta');
?>

<div class="wrapper">

  <!-- LOGO -->
  <div class="logo">
    <svg viewBox="0 0 64 64" width="34" height="34" aria-hidden="true">
      <rect x="20" y="4" width="24" height="56" rx="6" fill="#fff"/>
      <rect x="4" y="20" width="56" height="24" rx="6" fill="#fff"/>
      <path d="M18 34h6l4-8 6 16 4-8h8" fill="none" stroke="#0b8f55" stroke-width="3"/>
      <path d="M32 44c-8-6-12-10-12-15a7 7 0 0 1 12-5a7 7 0 0 1 12 5c0 5-4 9-12 15z" fill="#0b8f55"/>
    </svg>
    Hospital Privado X
  </div>

  <!-- CARD -->
  <div class="card">
    <h1>Crear Cuenta</h1>
    <p>Ingrese sus datos para registrarse</p>

    <?= $this->Flash->render() ?>

    <?= $this->Form->create($user, ['novalidate' => true, 'autocomplete' => 'off']) ?>

      <!-- Nombre completo -->
      <div class="input-group">
        <span class="icon left" aria-hidden="true">👤</span>
        <?= $this->Form->control('nombre_completo', [
          'label' => false,
          'required' => true,
          'placeholder' => 'Nombre completo'
        ]) ?>
      </div>

      <!-- Nombre de usuario -->
      <div class="input-group">
        <span class="icon left" aria-hidden="true">🆔</span>
        <?= $this->Form->control('nombre_usuario', [
          'label' => false,
          'required' => true,
          'placeholder' => 'Nombre de usuario'
        ]) ?>
      </div>

      <!-- Correo -->
      <div class="input-group">
        <span class="icon left" aria-hidden="true">📧</span>
        <?= $this->Form->control('correo', [
          'label' => false,
          'required' => true,
          'type' => 'email',
          'placeholder' => 'Correo electrónico'
        ]) ?>
      </div>

      <!-- Contraseña (se envía en este campo; luego se hashea al guardar) -->
      <div class="input-group">
        <span class="icon left" aria-hidden="true">🔒</span>
        <?= $this->Form->control('contrasena_hash', [
          'label' => false,
          'required' => true,
          'type' => 'password',
          'placeholder' => 'Contraseña'
        ]) ?>
      </div>

      <!-- Rol -->
      <div class="input-group">
        <span class="icon left" aria-hidden="true">🧩</span>
        <?= $this->Form->control('id_rol', [
          'label' => false,
          'type' => 'select',
          'required' => true,
          'options' => $roles,
          'empty' => 'Seleccione un rol'
        ]) ?>
      </div>

      <?= $this->Form->button('Registrarse', ['class' => 'btn btn-primary']) ?>

    <?= $this->Form->end() ?>

    <div class="terms">
      Al registrarse, acepta nuestros Términos y Condiciones
    </div>

    <div class="login">
      ¿Ya tiene una cuenta?
      <?= $this->Html->link('Inicie Sesión', ['action' => 'login'], ['class' => 'link']) ?>
    </div>
  </div>
</div>