<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AdministrativeUnit Entity.
 *
 * @property int $id
 * @property int $administrative_level_id
 * @property \App\Model\Entity\AdministrativeLevel $administrative_level
 * @property string $unit_name
 * @property int $parent
 * @property int $local_id
 * @property \App\Model\Entity\Local $local
 * @property int $global_id
 * @property \App\Model\Entity\Global $global
 * @property int $status
 * @property int $created_by
 * @property int $created_date
 * @property int $updated_by
 * @property int $updated_date
 * @property \App\Model\Entity\Customer[] $customers
 */
class AdministrativeUnit extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
