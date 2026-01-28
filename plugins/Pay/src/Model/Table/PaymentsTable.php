<?php
namespace Pay\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaymentsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('payments');
        $this->setPrimaryKey('id');

        // Relación con Users (app principal)
        $this->belongsTo('Users', [
            'className' => 'Users.Users',
            'foreignKey' => 'user_id'
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->numeric('amount')
            ->requirePresence('amount')
            ->notEmptyString('amount');
    }
}
