<?php
declare(strict_types=1);

namespace Users\Model\Entity;

use Cake\ORM\Entity;

/**
 * Role Entity
 *
 * @property int $id_rol
 * @property string $nombre_rol
 * @property string|null $descripcion
 * @property int|null $nivel_acceso
 * @property bool|null $activo
 * @property \Cake\I18n\DateTime|null $fecha_creacion
 */
class Role extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'nombre_rol' => true,
        'descripcion' => true,
        'nivel_acceso' => true,
        'activo' => true,
        'fecha_creacion' => true,
    ];
}
