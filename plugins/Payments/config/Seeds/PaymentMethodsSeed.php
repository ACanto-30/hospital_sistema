<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * PaymentMethods seed.
 */
class PaymentMethodsSeed extends BaseSeed
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
            ['name' => 'Efectivo', 'details' => 'Pago directo en ventanilla'],
            ['name' => 'Yappy', 'details' => 'Transferencia vía número de teléfono'],
            ['name' => 'Transferencia', 'details' => 'ACH o transferencia bancaria directa'],
        ];

        $table = $this->table('payment_methods');
        $table->insert($data)->save();
    }
}
