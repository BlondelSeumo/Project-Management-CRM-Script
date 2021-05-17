<script>

    //adjust height of items container
    function adjustHeightOfItemsContainer() {
        $('.js-left-menu-scrollbar').each(function () {
            var columnHeight = $(window).height() - 320;
            if ($(this).attr("id") === "menu-item-list-2") {
                columnHeight = columnHeight - 80;
            } else if ($(this).attr("id") === "left-menu-preview") {
                columnHeight = columnHeight - 15;
            }

            if ($("#js-left-menu-customization-area").height() > $(window).height() - 175) {
                $(this).height(columnHeight).addClass("overflow-y-scroll");
            }
        });
    }

    //initialize sortable
    function initSortable() {
        $(".menu-item-list").each(function (index) {
            var id = this.id;

            var options = {
                animation: 150,
                group: "menu-item-list",
                chosenClass: "sortable-chosen",
                ghostClass: "sortable-ghost",
                filter: ".empty-area-text",
                cancel: ".empty-area-text",
                onAdd: function (e) {
                    //moved to the new column. save the items position
                    saveItemsPosition();

                    removeEmptyAreaText(e.to);
                    addEmptyAreaText(e.from);
                    adjustHeightOfItemsContainer();
                    removeSubmenuClass(e.item, e.to);
                },
                onUpdate: function (e) {
                    //moved to the same column. save the items position
                    saveItemsPosition();
                    adjustHeightOfItemsContainer();
                    removeSubmenuClass("", "");
                }
            };

            Sortable.create($("#" + id)[0], options);
        });
    }

    //remove submenu class on sorting
    function removeSubmenuClass(item, area) {
        if ($(item).hasClass("ml20")) {
            if ($(area).hasClass("available-items-container")) {
                $(item).removeClass("ml20");
            }
        }

        //remove as sub menu of the first item always
        $(".sortable-items-container div:first-child").removeClass("ml20");
    }

    //remove drag/drop text from new added area if there is no elements available
    function removeEmptyAreaText(index) {
        if ($(index).has("div").length > 0) {
            $(index).find("span.empty-area-text").remove();
        }
    }

    //add drag/drop text from removed area if there is no elements available
    function addEmptyAreaText(index) {
        if ($(index).has("div").length < 1) {
            if ($(index).hasClass("available-items-container")) {
                //if it's items container area
                $(index).html("<span class='text-off empty-area-text'><?php echo app_lang('no_more_items_available'); ?></span>");
            } else {
                //if it's items sortable area
                $(index).html("<span class='text-off empty-area-text'><?php echo app_lang('drag_and_drop_items_here'); ?></span>");
            }
        }
    }

    //save the items position
    function saveItemsPosition() {
        var items = [];

        $("#menu-item-list-2 .left-menu-item").each(function () {
            var item = $(this).attr("data-value");

            if (item) {
                var itemObject = {name: item};

                //sub menu
                if ($(this).hasClass("ml20")) {
                    itemObject["is_sub_menu"] = "1";
                }

                //custom menu item
                if ($(this).attr("data-url")) {
                    itemObject["url"] = $(this).attr("data-url");
                    itemObject["icon"] = $(this).attr("data-icon");
                    itemObject["open_in_new_tab"] = $(this).attr("data-open_in_new_tab");
                }

                items.push(itemObject);
            }
        });

        //convert array to json data and save into an input field
        if (Object.keys(items).length) {
            $("#items-data").val(JSON.stringify(items));
        } else {
            $("#items-data").val("");
        }
    }

    //prepare sortable menu item dom
    function addOrUpdateCustomMenuItem(item_data) {
        if (window.customMenuItemTempId) {
            //update operation
            var $item = $(".sortable-items-container").find("[data-custom_menu_item_id='" + window.customMenuItemTempId + "']"),
                    $previousItem = $item.prev();

            $item.remove(); //remove old item
            $previousItem.after(item_data); //append after it's previous item

            window.customMenuItemTempId = "";
        } else {
            //insert operation
            $(".sortable-items-container").append(item_data);
        }
    }

    $(document).ready(function () {
        //initialize sortable
        initSortable();

        $("#left-menu-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.redirect_to) {
                    window.location.href = result.redirect_to;
                } else {
                    location.reload();
                }
            }
        });

        saveItemsPosition();
        adjustHeightOfItemsContainer();
        $(window).resize(function () {
            adjustHeightOfItemsContainer();
        });

        //delete left menu item
        $('body').on('click', '.delete-left-menu-item', function () {
            //restore the selected item to item container
            var $item = $(this).closest(".left-menu-item"),
                    itemClone = $item.clone();

            if (!$item.attr("data-url")) {
                //don't restore custom menu item
                itemClone.removeClass("ml20");
                $(".available-items-container").append(itemClone);
            }

            //remove drag/drop text from item container
            removeEmptyAreaText($(".available-items-container"));

            //remove the row finally
            $item.fadeOut(300, function () {
                $item.remove();

                saveItemsPosition();
                addEmptyAreaText($(".sortable-items-container"));
            });

            adjustHeightOfItemsContainer();
        });

        //make sub menu of it's previous item
        $('body').on('click', '.make-sub-menu', function () {
            var $item = $(this).closest(".left-menu-item");
            $item.toggleClass("ml20");

            saveItemsPosition();
        });

        //store the custom menu item temp id for update operation
        $('body').on('click', '.custom-menu-edit-button', function () {
            window.customMenuItemTempId = $(this).closest(".left-menu-item").attr("data-custom_menu_item_id");
        });

        //remove the temp id after clicking on add button
        $('body').on('click', '.custom-menu-item-add-button', function () {
            window.customMenuItemTempId = "";
        });
    });
</script>