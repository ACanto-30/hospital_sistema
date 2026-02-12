<?php
declare(strict_types=1);

namespace Payments\Model\Entity;

use Cake\ORM\Entity;

/**
 * PaymentDetail Entity
 *
 * @property int $id
 * @property int $payment_id
 * @property string $amount
 * @property string $proof_image
 * @property int $payment_method_id
 * @property int $payment_status_id
 * @property \Cake\I18n\FrozenTime $payment_date
 * @property int|null $processed_by_user_id
 *
 * @property \Payments\Model\Entity\Payment $payment
 * @property \Payments\Model\Entity\PaymentMethod $payment_method
 * @property \Payments\Model\Entity\PaymentStatus $payment_status
 * @property \App\Model\Entity\User $user
 */
class PaymentDetail extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'payment_id' => true,
        'amount' => true,
        'proof_image' => true,
        'payment_method_id' => true,
        'payment_status_id' => true,
        'payment_date' => true,
        'processed_by_user_id' => true,
        'payment' => true,
        'payment_method' => true,
        'payment_status' => true,
        'user' => true,
    ];
}
