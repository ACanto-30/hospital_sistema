<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateMembers extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('members', [
            'id' => false,
            'primary_key' => ['member_id'],
        ]);

        $table
            ->addColumn('member_id', 'integer', [
                'autoIncrement' => true,
            ])
            ->addColumn('id_card', 'string', [
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('first_name', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('last_name', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('phone', 'string', [
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('address', 'text', [
                'null' => true,
            ])
            ->addColumn('plan_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('member_status', 'enum', [
                'values' => ['active', 'inactive', 'suspended'],
                'default' => 'active',
            ])
            ->addColumn('registered_at', 'datetime', [
                'null' => false,
            ])
            ->create();
    }
}
