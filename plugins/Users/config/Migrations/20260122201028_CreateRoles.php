<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateRoles extends BaseMigration
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
    $table = $this->table('roles');
    $table
        ->addColumn('name', 'string', [
            'limit' => 50,
            'null' => false,
        ])
        ->addColumn('description', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('access_level', 'integer', [
            'null' => false,
        ])
        ->addColumn('active', 'boolean', [
            'default' => true,
            'null' => false,
        ])
        ->addColumn('created_at', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
        ])
        ->create();
}
}
