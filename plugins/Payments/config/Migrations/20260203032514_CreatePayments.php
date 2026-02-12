<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePayments extends BaseMigration
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
        $table = $this->table('payments');

        $table
            ->addColumn('associate_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('amount', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'null' => false,
            ])
            ->addColumn('is_paid', 'boolean', [
                'null' => true,
                'default' => false,
            ])
            ->addColumn('payment_date', 'datetime', [
                'null' => true,
                'default'=> null,
            ])

            ->addForeignKey('associate_id', 'associates', 'id')

            ->create();
    }
}