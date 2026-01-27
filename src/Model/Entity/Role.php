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
        'nombre_rol' => true,
        'descripcion' => true,
        'nivel_acceso' => true,
        'activo' => true,
        'fecha_creacion' => true,
    ];

    /**
     * Campos ocultos en JSON/arrays (si no hay nada sensible, se puede dejar vacío).
     */
    protected array $_hidden = [];
}

