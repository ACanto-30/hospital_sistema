<?php
declare(strict_types=1);

namespace Associates\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InsurancePlans Fixture
 */
class InsurancePlansFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'insurance_plans';

    /**
     * Fields
     *
     * Si ya tienes schema real, puedes eliminar esto
     * y usar $import
     */
    public array $fields = [
        'id' => [
            'type' => 'integer',
            'autoIncrement' => true,
        ],
        'name' => [
            'type' => 'string',
            'length' => 255,
            'null' => false,
        ],
        'description' => [
            'type' => 'text',
            'null' => true,
        ],
        'biweekly_fee' => [
            'type' => 'decimal',
            'length' => 10,
            'precision' => 2,
            'null' => false,
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
                'name' => 'Plan Básico',
                'description' => 'Cobertura básica de salud',
                'biweekly_fee' => 15.50,
            ],
            [
                'id' => 2,
                'name' => 'Plan Premium',
                'description' => 'Cobertura completa',
                'biweekly_fee' => 35.00,
            ],
        ];

        parent::init();
    }
}