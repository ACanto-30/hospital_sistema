<?php
declare(strict_types=1);

namespace Users\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Roles Model
 *
 * @method \Users\Model\Entity\Role newEmptyEntity()
 * @method \Users\Model\Entity\Role newEntity(array $data, array $options = [])
 * @method array<\Users\Model\Entity\Role> newEntities(array $data, array $options = [])
 * @method \Users\Model\Entity\Role get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Users\Model\Entity\Role findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \Users\Model\Entity\Role patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Users\Model\Entity\Role> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Users\Model\Entity\Role|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Users\Model\Entity\Role saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Users\Model\Entity\Role>|\Cake\Datasource\ResultSetInterface<\Users\Model\Entity\Role>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Users\Model\Entity\Role>|\Cake\Datasource\ResultSetInterface<\Users\Model\Entity\Role> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Users\Model\Entity\Role>|\Cake\Datasource\ResultSetInterface<\Users\Model\Entity\Role>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Users\Model\Entity\Role>|\Cake\Datasource\ResultSetInterface<\Users\Model\Entity\Role> deleteManyOrFail(iterable $entities, array $options = [])
 */
class RolesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('roles');
        $this->setDisplayField('nombre_rol');
        $this->setPrimaryKey('id_rol');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('nombre_rol')
            ->maxLength('nombre_rol', 50)
            ->requirePresence('nombre_rol', 'create')
            ->notEmptyString('nombre_rol')
            ->add('nombre_rol', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['nombre_rol']), ['errorField' => 'nombre_rol']);

        return $rules;
    }
}
