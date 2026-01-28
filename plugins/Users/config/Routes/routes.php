<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {

    $routes->plugin('Users', ['path' => '/users'], function (RouteBuilder $builder) {
        $builder->connect('/', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/register', ['controller' => 'Users', 'action' => 'register']);
        $builder->connect('/dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
        $builder->fallbacks();
    });

};