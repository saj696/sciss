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
            <?= $this->Html->link(__('Payments'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('New Payment') ?></li>

    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus-square-o fa-lg"></i><?= __('Add New Payment') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'],['class'=>'btn btn-sm btn-success']); ?>
                </div>

            </div>
            <div class="portlet-body">
                <?= $this->Form->create($payment,['class' => 'form-horizontal','role'=>'form']) ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php
                        echo $this->Form->input('parent_level',['options' => $parantsLevels, 'label'=>'Customers Parents Level','class'=> 'form-control level', 'empty'=>__('Select'), 'templates'=>['select' => '<div id="container_{{name}}" class="col-sm-9 levelContainer"><select name="{{name}}"{{attrs}} class="form-control">{{content}}</select></div>']]);
                        echo $this->Form->input('parent_unit', ['options' => [],'label'=>'Customer Parent Unit', 'empty' => __('Select'),'class'=> 'form-control unit','templates' => ['select' => '<div id="container_{{name}}" class="col-sm-9 unitContainer"><select name="{{name}}"{{attrs}} class="form-control">{{content}}</select></div>']]);
                        echo $this->Form->input('customer_id', ['options' => [],'label'=>'Customer', 'empty' => __('Select'),'class'=> 'form-control customer', 'templates' => ['select' => '<div id="container_{{name}}" class="col-sm-9 customerContainer"><select name="{{name}}"{{attrs}} class="form-control">{{content}}</select></div>']]);
                        echo $this->Form->input('due_invoice', ['options' => [],'label'=>'Due Invoice', 'empty' => __('Select'),'class'=> 'form-control dueInvoice', 'templates' => ['select' => '<div id="container_{{name}}" class="col-sm-9 dueInvoiceContainer"><select name="{{name}}"{{attrs}} class="form-control">{{content}}</select></div>']]);
                        ?>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2 tableDesign">
                        <table class="table table-bordered">
                            <tbody class="appendTr">
                            <tr class="portlet box grey-silver" style="color: white">
                                <th>SN</th>
                                <th>Invoice Date</th>
                                <th>Net Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Current Payment</th>
                                <th>Remove</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row"><div class="col-md-12"></div></div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php
                        echo $this->Form->input('payment_account',['options' =>$paymentAccounts, 'label' =>'Payment Account','class' => 'form-control payment', 'empty' => __('Select')]);
                        ?>
                        <div class="paymentsGroups">
                            <?php
                            echo $this->Form->input('reference_number');
                            echo $this->Form->input('bank_branch');
                            echo $this->Form->input('description');
                            echo $this->Form->input('collection_serial_no');
                            echo $this->Form->input('collection_date', ['type' => 'text' ,'class' => 'form-control datepicker','required'=>'required' ]);
                            echo $this->Form->input('amount',['type'=>'text','class'=>'form-control amount', 'required' => 'required'])
                            ?>
                            <?= $this->Form->button(__('Submit'),['class'=>'btn blue pull-right submitCheck','style'=>'margin-top:20px']) ?>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

<script>
    $(document).ready(function(){

        //default condition
        $('.paymentsGroups').addClass('hidden');

        // Parent Level Onchange function
        $(document).on('change', '.level', function () {
            var obj = $(this);
            var level = obj.val();
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build("/Payments/ajax/units")?>',
                data: {level: level},
                dataType: 'json',
                success: function (data, status) {

                    //   Clear Unit Container
                    var $el = $('.unitContainer select');
                    $el.empty();
                    $el.append($("<option></option>")
                        .attr("value", '').text('Select'));

                    //   Clear Customer Container
                    var $el = $('.customerContainer select');
                    $el.empty();
                    $el.append($("<option></option>")
                        .attr("value", '').text('Select'));

                    //   Clear Invoice Container
                    var $el = $('.dueInvoiceContainer select');
                    $el.empty();
                    $el.append($("<option></option>")
                        .attr("value", '').text('Select'));

                    //   Append Unit Container
                    $.each(data, function(key, value) {
                        $('.unitContainer select')
                            .append($("<option></option>")
                                .attr("value",key)
                                .text(value));
                    });
                }
            });
        });

        // Parent Unit Onchange function
        $(document).on('change', '.unit', function () {
            var obj = $(this);
            var unit = obj.val();
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build("/Payments/ajax/customers")?>',
                data: {unit: unit},
                dataType: 'json',
                success: function (data, status) {

                    //   Clear Customer Container
                    var $el = $('.customerContainer select');
                    $el.empty();
                    $el.append($("<option></option>")
                        .attr("value", '').text('Select'));

                    //   Clear Invoice Container
                    var $el = $('.dueInvoiceContainer select');
                    $el.empty();
                    $el.append($("<option></option>")
                        .attr("value", '').text('Select'));

                    //   Append Customer Container
                    $.each(data, function(key, value) {
                        $('.customerContainer select')
                            .append($("<option></option>")
                                .attr("value",key)
                                .text(value));
                    });
                }
            });
        });

        // Customer Onchange function
        $(document).on('change', '.customer', function () {
            var obj = $(this);
            var customer = obj.val();
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build("/Payments/ajax/dueInvoice")?>',
                data: {customer: customer},
                dataType: 'json',
                success: function (data, status) {

                    //   Clear Invoice Container
                    var $el = $('.dueInvoiceContainer select');
                    $el.empty();
                    $el.append($("<option></option>")
                        .attr("value", '').text('Select'));

                    //   Append Invoice Container
                    $.each(data, function(key, value) {
                        $('.dueInvoiceContainer select')
                            .append($("<option></option>")
                                .attr("value",key)
                                .text(value));
                    });

                }
            });
        });

        // Due invoice Onchange function and add table row
        $(document).on('change', '.dueInvoice', function () {
            var obj = $(this);
            var dueInvoice = obj.val();
            if(dueInvoice){
                $.ajax({
                    type: 'POST',
                    url: '<?= $this->Url->build("/Payments/ajax/paymentTable")?>',
                    data: {dueInvoice: dueInvoice},
                    success: function (data, status) {
                        var numberOfRow = $('.appendTr tr').length;
                        // to check the existance invoice
                        var invoiceRow = '[data-invoice-id='+dueInvoice+']';
                        if($(invoiceRow).length==0){
                            $('.appendTr').append(data);
                            $('.appendTr tr:last-child').find('td:first-child').html(numberOfRow);
                        }
                        else {
                            alert("Already choose this invoice");
                        }
                    }
                });
            }
        });

        // Single row remove function
        $(document).on('click','.remove', function(){
            var obj = $(this);
            var count = $(".main_tr").length;
            if(count > 1){
                obj.closest(".main_tr").remove();
            }
        });

        // Payments method selection function
        $(document).on('change', '.payment', function(){
            var obj = $(this);
            var payment = obj.val();
            if(payment == ''){
                $('.paymentsGroups').addClass('hidden');
            }
            else{
                $('.paymentsGroups').removeClass('hidden');
            }
        });

        // Datepicker function
        $(document).on('focus','.datepicker',function(){
            $(this).removeClass('hasDatepicker').datepicker({
                dateFormat: "dd-mm-yy"
            });
        });

        // Amount class onchange function
        $(document).on('keyup','.amount',function(){
            var obj = $(this);
            var amount = parseFloat(obj.val());
            amount = parseFloat(amount);

            $('.due_hidden').each(function(){
                $(this).closest('.main_tr').find('.current_payment').val('');
            });
            $('.due_hidden').each(function(){
                var due = ($(this).val());
                due = parseFloat(due);

                if(amount>=due){
                    $(this).closest('.main_tr').find('.current_payment').val(due);
                }else{
                    var rem = due-amount;
                    if(rem>0 && amount>0){
                        $(this).closest('.main_tr').find('.current_payment').val(amount);
                    }
                }
                amount -= due;
            });
            if(amount>0)
            {
                alert('Amount big');
                $('.due_hidden').each(function(){
                    $(this).closest('.main_tr').find('.current_payment').val('');
                });
            }
        });

        // submit class onclick function
        $(document).on("click",".submitCheck", function(){

        });

        //End all funciton

    });
</script>