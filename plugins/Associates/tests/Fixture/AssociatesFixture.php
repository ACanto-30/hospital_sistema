<?php
declare(strict_types=1);

namespace Associates\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

final class AssociatesFixture extends TestFixture
{
    public string $table = 'associates';

    public array $records = [
        [
            'id' => 1,
            'user_id' => 1,
            'id_card' => '8-999-999',
            'first_name' => 'Test',
            'last_name' => 'Associate',
            'birth_date' => '1990-01-01',
            'phone' => '6000-0000',
            'email' => 'test@local.com',
            'address' => 'Test address',
            'plan_id' => 1,
            'member_status' => 'activo',
            'registered_at' => '2026-01-01 00:00:00',
        ],
    ];
}