<?php
/**
 * Created by PhpStorm.
 * User: JR
 * Date: 02-Oct-16
 * Time: 11:05 AM
 */
?>

<select name="constituent_unit_id"  id="constituent-unit-id" required="required" class="form-control unit">
    <option value="">Select</option>
    <?php
    foreach ($dropArray as $key => $drop):
        ?>
        <option value="<?= $key ?>"><?= $drop ?></option>
    <?php endforeach; ?>
</select>