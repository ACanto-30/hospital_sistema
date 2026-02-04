<?php
declare(strict_types=1);

namespace Associates\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Members Model
 *
 * @method \Associates\Model\Entity\Member newEmptyEntity()
 * @method \Associates\Model\Entity\Member newEntity(array $data, array $options = [])
 * @method array<\Associates\Model\Entity\Member> newEntities(array $data, array $options = [])
 * @method \Associates\Model\Entity\Member get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Associates\Model\Entity\Member findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \Associates\Model\Entity\Member patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Associates\Model\Entity\Member> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Associates\Model\Entity\Member|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Associates\Model\Entity\Member saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Associates\Model\Entity\Member>|\Cake\Datasource\ResultSetInterface<\Associates\Model\Entity\Member>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Associates\Model\Entity\Member>|\Cake\Datasource\ResultSetInterface<\Associates\Model\Entity\Member> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Associates\Model\Entity\Member>|\Cake\Datasource\ResultSetInterface<\Associates\Model\Entity\Member>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Associates\Model\Entity\Member>|\Cake\Datasource\ResultSetInterface<\Associates\Model\Entity\Member> deleteManyOrFail(iterable $entities, array $options = [])
 */
class MembersTable extends Table
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

        $this->setTable('members');
        $this->setDisplayField('id_card');
        $this->setPrimaryKey('member_id');
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
            ->scalar('id_card')
            ->maxLength('id_card', 20)
            ->requirePresence('id_card', 'create')
            ->notEmptyString('id_card');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 100)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 100)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 20)
            ->allowEmptyString('phone');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('address')
            ->allowEmptyString('address');

        $validator
            ->integer('plan_id')
            ->requirePresence('plan_id', 'create')
            ->notEmptyString('plan_id');

        $validator
            ->scalar('member_status')
            ->notEmptyString('member_status');

        $validator
            ->dateTime('registered_at')
            ->requirePresence('registered_at', 'create')
            ->notEmptyDateTime('registered_at');

        return $validator;
    }
}
