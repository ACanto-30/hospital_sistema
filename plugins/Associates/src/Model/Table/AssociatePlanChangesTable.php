<?php
declare(strict_types=1);

namespace Associates\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AssociatePlanChangesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('associate_plan_changes');
        $this->setPrimaryKey('id');

        $this->belongsTo('Associates', [
            'foreignKey' => 'associate_id',
            'joinType' => 'INNER',
            'className' => 'Associates.Associates',
        ]);

        $this->belongsTo('OldPlans', [
            'foreignKey' => 'old_plan_id',
            'joinType' => 'INNER',
            'className' => 'Associates.InsurancePlans',
        ]);

        $this->belongsTo('NewPlans', [
            'foreignKey' => 'new_plan_id',
            'joinType' => 'INNER',
            'className' => 'Associates.InsurancePlans',
        ]);

        $this->belongsTo('ChangeByUsers', [
            'foreignKey' => 'change_by_user_id',
            'joinType' => 'INNER',
            'className' => 'Users.Users',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('associate_id')
            ->requirePresence('associate_id', 'create')
            ->notEmptyString('associate_id');

        $validator
            ->integer('old_plan_id')
            ->requirePresence('old_plan_id', 'create')
            ->notEmptyString('old_plan_id');

        $validator
            ->integer('new_plan_id')
            ->requirePresence('new_plan_id', 'create')
            ->notEmptyString('new_plan_id');

        $validator
            ->scalar('reason')
            ->allowEmptyString('reason');

        $validator
            ->integer('change_by_user_id')
            ->requirePresence('change_by_user_id', 'create')
            ->notEmptyString('change_by_user_id');

        $validator
            ->date('change_date')
            ->requirePresence('change_date', 'create')
            ->notEmptyDate('change_date');

        return $validator;
    }
}
