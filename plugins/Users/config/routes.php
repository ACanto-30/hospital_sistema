<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin('Users', ['path' => '/'], function (RouteBuilder $builder) {
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/register', ['controller' => 'Users', 'action' => 'register']);
        $builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
        $builder->connect('/dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
        $builder->fallbacks();
    });
};
