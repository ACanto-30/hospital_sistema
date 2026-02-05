<?= $this->Form->create(null, [
    'url' => [
        'plugin' => 'Pagos',
        'controller' => 'Payments',
        'action' => 'add'
    ]
]) ?>

<?= $this->Form->control('amount', [
    'label' => 'Monto a pagar'
]) ?>

<?= $this->Form->control('method', [
    'label' => 'Método de pago',
    'options' => [
        'Visa' => 'Visa',
        'MasterCard' => 'MasterCard',
        'PayPal' => 'PayPal'
    ]
]) ?>

<?= $this->Form->control('card_number', [
    'label' => 'Número de tarjeta'
]) ?>

<?= $this->Form->button('Pagar ahora') ?>
<?= $this->Form->end() ?>
