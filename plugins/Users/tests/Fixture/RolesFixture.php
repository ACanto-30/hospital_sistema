<?php
declare(strict_types=1);

namespace Users\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class RolesFixture extends TestFixture
{
    // Importa la estructura de la tabla real 'roles'
    public $import = ['table' => 'roles'];

    // Registros de ejemplo para los tests
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Administrator',
                'description' => 'Admin role',
                'access_level' => 100,
                'active' => 1,
                'created' => '2026-01-01 00:00:00',
                'modified' => '2026-01-01 00:00:00',
            ],
            [
                'id' => 2,
                'name' => 'User',
                'description' => 'User role',
                'access_level' => 10,
                'active' => 1,
                'created' => '2026-01-01 00:00:00',
                'modified' => '2026-01-01 00:00:00',
            ],
        ];
        parent::init();
    }
}
