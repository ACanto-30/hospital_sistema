<?php
declare(strict_types=1);

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
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;

class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Bootstrap general de la aplicación
     */
    public function bootstrap(): void
    {
        parent::bootstrap();

        // JWT (opcional, por si luego uso API)
        $jwtKeyPath = CONFIG . 'jwt.key';
        Configure::write(
            'JWT.key',
            file_exists($jwtKeyPath) ? file_get_contents($jwtKeyPath) : null
        );

        // Configuración del ORM
        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        // DebugKit solo en modo debug
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Cargar Plugin de Usuarios
        $this->addPlugin('Users', ['routes' => true]);


    }

    /**
     * Middleware de la aplicación
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))

            // Middleware de Authentication
            ->add(new AuthenticationMiddleware($this))

            ->add(new BodyParserMiddleware())
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

    /**
     * Configuración del sistema de autenticación
     */
    public function getAuthenticationService(
        ServerRequestInterface $request
    ): AuthenticationServiceInterface {
        $service = new AuthenticationService();

        /**
         * Mi tabla `usuarios` usa:
         * - correo como usuario
         * - contrasena_hash como contraseña (hash en BD)
         *
         * En el formulario:
         * - correo
         * - contrasena_hash (texto plano)
         */
        $fields = [
            'username' => 'correo',
            'password' => 'contrasena_hash',
        ];

        // Si no está logueado, lo mando al login
        $service->setConfig([
            'unauthenticatedRedirect' => Router::url([
                'plugin' => 'Users',
                'controller' => 'Users',
                'action' => 'login',
            ]),
            'queryParam' => 'redirect',
        ]);

        // Autenticación por sesión
        $service->loadAuthenticator('Authentication.Session');

        // Autenticación por formulario
        $service->loadAuthenticator('Authentication.Form', [
            'fields' => $fields,
            'loginUrl' => [
                'plugin' => 'Users',
                'controller' => 'Users',
                'action' => 'login',
            ],
        ]);

        // JWT (opcional, para API)
        $service->loadAuthenticator('Authentication.Jwt', [
            'secretKey' => Configure::read('JWT.key'),
            'algorithm' => 'HS256',
            'header' => 'Authorization',
            'tokenPrefix' => 'Bearer',
            'returnPayload' => true,
        ]);

        // Identifier de password
        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
        ]);

        return $service;
    }
}