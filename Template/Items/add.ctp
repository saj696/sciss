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
            <?= $this->Html->link(__('Items'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('New Item') ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus-square-o fa-lg"></i><?= __('Add New Item') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>
            </div>

            <div class="portlet-body">
                <?= $this->Form->create($item, ['class' => 'form-horizontal', 'role' => 'form']) ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php
                        echo $this->Form->input('category_id', ['options' => $categories, 'class'=>'form-control category', 'empty' => __('Select'),'required'=>'required']);
                        ?>
                        <div class="subDiv"></div>
                        <?php
                        echo $this->Form->input('name');
                        echo $this->Form->input('code',['class'=>'form-control codeItem','readonly']);
                        echo $this->Form->input('pack_size',['type'=>'text']);
                        echo $this->Form->input('unit', ['options'=>Configure::read('pack_size_units'), 'empty'=>'Select', 'required'=>'required']);
                        echo $this->Form->input('generic_name');
                        echo $this->Form->input('box_size',['type'=>'text']);
                        echo $this->Form->input('cash_sales_price',['type'=>'text']);
                        echo $this->Form->input('credit_sales_price',['type'=>'text']);
                        echo $this->Form->input('retail_price',['type'=>'text']);
                        ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn blue pull-right', 'style' => 'margin-top:20px']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('change', '.category', function () {
            var obj = $(this);
            var category = obj.val();
            obj.closest('.input').next().find('.category_div').remove();
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build("/Items/ajax")?>',
                data: {category: category},
                success: function (data, status) {
                    if (data) {
                        $('.subDiv').append(data);
                        obj.attr('name', '');
                        obj.prevAll('.category').attr('name', '');
                    }
                }
            });
            $('.codeItem').val('');
            if(category!= ''){
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build("/Items/generateCode")?>',
                data: {category: category},
                success: function (data, status) {
                    $('.codeItem').val(data);
                }
            });
        }
        });
    });
</script>
