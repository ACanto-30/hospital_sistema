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
                'id' => 1,
                'name' => 'Administrador',
                'description' => 'Acceso total al sistema',
                'access_level' => 1,
                'active' => 1,
                'created_at' => '2026-01-22 20:01:59',
            ],
            [
                'id' => 2,
                'name' => 'Cajero',
                'description' => 'Registro y validación de pagos',
                'access_level' => 2,
                'active' => 1,
                'created_at' => '2026-01-22 20:01:59',
            ],
            [
                'id' => 3,
                'name' => 'Médico',
                'description' => 'Consulta de información clínica de asociados',
                'access_level' => 3,
                'active' => 1,
                'created_at' => '2026-01-22 20:01:59',
            ],
            [
                'id' => 4,
                'name' => 'Asociado',
                'description' => 'Asociado de la institución',
                'access_level' => 4,
                'active' => 1,
                'created_at' => '2026-01-22 20:01:59',
            ]
        ];

        $table = $this->table('roles');
        $table->insert($data)->save();
    }
}
