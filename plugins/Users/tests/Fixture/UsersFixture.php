<?php
declare(strict_types=1);

namespace Users\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{
    // Importa la estructura de la tabla real 'users'
    public $import = ['table' => 'users'];

    // Registros de ejemplo para los tests
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => '$2y$10$examplehash', // hash de contraseña de prueba
                'role_id' => 1,
                'created' => '2026-01-01 00:00:00',
                'modified' => '2026-01-01 00:00:00',
            ],
            [
                'id' => 2,
                'username' => 'jose',
                'email' => 'jose@example.com',
                'password' => '$2y$10$examplehash2',
                'role_id' => 2,
                'created' => '2026-01-01 00:00:00',
                'modified' => '2026-01-01 00:00:00',
            ],
        ];
        parent::init();
    }
}
