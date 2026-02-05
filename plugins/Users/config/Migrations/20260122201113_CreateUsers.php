<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUsers extends BaseMigration
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
    $table = $this->table('users');
    $table
        ->addColumn('role_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('full_name', 'string', [
            'limit' => 100,
            'null' => false,
        ])
        ->addColumn('username', 'string', [
            'limit' => 50,
            'null' => false,
        ])
        ->addColumn('email', 'string', [
            'limit' => 100,
            'null' => false,
        ])
        ->addColumn('password', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('status', 'string', [
            'limit' => 20,
            'null' => false,
        ])
        ->addColumn('created_at', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
        ])
        ->addForeignKey('role_id', 'roles', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE',
        ])
        ->create();
}
}
