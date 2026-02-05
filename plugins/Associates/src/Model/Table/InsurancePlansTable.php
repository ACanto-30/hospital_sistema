<?php
declare(strict_types=1);

namespace Associates\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class InsurancePlansTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('insurance_plans');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');

        $this->hasMany('Associates', [
            'foreignKey' => 'plan_id',
            'className' => 'Associates.Associates',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->decimal('biweekly_fee')
            ->requirePresence('biweekly_fee', 'create')
            ->notEmptyString('biweekly_fee');

        return $validator;
    }
}
