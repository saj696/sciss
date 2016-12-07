<?php
/**
 * Created by PhpStorm.
 * User: JR
 * Date: 02-Oct-16
 * Time: 11:05 AM
 */
?>
<?php $key = 0; ?>
<tr class="main_tr" data-invoice-id="<?= $dropArray['id']?>" data-due="<?= $dropArray['due'] ?>">
    <td><?= $this->Number->format($key + 1) ?></td>
    <td><?= date('d-m-y',$dropArray['invoice_date']) ?><input type="hidden" name="invoice_details[<?= $dropArray['id'] ?>][invoice_date]" value="<?= $dropArray['invoice_date']?>"></td>
    <td><?= $dropArray['net_total'] ?><input type="hidden" name="invoice_details[<?= $dropArray['id'] ?>][net_total]"  value="<?= $dropArray['net_total']?>"></td>
    <td><?= $dropArray['net_total'] - $dropArray['due'] ?></td>
    <td><?= $dropArray['due'] ?><input type="hidden" name="invoice_details[<?= $dropArray['id'] ?>][due]" class="due_hidden" value="<?= $dropArray['due']?>"></td>
    <td><input type="text" name="invoice_details[<?= $dropArray['id'] ?>][current_payment]" class="form-control current_payment" readonly/></td>
    <td width="50px;"><span class="btn btn-sm btn-circle btn-danger remove pull-right">X</span></td>
</tr>
