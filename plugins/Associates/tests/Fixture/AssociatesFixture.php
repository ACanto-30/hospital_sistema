<?php
declare(strict_types=1);

namespace Associates\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssociatesFixture
 */
class AssociatesFixture extends TestFixture
{
    public string $table = 'associates';

    public array $records = [
        [
            'id' => 1,
            'user_id' => 1,
            'id_card' => '8-888-888',
            'first_name' => 'Juan',
            'last_name' => 'Perez',
            'birth_date' => '1990-01-01',
            'phone' => '6000-0000',
            'email' => 'juan@test.com',
            'address' => 'Panama',
            'plan_id' => 1,
            'member_status' => 'active',
            'registered_at' => '2026-01-01 00:00:00',
        ],
    ];
}