<?php
declare(strict_types=1);

namespace Associates\Model\Entity;

use Cake\ORM\Entity;

/**
 * Condition Entity
 *
 * @property int $id
 * @property string $condition
 */
class Condition extends Entity
{
    protected array $_accessible = [
        'condition' => true,
    ];
}
