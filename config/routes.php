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

        // Login (SRC)
        $builder->connect(
            '/login',
            ['controller' => 'Users', 'action' => 'login']
        );

        // Register (SRC)
        $builder->connect(
            '/register',
            ['controller' => 'Users', 'action' => 'register']
        );

        // Logout (SRC)
        $builder->connect(
            '/logout',
            ['controller' => 'Users', 'action' => 'logout']
        );

        // Si quieres CRUD de usuarios con URLs normales:
        // /users, /users/add, /users/edit/1, etc.
        $builder->fallbacks();
    });
};

