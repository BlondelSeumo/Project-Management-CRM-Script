<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "footer";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_footer_settings"), array("id" => "footer-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="card">
                <div class=" card-header">
                    <h4><?php echo app_lang("footer"); ?></h4>
                </div>
                <div class="card-body">
                    <input type="hidden" id="footer-menus-data" name="footer_menus" value="" />

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <i data-feather="info" class="icon-16"></i> <?php echo app_lang("footer_description_message"); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="enable_footer" class=" col-md-2"><?php echo app_lang('enable_footer'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_checkbox("enable_footer", "1", get_setting("enable_footer") ? true : false, "id='enable_footer' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div id="footer-details-area" class="<?php echo get_setting("enable_footer") ? "" : "hide"; ?>">
                        <div class="form-group" id="footer-menu-input-area">
                            <div class="row">
                                <label for="footer_menus" class=" col-md-2"><?php echo app_lang('footer_menus'); ?></label>
                                <div class="col-md-10">
                                    <div id="footer-menus-show-area">
                                        <?php echo $footer_menus; ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            echo form_input(array(
                                                "id" => "menu_name",
                                                "name" => "menu_name",
                                                "class" => "form-control",
                                                "placeholder" => app_lang('menu_name')
                                            ));
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            echo form_input(array(
                                                "id" => "url",
                                                "name" => "url",
                                                "class" => "form-control",
                                                "placeholder" => "URL"
                                            ));
                                            ?>
                                        </div>
                                        <div id="footer-menus-options-area" class="col-md-12 mt15 hide">
                                            <button id="footer-menus-add-button" type="button" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('add'); ?></button> 
                                            <button id="footer-menus-close-button" type="button" class="btn btn-default"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('cancel'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label for="footer_copyright_text" class=" col-md-2"><?php echo app_lang('footer_copyright_text'); ?></label>
                                <div class="col-md-10">
                                    <?php
                                    echo form_input(array(
                                        "id" => "footer_copyright_text",
                                        "name" => "footer_copyright_text",
                                        "value" => get_setting("footer_copyright_text"),
                                        "class" => "form-control",
                                        "placeholder" => app_lang('footer_copyright_text')
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>

                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#footer-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        //save positions for first time
        setTimeout(function () {
            saveMenusPosition();
        }, 300);

        //show/hide details area
        $("#enable_footer").click(function () {
            if ($(this).is(":checked")) {
                $("#footer-details-area").removeClass("hide");
            } else {
                $("#footer-details-area").addClass("hide");
            }
        });

        var $footerMenusShowArea = $("#footer-menus-show-area"),
                $footerMenusOptionsArea = $("#footer-menus-options-area"),
                $addBtn = $("#footer-menus-add-button"),
                $closeBtn = $("#footer-menus-close-button");

        //show save & cancel button when the input is focused
        $("#menu_name, #url").focus(function () {
            $footerMenusOptionsArea.removeClass("hide");
        });

        //add menu
        $addBtn.click(function () {
            var menuName = $("#menu_name").val(),
                    url = $("#url").val();

            if (menuName && url) {
                $.ajax({
                    url: "<?php echo get_uri('settings/save_footer_menu') ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {menu_name: menuName, url: url},
                    success: function (result) {
                        if (result.success) {
                            $footerMenusShowArea.append(result.data);

                            $("#menu_name").val("").focus();
                            $("#url").val("");

                            saveMenusPosition();
                        }
                    }
                });
            }
        });

        //close options
        $closeBtn.click(function () {
            $footerMenusOptionsArea.addClass("hide");
        });

        //delete
        $("body").on("click", ".footer-menu-delete-btn", function () {
            $(this).closest("div.footer-menu-item").fadeOut(300, function () {
                $(this).closest("div.footer-menu-item").remove();

                saveMenusPosition();
            });
        });

        //store the temp id for update operation
        $("body").on("click", ".footer-menu-item .footer-menu-edit-btn", function () {
            window.footerMenuItemTempId = $(this).closest(".footer-menu-item").attr("data-footer_menu_temp_id");
        });

        //make the menus sortable
        var $selector = $("#footer-menus-show-area");
        Sortable.create($selector[0], {
            animation: 150,
            chosenClass: "sortable-chosen",
            ghostClass: "sortable-ghost",
            onUpdate: function (e) {
                saveMenusPosition();
            }
        });

        $("#footer-menu-input-area input").keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                $addBtn.trigger("click");
            }
        });
    });

    function saveMenusPosition() {
        var menus = [];

        $("#footer-menus-show-area .footer-menu-item").each(function (index) {
            var menuName = $(this).find("a").text(),
                    url = $(this).find("a").attr("href");

            if (menuName && url) {
                menus.push({menu_name: menuName, url: url});
            }
        });

        //convert array to json data and save into an input field
        $("#footer-menus-data").val(JSON.stringify(menus));
    }
</script>