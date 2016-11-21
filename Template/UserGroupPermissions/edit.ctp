<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-coffee"></i>User Roles of <?= $userGroups['title'] ?>
                </div>
                <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <?= $this->Form->create() ?>
                    <table class="table table-responsive">
                        <tbody>
                            <?php
                            foreach($controllers_methods as $controller=>$methods)
                            {
                                ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="select_all"/>
                                        </td>
                                        <td>
                                            <?= $controller ?>
                                        </td>
                                        <td class="methods">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <?php
                                                    foreach($methods as $method)
                                                    {
                                                        ?>
                                                        <td><input type="checkbox" <?= (isset($old_data[$controller][$method]) && $old_data[$controller][$method]['status'] ? 'checked' : '') ?> value="<?= $method ?>" name="roles[<?= $controller ?>][]"/> <span class="label label-danger"><?= $method ?></span></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>

                                            </table>

                                        </td>
                                    </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <?= $this->Form->button(__('Submit'),['class'=>'btn yellow pull-right','style'=>'margin:10px']) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>
<script>
    $(document).ready(function(){
       $(document).on('change','.select_all',function(){
            var change = $(this);
            $(this).closest('tr').find('.methods').find('input').each(function(){
                if(change.is(':checked'))
                {
                    $(this).prop('checked', true);
                }
                else
                {
                    $(this).prop('checked', false);
                }
                $.uniform.update();
          });
       });
    });
</script>