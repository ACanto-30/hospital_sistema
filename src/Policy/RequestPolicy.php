<?php
declare(strict_types=1);

namespace App\Policy;

use Psr\Http\Message\ServerRequestInterface;

class RequestPolicy
{
    public function canAccess($identity, ServerRequestInterface $request): bool
    {
        $params = $request->getAttribute('params');
        $plugin = $params['plugin'] ?? null;
        $controller = $params['controller'] ?? null;
        $action = $params['action'] ?? null;
        $path = $request->getUri()->getPath();

        // 1. Rutas públicas base
        if ($path === '/' || ($plugin === 'Users' && $controller === 'Users' && in_array($action, ['login', 'register', 'logout'], true))) {
            return true;
        }

        // Si no hay identidad después de las rutas públicas, denegar
        if (!$identity) {
            return false;
        }

        // 2. LÓGICA DE USUARIOS AUTENTICADOS
        // Obtenemos el role_name de la identidad (usando la propiedad virtual)
        $roleName = $identity->role_name ?? null;

        // Administrador: Acceso Total
        if ($roleName === 'Administrador') {
            return true;
        }

        // Permitir acceso general al plugin Users para cualquier logueado (excepto dashboard de admin)
        if ($plugin === 'Users') {
            if ($action === 'administratorDashboard') {
                return false;
            }
            return true;
        }

        // 3. ZONAS POR PLUGIN
        $zoneAccess = [
            'Payments' => ['Cajero', 'Asociado'],
            'Associates' => ['Asociado', 'Medico']
        ];

        if (isset($zoneAccess[$plugin]) && in_array($roleName, $zoneAccess[$plugin])) {
            return true;
        }

        return false;
    }
}
