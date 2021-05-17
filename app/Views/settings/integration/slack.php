<div class="card no-border clearfix mb0">

    <?php echo form_open(get_uri("settings/save_slack_settings"), array("id" => "slack-form", "class" => "general-form dashed-row", "role" => "form")); ?>

    <div class="card-body">

        <div class="form-group">
            <div class="row">
                <label for="enable_slack" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('enable_slack'); ?></label>
                <div class="col-md-10 col-xs-4 col-sm-8">
                    <?php
                    echo form_checkbox("enable_slack", "1", get_setting("enable_slack") ? true : false, "id='enable_slack' class='form-check-input ml15'");
                    ?>
                </div>
            </div>
        </div>

        <div id="slack-details-area" class="<?php echo get_setting("enable_slack") ? "" : "hide" ?>">

            <div class="form-group">
                <div class="row">
                    <label for="" class=" col-md-12">
                        <?php echo app_lang("get_the_webhook_url_of_your_app_from_here") . " " . anchor("https://api.slack.com/apps", "Slack Apps", array("target" => "_blank")); ?>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="slack_webhook_url" class=" col-md-2"><?php echo app_lang('slack_webhook_url'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "slack_webhook_url",
                            "name" => "slack_webhook_url",
                            "value" => get_setting("slack_webhook_url"),
                            "class" => "form-control",
                            "placeholder" => app_lang('slack_webhook_url'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="slack_dont_send_any_projects" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('dont_send_any_project_related_notifications_to_this_channel'); ?></label>
                    <div class="col-md-10 col-xs-4 col-sm-8">
                        <?php
                        echo form_checkbox("slack_dont_send_any_projects", "1", get_setting("slack_dont_send_any_projects") ? true : false, "id='slack_dont_send_any_projects' class='form-check-input ml15'");
                        ?>
                    </div>
                </div>
            </div>

        </div>


    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        <?php if (get_setting("enable_slack") && get_setting("slack_webhook_url")) { ?>
            <button id="test-slack-btn" type="button" class="btn btn-info text-white ml15"><span data-feather="slack" class="icon-16"></span> <?php echo app_lang('send_a_test_message'); ?></button>
        <?php } ?>
    </div>
    <?php echo form_close(); ?>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#slack-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    if ($("#enable_slack").is(":checked")) {
                        window.location.href = "<?php echo_uri("settings/integration/slack"); ?>";
                    } else {
                        appAlert.success(result.message, {duration: 10000});
                    }
                }
            }
        });

        //show/hide slack details area
        $("#enable_slack").click(function () {
            $("#test-slack-btn").addClass("hide");
            if ($(this).is(":checked")) {
                $("#slack-details-area").removeClass("hide");
            } else {
                $("#slack-details-area").addClass("hide");
            }
        });

        //send a demo message
        $("#test-slack-btn").click(function () {
            appLoader.show();
            $.ajax({
                url: '<?php echo_uri("settings/test_slack_notification") ?>',
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