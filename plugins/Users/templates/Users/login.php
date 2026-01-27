<?php
/**
 * Vista de Login
 * Usa FormHelper y estilos inline para centrar el formulario
 */
?>

<div style="
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
">

    <div style="
        width: 350px;
        padding: 25px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        text-align: center;
    ">

        <h2>Iniciar Sesión</h2>

        <?= $this->Form->create() ?>

        <?= $this->Form->control('email', [
            'label' => 'Correo electrónico',
            'required' => true,
            'style' => 'width:100%; margin-bottom:15px;'
        ]) ?>

        <?= $this->Form->control('password', [
            'label' => 'Contraseña',
            'required' => true,
            'style' => 'width:100%; margin-bottom:20px;'
        ]) ?>

        <?= $this->Form->button('Ingresar', [
            'style' => 'width:100%; padding:10px;'
        ]) ?>

        <?= $this->Form->end() ?>

    </div>
</div>
