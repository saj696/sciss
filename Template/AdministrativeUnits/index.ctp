<?php
$status = \Cake\Core\Configure::read('status_options');
?>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->Url->build(('/Dashboard'), true); ?>"><?= __('Dashboard') ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= $this->Html->link(__('Administrative Units'), ['action' => 'index']) ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list-alt fa-lg"></i><?= __('Administrative Unit List') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('New Administrative Unit'), ['action' => 'add'], ['class' => 'btn btn-sm btn-primary']); ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th><?= __('Sl. No.') ?></th>
                            <th><?= __('Administrative Level') ?></th>
                            <th><?= __('Unit Name') ?></th>
                            <th><?= __('Parent') ?></th>
                            <th><?= __('Local ID') ?></th>
                            <th><?= __('Global ID') ?></th>
                            <th><?= __('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($administrativeUnits as $key => $administrativeUnit) { ?>
                            <tr>
                                <td><?= $this->Number->format($key + 1) ?></td>
                                <td><?= $administrativeUnit->has('administrative_level') ?
                                        $this->Html->link($administrativeUnit->administrative_level
                                            ->level_name, ['controller' => 'AdministrativeLevels',
                                            'action' => 'view', $administrativeUnit->administrative_level
                                                ->id]) : '' ?></td>
                                <td><?= h($administrativeUnit->unit_name) ?></td>
                                <td><?= $this->System->get_unit_name($administrativeUnit->parent) ?></td>
                                <td><?= $administrativeUnit->local_id ?></td>
                                <td><?= $administrativeUnit->global_id ?></td>
                                <td class="actions">
                                    <?php
                                    echo $this->Html->link(__('View'), ['action' => 'view', $administrativeUnit->id], ['class' => 'btn btn-sm btn-info']);
                                    echo $this->Html->link(__('Edit'), ['action' => 'edit', $administrativeUnit->id], ['class' => 'btn btn-sm btn-warning']);
                                    echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $administrativeUnit->id], ['class' => 'btn btn-sm btn-danger', 'confirm' => __('Are you sure you want to delete # {0}?', $administrativeUnit->id)]);
                                    ?>
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
                    echo $this->Paginator->prev('<<');
                    echo $this->Paginator->numbers();
                    echo $this->Paginator->next('>>');
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

