<?php
declare(strict_types=1);

namespace Users;

use Cake\Core\BasePlugin;
use Cake\Routing\RouteBuilder;

/**
 * Plugin Users
 *
 * Aquí se definen las rutas del plugin para el manejo de:
 * - login
 * - register
 * - logout
 *
 * Todas las rutas del plugin quedan bajo el prefijo:
 * /users
 */
class UsersPlugin extends BasePlugin
{
    /**
     * Rutas del plugin Users.
     *
     * - /users/login
     * - /users/register
     * - /users/logout
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin('Users', ['path' => '/users'], function (RouteBuilder $builder) {

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

            // Rutas por defecto del plugin
            $builder->fallbacks();
        });

        parent::routes($routes);
    }
}

