<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAssociatesConditions extends BaseMigration
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
        $table = $this->table('associates_conditions');
        $table
        ->addColumn('associate_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('condition_id', 'integer', [
            'null' => false,
        ])
        ->addForeignKey('associate_id', 'associates', 'id')
        ->addForeignKey('condition_id', 'conditions', 'id')
        ->create();
    }
}
