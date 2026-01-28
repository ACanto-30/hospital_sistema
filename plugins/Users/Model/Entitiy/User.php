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
        'id_rol' => true,
        'nombre_completo' => true,
        'nombre_usuario' => true,
        'correo' => true,
        'contrasena_hash' => true,
        'estado_usuario' => true,
        'fecha_creacion' => true,
    ];

    protected array $_hidden = [
        'contrasena_hash',
    ];

    protected function _setContrasenaHash(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (new DefaultPasswordHasher())->hash($value);
    }
}
