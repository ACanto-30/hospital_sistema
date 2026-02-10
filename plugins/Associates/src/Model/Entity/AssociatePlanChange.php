<?php
declare(strict_types=1);

namespace Associates\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssociatePlanChange Entity
 *
 * @property int $id
 * @property int $associate_id
 * @property int $old_plan_id
 * @property int $new_plan_id
 * @property string|null $reason
 * @property int $change_by_user_id
 * @property \Cake\I18n\Date $change_date
 *
 * @property \Associates\Model\Entity\Associate $associate
 * @property \Associates\Model\Entity\InsurancePlan $old_insurance_plan
 * @property \Associates\Model\Entity\InsurancePlan $new_insurance_plan
 * @property \Users\Model\Entity\User $change_by_user
 */
class AssociatePlanChange extends Entity
{
    protected array $_accessible = [
        'associate_id' => true,
        'old_plan_id' => true,
        'new_plan_id' => true,
        'reason' => true,
        'change_by_user_id' => true,
        'change_date' => true,
        'associate' => true,
        'old_insurance_plan' => true,
        'new_insurance_plan' => true,
        'change_by_user' => true,
    ];
}
