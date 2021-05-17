<table id="order-item-table" class="table display dataTable text-right strong table-responsive">     
    <tr>
        <td><?php echo app_lang("sub_total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($order_total_summary->order_subtotal); ?></td>
        <td style="width: 100px;"> </td>
    </tr>

    <?php
    $discount_row = "<tr>
                        <td style='padding-top:13px;'>" . app_lang("discount") . "</td>
                        <td style='padding-top:13px;'>" . to_currency($order_total_summary->discount_total) . "</td>
                        <td class='text-center option w100'>" . modal_anchor(get_uri("orders/discount_modal_form"), "<i data-feather='edit-2' class='icon-16'></i>", array("class" => "edit", "data-post-order_id" => $order_id, "title" => app_lang('edit_discount'))) . "<span class='p20'>&nbsp;&nbsp;&nbsp;</span></td>
                    </tr>";

    if ($order_total_summary->order_subtotal && (!$order_total_summary->discount_total || ($order_total_summary->discount_total !== 0 && $order_total_summary->discount_type == "before_tax"))) {
        //when there is discount and type is before tax or no discount
        echo $discount_row;
    }
    ?>

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

    <?php
    if ($order_total_summary->discount_total && $order_total_summary->discount_type == "after_tax") {
        //when there is discount and type is after tax
        echo $discount_row;
    }
    ?>

    <tr>
        <td><?php echo app_lang("total"); ?></td>
        <td><?php echo to_currency($order_total_summary->order_total); ?></td>
        <td></td>
    </tr>
</table>