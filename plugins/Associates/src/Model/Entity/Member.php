<?php
declare(strict_types=1);

namespace Associates\Model\Entity;

use Cake\ORM\Entity;

/**
 * Member Entity
 *
 * @property int $member_id
 * @property string $id_card
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone
 * @property string $email
 * @property string|null $address
 * @property int $plan_id
 * @property string $member_status
 * @property \Cake\I18n\DateTime $registered_at
 */
class Member extends Entity
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
        'id_card' => true,
        'first_name' => true,
        'last_name' => true,
        'phone' => true,
        'email' => true,
        'address' => true,
        'plan_id' => true,
        'member_status' => true,
        'registered_at' => true,
    ];
}
