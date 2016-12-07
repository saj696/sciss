<?php
use Cake\Core\Configure;
//echo $this->System->asked_level_global_id(1, 1048576);
//exit;
?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->Url->build(('/Dashboard'), true); ?>"><?= __('Dashboard') ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= $this->Html->link(__('Customers'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('New Customer') ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus-square-o fa-lg"></i><?= __('Add New Customer') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>
            </div>

            <div class="portlet-body">
                <?= $this->Form->create($customer, ['class' => 'form-horizontal', 'type'=>'file' ,'role' => 'form']) ?>
                <div class="row">
                    <div class="col-md-7 col-md-offset-2">
                        <?php
                        echo $this->Form->input('level_no', ['options'=>$administrativeLevels, 'label'=>'Level', 'class'=>'form-control level', 'empty'=>'Select', 'required'=>'required']);
                        echo $this->Form->input('administrative_unit_id', ['label'=>'Unit', 'required' => 'required','empty' => __('Select'),'class'=> 'form-control unit']);
                        echo $this->Form->input('prefix', ['options'=>$administrativeLevels, 'label'=>'Prefix Level', 'class'=>'form-control prefix', 'empty'=>'Select', 'required'=>'required']);
                        echo $this->Form->input('code', ['class'=>'form-control codeCustomer', 'readonly']);
                        echo $this->Form->input('name_distributor',['label'=>'Distributor Name']);
                        echo $this->Form->input('proprietor_name',['label'=>'Proprietor Name']);
                        echo $this->Form->input('address', ['rows'=>1]);
                        echo $this->Form->input('mobile_one',['label' => 'Mobile No','required'=>'required']);
                        echo $this->Form->input('mobile_two',['label' => 'Alternative No']);
                        echo $this->Form->input('phone');
                        echo $this->Form->input('email');
                        echo $this->Form->input('credit_limit',['type'=>'text']);
                        echo $this->Form->input('business_starting_date',['type'=>'text','class'=>'form-control datepicker','label'=>['text'=>__('Business Starting Date')]]);
                        echo $this->Form->input('credit_deposit_amount',['type' =>'Credit Deposit Scheme(CDS) Amount']);
                        echo $this->Form->input('emergency_contact',['label'=>'Emergency Contact Number']);
                        echo $this->Form->input('distributor_status', ['options'=>Configure::read('distributor_status')]);
                        ?>
                        <fieldset>
                            <legend>Semester Cheque deposit info</legend>
                                <?php
                                echo $this->Form->input('name_bank');
                                echo $this->Form->input('account_no');
                                echo $this->Form->input('account_status', ['default'=>1, 'type'=>'radio', 'class'=>'radio-inline form-control', 'options' => [1=>'Active', 2=>'Inactive'], 'templates'=>['inputContainer' => '<div class="form-group input {{required}}">{{content}}</div>', 'label' =>'<label {{attrs}} class="col-sm-3 control-label text-right" >{{text}}</label>', 'input' => '<div class="col-sm-7 container_{{name}}"> <input {{attrs}} class="form-control" type="{{type}}" name="{{name}}"></div>']]);
                                echo $this->Form->input('account_type', ['options'=>Configure::read('account_type'),'empty'=>__('Select')]);
                                echo $this->Form->input('micr_cheque', ['default'=>1, 'type'=>'radio', 'class'=>'radio-inline form-control', 'options' => [1=>'Yes', 2=>'No'], 'templates'=>['inputContainer' => '<div class="form-group input {{required}}">{{content}}</div>', 'label' =>'<label {{attrs}} class="col-sm-3 control-label text-right" >{{text}}</label>', 'input' => '<div class="col-sm-7 container_{{name}}"> <input {{attrs}} class="form-control" type="{{type}}" name="{{name}}"></div>']]);
                                echo $this->Form->input('cheque_date',['type'=>'text','class'=>'form-control datepicker','label'=>['text'=>__('Date')]]);
                                echo $this->Form->input('cheque_amount',['type'=>'text']);
                                echo $this->Form->input('distributor_feedback', ['rows'=>1]);
                                ?>
                        </fieldset>
                        <?php
                        echo $this->Form->input('nid');
                        echo $this->Form->input('picture_file', ['type'=>'file', 'label'=>'Photo']);
                        echo $this->Form->input('distributor_paper_file', ['type'=>'file', 'label'=>'Distributor Paper']);
                        echo $this->Form->input('appointment_form_file', ['type'=>'file', 'label'=>'Appointment Form']);
                        echo $this->Form->input('appraisal_form_file', ['type'=>'file', 'label'=>'Appraisal Form']);
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
        $(document).on('change', '.level', function () {
            var obj = $(this);
            var level = obj.val();
            obj.closest('.input').next().find('.unit').html('<option value="">Select</option>');
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build("/Customers/ajax")?>',
                data: {level: level},
                success: function (data, status) {
                    obj.closest('.input').next().find('.col-sm-9').html('');
                    obj.closest('.input').next().find('.col-sm-9').html(data);
                }
            });
        });

        $(document).on("focus",".datepicker", function(){
            $(this).removeClass('hasDatepicker').datepicker({
                dateFormat: 'dd-mm-yy'
            });
        });

        $(document).on('change', '.prefix', function () {
            var obj = $(this);
            $('.codeCustomer').val('');
            var prefix_level = obj.val();
            var unit = $('.unit').val();
            if(prefix_level!= ''){
                $.ajax({
                    type: 'POST',
                    url: '<?= $this->Url->build("/Customers/generateCode")?>',
                    data: {prefix_level: prefix_level,unit:unit},
                    success: function (data, status) {
                        console.log(data);
                        $('.codeCustomer').val(data);
                    }
                });
            }
        });


    });
</script>