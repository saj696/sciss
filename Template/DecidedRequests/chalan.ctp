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
            <?= $this->Html->link(__('Decided Requests'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('Chalan') ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o fa-lg"></i><?= __('Make Chalan') ?>
                </div>
                <div class="tools">
                    <button class="btn btn-sm btn-warning">Print</button>
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-scrollable">
                    <form method="post" class="form-horizontal" role="form" action="<?= $this->Url->build("/DecidedRequests/chalanForward")?>">
                        <?php foreach($eventIds as $eventId):?>
                            <input type="hidden" name="eventIds[]" value="<?= $eventId?>" />
                        <?php endforeach;?>
                        <input type="hidden" name="warehouse_id" value="<??>">

                        <table class="table table-bordered">
                            <tr>
                                <td class="pull-left">Chalan No: <?= $sl_no?></td>
                                <td class="pull-right">Date: <?= date('d-m-Y')?></td>
                            </tr>
                        </table>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                </tr>
                                <?php foreach($returnData as $detail):?>
                                    <input type="hidden" name="detail[<?=$detail['item_id']?>]" value="<?= $detail['quantity']?>" />
                                    <tr>
                                        <td><?= $itemArray[$detail['item_id']]?></td>
                                        <td><?= $detail['quantity']>0?$detail['quantity']:0?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    <div class="text-center" style="margin-bottom: 20px;">
                        <?= $this->Form->button(__('Forward'), ['class' => 'btn blue submitBtn', 'style'=>'font-size:13px; padding:6px 8px;']) ?>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

    });
</script>