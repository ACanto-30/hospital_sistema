<?php
declare(strict_types=1);

namespace Users\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RolesFixture
 */
class RolesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id_rol' => 1,
                'nombre_rol' => 'Lorem ipsum dolor sit amet',
                'descripcion' => 'Lorem ipsum dolor sit amet',
                'nivel_acceso' => 1,
                'activo' => 1,
                'fecha_creacion' => '2026-01-22 20:21:13',
            ],
        ];
        parent::init();
    }
}
