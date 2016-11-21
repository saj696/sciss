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
        <li><?= $this->Html->link(__('Reduced Stocks'), ['action' => 'index']) ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list-alt fa-lg"></i><?= __('Reduced Stock List') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Reduced Stock'), ['action' => 'add'], ['class' => 'btn btn-sm btn-primary']); ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th><?= __('Sl. No.') ?></th>
                            <th><?= __('Warehouse') ?></th>
                            <th><?= __('Item') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Quantity') ?></th>
<!--                            <th>--><?//= __('Actions') ?><!--</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($reducedStocks as $key => $reducedStock) { ?>
                            <tr>
                                <td><?= $this->Number->format($key + 1) ?></td>
                                <td><?= $reducedStock->warehouse->name ?></td>
                                <td><?= $reducedStock->item->name ?></td>
                                <td><?= Cake\Core\Configure::read('reduction_types')[$reducedStock->type] ?></td>
                                <td><?= $this->Number->format($reducedStock->quantity) ?></td>
<!--                                <td class="actions">-->
<!--                                    --><?php
//                                    echo $this->Html->link(__('View'), ['action' => 'view', $reducedStock->id], ['class' => 'btn btn-sm btn-info']);
//                                    echo $this->Html->link(__('Edit'), ['action' => 'edit', $reducedStock->id], ['class' => 'btn btn-sm btn-warning']);
//                                    echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $reducedStock->id], ['class' => 'btn btn-sm btn-danger', 'confirm' => __('Are you sure you want to delete # {0}?', $reducedStock->id)]);
//                                    ?>
<!--                                </td>-->
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

