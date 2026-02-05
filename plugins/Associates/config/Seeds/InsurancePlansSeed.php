<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * InsurancePlans seed.
 */
class InsurancePlansSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Plan Básico ($25/quincena)',
                'description' => 'Cobertura esencial con pagos quincenales',
                'biweekly_fee' => 25.00
            ],
            [
                'name' => 'Plan Estándar ($50/quincena)',
                'description' => 'Cobertura ampliada con pagos quincenales',
                'biweekly_fee' => 50.00
            ],
            [
                'name' => 'Plan Premium ($100/quincena)',
                'description' => 'Cobertura total VIP con pagos quincenales',
                'biweekly_fee' => 100.00
            ],
        ];

        $table = $this->table('insurance_plans');
        $table->insert($data)->save();
    }
}
