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

use Cake\Http\ServerRequest;

// Middleware
use App\Middleware\RoleAccessMiddleware;

class Application extends BaseApplication implements
    AuthenticationServiceProviderInterface,
    AuthorizationServiceProviderInterface
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        $jwtKeyPath = CONFIG . 'jwt.key';
        Configure::write(
            'JWT.key',
            file_exists($jwtKeyPath) ? file_get_contents($jwtKeyPath) : null
        );

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))

            
            ->add(new AuthenticationMiddleware($this))

            
            ->add(function (
                \Psr\Http\Message\ServerRequestInterface $request,
                \Psr\Http\Server\RequestHandlerInterface $handler
            ) {
                $path = $request->getUri()->getPath();

                if ($path === '/doctor-dashboard') {
                    
                    $request = $request
                        ->withAttribute('skipAuthorization', true)
                        ->withAttribute('publicDemo', true);

                    return $handler->handle($request);
                }

                return $handler->handle($request);
            })

            /**
             * RoleAccessMiddleware (bypass temporal)
             */
            ->add(function (
                \Psr\Http\Message\ServerRequestInterface $request,
                \Psr\Http\Server\RequestHandlerInterface $handler
            ) {
                $path = $request->getUri()->getPath();
                if ($path === '/doctor-dashboard') {
                    return $handler->handle($request);
                }

                $mw = new RoleAccessMiddleware();
                return $mw->process($request, $handler);
            })

            
            ->add(new \App\Middleware\UnauthorizedRedirectMiddleware())

            /**
             * AuthorizationMiddleware (bypass temporal)
             */
            ->add(function (
                \Psr\Http\Message\ServerRequestInterface $request,
                \Psr\Http\Server\RequestHandlerInterface $handler
            ) {
                $path = $request->getUri()->getPath();
                if ($path === '/doctor-dashboard') {
                    return $handler->handle($request);
                }

                $mw = new AuthorizationMiddleware($this);
                return $mw->process($request, $handler);
            })

            /**
             * RequestAuthorizationMiddleware (bypass temporal)
             */
            ->add(function (
                \Psr\Http\Message\ServerRequestInterface $request,
                \Psr\Http\Server\RequestHandlerInterface $handler
            ) {
                $path = $request->getUri()->getPath();
                if ($path === '/doctor-dashboard') {
                    return $handler->handle($request);
                }

                $mw = new RequestAuthorizationMiddleware();
                return $mw->process($request, $handler);
            })

            
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
    }

    public function getAuthenticationService(
        ServerRequestInterface $request
    ): AuthenticationServiceInterface {
        $service = new AuthenticationService();

        $fields = [
            'username' => 'email',
            'password' => 'password',
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
            'loginUrl' => ['/login', '/'],
        ]);

        $service->loadAuthenticator('Authentication.Jwt', [
            'secretKey' => Configure::read('JWT.key'),
            'algorithm' => 'HS256',
            'header' => 'Authorization',
            'tokenPrefix' => 'Bearer',
            'returnPayload' => true,
        ]);

        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Users.Users',
                'finder' => 'auth',
            ],
        ]);

        return $service;
    }

    public function getAuthorizationService(
        ServerRequestInterface $request
    ): AuthorizationServiceInterface {
        $mapResolver = new MapResolver();
        $mapResolver->map(ServerRequest::class, \App\Policy\RequestPolicy::class);

        $ormResolver = new OrmResolver();

        $resolver = new ResolverCollection([$mapResolver, $ormResolver]);

        return new AuthorizationService($resolver);
    }
}
