<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

/**
 * UsersTable
 * Validaciones y reglas de negocio para usuarios
 */
class UsersTable extends Table
{
    /**
     * Inicialización de la tabla
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre de la tabla en la base de datos
        $this->setTable('usuarios');

        // Clave primaria
        $this->setPrimaryKey('id_usuario');
    }

    /**
     * Validaciones de datos
     */
    public function validationDefault(Validator $validator): Validator
    {
        // Validar correo
        $validator
            ->requirePresence('correo', 'create')
            ->notEmptyString('correo', 'El correo es obligatorio')
            ->email('correo', false, 'Formato de correo inválido');

        // Validar contraseña (largo mínimo)
        $validator
            ->requirePresence('contrasena_hash', 'create')
            ->notEmptyString('contrasena_hash', 'La contraseña es obligatoria')
            ->minLength('contrasena_hash', 6, 'La contraseña debe tener al menos 6 caracteres');

        return $validator;
    }

    /**
     * Reglas de integridad
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Asegura que el correo sea único
        $rules->add(
            $rules->isUnique(['correo'], 'Este correo ya está registrado')
        );

        return $rules;
    }
}