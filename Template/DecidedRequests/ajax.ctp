<?php
/**
 * Created by PhpStorm.
 * User: JR
 * Date: 23-Oct-16
 * Time: 2:36 PM
 */
?>

<?php foreach($warehouseDetails as $detail):?>
    <tr>
        <td><?= $detail['item_name']?></td>
        <td><?= $warehouseInfo['name']?></td>
        <td><?= $detail['existing']?></td>
        <td width="20%">
            <input type="hidden" class="warehouse_id" value="<?= $detail['warehouse_id']?>">
            <input type="text" name="decided[<?= $detail['warehouse_id']?>][<?= $detail['item_id']?>]" style="height: 25px;" class="form-control decided_quantity" value="" />
        </td>
    </tr>
<?php endforeach;?>
