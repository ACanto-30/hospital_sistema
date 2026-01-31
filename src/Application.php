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

// Authentication
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;

// Authorization
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Authorization\Policy\MapResolver;
use Authorization\Policy\OrmResolver;
use Authorization\Policy\ResolverCollection;

// Para mapear Request -> RequestPolicy
use Cake\Http\ServerRequest;

class Application extends BaseApplication implements
    AuthenticationServiceProviderInterface,
    AuthorizationServiceProviderInterface
{
    
    public function bootstrap(): void
    {
        parent::bootstrap();

        // JWT (opcional)
        $jwtKeyPath = CONFIG . 'jwt.key';
        Configure::write(
            'JWT.key',
            file_exists($jwtKeyPath) ? file_get_contents($jwtKeyPath) : null
        );

        // Configuración del ORM / TableLocator
        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        // DebugKit solo cuando debug=true
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Plugin de Usuarios (con rutas)
        $this->addPlugin('Users', ['routes' => true]);

        // Plugin Authorization (permisos)
        $this->addPlugin('Authorization');
    }

   
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))

            // 1) Authentication: identifica al usuario (session/form/jwt)
            ->add(new AuthenticationMiddleware($this))

            // 2) Authorization: valida permisos (policies)
            ->add(new AuthorizationMiddleware($this))
            ->add(new RequestAuthorizationMiddleware())

            // Request body (JSON, form, etc.)
            ->add(new BodyParserMiddleware())

            // CSRF para formularios
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

    
    public function getAuthenticationService(
        ServerRequestInterface $request
    ): AuthenticationServiceInterface {
        $service = new AuthenticationService();

        $fields = [
            'username' => 'correo',
            'password' => 'contrasena_hash',
        ];

    
        $service->setConfig([
            'unauthenticatedRedirect' => Router::url([
                'plugin' => 'Users',
                'controller' => 'Users',
                'action' => 'login',
            ]),
            'queryParam' => 'redirect',
        ]);

       
        $service->loadAuthenticator('Authentication.Session');

        
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

        // Identifier: valida el password contra la BD
        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
            'resolver' => [
            'className' => 'Authentication.Orm',
            'userModel' => 'Users.Users',
    ],
]);


        

        return $service;
    }

  
    public function getAuthorizationService(
        ServerRequestInterface $request
    ): AuthorizationServiceInterface {
        // Request -> RequestPolicy
        $mapResolver = new MapResolver();
        $mapResolver->map(ServerRequest::class, \App\Policy\RequestPolicy::class);

        // Entidades ORM
        $ormResolver = new OrmResolver();

        // Primero intenta MapResolver, luego OrmResolver
        $resolver = new ResolverCollection([$mapResolver, $ormResolver]);

        return new AuthorizationService($resolver);
    }
}
