<?php
declare(strict_types=1);

use Cake\Core\Configure;

/**
 * Bootstrap para CLI (terminal)
 *
 * Este archivo solo aplica cuando corro comandos como:
 * - php bin/cake.php server
 * - php bin/cake.php migrations migrate
 *
 * Aquí lo único que hago es separar los logs de CLI
 * para que no se mezclen con los logs normales del navegador.
 */

// Si existen logs configurados, en CLI los mando a archivos distintos
if (Configure::check('Log.debug')) {
    Configure::write('Log.debug.file', 'cli-debug');
}
if (Configure::check('Log.error')) {
    Configure::write('Log.error.file', 'cli-error');
}
