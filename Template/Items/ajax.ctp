<?php
/**
 * Created by PhpStorm.
 * User: JR
 * Date: 02-Oct-16
 * Time: 11:05 AM
 */
?>

<div class="form-group input select category_div">
    <label for="category-id" class="col-sm-3 control-label text-right">Sub Category</label>
    <div id="container_category_id" class="col-sm-9">
        <select name="category_id" required="required" class="form-control category">
            <option value="">Select</option>
            <?php
            foreach($dropArray as $key=>$drop):
                ?>
                <option value="<?= $key?>"><?= $drop?></option>
            <?php endforeach;?>
        </select>
    </div>
</div>
