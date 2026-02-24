<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin('Users', ['path' => '/'], function (RouteBuilder $builder) {

        // Autenticación
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/register', ['controller' => 'Users', 'action' => 'register']);
        $builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
        $builder->connect('/edit-user/*', ['controller' => 'Users', 'action' => 'editUser']);

        // Dashboards
        $builder->connect('/administrator-dashboard', [
            'controller' => 'Users',
            'action' => 'administratorDashboard'
        ]);
        $builder->connect('/doctor-dashboard', [
            'controller' => 'Users',
            'action' => 'doctorDashboard'
        ]);
        $builder->connect('/toggle-user-status/*', [
            'controller' => 'Users',
            'action' => 'toggleUserStatus'
        ]);

        $builder->connect('/edit-associate-plan/*', [
            'controller' => 'Users',
            'action' => 'editAssociatePlan'
        ]);

        $builder->connect('/create-debt/:id', [
            'controller' => 'Users',
            'action' => 'createDebt'
        ], [
            '_name' => 'plugin_create_debt',
            'pass' => ['id'],
            'id' => '\d+'
        ]);

        // Fallbacks del plugin
        $builder->fallbacks();
    });
};
