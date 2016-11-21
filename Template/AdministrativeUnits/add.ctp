<?php
use Cake\Core\Configure;
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
        <li><?= __('New Administrative Unit') ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus-square-o fa-lg"></i><?= __('Add New Administrative Unit') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>
            </div>

            <div class="portlet-body">
                <?= $this->Form->create($administrativeUnit, ['class' => 'form-horizontal', 'role' => 'form']) ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php
                        echo $this->Form->input('administrative_level_id', ['options' => $administrativeLevels, 'label'=>'Level', 'class'=>'form-control administrative_level', 'empty' => __('Select')]);
                        ?>
                        <div class="hierarchy"></div>
                        <?php
                        echo $this->Form->input('unit_name');
                        echo $this->Form->input('prefix', ['pattern'=>".{4,}", 'required title'=>"4 characters minimum"]);
                        ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn blue pull-right', 'style' => 'margin-top:20px']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('change', '.administrative_level', function () {
            var administrative_level = $('.administrative_level').val();
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build("/AdministrativeUnits/ajax")?>',
                data: {administrative_level: administrative_level},
                success: function (data, status) {
                    $('.hierarchy').html('');
                    $('.hierarchy').html(data);
                }
            });
        });

        $(document).on('change', '.level', function() {
            var obj = $(this);
            var level_name = obj.closest('.input').find('.control-label').html();
            var next_level_name = obj.closest('.input').next().find('.control-label').html();
            var next_level = obj.closest('.input').next().find('.level');
            var level_val = obj.val();

//            console.log(next_level.length);
//            console.log(level_val);

            obj.closest('.input').nextAll().find('.level').html('<option value="">Select</option>');
            if (next_level.length>0 && level_val>0) {
                $.ajax({
                    type: 'POST',
                    url: '<?= $this->Url->build("/AdministrativeUnits/hierarchy")?>',
                    data: {level_name: level_name, level_val:level_val, next_level_name:next_level_name},
                    success: function (data, status) {
                        if (data) {
                            obj.closest('.input').next().find('.col-sm-9').html(data);
                        }
                    }
                });
            }
        });
    });
</script>

