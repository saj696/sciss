<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserGroupPermission Entity.
 *
 * @property int $id
 * @property int $user_group_id
 * @property \App\Model\Entity\UserGroup $user_group
 * @property string $controller
 * @property string $action
 * @property int $status
 * @property int $created_by
 * @property int $created_time
 * @property int $updated_by
 * @property int $updated_time
 */
class UserGroupPermission extends Entity
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
    protected function _getCreatedTime($created_time){
        return $created_time ? date('d-m-Y',$created_time) : '';
    }
    protected function _getUpdatedTime($updated_time){
        return $updated_time ? date('d-m-Y',$updated_time) : '';
    }
}
