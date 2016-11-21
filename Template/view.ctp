<?php
$status = \Cake\Core\Configure::read('status_options');
use App\View\Helper\MyHelper;
?>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->Url->build(('/Dashboard'), true); ?>"><?= __('Dashboard') ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= $this->Html->link(__('Item Units'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('View Item Unit') ?></li>
    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o fa-lg"></i><?= __('Item Unit Details') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th><?= __('Unit Name') ?></th>
                            <td><?= h($itemUnit->unit_name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Unit Type') ?></th>
                            <td><?= h($itemUnit->unit_type) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Unit Level') ?></th>
                            <td><?= $this->Number->format($itemUnit->unit_level) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Constituent Unit Id') ?></th>
                            <td><?= $this->Number->format($itemUnit->constituent_unit_id) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Unit Size') ?></th>
                            <td><?= $this->Number->format($itemUnit->unit_size) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Converted Quantity') ?></th>
                            <td><?= $this->Number->format($itemUnit->converted_quantity) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Created By') ?></th>
                            <td><?= $this->Number->format($itemUnit->created_by) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Created Date') ?></th>
                            <td><?= $this->System->display_date($itemUnit->created_date) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Updated By') ?></th>
                            <td><?= $this->Number->format($itemUnit->updated_by) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('Updated Date') ?></th>
                            <td><?= $this->System->display_date($itemUnit->updated_date) ?></td>
                        </tr>


                        <tr>
                            <th><?= __('Status') ?></th>
                            <td><?= __($status[$itemUnit->status]) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

