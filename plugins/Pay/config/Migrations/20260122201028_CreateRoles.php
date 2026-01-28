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
        ->addColumn('nombre_rol', 'string', [
            'limit' => 50,
            'null' => false,
        ])
        ->addColumn('descripcion', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('nivel_acceso', 'integer', [
            'null' => false,
        ])
        ->addColumn('activo', 'boolean', [
            'default' => true,
            'null' => false,
        ])
        ->addColumn('fecha_creacion', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
        ])
        ->create();
}
}
