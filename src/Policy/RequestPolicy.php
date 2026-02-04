<?php
declare(strict_types=1);

namespace App\Policy;

use Psr\Http\Message\ServerRequestInterface;

class RequestPolicy
{
    public function canAccess($identity, ServerRequestInterface $request): bool
    {
        // Datos de la ruta actual
        $params = (array)$request->getAttribute('params', []);
        $plugin = $params['plugin'] ?? null;
        $controller = $params['controller'] ?? null;
        $action = $params['action'] ?? null;

        // Path real (útil cuando plugin viene null por 'path' => '')
        $path = $request->getUri()->getPath();

        /**
         * 1) Rutas públicas (no requieren login)
         */
        if (in_array($controller, ['Pages', 'Error'], true)) {
            return true;
        }

        if (
            $plugin === 'Users' &&
            $controller === 'Users' &&
            in_array($action, ['login', 'register'], true)
        ) {
            return true;
        }

        /**
         * 2) Si no hay usuario logueado, todo lo demás se bloquea
         */
        if (!$identity) {
            return false;
        }

        /**
         * 3) Acciones permitidas para cualquier usuario autenticado
         */
        if ($plugin === 'Users' && $controller === 'Users' && $action === 'logout') {
            return true;
        }

        /**
         * 4) Reglas por rol
         */
        $idRol = (int)$identity->get('id_rol');

        // Administrador = 1
        if ($idRol === 1) {
            return true;
        }

        /**
         * 5) Otros roles (2-5): accesos mínimos por módulo
         */

        // Cajero = 2
        if ($idRol === 2) {

            // Permitir por controller/action 
            if ($controller === 'Payments' && $action === 'dashboardCashier') {
                return true;
            }

            // Respaldo por URL exacta 
            if (in_array($path, ['/payments-dashboard', '/dashboard-cajero'], true)) {
                return true;
            }

            // (Opcional) permitir pantalla de pagos y registrar pago:
            // if ($controller === 'Payments' && in_array($action, ['pay', 'add'], true)) {
            //     return true;
            // }
        }

        return false;
    }
}
