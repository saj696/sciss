<?php
use Cake\Core\Configure;
?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-coffee"></i>View User Group
                </div>
                <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-bordered">
                    <tr>
                        <th><?= __('Title') ?></th>
                        <td><?= h($userGroup->name_en) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Created By') ?></th>
                        <td><?= $userGroup->created->name_en ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Created Time') ?></th>
                        <td><?= $userGroup->formatted_created_time ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Updated By') ?></th>
                        <td><?= $userGroup->updated->name_en ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Updated Time') ?></th>
                        <td><?= $userGroup->formatted_updated_time ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Ordering') ?></th>
                        <td><?= $this->Number->format($userGroup->ordering) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Status') ?></th>
                        <td><?= Configure::read('status')[$userGroup->status] ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>