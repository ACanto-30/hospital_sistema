<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin('Associates', ['path' => ''], function (RouteBuilder $builder) {
        $builder->fallbacks();
        $builder->connect('/associate-dashboard', ['controller' => 'Associates', 'action' => 'dashboard']);
    });
};
