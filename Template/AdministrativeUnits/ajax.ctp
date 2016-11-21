<?php
/**
 * Created by PhpStorm.
 * User: JR
 * Date: 01-Oct-16
 * Time: 1:46 PM
 */

use Cake\ORM\TableRegistry;

$render = [];
$html = [];
for($i=0; $i<$level; $i++):
    $levelInfo = TableRegistry::get('administrative_levels')->find('all', ['conditions' => ['level_no' => $i]])->first();
    $inputOptions = TableRegistry::get('administrative_units')->find('list', ['conditions' => ['level_no' => $i]]);
    $html = '';
    $html = '<div class="form-group input select"><label class="col-sm-3 control-label text-right">'.$levelInfo['level_name'].'</label>'.
        '<div class="col-sm-9"><select name="'.$levelInfo['level_name'].'" required="required" class="form-control '.$levelInfo['level_name'].' level">';
    $html.='<option value="">Select</option>';
    if($i==0):
        foreach($inputOptions as $ke=>$option):
            $html.='<option value="'.$ke.'">'.$option.'</option>';
        endforeach;
    endif;
    $html.= '</select></div></div>';
    $render[] = $html;
endfor;

foreach($render as $input):
    echo $input;
endforeach;