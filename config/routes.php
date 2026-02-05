<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {

       
        $builder->connect(
            '/',
            ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']
        );

        
        $builder->connect(
            '/payments-dashboard',
            ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'dashboardCashier']
        );

        $builder->connect(
            '/dashboard-cajero',
            ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'dashboardCashier']
        );

        
        $builder->connect(
            '/registrar-pago',
            ['plugin' => 'Pay', 'controller' => 'Payments', 'action' => 'pay']
        );

        $builder->fallbacks();
    });
};
