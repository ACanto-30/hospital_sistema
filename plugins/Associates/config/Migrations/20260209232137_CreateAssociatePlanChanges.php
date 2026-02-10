<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAssociatePlanChanges extends BaseMigration
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
        $table = $this->table('associate_plan_changes');

        $table
        ->addColumn('associate_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('old_plan_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('new_plan_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('reason', 'text', [
            'null' => true,
        ])
        ->addColumn('change_by_user_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('change_date', 'date', [
            'null' => false,
        ])

        ->addForeignKey('associate_id', 'associates', 'id')
        
        ->addForeignKey('old_plan_id', 'insurance_plans', 'id')

        ->addForeignKey('new_plan_id', 'insurance_plans', 'id')

        ->addForeignKey('change_by_user_id', 'users', 'id')

        ->create();
    }
}
