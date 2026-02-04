<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateMedicalRecord extends BaseMigration
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
    $table = $this->table('medical_records');
    
    $table
        ->addColumn('associate_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('doctor_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('diagnosis', 'text', [
            'null' => false,
        ])
        ->addColumn('treatment', 'text', [
            'null' => true,
        ])
        ->addColumn('visit_date', 'date', [
            'null' => false,
        ])

        ->addForeignKey('associate_id', 'members', 'member_id')
        ->addForeignKey('doctor_id', 'usuarios', 'id')

        ->create();
}
}