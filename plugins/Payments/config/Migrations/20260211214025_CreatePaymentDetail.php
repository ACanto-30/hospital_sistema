<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePaymentDetail extends BaseMigration
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
        $table = $this->table('payment_detail');
        $table
        ->addColumn('payment_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('amount', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'null' => false,
            ])
        ->addColumn('proof_image', 'string', [
            'null' => false,
        ])
        ->addColumn('payment_method_id', 'integer', [
            'null' => false,
        ])  
        ->addColumn('payment_status_id', 'integer', [
            'null' => false,
            'default' => 1,
        ])
        ->addColumn('payment_date', 'datetime', [
            'null' => false,
        ])
        ->addColumn('processed_by_user_id', 'integer', [
            'null' => true,
        ])
        ->addForeignKey('payment_id', 'payments', 'id')
        ->addForeignKey('payment_method_id', 'payment_methods', 'id')
        ->addForeignKey('payment_status_id', 'payment_statuses', 'id')
        ->addForeignKey('processed_by_user_id', 'users', 'id')
        ->create();
    }
}
