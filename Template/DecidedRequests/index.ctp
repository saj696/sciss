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
        <li><?= $this->Html->link(__('Decided Requests'), ['action' => 'index']) ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" role="form" action="<?= $this->Url->build("/DecidedRequests/chalan")?>" method="post">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list-alt fa-lg"></i><?= __('Decided Request List') ?>
                </div>
                <div class="pull-right">
                    <button type="submit" style="margin-top: 6px;" class="btn btn-sm btn-warning">Make chalan</button>
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th><?= __('Sl. No.') ?></th>
                            <th><?= __('Decision Date') ?></th>
                            <th><?= __('Warehouse')?></th>
                            <th><?= __('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($events as $key => $event)
                        {
                            ?>
                            <tr>
                                <td style="width: 4%">
                                    <?php if(in_array($event['transfer_resource']['transfer_items'][0]['warehouse_id'], $myLevelWarehouses)):?>
                                        <input type="checkbox" name="chalan_event[]" data-warehouse-id="<?=$event['transfer_resource']['transfer_items'][0]['warehouse_id']?>" class="form-control warehouse_for_chalan" value="<?=$event->id?>" />
                                    <?php endif;?>
                                </td>
                                <td><?= $this->Number->format($key + 1) ?></td>
                                <td><?= date('d-m-Y', $event->created_date) ?></td>
                                <td><?= $warehouses[$event['transfer_resource']['transfer_items'][0]['warehouse_id']]?></td>
                                <td class="actions" width="25%">
                                    <?php
                                    if(in_array($event['transfer_resource']['transfer_items'][0]['warehouse_id'], $myLevelWarehouses)):
                                        if($event['is_action_taken']==0):
                                            echo $this->Html->link(__('Send Delivery'), ['action' => 'sendDelivery', $event->id], ['class' => 'btn btn-sm btn-primary']);
                                            echo $this->Html->link(__('Forward'), ['action' => 'forward', $event->id], ['class' => 'btn btn-sm btn-success']);
                                        else:
                                            echo $this->Html->link(__('Send Delivery'), ['action' => 'sendDelivery', $event->id], ['class' => 'btn btn-sm btn-primary', 'disabled']);
                                            echo $this->Html->link(__('Forward'), ['action' => 'forward', $event->id], ['class' => 'btn btn-sm btn-success', 'disabled']);
                                        endif;
                                    else:
                                        if($event['is_action_taken']==0):
                                            ?>
                                            <div class="row popContainer" style="display: none;">
                                                <form method="post" class="form-horizontal" role="form" action="<?= $this->Url->build("/DecidedRequests/forward")?>">
                                                    <input type="hidden" name="event_id" value="<?= $event->id?>" />
                                                    <table class="table table-bordered" style="margin-bottom: 0px;">
                                                        <tr>
                                                            <td class="text-center"><label class="label label-warning">Forward To</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo $this->Form->input('forward_user', ['options' => $forwardingUsers, 'style'=>'width:100%', 'empty' => __('Select'), 'templates'=>['select' => '<div id="container_{{name}}" class="col-sm-12"><select name="{{name}}"{{attrs}} class="form-control">{{content}}</select></div>', 'label'=>'']]);?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class="text-center" style="border: 0px;">
                                                                <label style="width: 80px;" class="btn btn-sm btn-danger crossSpan"><?= __('Cancel')?></label>
                                                                <button style="width: 80px;" type="submit" class="btn btn-sm btn-success"><?= __('Ok')?></button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                            <?php
                                            ?>
                                        <button class="btn btn-sm btn-success forward">Forward</button>
                                        <?php
                                        else:
                                            echo $this->Html->link(__('Forward'), ['action' => 'forward', $event->id], ['class' => 'btn btn-sm btn-success', 'disabled']);
                                        endif;
                                    endif;
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
    </form>
    </div>
</div>

<script>
    $(document).ready(function()
    {
        $(document).on("click", ".forward", function(event)
        {
            $(".popContainer").hide();
            $(this).closest('td').find('.popContainer').show();
        });

        $(document).on("click",".crossSpan",function()
        {
            $(".popContainer").hide();
        });

        $(document).on("click", ".warehouse_for_chalan", function()
        {
            var obj = $(this);
            var myArr = [];
            $( ".warehouse_for_chalan" ).each(function( index ) {
                if($(this).prop('checked')){
                    myArr.push($(this).attr('data-warehouse-id'));
                }
            });

            //console.log(myArr);
            var uniqueArr = uniqueArray(myArr);
            if(uniqueArr.length>1) {
                $(this).prop('checked', false);
                $(this).closest('span').removeClass('checked');
                alert('Multiple warehouse not allowed! Make chalan for a single warehouse.');
            }
        });
    });

    function uniqueArray(arr) {
        var i,
            len = arr.length,
            out = [],
            obj = { };

        for (i = 0; i < len; i++) {
            obj[arr[i]] = 0;
        }
        for (i in obj) {
            out.push(i);
        }
        return out;
    }
</script>