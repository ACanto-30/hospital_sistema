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

    /**
     * Limpieza de datos antes de validar/guardar
     */
    public function beforeMarshal(\Cake\Event\EventInterface $event, \ArrayObject $data, \ArrayObject $options): void
    {
        if (isset($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }
        if (isset($data['username'])) {
            $data['username'] = trim($data['username']);
        }
        // Estandarizar estado a 'activo' si viene como 'active' o vacío
        if (isset($data['status']) && ($data['status'] === 'active' || empty($data['status']))) {
            $data['status'] = 'activo';
        }
    }

    public function validationDefault(Validator $validator): Validator
{
    $validator
        ->integer('id')
        ->allowEmptyString('id', null, 'create');

    $validator
        ->integer('role_id')
        ->requirePresence('role_id', 'create', 'Debe seleccionar un rol')
        ->notEmptyString('role_id', 'Debe seleccionar un rol');

    $validator
        ->scalar('full_name')
        ->maxLength('full_name', 100, 'El nombre completo no puede exceder 100 caracteres')
        ->requirePresence('full_name', 'create', 'El nombre completo es obligatorio')
        ->notEmptyString('full_name', 'El nombre completo no puede estar vacío');

    $validator
        ->scalar('username')
        ->maxLength('username', 50, 'El nombre de usuario no puede exceder 50 caracteres')
        ->requirePresence('username', 'create', 'El nombre de usuario es obligatorio')
        ->notEmptyString('username', 'El nombre de usuario no puede estar vacío');

    $validator
        ->email('email', false, 'Ingrese un correo electrónico válido')
        ->maxLength('email', 100, 'El correo electrónico no puede exceder 100 caracteres')
        ->requirePresence('email', 'create', 'El correo electrónico es obligatorio')
        ->notEmptyString('email', 'El correo electrónico no puede estar vacío');

    $validator
        ->scalar('password')
        ->maxLength('password', 255)
        ->requirePresence('password', 'create', 'La contraseña es obligatoria')
        ->notEmptyString('password', 'La contraseña no puede estar vacía')
        ->minLength('password', 6, 'La contraseña debe tener al menos 6 caracteres');

    $validator
        ->scalar('status')
        ->maxLength('status', 20, 'El estado no puede exceder 20 caracteres')
        ->requirePresence('status', 'create', 'El estado es obligatorio')
        ->notEmptyString('status', 'El estado no puede estar vacío');

    $validator
        ->dateTime('created_at')
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
