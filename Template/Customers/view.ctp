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
            <?= $this->Html->link(__('Customers'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('View Customer') ?></li>
    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o fa-lg"></i><?= __('Customer Details') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'],['class'=>'btn btn-sm btn-success']); ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                                                                                                        <tr>
                                    <th><?= __('Administrative Unit') ?></th>
                                    <td><?= $customer->has('administrative_unit') ? $this->Html->link($customer->administrative_unit->unit_name, ['controller' => 'AdministrativeUnits', 'action' => 'view', $customer->administrative_unit->id]) : '' ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Code') ?></th>
                                    <td><?= h($customer->code) ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Name') ?></th>
                                    <td><?= h($customer->name) ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Proprietor') ?></th>
                                    <td><?= h($customer->proprietor) ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Contact Person') ?></th>
                                    <td><?= h($customer->contact_person) ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Mobile') ?></th>
                                    <td><?= h($customer->mobile) ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Telephone') ?></th>
                                    <td><?= h($customer->telephone) ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Email') ?></th>
                                    <td><?= h($customer->email) ?></td>
                                </tr>
                                                                                                                                                                                                                
                                                            <tr>
                                    <th><?= __('Business Type') ?></th>
                                    <td><?= $this->Number->format($customer->business_type) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Credit Limit') ?></th>
                                    <td><?= $this->Number->format($customer->credit_limit) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Credit Invoice Days') ?></th>
                                    <td><?= $this->Number->format($customer->credit_invoice_days) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Cash Invoice Days') ?></th>
                                    <td><?= $this->Number->format($customer->cash_invoice_days) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Approved By') ?></th>
                                    <td><?= $this->Number->format($customer->approved_by) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Approval Date') ?></th>
                                    <td><?= $this->Number->format($customer->approval_date) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Is Mango') ?></th>
                                    <td><?= $this->Number->format($customer->is_mango) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Is Potato') ?></th>
                                    <td><?= $this->Number->format($customer->is_potato) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Customer Type') ?></th>
                                    <td><?= $this->Number->format($customer->customer_type) ?></td>
                                </tr>
                                                    
                            
                                <tr>
                                    <th><?= __('Status') ?></th>
                                    <td><?= __($status[$customer->status]) ?></td>
                                </tr>
                                                            
                                                            <tr>
                                    <th><?= __('Created By') ?></th>
                                    <td><?= $this->Number->format($customer->created_by) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Created Date') ?></th>
                                    <td><?= $this->Number->format($customer->created_date) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Updated By') ?></th>
                                    <td><?= $this->Number->format($customer->updated_by) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Updated Date') ?></th>
                                    <td><?= $this->Number->format($customer->updated_date) ?></td>
                                </tr>
                                                                                                                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

