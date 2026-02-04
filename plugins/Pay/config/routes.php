<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {

    $routes->plugin('Pay', ['path' => ''], function (RouteBuilder $builder): void {

        // Dashboard cajero 
        $builder->connect(
            '/payments-dashboard',
            ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'dashboardCashier']
        );

        $builder->connect(
            '/dashboard-cajero',
            ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'dashboardCashier']
        );

        // Página principal de pagos
        $builder->connect(
            '/',
            ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'pay']
        );

        // Registrar pago
        $builder->connect(
            '/pagar',
            ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'add']
        );

        // Fallbacks al final
        $builder->fallbacks(DashedRoute::class);
    });
};
