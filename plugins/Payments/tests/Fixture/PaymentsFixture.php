<?php
declare(strict_types=1);

namespace Payments\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Payments Fixture
 */
class PaymentsFixture extends TestFixture
{
    /**
     * Table
     *
     * @var string
     */
    public string $table = 'payments';

    /**
     * Fields
     */
    public array $fields = [
        'id' => [
            'type' => 'integer',
            'autoIncrement' => true,
        ],
        'associate_id' => [
            'type' => 'integer',
            'null' => false,
        ],
        'amount' => [
            'type' => 'decimal',
            'length' => 10,
            'precision' => 2,
            'null' => false,
        ],
        'is_paid' => [
            'type' => 'boolean',
            'default' => false,
        ],
        'payment_date' => [
            'type' => 'datetime',
            'null' => true,
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ];

    /**
     * Init
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'associate_id' => 1,
                'amount' => 15.50,
                'is_paid' => true,
                'payment_date' => '2026-02-27 06:27:24',
            ],
        ];

        parent::init();
    }
}
