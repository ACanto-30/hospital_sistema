<?php
declare(strict_types=1);

namespace Payments\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payment Entity
 *
 * @property int $id
 * @property int $associate_id
 * @property int|null $processed_by_user_id
 * @property int $payment_method_id
 * @property int $payment_status_id
 * @property float $amount
 * @property bool|null $is_paid
 * @property string|null $proof_image
 * @property \Cake\I18n\FrozenTime $payment_date
 *
 * @property \Payments\Model\Entity\Associate $associate
 * @property \Payments\Model\Entity\PaymentMethod $payment_method
 * @property \Payments\Model\Entity\PaymentStatus $payment_status
 */
class Payment extends Entity
{
    protected array $_accessible = [
        'associate_id' => true,
        'processed_by_user_id' => true,
        'payment_method_id' => true,
        'payment_status_id' => true,
        'amount' => true,
        'is_paid' => true,
        'proof_image' => true,
        'payment_date' => true,
        'associate' => true,
        'user' => true,
        'payment_method' => true,
        'payment_status' => true,
    ];
}
