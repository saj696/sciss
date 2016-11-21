<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TransferEvent Entity
 *
 * @property int $id
 * @property int $transfer_resource_id
 * @property int $recipient_id
 * @property int $recipient_action
 * @property int $status
 * @property int $created_by
 * @property int $created_date
 * @property int $updated_by
 * @property int $updated_date
 *
 * @property \App\Model\Entity\TransferResource $transfer_resource
 * @property \App\Model\Entity\Recipient $recipient
 */
class TransferEvent extends Entity
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
