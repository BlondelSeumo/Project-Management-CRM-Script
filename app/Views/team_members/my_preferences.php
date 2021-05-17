<div class="tab-content">
    <?php
    $user_id = $login_user->id;
    echo form_open(get_uri("team_members/save_my_preferences/"), array("id" => "my-preferences-form", "class" => "general-form dashed-row white", "role" => "form"));
    ?>
    <div class="card">
        <div class=" card-header">
            <h4> <?php echo app_lang('my_preferences'); ?></h4>
        </div>
        <div class="card-body">

            <div class="form-group">
                <div class="row">
                    <label for="notification_sound_volume" class=" col-md-2"><?php echo app_lang('notification_sound_volume'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_dropdown(
                                "notification_sound_volume", array(
                            "0" => "-",
                            "1" => "|",
                            "2" => "||",
                            "3" => "|||",
                            "4" => "||||",
                            "5" => "|||||",
                            "6" => "||||||",
                            "7" => "|||||||",
                            "8" => "||||||||",
                            "9" => "|||||||||",
                                ), get_setting('user_' . $user_id . '_notification_sound_volume'), "class='select2 mini'"
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="enable_web_notification" class=" col-md-2"><?php echo app_lang('enable_web_notification'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_dropdown(
                                "enable_web_notification", array(
                            "1" => app_lang("yes"),
                            "0" => app_lang("no")
                                ), $user_info->enable_web_notification, "class='select2 mini' id='enable-web-notification'"
                        );
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group <?php echo get_setting("enable_push_notification") && $user_info->enable_web_notification ? '' : 'hide'; ?>" id="disable-push-notification-area">
                <div class="row">
                    <label for="disable_push_notification" class="col-md-2"><?php echo app_lang('disable_push_notification'); ?></label>
                    <div class="col-md-10 mt5">
                        <?php
                        $push_notification = get_setting('user_' . $user_id . '_disable_push_notification');
                        $push_notification = $push_notification ? $push_notification : "0";

                        echo form_dropdown(
                                "disable_push_notification", array(
                            "1" => app_lang("yes"),
                            "0" => app_lang("no")
                                ), $push_notification, "class='select2 mini' id='disable_push_notification'"
                        );
                        ?>                       
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="enable_email_notification" class=" col-md-2"><?php echo app_lang('enable_email_notification'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_dropdown(
                                "enable_email_notification", array(
                            "1" => app_lang("yes"),
                            "0" => app_lang("no")
                                ), $user_info->enable_email_notification, "class='select2 mini'"
                        );
                        ?>
                    </div>
                </div>
            </div>

            <?php if (count($language_dropdown) && !get_setting("disable_language_selector_for_team_members")) { ?>
                <div class="form-group">
                    <div class="row">
                        <label for="personal_language" class=" col-md-2"><?php echo app_lang('language'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "personal_language", $language_dropdown, get_setting('user_' . $user_info->id . '_personal_language') ? get_setting('user_' . $user_info->id . '_personal_language') : get_setting("language"), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group">
                <div class="row">
                    <label for="hidden_topbar_menus" class=" col-md-2"><?php echo app_lang('hide_menus_from_topbar'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "hidden_topbar_menus",
                            "name" => "hidden_topbar_menus",
                            "value" => get_setting('user_' . $user_id . '_hidden_topbar_menus'),
                            "class" => "form-control",
                            "placeholder" => app_lang('hidden_topbar_menus')
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="disable_keyboard_shortcuts" class=" col-md-2"><?php echo app_lang('disable_keyboard_shortcuts'); ?></label>
                    <div class=" col-md-4">
                        <?php
                        $disable_keyboard_shortcuts = get_setting('user_' . $user_id . '_disable_keyboard_shortcuts');
                        $disable_keyboard_shortcuts = $disable_keyboard_shortcuts ? $disable_keyboard_shortcuts : "0";

                        echo form_dropdown(
                                "disable_keyboard_shortcuts", array(
                            "1" => app_lang("yes"),
                            "0" => app_lang("no")
                                ), $disable_keyboard_shortcuts, "class='select2 mini'"
                        );

                        echo modal_anchor(get_uri("team_members/keyboard_shortcut_modal_form/"), "<i data-feather='info' class='icon-16'></i>", array("class" => "btn btn-default keyboard-shortcut-info-icon ml10 float-end", "title" => app_lang('keyboard_shortcuts_info'), "data-post-user_id" => $login_user->id));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="recently_meaning" class=" col-md-2"><?php echo app_lang('recently_meaning'); ?></label>
                    <div class=" col-md-3 mt5">
                        <?php
                        $recently_meaning = get_setting("user_" . $login_user->id . "_recently_meaning");
                        echo form_dropdown("recently_meaning", $recently_meaning_dropdown, $recently_meaning ? $recently_meaning : "1_days", "class='select2 mini'");
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer rounded-0">
            <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#my-preferences-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#my-preferences-form .select2").select2();

        $("#hidden_topbar_menus").select2({
            multiple: true,
            data: <?php echo ($hidden_topbar_menus_dropdown); ?>
        });

        $("#enable-web-notification").select2().on("change", function () {
            var value = $(this).val();
            if (value === "1") {
<?php if (get_setting("enable_push_notification")) { ?>
                    $("#disable-push-notification-area").removeClass("hide");
<?php } ?>
            } else {
                $("#disable-push-notification-area").addClass("hide");
            }
        });
    });
</script>    