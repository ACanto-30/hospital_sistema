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
        $this->setPrimaryKey('id');          // PK real en la BD
        $this->setDisplayField('name');

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
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->integer('access_level')
            ->allowEmptyString('access_level');

        $validator
            ->boolean('active')
            ->allowEmptyString('active');

        $validator
            ->dateTime('created_at')
            ->allowEmptyDateTime('created_at');

        return $validator;
    }
}