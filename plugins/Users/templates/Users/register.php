<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $roles
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Hospital Privado X | Crear Cuenta</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?= $this->Html->css('users') ?>
</head>

<body>
<div class="wrapper">

    <!-- LOGO -->
    <div class="logo">
        <svg viewBox="0 0 64 64" width="34" height="34">
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

        <?= $this->Form->create($user, ['novalidate' => true]) ?>

        <!-- Nombre -->
        <div class="input-group">
            <span class="icon left">👤</span>
            <?= $this->Form->control('name', [
                'label' => false,
                'placeholder' => 'Nombre completo'
            ]) ?>
        </div>

        <!-- Email -->
        <div class="input-group">
            <span class="icon left">📧</span>
            <?= $this->Form->control('email', [
                'label' => false,
                'placeholder' => 'Correo electrónico'
            ]) ?>
        </div>

        <!-- Contraseña -->
        <div class="input-group">
            <span class="icon left">🔒</span>
            <?= $this->Form->control('password', [
                'label' => false,
                'placeholder' => 'Contraseña'
            ]) ?>
        </div>

        <!-- Rol -->
        <div class="input-group">
            <span class="icon left">🧩</span>
            <?= $this->Form->control('role_id', [
                'label' => false,
                'type' => 'select',
                'options' => $roles,
                'empty' => 'Seleccione un rol'
            ]) ?>
        </div>

        <?= $this->Form->button('Registrarse') ?>
        <?= $this->Form->end() ?>

        <div class="terms">
            Al registrarse, acepta nuestros Términos y Condiciones
        </div>

        <div class="login">
            ¿Ya tiene una cuenta?
            <?= $this->Html->link('Inicie Sesión', ['action' => 'login']) ?>
        </div>
    </div>
</div>
</body>
</html>