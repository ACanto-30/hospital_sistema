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
        'id_rol' => true,
        'nombre_completo' => true,
        'nombre_usuario' => true,
        'correo' => true,
        'contrasena_hash' => true,   // aquí entra la contraseña en texto plano y se hashea con el setter
        'estado_usuario' => true,
        'fecha_creacion' => true,
    ];

    /**
     * Campos ocultos al convertir a array/json.
     */
    protected array $_hidden = [
        'contrasena_hash',
    ];

    /**
     * Setter automático:
     * Cuando se asigna contrasena_hash, se guarda hasheado.
     */
    protected function _setContrasenaHash(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (new DefaultPasswordHasher())->hash($value);
    }
}
