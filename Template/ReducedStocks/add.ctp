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
            <?= $this->Html->link(__('Reduced Stocks'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('New Reduced Stock') ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus-square-o fa-lg"></i><?= __('Add New Reduced Stock') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>
            </div>

            <div class="portlet-body">
                <?= $this->Form->create($reducedStock, ['class' => 'form-horizontal', 'role' => 'form']) ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php echo $this->Form->input('warehouse_id', ['options' => $warehouses, 'class'=>'form-control store', 'empty' => __('Select')]);?>
                    </div>
                </div>

                <div class="row loadView">
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', '.store', function() {
            var store = $(this).val();
            if(store>0)
            {
                $.ajax({
                    type: 'POST',
                    url: '<?= $this->Url->build("/ReducedStocks/ajax")?>',
                    data: {store: store},
                    success: function (data, status) {
                        $('.loadView').html('');
                        $('.loadView').html(data);
                    }
                });
            }
            else
            {
                $('.loadView').html('');
            }
        });

        $(document).on('change', '.item', function() {
            var obj = $(this);
            var item_id = obj.val();
            var store_id = $('.store').val();
            obj.closest('.item_tr').find('.quantity').val('');
            obj.closest('.item_tr').find('.quantity').removeAttr('placeholder');

            var myArr = [];
            $( ".item" ).each(function( index ) {
                myArr.push($(this).val());
            });

            var uniqueArr = uniqueArray(myArr);

            if(myArr.length != uniqueArr.length) {
                alert('Duplicate item not acceptable!');
                $(this).val('');
            } else {
                $.ajax({
                    type: 'POST',
                    url: '<?= $this->Url->build("/ReducedStocks/existing")?>',
                    data: {item_id: item_id, store_id:store_id},
                    success: function (data, status) {
                        //console.log(data);
                        obj.closest('.item_tr').find('.quantity').attr('placeholder', data);
                    }
                });
            }
        });

        $(document).on('keyup', '.quantity', function()
        {
            var obj = $(this);
            var input = parseFloat(obj.val());
            var existing = parseFloat(obj.attr('placeholder'));
            if(input>existing){
                obj.val(0);
                alert('Try lesser quantity!');
            }
        });

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
            if(count > 1){
                obj.closest('.single_list').remove();
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