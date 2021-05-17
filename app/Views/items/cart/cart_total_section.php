<div class="strong">
    <div class="clearfix mb5">
        <div class="float-start"><?php echo app_lang("sub_total"); ?></div>
        <div class="float-end"><?php echo to_currency($order_total_summary->order_subtotal); ?></div>
    </div>

    <?php if ($order_total_summary->tax) { ?>
        <div class="clearfix mb5">
            <div class="float-start"><?php echo $order_total_summary->tax_name; ?></div>
            <div class="float-end"><?php echo to_currency($order_total_summary->tax); ?></div>
        </div>
    <?php } ?>
    <?php if ($order_total_summary->tax2) { ?>
        <div class="clearfix mb5">
            <div class="float-start"><?php echo $order_total_summary->tax_name2; ?></div>
            <div class="float-end"><?php echo to_currency($order_total_summary->tax2); ?></div>
        </div>
    <?php } ?>

    <div class="clearfix">
        <div class="float-start"><?php echo app_lang("total"); ?></div>
        <div class="float-end cart-total-value" data-value="<?php echo $order_total_summary->order_total; ?>"><?php echo to_currency($order_total_summary->order_total); ?></div>
    </div>
</div>