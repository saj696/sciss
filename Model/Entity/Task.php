<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity.
 *
 * @property int $id
 * @property int $parent_id
 * @property \App\Model\Entity\Task $parent_task
 * @property string $name_en
 * @property string $name_bn
 * @property string $description
 * @property string $icon
 * @property string $controller
 * @property int $ordering
 * @property int $position_left_01
 * @property int $position_top_01
 * @property int $create_by
 * @property int $create_date
 * @property int $update_by
 * @property int $update_date
 * @property int $status
 * @property \App\Model\Entity\Task[] $child_tasks
 * @property \App\Model\Entity\UserGroupRole[] $user_group_role
 */
class Task extends Entity
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
