<?php
use Cake\Core\Configure;
$depot['warehouses'] = json_decode($depot['warehouses'], true);
?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->Url->build(('/Dashboard'), true); ?>"><?= __('Dashboard') ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= $this->Html->link(__('Sales Point'), ['action' => 'index']) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><?= __('Edit Sales Point') ?></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-pencil-square-o fa-lg"></i><?= __('Edit Sales Point') ?>
                </div>
                <div class="tools">
                    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-sm btn-success']); ?>
                </div>
            </div>

            <div class="portlet-body">
                <?= $this->Form->create($depot, ['class' => 'form-horizontal', 'role' => 'form']) ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php
                        echo $this->Form->input('name');
                        echo $this->Form->input('level_no', ['options'=>$administrativeLevels, 'class'=>'form-control level', 'empty'=>'Select', 'required'=>'required']);
                        echo $this->Form->input('unit_id', ['options'=>$unitDrops, 'required'=>'required', 'class'=>'form-control unit']);
                        echo $this->Form->input('address', ['rows'=>1]);
                        ?>
                        <div class="form-group input select required">
                            <label for="warehouses" class="col-sm-3 control-label text-right">Warehouses</label>
                            <div id="container_warehouses[]" class="col-sm-9">
                                <select name="warehouses[]" class="form-control" multiple="multiple" required="required" id="warehouses">
                                    <option value="">Select</option>
                                    <?php foreach($warehouses as $key=>$text):?>
                                        <option <?php if(in_array($key, $depot['warehouses'])){echo 'selected';}?> value="<?= $key?>"><?= $text?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn blue pull-right', 'style' => 'margin-top:20px']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

