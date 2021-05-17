<div class="box">
    <div class="box-content w50 cart-item-details">
        <div class='cart-item-image-container'>
            <div class="cart-item-image" style='background-image: url(<?php echo get_store_item_image($item->files); ?>)'></div>
        </div>
    </div>

    <div class="box-content cart-item-details-container cart-item-details">
        <div class="ml15">
            <strong><?php echo $item->title; ?></strong>
            <div class='text-off'><?php echo nl2br($item->description); ?></div>
        </div>
    </div>

    <div class="box-content cart-item-details">
        <div class="w85 text-center mr15">
            <div class="inline-block clearfix">
                <?php echo js_anchor('<i data-feather="minus" class="icon-16"></i>', array("class" => "cart-item-quantity-btn float-start mr10 mt5", "data-action" => "minus")); ?>
                <div class="b-a w cart-item-quantity float-start" data-quantity="<?php echo $item->quantity; ?>"><?php echo $item->quantity; ?></div>
                <?php echo js_anchor('<i data-feather="plus" class="icon-16"></i>', array("class" => "cart-item-quantity-btn float-start ml10 mt5", "data-action" => "plus")); ?>
            </div>
        </div>
    </div>

    <div class="box-content cart-item-details text-right">
        <strong><?php echo to_currency($item->total); ?></strong>
    </div>
</div>
