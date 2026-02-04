<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateMemberStatuses extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('member_statuses');

        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
            ->addPrimaryKey(['id'])

            ->addColumn('name', 'string', [
                'limit' => 50,
                'null' => false,
            ])

            ->addColumn('created', 'datetime', [
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'null' => false,
            ])

            ->create();
    }
}
