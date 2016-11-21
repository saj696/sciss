<?php
/**
 * Created by PhpStorm.
 * User: JR
 * Date: 02-Oct-16
 * Time: 11:05 AM
 */
?>

<select name="recipient_id" class="form-control recipient_id" style="width: 40%;">
    <option value="">Select</option>
    <?php
    foreach($dropArray as $key=>$drop):
    ?>
        <option value="<?= $key?>"><?= $drop?></option>
    <?php endforeach;?>
</select>