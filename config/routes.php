<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {

        // Página principal -> Login del plugin Users
        $builder->connect(
            '/',
            ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']
        );

        $builder->fallbacks();
    });
};
