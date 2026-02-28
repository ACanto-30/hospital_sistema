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
        // 1. Rutas públicas base
if (
    $path === '/' ||
    str_starts_with($path, '/pages') ||
    ($plugin === 'Users' && $controller === 'Users' && in_array($action, ['login', 'register', 'logout'], true))
) {
    return true;
}

        // Si no hay identidad después de las rutas públicas, denegar
        if (!$identity) {
            return false;
        }

        // 2. LÓGICA DE USUARIOS AUTENTICADOS
        $user = $identity->getOriginalData();
        $roleName = $identity->role_name ?? ($user->role_name ?? ($user->role->name ?? null));

        // Dashboard genérico (Pages::dashboard): permitir a cualquier autenticado.
        if ($controller === 'Pages' && $action === 'dashboard') {
            return true;
        }

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
            'Payments' => ['Cajero', 'Asociado', 'Administrador'],
            'Associates' => ['Asociado', 'Medico', 'Médico'],
            'Users' => ['Administrador', 'Cajero', 'Asociado', 'Medico', 'Médico']
        ];

        if ($plugin && isset($zoneAccess[$plugin]) && in_array($roleName, $zoneAccess[$plugin], true)) {
            return true;
        }

      
        if (!$plugin && strpos($path, '/payments') === 0 && in_array($roleName, ['Administrador', 'Cajero'], true)) {
            return true;
        }

        return false;
    }
}