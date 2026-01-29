<?php
declare(strict_types=1);

namespace Users\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RolesTable (App)
 *
 * Maneja la tabla `roles` en la BD.
 * Un rol puede tener muchos usuarios (tabla `usuarios`).
 */
class RolesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('roles');
        $this->setPrimaryKey('id');          // ✅ PK real en la BD
        $this->setDisplayField('nombre_rol');

        // Un rol tiene muchos usuarios
        $this->hasMany('Users', [
            'foreignKey' => 'id_rol',
            'className' => 'Users.Users',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('nombre_rol')
            ->maxLength('nombre_rol', 50)
            ->requirePresence('nombre_rol', 'create')
            ->notEmptyString('nombre_rol');

        $validator
            ->scalar('descripcion')
            ->maxLength('descripcion', 255)
            ->allowEmptyString('descripcion');

        $validator
            ->integer('nivel_acceso')
            ->allowEmptyString('nivel_acceso');

        $validator
            ->boolean('activo')
            ->allowEmptyString('activo');

        $validator
            ->dateTime('fecha_creacion')
            ->allowEmptyDateTime('fecha_creacion');

        return $validator;
    }
}