<?php
/**
 * Registro de usuarios
 */
?>

<h1>Registro de usuario</h1>

<?= $this->Flash->render() ?>

<?= $this->Form->create($user) ?>

    <?= $this->Form->control('nombre_completo') ?>
    <?= $this->Form->control('nombre_usuario') ?>
    <?= $this->Form->control('correo') ?>

    <?= $this->Form->control('contrasena_hash', [
        'type' => 'password',
        'label' => 'Contraseña'
    ]) ?>

    <?= $this->Form->control('id_rol', [
        'type' => 'select',
        'options' => $roles,
        'label' => 'Rol'
    ]) ?>

    <?= $this->Form->button('Registrarse') ?>
<?= $this->Form->end() ?>
