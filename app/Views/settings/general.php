<div id="page-content" class="page-wrapper clearfix">
    <div class="row">

        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "general";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_general_settings"), array("id" => "general-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="card">
                <div class="card-header">
                    <h4><?php echo app_lang("general_settings"); ?></h4>
                </div>
                <div class="card-body post-dropzone">
                    <div class="form-group">
                        <div class="row">
                            <label for="logo" class=" col-md-2"><?php echo app_lang('site_logo'); ?> (175x40) </label>
                            <div class=" col-md-10">
                                <div class="float-start mr15">
                                    <img id="site-logo-preview" src="<?php echo get_logo_url(); ?>" alt="..." style="width: 175px" />
                                </div>
                                <div class="float-start file-upload btn btn-default btn-sm">
                                    <i data-feather="upload" class="icon-14"></i> <?php echo app_lang("upload_and_crop"); ?>
                                    <input id="site_logo_file" class="cropbox-upload upload" name="site_logo_file" type="file" data-height="40" data-width="175" data-preview-container="#site-logo-preview" data-input-field="#site_logo" />
                                </div>
                                <div class="mt10 ml10 float-start">
                                    <?php
                                    echo form_upload(array(
                                        "id" => "site_logo_file_upload",
                                        "name" => "site_logo_file",
                                        "class" => "no-outline hidden-input-file"
                                    ));
                                    ?>
                                    <label for="site_logo_file_upload" class="btn btn-default btn-sm">
                                        <i data-feather="upload" class="icon-14"></i> <?php echo app_lang("upload"); ?>
                                    </label>
                                </div>
                                <input type="hidden" id="site_logo" name="site_logo" value=""  />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="favicon" class="col-md-2"><?php echo app_lang('favicon'); ?> (32x32)</label>
                            <div class="col-lg-10">
                                <div class="float-start mr15">
                                    <img id="favicon-preview" src="<?php echo get_favicon_url(); ?>" alt="..." style="width: 32px" />
                                </div>
                                <div class="float-start file-upload btn btn-default btn-sm">
                                    <i data-feather="upload" class="icon-14"></i> <?php echo app_lang("upload_and_crop"); ?>
                                    <input id="favicon_file" class="cropbox-upload upload" name="favicon_file" type="file" data-height="32" data-width="32" data-preview-container="#favicon-preview" data-input-field="#favicon" />
                                </div>
                                <div class="mt10 ml10 float-start">
                                    <?php
                                    echo form_upload(array(
                                        "id" => "favicon_file_upload",
                                        "name" => "favicon_file",
                                        "class" => "no-outline hidden-input-file"
                                    ));
                                    ?>
                                    <label for="favicon_file_upload" class="btn btn-default btn-sm">
                                        <i data-feather="upload" class="icon-14"></i> <?php echo app_lang("upload"); ?>
                                    </label>
                                </div>
                                <input type="hidden" id="favicon" name="favicon" value="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="show_logo_in_signin_page" class=" col-md-2"><?php echo app_lang('show_logo_in_signin_page'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "show_logo_in_signin_page", array(
                                    "no" => app_lang("no"),
                                    "yes" => app_lang("yes")
                                        ), get_setting('show_logo_in_signin_page'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="show_background_image_in_signin_page" class=" col-md-2"><?php echo app_lang('show_background_image_in_signin_page'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "show_background_image_in_signin_page", array(
                                    "no" => app_lang("no"),
                                    "yes" => app_lang("yes")
                                        ), get_setting('show_background_image_in_signin_page'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class=" col-md-2"><?php echo app_lang('signin_page_background'); ?></label>
                            <div class=" col-md-10">
                                <div class="float-start mr15">
                                    <img id="signin-background-preview" style="max-width: 100px; max-height: 80px;" src="<?php echo get_file_from_setting("signin_page_background"); ?>" alt="..." />
                                </div>
                                <div class="float-start mr15">
                                    <?php echo view("includes/dropzone_preview"); ?>    
                                </div>
                                <div class="float-start upload-file-button btn btn-default btn-sm">
                                    <span>...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="app_title" class=" col-md-2"><?php echo app_lang('app_title'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "app_title",
                                    "name" => "app_title",
                                    "value" => get_setting('app_title'),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('app_title'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="language" class=" col-md-2"><?php echo app_lang('language'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "language", $language_dropdown, get_setting('language'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="timezone" class=" col-md-2"><?php echo app_lang('timezone'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "timezone", $timezone_dropdown, get_setting('timezone'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="date_format" class=" col-md-2"><?php echo app_lang('date_format'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "date_format", array(
                                    "d-m-Y" => "d-m-Y",
                                    "m-d-Y" => "m-d-Y",
                                    "Y-m-d" => "Y-m-d",
                                    "d/m/Y" => "d/m/Y",
                                    "m/d/Y" => "m/d/Y",
                                    "Y/m/d" => "Y/m/d",
                                    "d.m.Y" => "d.m.Y",
                                    "m.d.Y" => "m.d.Y",
                                    "Y.m.d" => "Y.m.d",
                                        ), get_setting('date_format'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="time_format" class=" col-md-2"><?php echo app_lang('time_format'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "time_format", array(
                                    "capital" => "12 AM",
                                    "small" => "12 am",
                                    "24_hours" => "24 hours"
                                        ), get_setting('time_format'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="first_day_of_week" class=" col-md-2"><?php echo app_lang('first_day_of_week'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "first_day_of_week", array(
                                    "0" => app_lang("sunday"),
                                    "1" => app_lang("monday"),
                                    "2" => app_lang("tuesday"),
                                    "3" => app_lang("wednesday"),
                                    "4" => app_lang("thursday"),
                                    "5" => app_lang("friday"),
                                    "6" => app_lang("saturday")
                                        ), get_setting('first_day_of_week'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="weekends" class=" col-md-2"><?php echo app_lang('weekends'); ?></label>
                            <div class="col-md-10">
                                <?php
                                $days_dropdown = array(
                                    array("id" => "0", "text" => app_lang("sunday")),
                                    array("id" => "1", "text" => app_lang("monday")),
                                    array("id" => "2", "text" => app_lang("tuesday")),
                                    array("id" => "3", "text" => app_lang("wednesday")),
                                    array("id" => "4", "text" => app_lang("thursday")),
                                    array("id" => "5", "text" => app_lang("friday")),
                                    array("id" => "6", "text" => app_lang("saturday")),
                                );

                                echo form_input(array(
                                    "id" => "weekends",
                                    "name" => "weekends",
                                    "value" => get_setting("weekends"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('weekends')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="default_currency" class=" col-md-2"><?php echo app_lang('currency'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "default_currency", $currency_dropdown, get_setting('default_currency'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="currency_symbol" class=" col-md-2"><?php echo app_lang('currency_symbol'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "currency_symbol",
                                    "name" => "currency_symbol",
                                    "value" => get_setting('currency_symbol'),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('currency_symbol'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="currency_position" class=" col-md-2"><?php echo app_lang('currency_position'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "currency_position", array(
                                    "left" => app_lang("left"),
                                    "right" => app_lang("right")
                                        ), get_setting('currency_position'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="decimal_separator" class=" col-md-2"><?php echo app_lang('decimal_separator'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "decimal_separator", array("." => "Dot (.)", "," => "Comma (,)"), get_setting('decimal_separator'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="no_of_decimals" class=" col-md-2"><?php echo app_lang('no_of_decimals'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "no_of_decimals", array(
                                    "0" => "0",
                                    "2" => "2"
                                        ), get_setting('no_of_decimals') == "0" ? "0" : "2", "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="accepted_file_formats" class=" col-md-2"><?php echo app_lang('accepted_file_format'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "accepted_file_formats",
                                    "name" => "accepted_file_formats",
                                    "value" => get_setting('accepted_file_formats'),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('comma_separated'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="rows_per_page" class=" col-md-2"><?php echo app_lang('rows_per_page'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "rows_per_page", array(
                                    "10" => "10",
                                    "25" => "25",
                                    "50" => "50",
                                    "100" => "100",
                                        ), get_setting('rows_per_page'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="scrollbar" class=" col-md-2"><?php echo app_lang('scrollbar'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "scrollbar", array(
                                    "jquery" => "jQuery",
                                    "native" => "Native"
                                        ), get_setting('scrollbar'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>

                    <?php if (get_setting("disable_html_input")) { ?>
                        <!--flag the enable_rich_text_editor as disabled, when the disable_html_input is enabled-->
                        <input type="hidden" name="enable_rich_text_editor" value="no" />
                    <?php } else { ?>
                        <div class="form-group">
                            <div class="row">
                                <label for="enable_rich_text_editor" class=" col-md-2"><?php echo app_lang('enable_rich_text_editor'); ?></label>
                                <div class="col-md-10">
                                    <?php
                                    echo form_dropdown(
                                            "enable_rich_text_editor", array(
                                        "0" => app_lang("no"),
                                        "1" => app_lang("yes")
                                            ), get_setting('enable_rich_text_editor'), "class='select2 mini'"
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <div class="row">
                            <label for="rtl" class=" col-md-2"><?php echo app_lang('rtl'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "rtl", array(
                                    "0" => app_lang("no"),
                                    "1" => app_lang("yes")
                                        ), get_setting('rtl'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="item_purchase_code" class=" col-md-2"><?php echo app_lang('item_purchase_code'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "item_purchase_code",
                                    "name" => "item_purchase_code",
                                    "value" => get_setting('item_purchase_code') ? "******" : "",
                                    "class" => "form-control",
                                    "placeholder" => "Envato Purchase Code",
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="show_theme_color_changer" class=" col-md-2"><?php echo app_lang('show_theme_color_changer'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "show_theme_color_changer", array(
                                    "no" => app_lang("no"),
                                    "yes" => app_lang("yes")
                                        ), get_setting('show_theme_color_changer'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group color-plate" id="settings-color-plate">
                        <div class="row">
                            <label for="default_theme_color" class="col-md-2"><?php echo app_lang('default_theme_color'); ?></label>
                            <div class="col-md-10">
                                <?php echo get_custom_theme_color_list(); ?>
                                <input id="default-theme-color" type="hidden" name="default_theme_color" value="<?php echo get_setting("default_theme_color"); ?>" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php echo view("includes/cropbox"); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#general-settings-form .select2").select2();

        $("#general-settings-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "invoice_logo" || obj.name === "site_logo" || obj.name === "favicon") {
                        var image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = image;
                    }
                });
            },
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                if ($("#site_logo").val() || $("#invoice_logo").val() || $("#favicon").val() || result.reload_page) {
                    location.reload();
                }
            }
        });

        var uploadUrl = "<?php echo get_uri("settings/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("settings/validate_file"); ?>";

        var dropzone = attachDropzoneWithForm("#general-settings-form", uploadUrl, validationUrl, {maxFiles: 1});


        $(".cropbox-upload").change(function () {
            showCropBox(this);
        });

        var existingColor = "<?php echo get_setting("default_theme_color"); ?>";
        if (existingColor === "F2F2F2") {
            $("#settings-color-plate span:first-child").addClass("active");
        } else {
            $("#settings-color-plate").find("[data-color='" + existingColor + "']").addClass("active");
        }

        $("#settings-color-plate span").click(function () {
            $("#settings-color-plate span").removeClass("active");
            $(this).addClass("active");

            var color = $(this).attr("data-color");
            if (color) {
                $("#default-theme-color").val($(this).attr("data-color"));
            } else {
                $("#default-theme-color").val("F2F2F2");
            }
        });

        $("#weekends").select2({
            multiple: true,
            data: <?php echo (json_encode($days_dropdown)); ?>
        });

    });
</script>