<table id="order-item-table" class="mt0 table display dataTable text-right strong table-responsive">
    <tr>
        <td><?php echo app_lang("sub_total"); ?></td>
        <td style="width: 118px;"><?php echo to_currency($order_total_summary->order_subtotal); ?></td>
        <td style="width: 100px;"> </td>
    </tr>

    <?php if ($order_total_summary->tax) { ?>
        <tr>
            <td><?php echo $order_total_summary->tax_name; ?></td>
            <td><?php echo to_currency($order_total_summary->tax); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <?php if ($order_total_summary->tax2) { ?>
        <tr>
            <td><?php echo $order_total_summary->tax_name2; ?></td>
            <td><?php echo to_currency($order_total_summary->tax2); ?></td>
            <td></td>
        </tr>
    <?php } ?>

    <tr>
        <td><?php echo app_lang("total"); ?></td>
        <td><?php echo to_currency($order_total_summary->order_total); ?></td>
        <td></td>
    </tr>
</table>