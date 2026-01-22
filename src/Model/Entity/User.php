<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class User extends Entity
{
    /**
     * Campos accesibles (mass assignment)
     */
    protected array $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Campos ocultos en respuestas JSON / arrays
     */
    protected array $_hidden = [
        'password',
    ];

    /**
     * Hasheo automático de contraseña
     * Permite asignar: $user->password = '1234'
     */
    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }

        return null;
    }
}
