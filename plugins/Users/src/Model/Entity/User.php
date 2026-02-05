<?php
declare(strict_types=1);

namespace Users\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * Entidad User
 *
 * Representa un registro de la tabla `usuarios`.
 * Aquí se controla qué campos se pueden asignar con patchEntity()
 * y se genera el hash de la contraseña.
 */
class User extends Entity
{
    /**
     * Campos permitidos para asignación masiva.
     * Evito '*' => true por seguridad.
     */
    protected array $_accessible = [
        'role_id' => true,
        'full_name' => true,
        'username' => true,
        'email' => true,
        'password' => true,   // aquí entra la contraseña en texto plano y se hashea con el setter
        'status' => true,
        'created_at' => true,
    ];

    /**
     * Campos ocultos al convertir a array/json.
     */
    protected array $_hidden = [
        'password',
    ];

    /**
     * Setter automático:
     * Cuando se asigna password, se guarda hasheado.
     */
    protected function _setPassword(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        \Cake\Log\Log::debug('Hashing password for user entity...');
        return (new DefaultPasswordHasher())->hash($value);
    }
    /**
     * Propiedad virtual para obtener el nombre del rol fácilmente
     */
    protected function _getRoleName(): ?string
    {
        if (isset($this->role) && isset($this->role->name)) {
            return $this->role->name;
        }
        return null;
    }
}
