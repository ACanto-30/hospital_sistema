<?php
declare(strict_types=1);

namespace App\Policy;

use Psr\Http\Message\ServerRequestInterface;

class RequestPolicy
{
    /**
     * Policy por Request:
     * Aquí verifico si un usuario puede acceder a una ruta (controller/action).
     *
     * Mi BD tiene roles:
     * 1 Administrador
     * 2 Cajero
     * 3 Médico
     * 4 Analista
     * 5 Auditor
     */
    public function canAccess($identity, ServerRequestInterface $request): bool
    {
        // Datos de la ruta actual
        $params = (array)$request->getAttribute('params', []);
        $plugin = $params['plugin'] ?? null;
        $controller = $params['controller'] ?? null;
        $action = $params['action'] ?? null;

        /**
         * 1) Rutas públicas (no requieren login)
         * - Home / páginas del sistema
         * - Pantallas de login y register del plugin Users
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
         * (mínimo necesario para que el sistema sea usable)
         */
        if ($plugin === 'Users' && $controller === 'Users' && $action === 'logout') {
            return true;
        }

        /**
         * 4) Reglas por rol:
         * Por ahora lo uso para demostrar Authorization en la tarea.
         * - Admin puede listar/ver usuarios
         * - Otros roles NO (por seguridad)
         */
        $idRol = (int)$identity->get('id_rol');

        // Administrador = 1
        if ($idRol === 1) {
            // Acceso total (si quieres full acceso del admin)
            return true;

            // Si prefieres que admin solo tenga index/view aquí, sería:
            // return ($plugin === 'Users' && $controller === 'Users' && in_array($action, ['index','view'], true));
        }

        /**
         * 5) Otros roles (2-5):
         * Por ahora solo los dejo usar logout y páginas públicas.
         * Cuando agregue módulos (pagos, clínico, reportes...), aquí les doy acceso.
         */
        return false;
    }
}


