<?php
use Cake\Core\Configure;
?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-coffee"></i>Edit User Group
                </div>
                <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <?= $this->Form->create($userGroup,['class'=>'form-horizontal']) ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <legend><?= __('Add User Group') ?></legend>
                        <?php
                        echo $this->Form->input('title_en',['class'=>'form-control']);
                        echo $this->Form->input('title_bn',['class'=>'form-control']);
                        echo $this->Form->input('ordering',['class'=>'form-control']);
                        echo $this->Form->input('status',['class'=>'form-control','options'=>Configure::read('status_options')]);
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