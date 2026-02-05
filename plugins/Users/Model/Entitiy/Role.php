<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entidad Role
 *
 * Representa un registro de la tabla `roles`.
 */
class Role extends Entity
{
    /**
     * Campos accesibles para asignación masiva.
     * Por seguridad, NO habilito el id.
     */
    protected array $_accessible = [
        'name' => true,
        'description' => true,
        'access_level' => true,
        'active' => true,
        'created_at' => true,
    ];

    /**
     * Campos ocultos en JSON/arrays (si no hay nada sensible, se puede dejar vacío).
     */
    protected array $_hidden = [];
}

