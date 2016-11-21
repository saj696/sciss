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
        <li><?= $this->Html->link(__('Request Items'), ['action' => 'index']) ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list-alt fa-lg"></i><?= __('Request Item List') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('New Request'), ['action' => 'add'], ['class' => 'btn btn-sm btn-primary']); ?>
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th><?= __('Sl. No.') ?></th>
                            <th><?= __('Request Date') ?></th>
                            <th><?= __('Items') ?></th>
                            <th><?= __('Forwarded?') ?></th>
                            <th><?= __('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($resources as $key => $resource) { ?>
                            <tr>
                                <td><?= $this->Number->format($key + 1) ?></td>
                                <td><?= date('d-m-Y', $resource->created_date) ?></td>
                                <td>
                                    <?php
                                    $size = sizeof($resource['transfer_items']);
                                    if(sizeof($resource['transfer_items'])>0):
                                        foreach($resource['transfer_items'] as $key=>$item):
                                            if($size==$key+1):
                                                echo $itemArray[$item['item_id']].' ('.$item['quantity'].')';
                                            else:
                                                echo $itemArray[$item['item_id']].' ('.$item['quantity'].')'.' | ';
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </td>
                                <td><?= sizeof($resource['transfer_events'])>0?'Yes':'No' ?></td>
                                <td class="actions">
                                    <?php
                                    echo $this->Html->link(__('View'), ['action' => 'view', $resource->id], ['class' => 'btn btn-sm btn-info']);
                                    if(sizeof($resource['transfer_events'])>0):
                                        ?>
                                        <button class="btn btn-sm btn-danger">Forwarded</button>
                                        <?php
                                    else:
                                        ?>
                                        <button class="btn btn-sm btn-warning forward">Forward</button>
                                        <div class="row popContainer" style="display: none;">
                                            <form action="RequestItems/edit">
                                                <input type="hidden" name="id" value="<?= $resource->id?>" />
                                                <table class="table table-bordered" style="margin-bottom: 0px;">
                                                    <tr><td class="text-center"><label class="label label-warning">HO User</label></td></tr>
                                                    <tr>
                                                        <td class="text-center">
                                                            <div class="form-group input select">
                                                                <div id="container_recipient_id" class="col-sm-12">
                                                                    <select name="recipient_id" class="form-control recipient_id">
                                                                        <option value="">Select a user</option>
                                                                        <?php
                                                                        foreach($users as $val=>$user):
                                                                            ?>
                                                                            <option value="<?= $val?>"><?= $user?></option>
                                                                        <?php endforeach;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-center" style="border: 0px;">
                                                            <button type="submit" class="btn btn-sm btn-success crossSpan"><?= __('Ok')?></button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </div>
                                        <?php
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
    });
</script>