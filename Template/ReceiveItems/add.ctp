<?php
use Cake\Core\Configure;
?>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->Url->build(('/Dashboard'), true); ?>"><?= __('Dashboard') ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= $this->Html->link(__('Request Items'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('New Request') ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus-square-o fa-lg"></i><?= __('New Request') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>
            </div>

            <div class="portlet-body">
                <?= $this->Form->create($requestItem, ['class' => 'form-horizontal', 'role' => 'form']) ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="list" data-index_no="0">
                            <div class="itemWrapper">
                                <table class="table table-bordered moreTable">
                                    <tr>
                                        <th><?= __('Item')?></th>
                                        <th><?= __('Quantity')?></th>
                                        <th></th>
                                    </tr>
                                    <tr class="item_tr single_list">
                                        <td style="width: 50%;"><?php echo $this->Form->input('details.0.item_id', ['options' => $dropArray, 'required'=>'required', 'style'=>'max-width: 100%', 'class'=>'form-control item', 'empty' => __('Select'), 'templates'=>['label' => '']]);?></td>
                                        <td><?php echo $this->Form->input('details.0.quantity', ['type' => 'text', 'style'=>'width: 100%', 'required'=>'required', 'class'=>'form-control quantity', 'templates'=>['label' => '']]);?></td>
                                        <td width="50px;"><span class="btn btn-sm btn-circle btn-danger remove pull-right">X</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row col-md-offset-11">
                        <input type="button" class="btn btn-circle btn-warning add_more" value="Add" />
                    </div>

                    <div class="row text-center">
                        <input type="checkbox" class="form-control forward_check" value="1" /> Want to forward?
                    </div>
                    <div class="row col-md-offset-5 text-center recipient_div hidden">
                        <?php echo $this->Form->input('recipient_user_group', ['options' => $userGroups, 'style'=>'width:40%; margin-top:20px;', 'class'=>'form-control recipient_user_group', 'empty' => __('Select group'), 'templates'=>['label' => '']]);?>
                        <?php echo $this->Form->input('recipient_id', ['empty' => 'Select user', 'style'=>'width:40%;', 'class'=>'form-control recipient_id', 'templates'=>['label' => '']]);?>
                    </div>

                    <div class="row text-center" style="margin-bottom: 20px;">
                        <?= $this->Form->button(__('Forward'), ['class' => 'btn green', 'name'=>'forward', 'style' => 'margin-top:20px']) ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn blue', 'name'=>'submit', 'style' => 'margin-top:20px']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('click', '.add_more', function () {
            var index = $('.list').data('index_no');
            $('.list').data('index_no', index + 1);
            var html = $('.itemWrapper .item_tr:last').clone().find('.form-control').each(function () {
                this.name = this.name.replace(/\d+/, index+1);
                this.id = this.id.replace(/\d+/, index+1);
                this.value = '';
            }).end();

            $('.moreTable').append(html);
        });

        $(document).on('click', '.remove', function () {
            var obj=$(this);
            var count= $('.single_list').length;
            if(count > 1) {
                obj.closest('.single_list').remove();
            }
        });

        $(document).on('change', '.item', function() {
            var myArr = [];
            $( ".item" ).each(function( index ) {
                myArr.push($(this).val());
            });

            var uniqueArr = uniqueArray(myArr);

            if(myArr.length != uniqueArr.length) {
                alert('Duplicate item not acceptable!');
                $(this).val('');
            }
        });

        $(document).on('click', '.forward_check', function() {
            if($(this).attr('checked')) {
                $(".recipient_div").removeClass('hidden');
            } else {
                $(".recipient_div").addClass('hidden');
                $('.recipient_user_group').val('');
                $('.recipient_id').html('<option value="">Select user</option>');
            }
        });

        $(document).on('change', '.recipient_user_group', function() {
            var obj = $(this);
            var user_group = obj.val();
            $('.recipient_id').html('<option value="">Select</option>');
            if(user_group>0) {
                $.ajax({
                    type: 'POST',
                    url: '<?= $this->Url->build("/RequestItems/ajax")?>',
                    data: {user_group: user_group},
                    success: function (data, status) {
                        obj.closest('.input').next().find('.col-sm-9').html('');
                        obj.closest('.input').next().find('.col-sm-9').html(data);
                    }
                });
            }else{
                $('.recipient_id').html('<option value="">Select user</option>');
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
