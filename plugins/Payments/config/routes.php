<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin('Payments', ['path' => ''], function (RouteBuilder $builder) {
        $builder->fallbacks();
        $builder->connect('/pay', ['controller' => 'Payments', 'action' => 'pay']);
        $builder->connect('/dashboard-cashier', ['controller' => 'Payments', 'action' => 'dashboardCashier']);
    });
};