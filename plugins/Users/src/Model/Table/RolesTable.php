<?php
declare(strict_types=1);

namespace Users\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tabla Roles
 *
 * Maneja la tabla `roles` en la base de datos.
 * Aquí se definen:
 * - La tabla real en BD
 * - La clave primaria
 * - Validaciones
 * - Reglas de integridad (como UNIQUE)
 */
class RolesTable extends Table
{
    /**
     * Configuración inicial de la tabla.
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre exacto de la tabla en la BD
        $this->setTable('roles');

        // Campo que se usará para mostrar el rol en listas
        $this->setDisplayField('nombre_rol');

        // En la BD la PK se llama "id"
        $this->setPrimaryKey('id');
    }

    /**
     * Validaciones por defecto.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('nombre_rol')
            ->maxLength('nombre_rol', 50)
            ->requirePresence('nombre_rol', 'create')
            ->notEmptyString('nombre_rol')
            ->add('nombre_rol', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table'
            ]);

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

    /**
     * Reglas de integridad (por ejemplo UNIQUE).
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Evita roles duplicados por nombre
        $rules->add($rules->isUnique(['nombre_rol']), [
            'errorField' => 'nombre_rol'
        ]);

        return $rules;
    }
}