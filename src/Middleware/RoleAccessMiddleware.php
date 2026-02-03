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
    // Ajusta estos IDs según tu tabla roles
    private const ROLE_ADMIN   = 1;
    private const ROLE_PAGOS   = 2; // placeholder
    private const ROLE_CAJERO  = 3; // placeholder
    private const ROLE_MEDICO  = 4; // placeholder

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        // Identity lo agrega AuthenticationMiddleware
        $identity = $request->getAttribute('identity');

        // Si NO hay sesión y quiere entrar a algún dashboard, mando a login
        if (!$identity) {
            if ($this->isDashboardPath($path)) {
                return (new Response())
                    ->withHeader('Location', '/login')
                    ->withStatus(302);
            }

            return $handler->handle($request);
        }

        $user = $identity->getOriginalData();
        $roleId = (int)($user->id_rol ?? 0);

        // Mapa rol -> dashboard permitido
        $roleToDashboard = [
            self::ROLE_ADMIN  => '/administrator-dashboard',
            self::ROLE_PAGOS  => '/payments-dashboard',
            self::ROLE_CAJERO => '/cashier-dashboard',
            self::ROLE_MEDICO => '/doctor-dashboard',
        ];

        $allowedDashboard = $roleToDashboard[$roleId] ?? '/dashboard';

        // Si entra a /dashboard, lo mando al dashboard real por rol
        if ($path === '/dashboard' && $allowedDashboard !== '/dashboard') {
            return (new Response())
                ->withHeader('Location', $allowedDashboard)
                ->withStatus(302);
        }

        // Si intenta entrar a un dashboard que no le toca, lo redirijo
        if ($this->isSpecificDashboardPath($path) && $path !== $allowedDashboard) {
            return (new Response())
                ->withHeader('Location', $allowedDashboard)
                ->withStatus(302);
        }

        return $handler->handle($request);
    }

    private function isDashboardPath(string $path): bool
    {
        return $path === '/dashboard' || $this->isSpecificDashboardPath($path);
    }

    private function isSpecificDashboardPath(string $path): bool
    {
        return in_array($path, [
            '/administrator-dashboard',
            '/payments-dashboard',
            '/cashier-dashboard',
            '/doctor-dashboard',
        ], true);
    }
}