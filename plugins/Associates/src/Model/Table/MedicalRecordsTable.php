<?php
declare(strict_types=1);

namespace Associates\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MedicalRecords Model
 *
 * @method \Associates\Model\Entity\MedicalRecord newEmptyEntity()
 * @method \Associates\Model\Entity\MedicalRecord newEntity(array $data, array $options = [])
 * @method array<\Associates\Model\Entity\MedicalRecord> newEntities(array $data, array $options = [])
 * @method \Associates\Model\Entity\MedicalRecord get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Associates\Model\Entity\MedicalRecord findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \Associates\Model\Entity\MedicalRecord patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Associates\Model\Entity\MedicalRecord> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Associates\Model\Entity\MedicalRecord|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Associates\Model\Entity\MedicalRecord saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Associates\Model\Entity\MedicalRecord>|\Cake\Datasource\ResultSetInterface<\Associates\Model\Entity\MedicalRecord>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Associates\Model\Entity\MedicalRecord>|\Cake\Datasource\ResultSetInterface<\Associates\Model\Entity\MedicalRecord> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Associates\Model\Entity\MedicalRecord>|\Cake\Datasource\ResultSetInterface<\Associates\Model\Entity\MedicalRecord>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Associates\Model\Entity\MedicalRecord>|\Cake\Datasource\ResultSetInterface<\Associates\Model\Entity\MedicalRecord> deleteManyOrFail(iterable $entities, array $options = [])
 */
class MedicalRecordsTable extends Table
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

        $this->setTable('medical_records');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Associates', [
            'foreignKey' => 'associate_id',
            'className' => 'Associates.Associates',
        ]);
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
            ->integer('doctor_id')
            ->requirePresence('doctor_id', 'create')
            ->notEmptyString('doctor_id');

        $validator
            ->scalar('diagnosis')
            ->requirePresence('diagnosis', 'create')
            ->notEmptyString('diagnosis');

        $validator
            ->scalar('treatment')
            ->allowEmptyString('treatment');

        $validator
            ->date('visit_date')
            ->requirePresence('visit_date', 'create')
            ->notEmptyDate('visit_date');

        return $validator;
    }
}
