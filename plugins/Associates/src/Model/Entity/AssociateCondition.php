<?php
declare(strict_types=1);

namespace Associates\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssociateCondition Entity
 *
 * @property int $associate_id
 * @property int $condition_id
 *
 * @property \Associates\Model\Entity\Associate $associate
 * @property \Associates\Model\Entity\Condition $condition
 */
class AssociateCondition extends Entity
{
    protected array $_accessible = [
        'associate_id' => true,
        'condition_id' => true,
        'associate' => true,
        'condition' => true,
    ];
}
