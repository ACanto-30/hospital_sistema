<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateInsurancePlans extends BaseMigration
{
    public function change(): void
{
    $table = $this->table('insurance_plans');
    $table
        ->addColumn('name', 'string', ['limit' => 100])
        ->addColumn('description', 'text', ['null' => true])
        ->addColumn('biweekly_fee', 'decimal', ['precision' => 10, 'scale' => 2])
        ->create();
}
}