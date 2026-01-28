<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Cake\Http\Exception\UnauthorizedException;

class JwtAuthMiddleware
{
    public function __invoke($request, $handler)
    {
        $jwt = $request->getCookie('auth_token');

        if (!$jwt) {
            throw new UnauthorizedException('Token no encontrado');
        }

        try {
            $decoded = JWT::decode(
                $jwt,
                new Key(env('JWT_SECRET'), 'HS256')
            );

            // Inyectamos el usuario al request
            $request = $request->withAttribute('authUser', [
                'id' => $decoded->sub,
                'email' => $decoded->email
            ]);

        } catch (\Exception $e) {
            throw new UnauthorizedException('Token inválido');
        }

        return $handler->handle($request);
    }
}
