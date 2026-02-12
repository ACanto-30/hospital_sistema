<?php
declare(strict_types=1);

namespace Payments\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PaymentDetails Model
 *
 * @property \Payments\Model\Table\PaymentsTable&\Cake\ORM\Association\BelongsTo $Payments
 * @property \Payments\Model\Table\PaymentMethodsTable&\Cake\ORM\Association\BelongsTo $PaymentMethods
 * @property \Payments\Model\Table\PaymentStatusesTable&\Cake\ORM\Association\BelongsTo $PaymentStatuses
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \Payments\Model\Entity\PaymentDetail newEmptyEntity()
 * @method \Payments\Model\Entity\PaymentDetail newEntity(array $data, array $options = [])
 * @method \Payments\Model\Entity\PaymentDetail[] newEntities(array $data, array $options = [])
 * @method \Payments\Model\Entity\PaymentDetail get($primaryKey, $options = [])
 * @method \Payments\Model\Entity\PaymentDetail findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Payments\Model\Entity\PaymentDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Payments\Model\Entity\PaymentDetail[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Payments\Model\Entity\PaymentDetail|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Payments\Model\Entity\PaymentDetail saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Payments\Model\Entity\PaymentDetail[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Payments\Model\Entity\PaymentDetail[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Payments\Model\Entity\PaymentDetail|false deleteOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class PaymentDetailsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('payment_detail');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Payments', [
            'foreignKey' => 'payment_id',
            'joinType' => 'INNER',
            'className' => 'Payments.Payments',
        ]);
        $this->belongsTo('PaymentMethods', [
            'foreignKey' => 'payment_method_id',
            'joinType' => 'INNER',
            'className' => 'Payments.PaymentMethods',
        ]);
        $this->belongsTo('PaymentStatuses', [
            'foreignKey' => 'payment_status_id',
            'joinType' => 'INNER',
            'className' => 'Payments.PaymentStatuses',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'processed_by_user_id',
            'className' => 'Users.Users',
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
            ->integer('payment_id')
            ->notEmptyString('payment_id');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->scalar('proof_image')
            ->maxLength('proof_image', 255)
            ->requirePresence('proof_image', 'create')
            ->notEmptyFile('proof_image');

        $validator
            ->integer('payment_method_id')
            ->requirePresence('payment_method_id', 'create')
            ->notEmptyString('payment_method_id');

        $validator
            ->integer('payment_status_id')
            ->notEmptyString('payment_status_id');

        $validator
            ->dateTime('payment_date')
            ->requirePresence('payment_date', 'create')
            ->notEmptyDateTime('payment_date');

        $validator
            ->integer('processed_by_user_id')
            ->allowEmptyString('processed_by_user_id');

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
        $rules->add($rules->existsIn('payment_id', 'Payments'), ['errorField' => 'payment_id']);
        $rules->add($rules->existsIn('payment_method_id', 'PaymentMethods'), ['errorField' => 'payment_method_id']);
        $rules->add($rules->existsIn('payment_status_id', 'PaymentStatuses'), ['errorField' => 'payment_status_id']);
        $rules->add($rules->existsIn('processed_by_user_id', 'Users'), ['errorField' => 'processed_by_user_id']);

        return $rules;
    }
}
