<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {

        // Página principal
        $builder->connect(
            '/',
            ['controller' => 'Pages', 'action' => 'display', 'home']
        );



        // Si quieres CRUD de usuarios con URLs normales:
        // /users, /users/add, /users/edit/1, etc.
        $builder->fallbacks();
    });
};

