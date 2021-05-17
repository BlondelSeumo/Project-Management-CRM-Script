<?php
$can_order = false;

//load cart ui if order module is enabled
//and if the user is client or permitted member
if (get_setting("module_order")) {
    $permissions = $login_user->permissions;
    if ($login_user->user_type === "staff" && ($login_user->is_admin || get_array_value($permissions, "order"))) {
        $can_order = true;
    } else if ($login_user->user_type === "client" && get_setting("client_can_access_store")) {
        $can_order = true;
    }
}

if ($can_order) {
    ?>
    <div id="js-init-cart-icon" class="init-chat-icon init-cart-icon">
        <!-- data-type= open/close/has_item -->
        <span id="js-cart-min-icon" data-type="open" class="chat-min-icon"><i data-feather="shopping-bag" class='icon-16'></i></span>
    </div>

    <div id="js-rise-cart-wrapper" class="rise-chat-wrapper hide rise-cart-wrapper"></div>

    <script type="text/javascript">
        $(document).ready(function () {
            window.countCartItems();

            var cartIconContent = {
                "open": "<i data-feather='shopping-bag' class='icon-16'></i>",
                "close": "<span class='chat-close'>&times;</span>",
                "has_item": ""
            };

            setCartIcon = function (type, count) {
                $("#js-cart-min-icon").attr("data-type", type).html(count ? "<i data-feather='shopping-bag' class='icon-16'></i><span class='badge bg-danger up cart-badge'>" + count + "</span>" : cartIconContent[type]);

                if (type === "open") {
                    $("#js-rise-cart-wrapper").addClass("hide"); //hide cart box
                } else if (type === "close") {
                    $("#js-rise-cart-wrapper").removeClass("hide"); //show cart box
                }
            };

            //otherwise show the cart icon only
            var $cartIcon = $("#js-init-cart-icon");

            //if the chat icon is visible, show the cart icon beside the chat icon
            if ($("#js-init-chat-icon").length) {
                $cartIcon.css({right: "90px"});
                if (!$("#js-rise-chat-wrapper").hasClass("hide")) {
                    //chat box is open
                    $cartIcon.css({right: "390px"});
                }
            }

            $cartIcon.click(function () {
                $("#js-rise-cart-wrapper").html("");
                var $cartIcon = $("#js-cart-min-icon");
                var windowSize = window.matchMedia("(max-width: 767px)");

                if ($cartIcon.attr("data-type") !== "close") {
                    //have to reload
                    setTimeout(function () {
                        loadCartTabs();
                    }, 200);
                    setCartIcon("close"); //show close icon
                    if (windowSize.matches) {
                        changeCartIconPosition("close");
                    }
                } else {
                    //have to close the cart box
                    setCartIcon("open"); //show open icon
                    window.countCartItems();
                    if (windowSize.matches) {
                        changeCartIconPosition("open");
                    }
                }

                window.placeCartBox();
                
                feather.replace();
            });


            $("body").on("click", ".cart-item-quantity-btn", function () {
                var action = $(this).attr("data-action");
                var $itemRow = $(this).closest(".js-item-row");
                var itemId = $itemRow.attr("data-id");

                appLoader.show({container: "#js-rise-cart-wrapper", css: "bottom: 35px"});

                //if the action is minus and the quantity is 1 then remove the item
                if (action === "minus" && $itemRow.find(".cart-item-quantity").attr("data-quantity") === "1") {
                    deleteCartItem($itemRow, itemId);
                } else {
                    $.ajax({
                        url: "<?php echo get_uri('items/change_cart_item_quantity'); ?>",
                        cache: false,
                        type: 'POST',
                        data: {action: action, id: itemId},
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                $itemRow.html(response.data);
                                $("#cart-total-section").html(response.cart_total_view);
                                appLoader.hide();
                            }
                        }
                    });
                }

            });
        });

        changeCartIconPosition = function (type) {
            if (type === "close") {
                $("#js-init-cart-icon").addClass("move-cart-icon");
            } else if (type === "open") {
                $("#js-init-cart-icon").removeClass("move-cart-icon");
            }
        };

        function deleteCartItem($itemRow, itemId) {
            if (itemId) {
                $.ajax({
                    url: "<?php echo get_uri('items/delete_cart_item'); ?>",
                    cache: false,
                    type: 'POST',
                    data: {id: itemId},
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $itemRow.fadeOut(300, function () {
                                $itemRow.remove();
                                $("#cart-total-section").html(response.cart_total_view);

                                //reload cart to show empty message if there is no item
                                if (!$(".rise-cart-body div").find(".cart-total-value").attr("data-value")) {
                                    loadCartTabs();
                                }

                                //make item clickable again
                                var $gridItem = $("#items-container").find("[data-item_id='" + $itemRow.attr("data-original_item_id") + "']");
                                $gridItem.text("<?php echo app_lang("add_to_cart"); ?>");
                                $gridItem.addClass("item-add-to-cart-btn");
                                $gridItem.removeAttr("disabled");
                            });
                            appLoader.hide();
                        }
                    }
                });
            }
        }

        window.placeCartBox = function () {
            var $cartIcon = $("#js-init-cart-icon"),
                    $cartBox = $("#js-rise-cart-wrapper"),
                    cartBtnState = $("#js-cart-min-icon").attr("data-type");

            if ($("#js-init-chat-icon").length) {
                //so, the chat box icon is visible, check it's state
                if (cartBtnState === "open" || cartBtnState === "has_item") {
                    //cart box closed
                    //move back to it's previous position
                    if (!$("#js-rise-chat-wrapper").hasClass("hide")) {
                        //chat box is visible
                        $cartIcon.css({right: "390px"});
                    } else {
                        //chat box is closed
                        $cartIcon.css({right: "90px"});
                    }
                } else {
                    //cart box is open
                    if (!$("#js-rise-chat-wrapper").hasClass("hide")) {
                        //chat box is visible
                        $cartIcon.css({right: "390px"});
                        $cartBox.css({right: "450px"});
                    } else {
                        //chat box isn't visible
                        $cartBox.css({right: "150px"});

                        if (cartBtnState === "close") {
                            $cartIcon.css({right: "90px"});
                        }
                    }
                }
            }
        };

        function loadCartTabs() {
            setCartIcon("close"); //show close icon
            appLoader.show({container: "#js-rise-cart-wrapper", css: "bottom: 31%; right: 41%;"});

            $.ajax({
                url: "<?php echo get_uri("items/load_cart_items"); ?>",
                success: function (response) {
                    $("#js-rise-cart-wrapper").html(response);
                    appLoader.hide();
                }
            });
        }

        //count total items in the cart for the login client user
        window.countCartItems = function () {
            $.ajax({
                url: "<?php echo get_uri('items/count_cart_items'); ?>",
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.cart_items_count) {
                        window.prepareAddedItemsCartBox(response.cart_items_count);
                    }
                }
            });
        };

        //show total items count
        window.prepareAddedItemsCartBox = function (totalItems) {
            setCartIcon("has_item", totalItems); //show close icon

            //reload cart if it's shown
            if (!$("#js-rise-cart-wrapper").hasClass("hide")) {
                loadCartTabs();
                setCartIcon("close");
            }
        };

    </script>  


<?php } ?>