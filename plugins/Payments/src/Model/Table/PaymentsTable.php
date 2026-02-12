<?php
declare(strict_types=1);

namespace Payments\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaymentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('payments');
        $this->setPrimaryKey('id');

        $this->belongsTo('Associates', [
            'foreignKey' => 'associate_id',
            'joinType' => 'INNER',
            'className' => 'Associates.Associates',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'processed_by_user_id',
            'className' => 'Users.Users',
        ]);



        $this->belongsTo('PaymentStatuses', [
            'foreignKey' => 'payment_status_id',
            'joinType' => 'INNER',
            'className' => 'Payments.PaymentStatuses',
        ]);

        $this->hasMany('PaymentDetails', [
            'foreignKey' => 'payment_id',
            'className' => 'Payments.PaymentDetails',
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'payment_date' => 'new',
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
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');



        return $validator;
    }
}
