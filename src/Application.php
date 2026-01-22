<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc.
 *
  * Licensed under The MIT License
  */

namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

/* ✅ AÑADIDO */
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
/* ✅ FIN AÑADIDO */

/**
  * Application setup class.
  */
class Application extends BaseApplication
 {
    public function bootstrap(): void
    {
    parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

    if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            /* 🔐 AÑADIDO: JWT Cookie → Authorization Header */
            ->add(function (
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {

                // Leer la cookie jwt_token
                $cookies = $request->getCookieParams();

                if (!empty($cookies['jwt_token'])) {
                    $jwt = $cookies['jwt_token'];

                    // Inyectar el token en el header Authorization
                    $request = $request->withHeader(
                        'Authorization',
                        'Bearer ' . $jwt
                    );
                }

                return $handler->handle($request);
            })
            /* 🔐 FIN AÑADIDO */

            // Handle plugin/theme assets
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Routing
            ->add(new RoutingMiddleware($this))

            // Body parser
            ->add(new BodyParserMiddleware())

            // CSRF
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));

        return $middlewareQueue;
    }

public function services(ContainerInterface $container): void
    {
    }

protected function bootstrapCli(): void
    {
    $this->addOptionalPlugin('Bake');
    $this->addPlugin('Migrations');
    }
}
