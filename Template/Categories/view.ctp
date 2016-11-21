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
            <?= $this->Html->link(__('Categories'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('View Category') ?></li>
    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o fa-lg"></i><?= __('Category Details') ?>
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
                            <td><?= h($category->name) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Level No') ?></th>
                            <td><?= $this->Number->format($category->level_no) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Parent') ?></th>
                            <td><?= $this->Number->format($category->parent) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Local Id') ?></th>
                            <td><?= $this->Number->format($category->local_id) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Global Id') ?></th>
                            <td><?= $this->Number->format($category->global_id) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Number Of Direct Successors') ?></th>
                            <td><?= $this->Number->format($category->number_of_direct_successors) ?></td>
                        </tr>


                        <tr>
                            <th><?= __('Status') ?></th>
                            <td><?= __($status[$category->status]) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Created By') ?></th>
                            <td><?= $this->Number->format($category->created_by) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Created Date') ?></th>
                            <td><?= $this->Number->format($category->created_date) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Updated By') ?></th>
                            <td><?= $this->Number->format($category->updated_by) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Updated Date') ?></th>
                            <td><?= $this->Number->format($category->updated_date) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

