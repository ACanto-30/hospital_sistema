<?php
declare(strict_types=1);

namespace Payments\Model\Entity;

use Cake\ORM\Entity;

class Payment extends Entity
{
    protected array $_accessible = [
        'associate_id' => true,
        'processed_by_user_id' => true,
        'payment_method_id' => true,
        'payment_status_id' => true,
        'amount' => true,
        'proof_image' => true,
        'payment_date' => true,
        'associate' => true,
        'user' => true,
        'payment_method' => true,
        'payment_status' => true,
    ];
}
