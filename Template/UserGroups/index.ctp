<div class="row">
    <div class="col-md-12">

        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-coffee"></i>Users
                    List <?= $this->Html->link(__('New User Group'), ['action' => 'add'], ['class' => 'btn btn-sm btn-danger']) ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?= $this->Paginator->sort('title') ?></th>
                            <th><?= $this->Paginator->sort('ordering') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($userGroups as $i => $userGroup):
                            ?>
                            <tr>
                                <td><?= $this->Number->format(++$i) ?></td>
                                <td><?= h($userGroup->title_en) ?></td>
                                <td><?= $this->Number->format($userGroup->ordering) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('View'), ['action' => 'view', $userGroup->id], ['class' => 'btn btn-sm btn-info']) ?>
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $userGroup->id], ['class' => 'btn btn-sm btn-warning']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>