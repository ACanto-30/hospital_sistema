<?php
declare(strict_types=1);

namespace Associates\Model\Entity;

use Cake\ORM\Entity;

/**
 * InsurancePlan Entity
 *
 * @property int $id
 * @property string $name
 * @property string $biweekly_fee
 *
 * @property \Associates\Model\Entity\Associate[] $associates
 */
class InsurancePlan extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'biweekly_fee' => true,
        'associates' => true,
    ];
}
