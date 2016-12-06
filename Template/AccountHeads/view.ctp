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
            <?= $this->Html->link(__('Account Heads'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('View Account Head') ?></li>
    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o fa-lg"></i><?= __('Account Head Details') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'],['class'=>'btn btn-sm btn-success']); ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($accountHead->name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Code') ?></th>
                            <td><?= h($accountHead->code) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Parent') ?></th>
                            <td><?= h($accountHead->parent_id) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Applies To') ?></th>
                            <td><?= h($accountHead->applies_to) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Contra Status') ?></th>
                            <td><?= h($accountHead->is_contra) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Contra ID') ?></th>
                            <td><?= h($accountHead->contra_id) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Status') ?></th>
                            <td><?= __($status[$accountHead->status]) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Created By') ?></th>
                            <td><?= $this->Number->format($accountHead->created_by) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Created Date') ?></th>
                            <td><?= $this->Number->format($accountHead->created_date) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Updated By') ?></th>
                            <td><?= $this->Number->format($accountHead->updated_by) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Updated Date') ?></th>
                            <td><?= $this->Number->format($accountHead->updated_date) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

