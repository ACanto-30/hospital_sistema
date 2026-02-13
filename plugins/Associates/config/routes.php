<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin('Associates', ['path' => ''], function (RouteBuilder $builder) {
        $builder->fallbacks();
        $builder->connect('/associate-dashboard', ['controller' => 'Associates', 'action' => 'dashboard']);
        // editar perfil de asociado
        $builder->connect('/associate-profile/edit', ['controller' => 'Associates', 'action' => 'editProfile']);
    });
};
