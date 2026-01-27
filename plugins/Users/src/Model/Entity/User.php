<?php
declare(strict_types=1);

namespace Users\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * Entidad User
 *
 * Representa un usuario del sistema (tabla `usuarios`).
 * Aquí se define qué campos se pueden asignar con patchEntity()
 * y cómo se genera el hash de la contraseña.
 */
class User extends Entity
{
    /**
     * Campos permitidos para asignación masiva.
     * (Evito usar '*' => true por seguridad)
     */
    protected array $_accessible = [
        'id_rol' => true,
        'nombre_completo' => true,
        'nombre_usuario' => true,
        'correo' => true,
        'contrasena_hash' => true, // aquí entra la contraseña en texto plano y se hashea con el setter
        'estado_usuario' => true,
        'fecha_creacion' => true,
    ];

    /**
     * Campos ocultos (no se deben exponer al convertir en array/json)
     */
    protected array $_hidden = [
        'contrasena_hash',
    ];

    /**
     * Setter automático:
     * Cada vez que se asigne "contrasena_hash", se guarda hasheada.
     *
     * Ejemplo:
     * $user = $this->Users->patchEntity($user, ['contrasena_hash' => '1234']);
     * -> se guardará el hash en BD.
     */
    protected function _setContrasenaHash(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (new DefaultPasswordHasher())->hash($value);
    }
}

