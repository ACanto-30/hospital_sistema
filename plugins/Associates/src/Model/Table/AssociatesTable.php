<?php
declare(strict_types=1);

namespace Associates\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AssociatesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('associates');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Users.Users',
        ]);

        $this->belongsTo('InsurancePlans', [
            'foreignKey' => 'plan_id',
            'joinType' => 'INNER',
            'className' => 'Associates.InsurancePlans',
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'registered_at' => 'new',
                ]
            ]
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->integer('plan_id')
            ->requirePresence('plan_id', 'create')
            ->notEmptyString('plan_id');

        return $validator;
    }
}
