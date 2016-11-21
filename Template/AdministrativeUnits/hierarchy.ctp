<?php
/**
 * Created by PhpStorm.
 * User: JR
 * Date: 02-Oct-16
 * Time: 11:05 AM
 */
?>

<select name="<?= $next_level_name?>" required="required" class="form-control <?= $next_level_name?> level">
    <option value="">Select</option>
    <?php
    foreach($dropArray as $key=>$drop):
    ?>
        <option value="<?= $key?>"><?= $drop?></option>
    <?php endforeach;?>
</select>