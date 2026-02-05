<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RoleAccessMiddleware
 *
 * Middleware para controlar acceso a dashboards según el rol.
 */
class RoleAccessMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $identity = $request->getAttribute('identity');

        if (!$identity) {
            return $handler->handle($request);
        }

        $user = $identity->getOriginalData();
        $role_name = $identity->role_name ?? ($user->role_name ?? ($user->role->name ?? null));

        // Mapeo Maestro
        $roleDashboards = [
            'Administrador' => '/administrator-dashboard',
            'Cajero' => '/dashboard-cashier',
            'Medico' => '/doctor-dashboard',
            'Asociado' => '/associate-dashboard'
        ];

        // Obtenemos el role_name de la identidad
        $role_name = $identity->role_name ?? null;
        $target = $roleDashboards[$role_name] ?? null;

        // Si estamos en la raíz y ya hay sesión, mandarlo a su dashboard si lo tiene
        $basePaths = ['/', '/dashboard'];

        if (in_array($path, $basePaths) && $target) {
            if ($path !== $target) {
                return (new Response())
                    ->withHeader('Location', $target)
                    ->withStatus(302);
            }
        }

        // Protección contra acceso a otros dashboards
        $allDashboards = array_values($roleDashboards);
        if (in_array($path, $allDashboards) && $target) {
            if ($path !== $target) {
                return (new Response())
                    ->withHeader('Location', $target)
                    ->withStatus(302);
            }
        }

        return $handler->handle($request);
    }
}