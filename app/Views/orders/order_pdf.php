<div style=" margin: auto;">
    <?php
    $color = get_setting("order_color");
    if (!$color) {
        $color = get_setting("invoice_color") ? get_setting("invoice_color") : "#2AA384";
    }
    $style = get_setting("invoice_style");
    ?>
    <?php
    $data = array(
        "client_info" => $client_info,
        "color" => $color,
        "order_info" => $order_info
    );
    if ($style === "style_2") {
        echo view('orders/order_parts/header_style_2.php', $data);
    } else {
        echo view('orders/order_parts/header_style_1.php', $data);
    }

    $discount_row = '<tr>
                        <td colspan="3" style="text-align: right;">' . app_lang("discount") . '</td>
                        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">' . to_currency($order_total_summary->discount_total) . '</td>
                    </tr>';
    ?>
</div>

<br />

<table class="table-responsive" style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 45%; border-right: 1px solid #eee;"> <?php echo app_lang("item"); ?> </th>
        <th style="text-align: center;  width: 15%; border-right: 1px solid #eee;"> <?php echo app_lang("quantity"); ?></th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo app_lang("rate"); ?></th>
        <th style="text-align: right;  width: 20%; "> <?php echo app_lang("total"); ?></th>
    </tr>
    <?php
    foreach ($order_items as $item) {
        ?>
        <tr style="background-color: #f4f4f4; ">
            <td style="width: 45%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
            </td>
            <td style="text-align: center; width: 15%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo to_currency($item->rate); ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo to_currency($item->total); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="3" style="text-align: right;"><?php echo app_lang("sub_total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($order_total_summary->order_subtotal); ?>
        </td>
    </tr>
    <?php
    if ($order_total_summary->discount_total && $order_total_summary->discount_type == "before_tax") {
        echo $discount_row;
    }
    ?>  
    <?php if ($order_total_summary->tax) { ?>
        <tr>
            <td colspan="3" style="text-align: right;"><?php echo $order_total_summary->tax_name; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($order_total_summary->tax); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($order_total_summary->tax2) { ?>
        <tr>
            <td colspan="3" style="text-align: right;"><?php echo $order_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($order_total_summary->tax2); ?>
            </td>
        </tr>
    <?php } ?>
    <?php
    if ($order_total_summary->discount_total && $order_total_summary->discount_type == "after_tax") {
        echo $discount_row;
    }
    ?> 
    <tr>
        <td colspan="3" style="text-align: right;"><?php echo app_lang("total"); ?></td>
        <td style="text-align: right; width: 20%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo to_currency($order_total_summary->order_total); ?>
        </td>
    </tr>
</table>
<?php if ($order_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br /><?php echo nl2br($order_info->note); ?></div>
<?php } else { ?><!-- use table to avoid extra spaces -->
    <br /><br /><table class="invoice-pdf-hidden-table" style="border-top: 2px solid #f2f2f2; margin: 0; padding: 0; display: block; width: 100%; height: 10px;"></table>
<?php } ?>

<span style="color:#444; line-height: 14px;">
    <?php echo get_setting("order_footer"); ?>
</span>

