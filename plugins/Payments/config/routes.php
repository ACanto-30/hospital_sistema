<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin('Payments', ['path' => ''], function (RouteBuilder $builder) {
        $builder->connect('/pay', ['controller' => 'Payments', 'action' => 'pay']);
        $builder->connect('/dashboard-cashier', ['controller' => 'Payments', 'action' => 'dashboardCashier']);
        $builder->connect(
            '/serve-payment-receipt/{id}',
            ['controller' => 'Payments', 'action' => 'serveReceipt'],
            ['_name' => 'serve_receipt']
        )->setPass(['id'])->setPatterns(['id' => '\d+']);
        $builder->connect(
            '/process-payment/{id}',
            ['controller' => 'Payments', 'action' => 'processPayment'],
            ['_name' => 'process_payment']
        )->setPass(['id'])->setPatterns(['id' => '\d+']);
        $builder->fallbacks();
    });
};