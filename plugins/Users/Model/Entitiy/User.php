<?php
declare(strict_types=1);

namespace Users\Model\Entity;

use Cake\ORM\Entity;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Entidad User (Plugin Users)
 * Representa un registro de la tabla `usuarios`.
 */
class User extends Entity
{
    protected array $_accessible = [
        'role_id' => true,
        'full_name' => true,
        'username' => true,
        'email' => true,
        'password' => true,
        'status' => true,
        'created_at' => true,
    ];

    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (new DefaultPasswordHasher())->hash($value);
    }

    /**
     * Virtual property for role_name
     */
    protected function _getRoleName(): ?string
    {
        if (isset($this->role) && isset($this->role->name)) {
            return $this->role->name;
        }
        return null;
    }
}
