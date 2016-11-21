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
            <?= $this->Html->link(__('Items'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('View Item') ?></li>
    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o fa-lg"></i><?= __('Item Details') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'],['class'=>'btn btn-sm btn-success']); ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                                                                                                        <tr>
                                    <th><?= __('Category') ?></th>
                                    <td><?= $item->has('category') ? $this->Html->link($item->category->name, ['controller' => 'Categories', 'action' => 'view', $item->category->id]) : '' ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Name') ?></th>
                                    <td><?= h($item->name) ?></td>
                                </tr>
                                                                                                        <tr>
                                    <th><?= __('Code') ?></th>
                                    <td><?= h($item->code) ?></td>
                                </tr>
                                                                                                                                                                                                                
                                                            <tr>
                                    <th><?= __('Pack Size') ?></th>
                                    <td><?= $this->Number->format($item->pack_size) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Unit') ?></th>
                                    <td><?= $this->Number->format($item->unit) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Box Size') ?></th>
                                    <td><?= $this->Number->format($item->box_size) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Cash Sales Price') ?></th>
                                    <td><?= $this->Number->format($item->cash_sales_price) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Credit Sales Price') ?></th>
                                    <td><?= $this->Number->format($item->credit_sales_price) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Retail Price') ?></th>
                                    <td><?= $this->Number->format($item->retail_price) ?></td>
                                </tr>
                                                    
                            
                                <tr>
                                    <th><?= __('Status') ?></th>
                                    <td><?= __($status[$item->status]) ?></td>
                                </tr>
                                                            
                                                            <tr>
                                    <th><?= __('Created By') ?></th>
                                    <td><?= $this->Number->format($item->created_by) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Created Date') ?></th>
                                    <td><?= $this->Number->format($item->created_date) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Updated By') ?></th>
                                    <td><?= $this->Number->format($item->updated_by) ?></td>
                                </tr>
                                                    
                                                            <tr>
                                    <th><?= __('Updated Date') ?></th>
                                    <td><?= $this->Number->format($item->updated_date) ?></td>
                                </tr>
                                                                                                                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

