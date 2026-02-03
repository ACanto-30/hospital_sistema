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
            // 🔑 Primary Key
            ->addColumn('member_id', 'integer', [
                'autoIncrement' => true,
            ])

            // 🔗 Relación 1 a 1 con usuarios
            ->addColumn('user_id', 'integer', [
                'null' => false,
            ])

            // 🪪 Identificación
            ->addColumn('id_card', 'string', [
                'limit' => 20,
                'null' => false,
            ])

            // 👤 Datos personales
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

            // 🏥 Plan
            ->addColumn('plan_id', 'integer', [
                'null' => false,
            ])

            // 📌 Estado
            ->addColumn('member_status', 'enum', [
                'values' => ['active', 'inactive', 'suspended'],
                'default' => 'active',
            ])

            // 📅 Registro
            ->addColumn('registered_at', 'datetime', [
                'null' => false,
            ])

            // 🔗 Foreign Keys
            ->addForeignKey('user_id', 'usuarios', 'id')

            ->create();
    }
}
