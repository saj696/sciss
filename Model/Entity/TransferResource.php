<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TransferResource Entity
 *
 * @property int $id
 * @property int $triggering_store
 * @property int $reference_resource_id
 * @property int $original_resource_id
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_date
 * @property int $updated_by
 * @property int $updated_date
 *
 * @property \App\Model\Entity\ReferenceResource $reference_resource
 * @property \App\Model\Entity\OriginalResource $original_resource
 * @property \App\Model\Entity\TransferEvent[] $transfer_events
 * @property \App\Model\Entity\TransferItem[] $transfer_items
 */
class TransferResource extends Entity
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
        'id' => false
    ];
}
