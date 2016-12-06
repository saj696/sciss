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
            <?= $this->Html->link(__('Account Heads'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('Edit Account Head') ?></li>

    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-pencil-square-o fa-lg"></i><?= __('Edit Account Head') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'],['class'=>'btn btn-sm btn-success']); ?>
                </div>
            </div>
            <div class="portlet-body">
                <?= $this->Form->create($accountHead,['class' => 'form-horizontal','role'=>'form']) ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php
                        echo $this->Form->input('name');
                        echo $this->Form->input('code',['type' => 'text']);
                        echo $this->Form->input('parent',['options'=>$parents,'empty'=>__('Select')]);
                        echo $this->Form->input('applies_to',['options' => configure::read('applies_to')]);
                        echo $this->Form->input('is_contra',['type' => 'checkbox','class' => 'form-control isContra']);
                        if($accountHead->is_contra==1):
                            echo $this->Form->input('contra_id',['options'=>$parents,'empty' => __('Select') ,'label' => 'Contra Account']);
                        else:
                            echo $this->Form->input('contra_id', ['options'=>$parents,'empty' => __('Select'), 'label' => 'Contra Account','templates'=>['inputContainer' => '<div class="form-group contra_id_div hidden input {{type}}{{required}}">{{content}}</div>']]);
                        endif;
                        ?>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn blue pull-right','style'=>'margin-top:20px']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.isContra', function () {
            if ($(this).prop('checked')) {
                $('.contra_id_div').removeClass('hidden');
            }
            else {
                $('.contra_id_div').addClass('hidden');
                $('.contra_id').val('');
            }
        });
    })
</script>