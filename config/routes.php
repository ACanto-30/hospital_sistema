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

        $builder->connect('/dashboard', ['controller' => 'Pages', 'action' => 'dashboard']);


        $builder->connect(
            '/payments-dashboard',
            ['plugin' => 'Payments', 'controller' => 'Payments', 'action' => 'dashboardCashier']
        );

        $builder->connect(
            '/dashboard-cajero',
            ['plugin' => 'Payments', 'controller' => 'Payments', 'action' => 'dashboardCashier']
        );


        $builder->connect(
            '/registrar-pago',
            ['plugin' => 'Payments', 'controller' => 'Payments', 'action' => 'pay']
        );
        $builder->connect(
            '/doctor-dashboard',
            ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'doctorDashboard']
        );

        $builder->fallbacks();
    });
};
