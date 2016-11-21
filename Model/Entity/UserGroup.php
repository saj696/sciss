<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserGroup Entity.
 *
 * @property int $id
 * @property string $title
 * @property int $created_by
 * @property int $created_time
 * @property int $updated_by
 * @property int $updated_time
 * @property int $ordering
 * @property int $status
 * @property \App\Model\Entity\UserGroupPermission[] $user_group_permissions
 * @property \App\Model\Entity\UserGroupRole[] $user_group_role
 * @property \App\Model\Entity\User[] $users
 */
class UserGroup extends Entity
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
    protected function _getFormattedCreatedTime(){
        return !empty($this->created_time) ? date('d-m-Y',$this->created_time) : '';
    }
    protected function _getFormattedUpdatedTime(){
        return !empty($this->updated_time) ? date('d-m-Y',$this->updated_time) : '';
    }
}
