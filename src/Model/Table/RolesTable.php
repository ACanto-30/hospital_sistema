<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RolesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('roles');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        // Asociación: un rol tiene muchos usuarios
        $this->hasMany('Users', [
            'foreignKey' => 'role_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 50)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        return $validator;
    }
}
