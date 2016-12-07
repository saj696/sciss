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
                            <th><?= __('name_distributor') ?></th>
                            <td><?= h($customer->name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Proprietor Name') ?></th>
                            <td><?= h($customer->proprietor_name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Address') ?></th>
                            <td><?= h($customer->address) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Mobile No') ?></th>
                            <td><?= h($customer->mobile_one) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Alternative No') ?></th>
                            <td><?= h($customer->mobile_two) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Phone') ?></th>
                            <td><?= h($customer->phone) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Email') ?></th>
                            <td><?= h($customer->email) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Credit Limit') ?></th>
                            <td><?= h($customer->credit_limit) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Business Starting Date') ?></th>
                            <td><?= date('d-m-y',$customer->business_starting_date) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Credit Deposit Amount') ?></th>
                            <td><?= h($customer->credit_deposit_amount) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Emergency Contact Number') ?></th>
                            <td><?= h($customer->emergency_contact) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Distributor Status') ?></th>
                            <td><?= h($customer->distributor_status) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Name Of Bank') ?></th>
                            <td><?= h($customer->name_bank) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Account No') ?></th>
                            <td><?= h($customer->account_no) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Account Status') ?></th>
                            <td><?= h($customer->account_status) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Account Type') ?></th>
                            <td><?= h($customer->account_type) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('MICR Cheque') ?></th>
                            <td><?= h($customer->micr_cheque) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Date') ?></th>
                            <td><?= date('d-m-y',$customer->cheque_date) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Amount') ?></th>
                            <td><?= h($customer->cheque_amount) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Distributor Feedback') ?></th>
                            <td><?= h($customer->distributor_feedback) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Profile Photo') ?></th>
                            <td><img src="<?php echo isset($customer->picture)?$this->request->webroot.''.$customer->picture:"http://placehold.it/100x100";?>" alt="" height="100" width="100"></td>
                        </tr>

                        <tr>
                            <th><?= __('Distributor Paper') ?></th>
                            <td> <a href="<?php echo $this->request->webroot.''.$customer->distributor_paper ?>" target="_blank">Click To Download</a></td>
                        </tr>
                        <tr>
                            <th><?= __('Appointment Form_') ?></th>
                            <td><a href="<?php echo $this->request->webroot.''.$customer->appointment_form ?>" target="_blank">Click To Download</a></td>
                        </tr>
                        <tr>
                            <th><?= __('Appraisal Form') ?></th>
                            <td><a href="<?php echo $this->request->webroot.''.$customer->appraisal_form ?>" target="_blank">Click To Download</a></td>
                        </tr>

                        <tr>
                            <th><?= __('Status') ?></th>
                            <td><?php echo ($customer->status)==0? "Not Approved": "Approved"; ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Created By') ?></th>
                            <td><?= $this->Number->format($customer->created_by) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Created Date') ?></th>
                            <td><?= date('d-m-y',$customer->created_date) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Updated By') ?></th>
                            <td><?= $this->Number->format($customer->updated_by) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Updated Date') ?></th>
                            <td><?= date('d-m-y',$customer->updated_date) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

