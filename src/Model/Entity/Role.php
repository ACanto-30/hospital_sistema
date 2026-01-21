<?php
declare(strict_types=1);

namespace Users\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;


class Role extends Entity
{
    /**
     * Campos accesibles para asignación masiva
     */
    protected array $_accessible = [
        'nombre_rol'      => true,
        'descripcion'     => true,
        'nivel_acceso'    => true,
        'activo'          => true,
        'fecha_creacion'  => true,
        'id_rol'          => false,
    ];

    /**
     * Campos ocultos en JSON / arrays
     */
    protected array $_hidden = [];
}
