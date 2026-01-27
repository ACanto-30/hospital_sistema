<?php
/**
 * Login de usuarios
 */
?>

<h1>Iniciar sesión</h1>

<?= $this->Flash->render() ?>

<?= $this->Form->create() ?>
    <?= $this->Form->control('correo', [
        'label' => 'Correo electrónico'
    ]) ?>

    <?= $this->Form->control('contrasena_hash', [
        'type' => 'password',
        'label' => 'Contraseña'
    ]) ?>

    <?= $this->Form->button('Ingresar') ?>
<?= $this->Form->end() ?>

