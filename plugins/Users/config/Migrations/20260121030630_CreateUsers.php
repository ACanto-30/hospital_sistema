<?php 
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUsers extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('users', [
            'id' => false,
            'primary_key' => ['id_usuario']
        ]);

        $table
            ->addColumn('id_usuario', 'integer', [
                'autoIncrement' => true,
                'null' => false
            ])
            ->addColumn('id_rol', 'integer', [
                'null' => false
            ])
            ->addColumn('nombre_completo', 'string', [
                'limit' => 150,
                'null' => false
            ])
            ->addColumn('nombre_usuario', 'string', [
                'limit' => 100,
                'null' => false
            ])
            ->addColumn('correo', 'string', [
                'limit' => 150,
                'null' => false
            ])
            ->addColumn('contrasena_hash', 'string', [
                'limit' => 255,
                'null' => false
            ])
            ->addColumn('estado_usuario', 'string', [
                'limit' => 20,
                'default' => 'activo',
                'null' => false
            ])
            ->addColumn('fecha_creacion', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false
            ])
            ->addIndex(['nombre_usuario'], ['unique' => true])
            ->addIndex(['correo'], ['unique' => true])

            // 🔐 FOREIGN KEY
            ->addForeignKey(
                'id_rol',
                'roles',
                'id_rol',
                [
                    'delete' => 'RESTRICT',
                    'update' => 'CASCADE'
                ]
            )

            ->create();
    }
}
