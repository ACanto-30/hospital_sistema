<?php
declare(strict_types=1);

namespace App\Policy;

use Psr\Http\Message\ServerRequestInterface;

class RequestPolicy
{
    public function canAccess($identity, ServerRequestInterface $request): bool
    {
      
        $params = (array)$request->getAttribute('params', []);
        $plugin = $params['plugin'] ?? null;
        $controller = $params['controller'] ?? null;
        $action = $params['action'] ?? null;

       
        $path = $request->getUri()->getPath();

        
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

       
        if (!$identity) {
            return false;
        }

       
        if ($plugin === 'Users' && $controller === 'Users' && $action === 'logout') {
            return true;
        }

       
        $idRol = (int)$identity->get('id_rol');

    
        if ($idRol === 1) {
            return true;
        }

    
        if ($idRol === 2) {

            
            if ($controller === 'Payments' && in_array($action, ['dashboardCashier', 'pay'], true)) {
                return true;
            }

            
            if (in_array($path, ['/payments-dashboard', '/dashboard-cajero', '/registrar-pago'], true)) {
                return true;
            }

        }

        return false;
    }
}
