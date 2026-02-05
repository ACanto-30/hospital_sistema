<?php
declare(strict_types=1);

namespace Payments\Model\Entity;

use Cake\ORM\Entity;

class PaymentStatus extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'payments' => true,
    ];
}
