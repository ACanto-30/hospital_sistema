<?php
declare(strict_types=1);

namespace Users\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class User extends Entity
{
    /**
     * Campos accesibles (mass assignment)
     */
    protected array $_accessible = [
        '*' => true,
        'id_usuario' => false,
    ];

    /**
     * Campos ocultos en JSON / arrays
     */
    protected array $_hidden = [
        'contrasena_hash',
    ];

    /**
     * Hasheo automático de la contraseña
     * Permite asignar: $user->contrasena = '1234'
     * y guarda en contrasena_hash
     */
    protected function _setContrasena(string $contrasena): ?string
    {
        if (strlen($contrasena) > 0) {
            return (new DefaultPasswordHasher())->hash($contrasena);
        }

        return null;
    }
}
