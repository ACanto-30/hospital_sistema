<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateInsurancePlans extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
{
    $table = $this->table('insurance_plans');
    $table
        ->addColumn('name', 'string', ['limit' => 100])
        ->addColumn('description', 'text', ['null' => true])
        ->addColumn('monthly_fee', 'decimal', ['precision' => 10, 'scale' => 2])
        ->create();
}
}