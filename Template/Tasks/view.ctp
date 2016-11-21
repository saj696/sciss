<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Task'), ['action' => 'edit', $task->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Task'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Tasks'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Task'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Tasks'), ['controller' => 'Tasks', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Task'), ['controller' => 'Tasks', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List User Group Role'), ['controller' => 'UserGroupRole', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Group Role'), ['controller' => 'UserGroupRole', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="tasks view large-9 medium-8 columns content">
    <h3><?= h($task->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Parent Task') ?></th>
            <td><?= $task->has('parent_task') ? $this->Html->link($task->parent_task->id, ['controller' => 'Tasks', 'action' => 'view', $task->parent_task->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name En') ?></th>
            <td><?= h($task->name_en) ?></td>
        </tr>
        <tr>
            <th><?= __('Name Bn') ?></th>
            <td><?= h($task->name_bn) ?></td>
        </tr>
        <tr>
            <th><?= __('Icon') ?></th>
            <td><?= h($task->icon) ?></td>
        </tr>
        <tr>
            <th><?= __('Controller') ?></th>
            <td><?= h($task->controller) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($task->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($task->ordering) ?></td>
        </tr>
        <tr>
            <th><?= __('Position Left 01') ?></th>
            <td><?= $this->Number->format($task->position_left_01) ?></td>
        </tr>
        <tr>
            <th><?= __('Position Top 01') ?></th>
            <td><?= $this->Number->format($task->position_top_01) ?></td>
        </tr>
        <tr>
            <th><?= __('Create By') ?></th>
            <td><?= $this->Number->format($task->create_by) ?></td>
        </tr>
        <tr>
            <th><?= __('Create Date') ?></th>
            <td><?= $this->Number->format($task->create_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Update By') ?></th>
            <td><?= $this->Number->format($task->update_by) ?></td>
        </tr>
        <tr>
            <th><?= __('Update Date') ?></th>
            <td><?= $this->Number->format($task->update_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($task->status) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($task->description)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Tasks') ?></h4>
        <?php if (!empty($task->child_tasks)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Parent Id') ?></th>
                <th><?= __('Name En') ?></th>
                <th><?= __('Name Bn') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Icon') ?></th>
                <th><?= __('Controller') ?></th>
                <th><?= __('Ordering') ?></th>
                <th><?= __('Position Left 01') ?></th>
                <th><?= __('Position Top 01') ?></th>
                <th><?= __('Create By') ?></th>
                <th><?= __('Create Date') ?></th>
                <th><?= __('Update By') ?></th>
                <th><?= __('Update Date') ?></th>
                <th><?= __('Status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($task->child_tasks as $childTasks): ?>
            <tr>
                <td><?= h($childTasks->id) ?></td>
                <td><?= h($childTasks->parent_id) ?></td>
                <td><?= h($childTasks->name_en) ?></td>
                <td><?= h($childTasks->name_bn) ?></td>
                <td><?= h($childTasks->description) ?></td>
                <td><?= h($childTasks->icon) ?></td>
                <td><?= h($childTasks->controller) ?></td>
                <td><?= h($childTasks->ordering) ?></td>
                <td><?= h($childTasks->position_left_01) ?></td>
                <td><?= h($childTasks->position_top_01) ?></td>
                <td><?= h($childTasks->create_by) ?></td>
                <td><?= h($childTasks->create_date) ?></td>
                <td><?= h($childTasks->update_by) ?></td>
                <td><?= h($childTasks->update_date) ?></td>
                <td><?= h($childTasks->status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Tasks', 'action' => 'view', $childTasks->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Tasks', 'action' => 'edit', $childTasks->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tasks', 'action' => 'delete', $childTasks->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childTasks->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related User Group Role') ?></h4>
        <?php if (!empty($task->user_group_role)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('User Group Id') ?></th>
                <th><?= __('Task Id') ?></th>
                <th><?= __('List') ?></th>
                <th><?= __('View') ?></th>
                <th><?= __('Add') ?></th>
                <th><?= __('Edit') ?></th>
                <th><?= __('Delete') ?></th>
                <th><?= __('Report') ?></th>
                <th><?= __('Print') ?></th>
                <th><?= __('Create By') ?></th>
                <th><?= __('Create Date') ?></th>
                <th><?= __('Update By') ?></th>
                <th><?= __('Update Date') ?></th>
                <th><?= __('Status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($task->user_group_role as $userGroupRole): ?>
            <tr>
                <td><?= h($userGroupRole->id) ?></td>
                <td><?= h($userGroupRole->user_group_id) ?></td>
                <td><?= h($userGroupRole->task_id) ?></td>
                <td><?= h($userGroupRole->list) ?></td>
                <td><?= h($userGroupRole->view) ?></td>
                <td><?= h($userGroupRole->add) ?></td>
                <td><?= h($userGroupRole->edit) ?></td>
                <td><?= h($userGroupRole->delete) ?></td>
                <td><?= h($userGroupRole->report) ?></td>
                <td><?= h($userGroupRole->print) ?></td>
                <td><?= h($userGroupRole->create_by) ?></td>
                <td><?= h($userGroupRole->create_date) ?></td>
                <td><?= h($userGroupRole->update_by) ?></td>
                <td><?= h($userGroupRole->update_date) ?></td>
                <td><?= h($userGroupRole->status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'UserGroupRole', 'action' => 'view', $userGroupRole->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'UserGroupRole', 'action' => 'edit', $userGroupRole->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UserGroupRole', 'action' => 'delete', $userGroupRole->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userGroupRole->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
