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
            <?= $this->Html->link(__('Item Units'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('New Item Unit') ?></li>

    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus-square-o fa-lg"></i><?= __('Add New Item Unit') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>

            </div>
            <div class="portlet-body">
                <?= $this->Form->create($itemUnit, ['class' => 'form-horizontal', 'role' => 'form']) ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php
                        echo $this->Form->input('unit_level', ['options' => Configure::read('unit_levels')]);
                        echo $this->Form->input('constituent_unit_id', ['label' => 'Constituent Level', 'required' => true]);
                        echo $this->Form->input('unit_name');
                        echo $this->Form->input('unit_size');
                        echo $this->Form->input('unit_type', ['options' => Configure::read('pack_size_units')]);
                        ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn blue center-block', 'style' => 'margin-top:20px']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#constituent-unit-id").hide();
        $('label[for=constituent-unit-id], input#constituent-unit-id').hide();

        $(document).on("change", "#unit-level", function () {
            var obj = $(this);
            var level = parseInt(obj.val());
            $("#constituent-unit-id").show();
            $('label[for=constituent-unit-id], input#constituent-unit-id').show();
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build("/ItemUnits/ajax")?>',
                data: {level: level},
                success: function (data, status) {
                    if (level == 1) {
                        obj.closest('.input').next().find('#constituent-unit-id').html('');
                        obj.closest('.input').next().find('#constituent-unit-id').html('<option value="">Select</option>');
                        obj.closest('.input').next().find('#constituent-unit-id').removeAttr('required');
                    }
                    else {
                        obj.closest('.input').next().find('.col-sm-9').html('');
                        obj.closest('.input').next().find('.col-sm-9').html(data);
                    }
                }
            });


        });
    });
</script>