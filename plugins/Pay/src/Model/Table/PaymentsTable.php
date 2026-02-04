<?php
declare(strict_types=1);

namespace Pay\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Payments Model
 *
 * @method \Pay\Model\Entity\Payment newEmptyEntity()
 * @method \Pay\Model\Entity\Payment newEntity(array $data, array $options = [])
 * @method array<\Pay\Model\Entity\Payment> newEntities(array $data, array $options = [])
 * @method \Pay\Model\Entity\Payment get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Pay\Model\Entity\Payment findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \Pay\Model\Entity\Payment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Pay\Model\Entity\Payment> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Pay\Model\Entity\Payment|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Pay\Model\Entity\Payment saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Pay\Model\Entity\Payment>|\Cake\Datasource\ResultSetInterface<\Pay\Model\Entity\Payment>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Pay\Model\Entity\Payment>|\Cake\Datasource\ResultSetInterface<\Pay\Model\Entity\Payment> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Pay\Model\Entity\Payment>|\Cake\Datasource\ResultSetInterface<\Pay\Model\Entity\Payment>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Pay\Model\Entity\Payment>|\Cake\Datasource\ResultSetInterface<\Pay\Model\Entity\Payment> deleteManyOrFail(iterable $entities, array $options = [])
 */
class PaymentsTable extends Table
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

        $this->setTable('payments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->integer('associate_id')
            ->requirePresence('associate_id', 'create')
            ->notEmptyString('associate_id');

        $validator
            ->integer('processed_by_user_id')
            ->requirePresence('processed_by_user_id', 'create')
            ->notEmptyString('processed_by_user_id');

        $validator
            ->integer('payment_method_id')
            ->requirePresence('payment_method_id', 'create')
            ->notEmptyString('payment_method_id');

        $validator
            ->integer('payment_status_id')
            ->requirePresence('payment_status_id', 'create')
            ->notEmptyString('payment_status_id');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->scalar('proof_image')
            ->maxLength('proof_image', 255)
            ->allowEmptyFile('proof_image');

        $validator
            ->dateTime('payment_date')
            ->requirePresence('payment_date', 'create')
            ->notEmptyDateTime('payment_date');

        return $validator;
    }
}
