use Cake\Routing\Route\DashedRoute;

return function ($routes) {
    $routes->plugin(
        'Pay',
        ['path' => '/Pay'],
        function ($builder) {
            $builder->connect('/pagar', [
                'controller' => 'Payments',
                'action' => 'add'
            ]);
            $builder->fallbacks(DashedRoute::class);
        }
    );
};
