<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin('Users', ['path' => '/'], function (RouteBuilder $builder) {

        // Autenticación
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/register', ['controller' => 'Users', 'action' => 'register']);
        $builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);

        // Dashboards
        $builder->connect('/dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
        $builder->connect('/administrator-dashboard', [
            'controller' => 'Users',
            'action' => 'administratorDashboard'
        ]);

        // Fallbacks del plugin
        $builder->fallbacks();
    });
};
