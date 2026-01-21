<?php
declare(strict_types=1);

namespace Users\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setPrimaryKey('id_usuario');
        $this->setDisplayField('nombre_usuario');

        /**
         * Relación con Roles
         */
        $this->belongsTo('Roles', [
            'foreignKey' => 'id_rol',
            'joinType' => 'INNER',
            'className' => 'Users.Roles',
        ]);
    }

    /**
     * Validaciones
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id_usuario')
            ->allowEmptyString('id_usuario', null, 'create');

        $validator
            ->integer('id_rol')
            ->notEmptyString('id_rol', 'El rol es obligatorio');

        $validator
            ->scalar('nombre_completo')
            ->maxLength('nombre_completo', 150)
            ->notEmptyString('nombre_completo');

        $validator
            ->scalar('nombre_usuario')
            ->maxLength('nombre_usuario', 100)
            ->notEmptyString('nombre_usuario');

        $validator
            ->email('correo')
            ->maxLength('correo', 150)
            ->notEmptyString('correo');

        $validator
            ->scalar('contrasena_hash')
            ->maxLength('contrasena_hash', 255)
            ->notEmptyString('contrasena_hash');

        $validator
            ->scalar('estado_usuario')
            ->maxLength('estado_usuario', 20)
            ->notEmptyString('estado_usuario');

        $validator
            ->dateTime('fecha_creacion')
            ->notEmptyDateTime('fecha_creacion');

        return $validator;
    }

    /**
     * Reglas de integridad
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(
            ['nombre_usuario'],
            'El nombre de usuario ya existe'
        ));

        $rules->add($rules->isUnique(
            ['correo'],
            'El correo ya está registrado'
        ));

        $rules->add($rules->existsIn(
            ['id_rol'],
            'Roles',
            'El rol seleccionado no existe'
        ));

        return $rules;
    }
}
