<div class="card no-border clearfix mb0">

    <?php echo form_open(get_uri("settings/save_push_notification_settings"), array("id" => "pusher-form", "class" => "general-form dashed-row", "role" => "form")); ?>

    <div class="card-body">

        <div class="form-group">
            <div class="row">
                <label for="enable_push_notification" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('enable_push_notification'); ?></label>
                <div class="col-md-10 col-xs-4 col-sm-8">
                    <?php
                    echo form_checkbox("enable_push_notification", "1", get_setting("enable_push_notification") ? true : false, "id='enable_push_notification' class='form-check-input ml15'");
                    ?>
                </div>
            </div>
        </div>

        <div id="push-notification-details-area" class="<?php echo get_setting("enable_push_notification") ? "" : "hide" ?>">

            <div class="form-group">
                <div class="row">
                    <label for="enable_chat_via_pusher" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('enable_chat_via_pusher'); ?></label>
                    <div class="col-md-10 col-xs-4 col-sm-8">
                        <?php
                        echo form_checkbox("enable_chat_via_pusher", "1", get_setting("enable_chat_via_pusher") ? true : false, "id='enable_chat_via_pusher' class='form-check-input ml15'");
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="" class=" col-md-12">
                        <?php echo app_lang("get_your_app_credentials_from_here") . " " . anchor("https://pusher.com", "Pusher", array("target" => "_blank")); ?>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="pusher_app_id" class=" col-md-2"><?php echo app_lang('pusher_app_id'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "pusher_app_id",
                            "name" => "pusher_app_id",
                            "value" => get_setting("pusher_app_id"),
                            "class" => "form-control",
                            "placeholder" => app_lang('pusher_app_id'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="pusher_key" class=" col-md-2"><?php echo app_lang('pusher_key'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "pusher_key",
                            "name" => "pusher_key",
                            "value" => get_setting("pusher_key"),
                            "class" => "form-control",
                            "placeholder" => app_lang('pusher_key'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="pusher_secret" class=" col-md-2"><?php echo app_lang('pusher_secret'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "pusher_secret",
                            "name" => "pusher_secret",
                            "value" => get_setting("pusher_secret"),
                            "class" => "form-control",
                            "placeholder" => app_lang('pusher_secret'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="pusher_cluster" class=" col-md-2"><?php echo app_lang('pusher_cluster'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "pusher_cluster",
                            "name" => "pusher_cluster",
                            "value" => get_setting("pusher_cluster"),
                            "class" => "form-control",
                            "placeholder" => app_lang('pusher_cluster'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
            </div>

        </div>


    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        <?php if (get_setting("enable_push_notification") && get_setting("pusher_app_id") && get_setting("pusher_key") && get_setting("pusher_secret") && get_setting("pusher_cluster")) { ?>
            <button id="test-push-notification-btn" type="button" class="btn btn-info text-white ml15"><span data-feather="bell" class="icon-16"></span> <?php echo app_lang('test_push_notification'); ?></button>
        <?php } ?>
    </div>
    <?php echo form_close(); ?>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#pusher-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    if ($("#enable_push_notification").is(":checked")) {
                        window.location.href = "<?php echo_uri("settings/integration/push_notification"); ?>";
                    } else {
                        appAlert.success(result.message, {duration: 10000});
                    }
                }
            }
        });

        //show/hide push notification details area
        $("#enable_push_notification").click(function () {
            $("#test-push-notification-btn").addClass("hide");
            if ($(this).is(":checked")) {
                $("#push-notification-details-area").removeClass("hide");
            } else {
                $("#push-notification-details-area").addClass("hide");
            }
        });

        //show a demo push notification
        $("#test-push-notification-btn").click(function () {
            appLoader.show();
            $.ajax({
                url: '<?php echo_uri("settings/test_push_notification") ?>',
                type: "POST",
                dataType: "json",
                success: function (result) {
                    appLoader.hide();
                    if (!result.success) {
                        appAlert.error(result.message);
                    }
                }
            });
        });

    });
</script>