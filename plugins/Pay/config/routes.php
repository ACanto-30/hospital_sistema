<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin('Pay', ['path' => ''], function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Payments', 'action' => 'pay']);

        $builder->connect('/pagar', ['controller' => 'Payments', 'action' => 'add']);

        $builder->fallbacks(DashedRoute::class);
    });
};