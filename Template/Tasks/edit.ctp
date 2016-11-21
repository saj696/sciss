<?php
use Cake\Core\Configure;
?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-coffee"></i>Edit Task/Menu
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'],['class'=>'btn btn-sm btn-success']) ?>
                </div>
            </div>
            <div class="portlet-body">
                <?= $this->Form->create($task,['class'=>'form-horizontal']) ?>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <?php
                        echo $this->Form->input('parent_id', ['class'=>'form-control select2me','options' => $parentTasks, 'empty' => 'Select']);
                        echo $this->Form->input('name_en',['class'=>'form-control']);
                        echo $this->Form->input('name_bn',['class'=>'form-control']);
                        echo $this->Form->input('description',['class'=>'form-control']);
                        echo $this->Form->input('icon',['class'=>'form-control']);
                        echo $this->Form->input('controller',['class'=>'select2me form-control','empty'=>'Select']);
                        ?>
                        <div class="form-group input select">
                            <label for="controller" class="col-sm-3 control-label text-right"><?php echo __('Method'); ?></label>
                            <div class="col-sm-9" id="container_controller">
                                <select id="method" name="method" class="form-control">
                                    <?php
                                    if($methods)
                                        foreach($methods as $method)
                                        {
                                            ?>
                                            <option value="<?= $method ?>" <?= ($method==$task['method']) ? 'selected' : '' ?>><?= $method ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        echo $this->Form->input('ordering',['class'=>'form-control']);
                        echo $this->Form->input('position_left_01',['class'=>'form-control','type'=>'checkbox']);
                        echo $this->Form->input('position_top_01',['class'=>'form-control','type'=>'checkbox']);
                        echo $this->Form->input('status',['class'=>'form-control','options'=>Configure::read('status_options')]);
                        ?>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn blue pull-right','style'=>'margin-top:20px']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('change','#controller',function(){
            var controller = $(this).val();
            $('#method').html('');
            if(controller)
            {
                $.ajax({
                    url: '<?=$this->Url->build(('/Tasks/ajax/get_method'), true)?>',
                    type: 'POST',
                    data:{controller:controller},
                    success: function (data, status)
                    {
                        $.each(JSON.parse(data), function(key, value) {
                            $('#method')
                                .append($("<option></option>")
                                    .attr("value",value)
                                    .text(value));
                        });
                    },
                    error: function (xhr, desc, err)
                    {
                        console.log("error");

                    }
                });
            }
        });
    });
</script>
