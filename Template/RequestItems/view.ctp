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
        <li>
            <?= $this->Html->link(__('Decide Storage'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('View Transfer Item') ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o fa-lg"></i><?= __('Decide Storage') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>
            </div>

            <div class="portlet-body">
                <div class="row text-center">
                    <div class="col-md-6 col-md-offset-3">
                        <?php echo $this->Form->input('warehouse', ['options' => $stores, 'style'=>'max-width: 100%', 'class'=>'form-control warehouse', 'empty' => __('Select'), 'templates'=>['label' => 'Warehouse']]);?>
                    </div>
                </div>

                <div class="table-scrollable">
                    <table class="table table-bordered">
                        <tr>
                            <th>Item</th>
                            <th>Warehouse</th>
                            <th>Quantity</th>
                        </tr>
                        <?php foreach($details as $detail):?>
                            <tr>
                                <td><?= $itemArray[$detail['item']]?></td>
                                <td><?= $stores[$detail['store']]?></td>
                                <td><?= $detail['quantity']?></td>
                            </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

