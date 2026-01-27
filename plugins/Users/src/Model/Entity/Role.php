<?php
declare(strict_types=1);

namespace Users\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entidad Role
 *
 * Representa un registro de la tabla `roles`.
 * Contiene la información del rol del usuario dentro del sistema.
 *
 * Campos principales:
 * - id: identificador único del rol
 * - nombre_rol: nombre del rol
 * - descripcion: descripción del rol
 * - nivel_acceso: nivel de permisos del rol
 * - activo: indica si el rol está activo
 * - fecha_creacion: fecha en la que se creó el rol
 */
class Role extends Entity
{
    /**
     * Campos que pueden asignarse de forma masiva usando
     * newEntity() o patchEntity().
     *
     * Por seguridad, el campo id no es asignable.
     */
    protected array $_accessible = [
        'nombre_rol' => true,
        'descripcion' => true,
        'nivel_acceso' => true,
        'activo' => true,
        'fecha_creacion' => true,
    ];
}
