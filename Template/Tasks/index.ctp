<?php
$status = \Cake\Core\Configure::read('status_options');
?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-coffee"></i>Task List <?= $this->Html->link(__('New Task'), ['action' => 'add'],['class'=>'btn btn-sm btn-primary']) ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Controller
                                </th>
                                <th>
                                    Method
                                </th>
                                <th>
                                    Parent
                                </th>
                                <th>
                                    Child
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task){
                            ?>
                                <tr>
                                    <td><?= h($task->id) ?></td>
                                    <td><?= h($task->name_en) ?></td>
                                    <td><?= h($task->controller) ?></td>
                                    <td><?= h($task->method) ?></td>
                                    <td><?= $task->has('parent_task') ? $this->Html->link($task->parent_task->name_en, ['controller' => 'Tasks', 'action' => 'view', $task->parent_task->id]) : '' ?></td>
                                    <td><?= $task->has('child_tasks') ? count($task->child_tasks) : '' ?></td>
                                    <td><?= $task->status?h($status[$task->status]):'' ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link(__('View'), ['action' => 'view', $task->id],['class'=>'btn btn-sm btn-info']) ?>
                                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $task->id],['class'=>'btn btn-sm btn-warning']) ?>
                                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $task->id],['class'=>'btn btn-sm btn-danger','confirm' => __('Are you sure you want to delete # {0}?', $task->id)]) ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <ul class="pagination">
                    <?php
                    echo $this->Paginator->prev(' << ');
                    echo $this->Paginator->numbers();
                    echo $this->Paginator->next(' >>');
                    ?>
                </ul>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>