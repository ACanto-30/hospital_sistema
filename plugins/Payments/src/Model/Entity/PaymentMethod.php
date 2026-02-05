<?php
declare(strict_types=1);

namespace Payments\Model\Entity;

use Cake\ORM\Entity;

class PaymentMethod extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'payments' => true,
    ];
}
