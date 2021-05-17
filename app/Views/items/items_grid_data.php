<?php
if ($items) {
    foreach ($items as $item) {
        ?>
        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body cart-grid-item p0">
                    <div class="cart-grid-item-image" style="background-image: url(<?php echo get_store_item_image($item->files); ?>)">
                        <span class="font-18 cart-grid-rate">
                            <strong><?php echo to_currency($item->rate); ?></strong><span class="text-off font-11"><?php echo $item->unit_type ? "/" . $item->unit_type : ""; ?></span>
                        </span>

                        <div class="cart-grid-item-details">
                            <div class="text-center">
                                <?php echo modal_anchor(get_uri("items/view"), "<span class='view-item-details-link-btn'>" . app_lang("view_details") . "</span>", array("data-modal-title" => app_lang("item_details"), "data-post-id" => $item->id)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="p15">
                        <div class="font-16 text-wrap-ellipsis strong"><?php echo $item->title; ?></div>
                        <div class="text-wrap-ellipsis mt5"><?php echo $item->description ? $item->description : "-"; ?></div>
                    </div>
                </div>
                <div class="card-footer bg-info no-border text-center p0">
                    <?php
                    if (isset($item->added_to_cart) && $item->added_to_cart) {
                        echo js_anchor(app_lang("added_to_cart"), array("class" => "btn btn-xs btn-info text-white p15 w100p", "data-item_id" => $item->id, "disabled" => "disabled"));
                    } else {
                        echo js_anchor(app_lang("add_to_cart"), array("class" => "btn btn-xs btn-info text-white item-add-to-cart-btn p15 w100p", "data-item_id" => $item->id));
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="text-center">
        <?php
        if ($result_remaining > 0) {
            echo ajax_anchor(get_uri("items/grid_view/" . $next_page_offset . "/20/" . $category_id . "/" . $search), app_lang("load_more"), array("class" => "btn btn-default mt15 mb15 round pl15 spinning-btn", "title" => app_lang("load_more"), "data-inline-loader" => "1", "data-closest-target" => "#items-container", "data-append" => true));
        }
        ?>
    </div>

    <?php
} else {
    ?>
    <div class="text-center box" style="height: 400px;">
        <div class="box-content" style="vertical-align: middle"> 
            <div class="mb15"><?php echo app_lang("item_empty_message"); ?></div>
            <span data-feather="frown" height="8rem" width="8rem" style="color:#d8d8d8"></span>
        </div>
    </div>  
<?php } ?>

