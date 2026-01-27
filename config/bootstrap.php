<?php
declare(strict_types=1);

/**
 * Bootstrap general del proyecto
 *
 * Aquí CakePHP carga lo básico:
 * - Paths del proyecto
 * - Configuración (app.php y app_local.php)
 * - Cache, Datasources (BD), Email, Logs, etc.
 * - Handlers de errores
 *
 * Nota: Los PLUGINS normalmente se cargan en src/Application.php (bootstrap()).
 * Si un plugin se carga 2 veces (en dos lugares), Cake lanza: "Plugin already loaded".
 */

/**
 * 1) Paths del proyecto (constantes como ROOT, APP, TMP, etc.)
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'paths.php';

/**
 * 2) Bootstrap interno de CakePHP (autoload + setup base)
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;

/**
 * 3) Funciones globales de CakePHP (helpers)
 */
require CAKE . 'functions.php';

/**
 * 4) Cargar configuración principal (config/app.php)
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/**
 * 5) Cargar configuración local (config/app_local.php) si existe
 *    - Aquí normalmente está la conexión a la BD (default/test)
 */
if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

/**
 * 6) En modo debug, hacer que el cache dure poco
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/**
 * 7) Zona horaria por defecto
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/**
 * 8) Encoding y locale
 */
mb_internal_encoding(Configure::read('App.encoding'));
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/**
 * 9) Manejadores de errores/excepciones
 */
(new ErrorTrap(Configure::read('Error')))->register();
(new ExceptionTrap(Configure::read('Error')))->register();

/**
 * 10) Ajustes extra para CLI
 */
if (PHP_SAPI === 'cli') {
    require CONFIG . 'bootstrap_cli.php';
}

/**
 * 11) Base URL (para links absolutos)
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
    $trustProxy = false;

    $s = null;
    if (env('HTTPS') || ($trustProxy && env('HTTP_X_FORWARDED_PROTO') === 'https')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        $fullBaseUrl = 'http' . $s . '://' . $httpHost;
    }
    unset($httpHost, $s);
}
if ($fullBaseUrl) {
    Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);

/**
 * 12) Aplicar configuraciones a CakePHP
 *     - Cache
 *     - Datasources (BD)
 *     - Email / Transport
 *     - Logs
 *     - Salt
 */
Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/**
 * 13) Detectores (mobile/tablet) - opcional
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isMobile();
});

ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isTablet();
});

/**
 * NOTA IMPORTANTE SOBRE PLUGINS:
 * - Este archivo normalmente NO carga plugins.
 * - Los plugins se cargan en src/Application.php (bootstrap()).
 * - Si ves el error: "Plugin already loaded", es porque lo estás cargando 2 veces.
 */
