<?php
declare(strict_types=1);

namespace Users\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tabla Usuarios
 *
 * Maneja la tabla `usuarios` en la base de datos.
 * Aquí se definen:
 * - Tabla real en BD
 * - Clave primaria
 * - Campo de visualización
 * - Relaciones (Usuarios pertenece a Roles)
 * - Validaciones
 * - Reglas de integridad (unique y FK)
 */
class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Tabla exacta en la BD
        $this->setTable('usuarios');

        // PK
        $this->setPrimaryKey('id');

        // Campo que se usa para mostrar el usuario en listados
        $this->setDisplayField('nombre_usuario');

        // Relación: cada usuario pertenece a un rol
        $this->belongsTo('Roles', [
            'foreignKey' => 'id_rol',
            'joinType' => 'INNER',
            'className' => 'Users.Roles',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('id_rol')
            ->notEmptyString('id_rol');

        $validator
            ->scalar('nombre_completo')
            ->maxLength('nombre_completo', 100)
            ->notEmptyString('nombre_completo');

        $validator
            ->scalar('nombre_usuario')
            ->maxLength('nombre_usuario', 50)
            ->notEmptyString('nombre_usuario');

        $validator
            ->email('correo')
            ->maxLength('correo', 100)
            ->notEmptyString('correo');

        // En este proyecto guardo el hash en contrasena_hash.
        // El hash se genera en la Entity usando el setter _setContrasenaHash().
        $validator
            ->scalar('contrasena_hash')
            ->notEmptyString('contrasena_hash');

        $validator
            ->scalar('estado_usuario')
            ->maxLength('estado_usuario', 20)
            ->notEmptyString('estado_usuario');

        $validator
            ->dateTime('fecha_creacion')
            ->allowEmptyDateTime('fecha_creacion');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Correo y nombre de usuario únicos
        $rules->add($rules->isUnique(['correo']), ['errorField' => 'correo']);
        $rules->add($rules->isUnique(['nombre_usuario']), ['errorField' => 'nombre_usuario']);

        // FK: id_rol debe existir en la tabla roles
        $rules->add($rules->existsIn(['id_rol'], 'Roles'), ['errorField' => 'id_rol']);

        return $rules;
    }
}
