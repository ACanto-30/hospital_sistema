<?php
declare(strict_types=1);

namespace Users\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsersTable (App)
 *
 * Maneja la tabla `usuarios` y su relación con `roles`.
 */
class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setEntityClass('Users\Model\Entity\User');
        $this->setPrimaryKey('id');
        $this->setDisplayField('username');

        // Cada usuario pertenece a un rol
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id', // Nombre de la FK
            'joinType' => 'INNER',
            'className' => 'Users.Roles',
        ]);

        // Manejo automático de created_at
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                ]
            ]
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('role_id')
            ->requirePresence('role_id', 'create')
            ->notEmptyString('role_id');

        $validator
            ->scalar('full_name') // nombre_completo -> full_name
            ->maxLength('full_name', 100)
            ->requirePresence('full_name', 'create')
            ->notEmptyString('full_name');

        $validator
            ->scalar('username') // nombre_usuario -> username
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->email('email') // correo -> email
            ->maxLength('email', 100)
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        // Validamos 'password' que viene del Form
        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password')
            ->minLength('password', 6);

        $validator
            ->scalar('status') // estado_usuario -> status
            ->maxLength('status', 20)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->dateTime('created_at') // fecha_creacion -> created_at
            ->allowEmptyDateTime('created_at');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
        $rules->add($rules->existsIn(['role_id'], 'Roles'), ['errorField' => 'role_id']);

        return $rules;
    }

    public function findAuth(\Cake\ORM\Query\SelectQuery $query, array $options)
    {
        \Cake\Log\Log::debug('[UsersTable] findAuth executing...');

        $query->contain(['Roles']);

        // Log para ver si el campo password está en el objeto resultante tras la consulta
        $query->formatResults(function ($results) {
            return $results->map(function ($row) {
                \Cake\Log\Log::debug('[UsersTable] User found in DB: ' . ($row->email ?? 'no email'));
                \Cake\Log\Log::debug('[UsersTable] Password field present: ' . (isset($row->password) ? 'YES' : 'NO'));
                if (isset($row->password)) {
                    \Cake\Log\Log::debug('[UsersTable] Password prefix: ' . substr($row->password, 0, 4));
                }
                return $row;
            });
        });

        return $query;
    }
}
