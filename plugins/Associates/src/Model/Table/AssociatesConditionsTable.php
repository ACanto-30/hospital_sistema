<?php
declare(strict_types=1);

namespace Associates\Model\Table;

use Cake\ORM\Table;

class AssociatesConditionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('associates_conditions');
        $this->setPrimaryKey(['associate_id', 'condition_id']);

        $this->belongsTo('Associates', [
            'foreignKey' => 'associate_id',
            'className' => 'Associates.Associates',
        ]);
        $this->belongsTo('Conditions', [
            'foreignKey' => 'condition_id',
            'className' => 'Associates.Conditions',
        ]);
    }
}
