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
      <rect x="20" y="4" width="24" height="56" rx="6" fill="#fff" />
      <rect x="4" y="20" width="56" height="24" rx="6" fill="#fff" />
      <path d="M18 34h6l4-8 6 16 4-8h8" fill="none" stroke="#0b8f55" stroke-width="3" />
      <path d="M32 44c-8-6-12-10-12-15a7 7 0 0 1 12-5a7 7 0 0 1 12 5c0 5-4 9-12 15z" fill="#0b8f55" />
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
      <?= $this->Form->control('full_name', [
        'label' => false,
        'required' => true,
        'placeholder' => 'Nombre completo'
      ]) ?>
    </div>

    <!-- Nombre de usuario -->
    <div class="input-group">
      <span class="icon left" aria-hidden="true">🆔</span>
      <?= $this->Form->control('username', [
        'label' => false,
        'required' => true,
        'placeholder' => 'Nombre de usuario'
      ]) ?>
    </div>

    <!-- Correo -->
    <div class="input-group">
      <span class="icon left" aria-hidden="true">📧</span>
      <?= $this->Form->control('email', [
        'label' => false,
        'required' => true,
        'type' => 'email',
        'placeholder' => 'Correo electrónico'
      ]) ?>
    </div>

    <!-- Contraseña (se envía en este campo; luego se hashea al guardar) -->
    <div class="input-group">
      <span class="icon left" aria-hidden="true">🔒</span>
      <?= $this->Form->control('password', [
        'label' => false,
        'required' => true,
        'type' => 'password',
        'placeholder' => 'Contraseña',
        'id' => 'password-input'
      ]) ?>

      <span class="icon right" aria-hidden="true" id="toggle-password" style="cursor: pointer;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
          <circle cx="12" cy="12" r="3" />
        </svg>
      </span>
    </div>

    <script>
      document.getElementById('toggle-password').addEventListener('click', function () {
        var passwordInput = document.getElementById('password-input');
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
        } else {
          passwordInput.type = 'password';
        }
      });
    </script>

    <!-- Rol -->
    <div class="input-group">
      <span class="icon left" aria-hidden="true">🧩</span>
      <?= $this->Form->control('role_id', [
        'label' => false,
        'type' => 'select',
        'required' => true,
        'options' => $roles,
        'empty' => 'Seleccione un rol',
        'id' => 'role-selector'
      ]) ?>
    </div>

    <!-- Campos extra para Asociado (Se muestran dinámicamente) -->
    <div id="associate-fields" style="display: none; border-top: 1px solid #eee; padding-top: 15px; margin-top: 5px;">
      <p style="font-size: 0.85em; color: #666; margin-bottom: 10px;">Información de Asociado</p>

      <div class="input-group">
        <span class="icon left" aria-hidden="true">🪪</span>
        <?= $this->Form->control('id_card', [
          'label' => false,
          'placeholder' => 'Número de Cédula'
        ]) ?>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
        <div class="input-group">
          <?= $this->Form->control('first_name', [
            'label' => false,
            'placeholder' => 'Nombre'
          ]) ?>
        </div>
        <div class="input-group">
          <?= $this->Form->control('last_name', [
            'label' => false,
            'placeholder' => 'Apellido'
          ]) ?>
        </div>
      </div>

      <div class="input-group">
        <span class="icon left" aria-hidden="true">📞</span>
        <?= $this->Form->control('phone', [
          'label' => false,
          'placeholder' => 'Teléfono (opcional)'
        ]) ?>
      </div>

      <div class="input-group">
        <span class="icon left" aria-hidden="true">📍</span>
        <?= $this->Form->control('address', [
          'label' => false,
          'placeholder' => 'Dirección (opcional)',
          'type' => 'textarea',
          'rows' => 2
        ]) ?>
      </div>

      <div class="input-group">
        <span class="icon left" aria-hidden="true">🏥</span>
        <?= $this->Form->control('plan_id', [
          'label' => false,
          'type' => 'select',
          'options' => $insurancePlans,
          'empty' => 'Seleccione un Plan de Seguro'
        ]) ?>
      </div>
    </div>

    <script>
      // Lógica para mostrar/ocultar campos de asociado
      document.getElementById('role-selector').addEventListener('change', function () {
        const associateFields = document.getElementById('associate-fields');
        // El ID 4 corresponde a 'Asociado' según el seed
        if (this.value == '4') {
          associateFields.style.display = 'block';
          // Hacer campos requeridos si es asociado
          document.getElementsByName('id_card')[0].required = true;
          document.getElementsByName('first_name')[0].required = true;
          document.getElementsByName('last_name')[0].required = true;
          document.getElementsByName('plan_id')[0].required = true;
        } else {
          associateFields.style.display = 'none';
          // Quitar obligatoriedad
          document.getElementsByName('id_card')[0].required = false;
          document.getElementsByName('first_name')[0].required = false;
          document.getElementsByName('last_name')[0].required = false;
          document.getElementsByName('plan_id')[0].required = false;
        }
      });
    </script>

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