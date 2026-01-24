<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $roles   // lista enviada desde el controlador
 */
?>

<h1>Registro de Usuario</h1>

<?= $this->Form->create($user) ?>

<fieldset>
    <legend>Crear cuenta</legend>

    <?= $this->Form->control('name', [
        'label' => 'Nombre completo',
        'required' => true
    ]) ?>

    <?= $this->Form->control('email', [
        'label' => 'Correo electrónico',
        'required' => true
    ]) ?>

    <?= $this->Form->control('password', [
        'label' => 'Contraseña',
        'required' => true
    ]) ?>

    <?= $this->Form->control('role_id', [
        'label'   => 'Rol',
        'type'    => 'select',
        'options' => $roles,
        'empty'   => 'Seleccione un rol',
        'required'=> true
    ]) ?>
</fieldset>

<?= $this->Form->button('Registrarse') ?>

<?= $this->Form->end() ?>