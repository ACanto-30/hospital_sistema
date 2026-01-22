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
    $table = $this->table('usuarios');
    $table
        ->addColumn('id_rol', 'integer', [
            'null' => false,
        ])
        ->addColumn('nombre_completo', 'string', [
            'limit' => 100,
            'null' => false,
        ])
        ->addColumn('nombre_usuario', 'string', [
            'limit' => 50,
            'null' => false,
        ])
        ->addColumn('correo', 'string', [
            'limit' => 100,
            'null' => false,
        ])
        ->addColumn('contrasena_hash', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('estado_usuario', 'string', [
            'limit' => 20,
            'null' => false,
        ])
        ->addColumn('fecha_creacion', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
        ])
        ->addForeignKey('id_rol', 'roles', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE',
        ])
        ->create();
}
}
