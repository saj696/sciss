<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User Group Permission'), ['action' => 'edit', $userGroupPermission->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User Group Permission'), ['action' => 'delete', $userGroupPermission->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userGroupPermission->id)]) ?> </li>
        <li><?= $this->Html->link(__('List User Group Permissions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Group Permission'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List User Groups'), ['controller' => 'UserGroups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Group'), ['controller' => 'UserGroups', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="userGroupPermissions view large-9 medium-8 columns content">
    <h3><?= h($userGroupPermission->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('User Group') ?></th>
            <td><?= $userGroupPermission->has('user_group') ? $this->Html->link($userGroupPermission->user_group->title, ['controller' => 'UserGroups', 'action' => 'view', $userGroupPermission->user_group->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Controller') ?></th>
            <td><?= h($userGroupPermission->controller) ?></td>
        </tr>
        <tr>
            <th><?= __('Action') ?></th>
            <td><?= h($userGroupPermission->action) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($userGroupPermission->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($userGroupPermission->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Created By') ?></th>
            <td><?= $this->Number->format($userGroupPermission->created_by) ?></td>
        </tr>
        <tr>
            <th><?= __('Created Time') ?></th>
            <td><?= $this->Number->format($userGroupPermission->created_time) ?></td>
        </tr>
        <tr>
            <th><?= __('Updated By') ?></th>
            <td><?= $this->Number->format($userGroupPermission->updated_by) ?></td>
        </tr>
        <tr>
            <th><?= __('Updated Time') ?></th>
            <td><?= $this->Number->format($userGroupPermission->updated_time) ?></td>
        </tr>
    </table>
</div>
