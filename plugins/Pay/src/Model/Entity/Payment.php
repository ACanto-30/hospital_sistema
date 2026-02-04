<?php
declare(strict_types=1);

namespace Pay\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payment Entity
 *
 * @property int $id
 * @property int $associate_id
 * @property int $processed_by_user_id
 * @property int $payment_method_id
 * @property int $payment_status_id
 * @property string $amount
 * @property string|null $proof_image
 * @property \Cake\I18n\DateTime $payment_date
 *
 * @property \Users\Model\Entity\User $user
 */
class Payment extends Entity
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
        'associate_id' => true,
        'processed_by_user_id' => true,
        'payment_method_id' => true,
        'payment_status_id' => true,
        'amount' => true,
        'proof_image' => true,
        'payment_date' => true,
        'user' => true,
    ];
}
