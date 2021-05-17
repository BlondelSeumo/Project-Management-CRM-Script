<?php if ($items) { ?>
    <div class="rise-cart-body">
        <?php
        foreach ($items as $item) {
            ?>
            <div class='js-item-row float-start message-row cart-item-row clearfix w100p' data-id='<?php echo $item->id; ?>' data-original_item_id="<?php echo $item->item_id; ?>">
                <?php echo view("items/cart/cart_item_data", array("item" => $item)); ?>
            </div>

        <?php }
        ?>

        <div id="cart-total-section" class="cart-item-row float-start message-row clearfix w100p cart-total no-border mt5 mb5">
            <?php echo view("items/cart/cart_total_section"); ?>
        </div>
    </div>

    <div class="p10 b-t">
        <?php echo anchor(get_uri("orders/process_order"), "<i data-feather='check-circle' class='icon-16'></i> " . app_lang("process_order"), array("class" => "btn btn-info text-white col-md-12 col-xs-12 col-sm-12")); ?>
    </div>
    <?php
} else {
    ?>

    <div class="chat-no-messages text-off text-center">
        <i data-feather="shopping-bag" height="4rem" width="4rem"></i><br />
        <?php echo app_lang("no_items_text"); ?>
    </div>

    <div class="text-center mt15">
        <?php echo anchor(get_uri("orders/process_order"), "<i data-feather='check-circle' class='icon-16'></i> " . app_lang("create_new_order"), array("class" => "btn btn-info")); ?>
    </div>

<?php } ?>