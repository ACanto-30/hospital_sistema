<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Roles seed.
 */
class RolesSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'id'         => 1,
                'nombre_rol'     => 'Administrador',
                'descripcion'   => 'Acceso total al sistema',
                'nivel_acceso'  => 1,
                'activo'        => 1,
                'fecha_creacion'=> '2026-01-22 20:01:59',
            ],
            [
                'id'         => 2,
                'nombre_rol'     => 'Cajero',
                'descripcion'   => 'Registro y validación de pagos',
                'nivel_acceso'  => 2,
                'activo'        => 1,
                'fecha_creacion'=> '2026-01-22 20:01:59',
            ],
            [
                'id'         => 3,
                'nombre_rol'     => 'Médico',
                'descripcion'   => 'Consulta de información clínica de asociados',
                'nivel_acceso'  => 3,
                'activo'        => 1,
                'fecha_creacion'=> '2026-01-22 20:01:59',
            ],
            [
                'id'         => 4,
                'nombre_rol'     => 'Analista',
                'descripcion'   => 'Generación de reportes y control estadístico',
                'nivel_acceso'  => 4,
                'activo'        => 1,
                'fecha_creacion'=> '2026-01-22 20:01:59',
            ],
            [
                'id'         => 5,
                'nombre_rol'     => 'Auditor',
                'descripcion'   => 'Revisión y supervisión de registros',
                'nivel_acceso'  => 5,
                'activo'        => 1,
                'fecha_creacion'=> '2026-01-22 20:01:59',
            ],
        ];

        $table = $this->table('roles');
        $table->insert($data)->save();
    }
}
