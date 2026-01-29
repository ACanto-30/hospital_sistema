<?php
declare(strict_types=1);

namespace Users;

use Cake\Core\BasePlugin;
use Cake\Routing\RouteBuilder;

class UsersPlugin extends BasePlugin
{
    /**
     * Rutas del plugin Users.
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin('Users', ['path' => '/'], function (RouteBuilder $builder) {
            // Login
            $builder->connect('/login', [
                'controller' => 'Users',
                'action' => 'login'
            ]);

            // Registro
            $builder->connect('/register', [
                'controller' => 'Users',
                'action' => 'register'
            ]);

            // Logout
            $builder->connect('/logout', [
                'controller' => 'Users',
                'action' => 'logout'
            ]);

            // Dashboard
            $builder->connect('/dashboard', [
                'controller' => 'Users',
                'action' => 'dashboard'
            ]);

            $builder->fallbacks();
        });

        parent::routes($routes);
    }
}
