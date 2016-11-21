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
            <?= $this->Html->link(__('Administrative Units'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('View Administrative Unit') ?></li>
    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o fa-lg"></i><?= __('Administrative Unit Details') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'],['class'=>'btn btn-sm btn-success']); ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                                                                                                        <tr>
                                    <th><?= __('Administrative Level') ?></th>
                                    <td><?= $administrativeUnit->has('administrative_level') ? $this->Html->link($administrativeUnit->administrative_level->id, ['controller' => 'AdministrativeLevels', 'action' => 'view', $administrativeUnit->administrative_level->id]) : '' ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Unit Name') ?></th>
                                    <td><?= h($administrativeUnit->unit_name) ?></td>
                                </tr>
                                                                                                                                                                                                                
                                                            <tr>
                                    <th><?= __('Parent') ?></th>
                                    <td><?= $this->Number->format($administrativeUnit->parent) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Local Id') ?></th>
                                    <td><?= $this->Number->format($administrativeUnit->local_id) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Global Id') ?></th>
                                    <td><?= $this->Number->format($administrativeUnit->global_id) ?></td>
                                </tr>
                                                    
                            
                                <tr>
                                    <th><?= __('Status') ?></th>
                                    <td><?= __($status[$administrativeUnit->status]) ?></td>
                                </tr>
                                                            
                                                            <tr>
                                    <th><?= __('Created By') ?></th>
                                    <td><?= $this->Number->format($administrativeUnit->created_by) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Created Date') ?></th>
                                    <td><?= $this->Number->format($administrativeUnit->created_date) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Updated By') ?></th>
                                    <td><?= $this->Number->format($administrativeUnit->updated_by) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Updated Date') ?></th>
                                    <td><?= $this->Number->format($administrativeUnit->updated_date) ?></td>
                                </tr>
                                                                                                                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

