<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\Exception\ForbiddenException;
use Authorization\Exception\ForbiddenException as AuthForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UnauthorizedRedirectMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ForbiddenException | AuthForbiddenException $e) {

            $path = $request->getUri()->getPath();



            return (new Response())
                ->withHeader('Location', '/')
                ->withStatus(302);
        }
    }
}

