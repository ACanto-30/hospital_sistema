<?php
declare(strict_types=1);

namespace App\Model\Table;

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

        $this->setTable('usuarios');
        $this->setPrimaryKey('id');
        $this->setDisplayField('nombre_usuario');

        // Cada usuario pertenece a un rol
        $this->belongsTo('Roles', [
            'foreignKey' => 'id_rol',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('id_rol')
            ->requirePresence('id_rol', 'create')
            ->notEmptyString('id_rol');

        $validator
            ->scalar('nombre_completo')
            ->maxLength('nombre_completo', 100)
            ->requirePresence('nombre_completo', 'create')
            ->notEmptyString('nombre_completo');

        $validator
            ->scalar('nombre_usuario')
            ->maxLength('nombre_usuario', 50)
            ->requirePresence('nombre_usuario', 'create')
            ->notEmptyString('nombre_usuario');

        $validator
            ->email('correo')
            ->maxLength('correo', 100)
            ->requirePresence('correo', 'create')
            ->notEmptyString('correo');

        // Nota: aquí llega la contraseña (texto plano) y se hashea en la Entity con _setContrasenaHash()
        $validator
            ->scalar('contrasena_hash')
            ->maxLength('contrasena_hash', 255)
            ->requirePresence('contrasena_hash', 'create')
            ->notEmptyString('contrasena_hash')
            ->minLength('contrasena_hash', 6);

        $validator
            ->scalar('estado_usuario')
            ->maxLength('estado_usuario', 20)
            ->requirePresence('estado_usuario', 'create')
            ->notEmptyString('estado_usuario');

        // fecha_creacion tiene default en BD
        $validator
            ->dateTime('fecha_creacion')
            ->allowEmptyDateTime('fecha_creacion');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['correo']), ['errorField' => 'correo']);
        $rules->add($rules->isUnique(['nombre_usuario']), ['errorField' => 'nombre_usuario']);
        $rules->add($rules->existsIn(['id_rol'], 'Roles'), ['errorField' => 'id_rol']);

        return $rules;
    }
}
