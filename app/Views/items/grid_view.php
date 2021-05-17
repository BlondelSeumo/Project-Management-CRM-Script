<div id="page-content" class="page-wrapper clearfix">
    <div class="clearfix mb15">
        <h4 class="float-start"><?php echo app_lang('store'); ?></h4>
        <div class="float-end">
            <?php
            echo form_input(array(
                "id" => "item-search-box",
                "class" => "form-control custom-filter-search item-search-box",
                "placeholder" => app_lang('search'),
            ));
            ?>
        </div>
        <div class="float-end custom-toolbar">
            <?php
            echo form_input(array(
                "id" => "item-categories-filter",
                "name" => "item-categories-filter",
                "class" => "select2 w200 mr15"
            ));
            ?>
        </div>
    </div>

    <div class="row" id="items-container">
        <?php echo view("items/items_grid_data"); ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        var $searchBox = $("#item-search-box");

        $searchBox.on("keyup", function (e) {
            if (!(e.which >= 37 && e.which <= 40)) {
                //witi 200 ms to request ajax cll
                clearTimeout($.data(this, 'timer'));
                var wait = setTimeout(getItemSuggestions, 200);
                $(this).data('timer', wait);
            }
        });

        var $itemCategoriesFilter = $("#item-categories-filter");
        $itemCategoriesFilter.select2({
            data: <?php echo $categories_dropdown; ?>
        }).on("change", function () {
            getItemSuggestions();
        });

        function getItemSuggestions() {
            appLoader.show();

            $.ajax({
                url: "<?php echo get_uri('items/grid_view/'); ?>",
                data: {search: $searchBox.val(), item_search: true, category_id: $itemCategoriesFilter.val()},
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    appLoader.hide();

                    if (response.success) {
                        $("#items-container").html(response.data);
                    }
                }
            });
        }

        $("body").on("click", ".item-add-to-cart-btn", function () {
            var itemId = $(this).attr("data-item_id"),
                    $instance = $(this);
            appLoader.show();

            //add item to the order items table and show count on cart box
            $.ajax({
                url: "<?php echo get_uri('items/add_item_to_cart'); ?>" + "/" + itemId,
                data: {id: itemId},
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    appLoader.hide();

                    if (response.success) {
                        //reload cart value
                        window.countCartItems();

                        //change the selector when it's added from view modal
                        if ($("#ajaxModal").hasClass('in')) {
                            $instance = $("#items-container").find("[data-item_id='" + itemId + "']");
                            $("#ajaxModal").modal('hide');
                        }

                        //change button text
                        $instance.text("<?php echo app_lang("added_to_cart"); ?>");
                        $instance.removeClass("item-add-to-cart-btn");
                        $instance.attr("disabled", "disabled");
                    }
                }
            });
        });

        window.refreshAfterUpdate = true;
    });
</script>