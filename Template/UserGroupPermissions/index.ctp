<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-coffee"></i>User Group Permissions
                </div>
                <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title="">
                    </a>
                    <a class="remove" href="javascript:;" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <td>User Group</td>
                                <td>Created by</td>
                                <td>Created time</td>
                                <td>Updated by</td>
                                <td>Updated time</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($user_groups as $user_group){
                                ?>
                                <tr>
                                    <td><span class="label label-info" style="font-weight: bold"><?= $user_group['title_en'] ?></span></td>
                                    <td><?= $user_group['created']['full_name_bn'] ?></td>
                                    <td><?= date('d-m-Y H:m',$user_group['created_time']) ?></td>
                                    <td><?= $user_group['updated']['full_name_bn'] ?></td>
                                    <td><?= date('d-m-Y H:m',$user_group['updated_time']) ?></td>
                                    <td>
                                        <?= $this->Html->link(__('Set Role'), ['action' => 'edit', $user_group['id']],['class'=>'btn-sm btn btn-danger']) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>