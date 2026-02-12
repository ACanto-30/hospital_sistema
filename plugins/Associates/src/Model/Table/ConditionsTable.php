<?php
declare(strict_types=1);

namespace Associates\Model\Table;

use Cake\ORM\Table;

class ConditionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('conditions');
        $this->setPrimaryKey('id');
        $this->setDisplayField('condition');
    }
}
