<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateInsurancePlans extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('insurance_plans');

        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
            ->addPrimaryKey(['id'])

            ->addColumn('name', 'string', [
                'limit' => 100,
                'null' => false,
            ])

            ->addColumn('description', 'text', [
                'null' => true,
            ])

            ->addColumn('monthly_fee', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'null' => false,
                'default' => 0.00,
            ])

            ->create();
    }
}