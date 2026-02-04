<?php
declare(strict_types=1);

namespace Associates\Model\Entity;

use Cake\ORM\Entity;

/**
 * MedicalRecord Entity
 *
 * @property int $id
 * @property int $associate_id
 * @property int $doctor_id
 * @property string $diagnosis
 * @property string|null $treatment
 * @property \Cake\I18n\Date $visit_date
 */
class MedicalRecord extends Entity
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
        'doctor_id' => true,
        'diagnosis' => true,
        'treatment' => true,
        'visit_date' => true,
    ];
}
