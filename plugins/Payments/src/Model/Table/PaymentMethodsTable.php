<?php
declare(strict_types=1);

namespace Payments\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaymentMethodsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('payment_methods');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');

        $this->hasMany('Payments', [
            'foreignKey' => 'payment_method_id',
            'className' => 'Payments.Payments',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
