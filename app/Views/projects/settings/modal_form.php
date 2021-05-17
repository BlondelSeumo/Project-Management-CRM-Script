<?php echo form_open(get_uri("projects/save_settings"), array("id" => "project-settings-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix p30">
    <div class="container-fluid">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
        <input type="hidden" id="send_a_test_message" name="send_a_test_message" value="" />

        <?php if ($can_edit_timesheet_settings) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="client_can_view_timesheet" class="col-md-5"><?php echo app_lang('client_can_view_timesheet'); ?></label>
                    <div class="col-md-7">
                        <?php
                        echo form_checkbox("client_can_view_timesheet", "1", get_setting("client_can_view_timesheet") ? true : false, "id='client_can_view_timesheet' class='form-check-input'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($can_edit_slack_settings) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="project_enable_slack" class="col-md-5"><?php echo app_lang('enable_slack'); ?></label>
                    <div class="col-md-7">
                        <?php
                        echo form_checkbox("project_enable_slack", "1", get_setting("project_enable_slack") ? true : false, "id='project_enable_slack' class='form-check-input'");
                        ?>
                    </div>
                </div>
            </div>

            <div id="slack-details-area" class="<?php echo get_setting("project_enable_slack") ? "" : "hide" ?>">

                <div class="form-group">
                    <label for="" class=" col-md-12">
                        <?php echo app_lang("get_the_webhook_url_of_your_app_from_here") . " " . anchor("https://api.slack.com/apps", "Slack Apps", array("target" => "_blank")); ?>
                    </label>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="project_slack_webhook_url" class=" col-md-3"><?php echo app_lang('slack_webhook_url'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "project_slack_webhook_url",
                                "name" => "project_slack_webhook_url",
                                "value" => get_setting("project_slack_webhook_url"),
                                "class" => "form-control",
                                "placeholder" => app_lang('slack_webhook_url'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        <?php } ?>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>

    <?php if ($can_edit_slack_settings) { ?>
        <button id="test-slack-btn" type="button" class="btn btn-info text-white <?php echo (get_setting("project_enable_slack") && get_setting("project_slack_webhook_url")) ? "" : "hide"; ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_send_a_test_message'); ?></button>
    <?php } ?>

    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#project-settings-form").appForm({
        });

        //show/hide slack details area
        $("#project_enable_slack").click(function () {
            if ($(this).is(":checked")) {
                $("#slack-details-area").removeClass("hide");
                $("#test-slack-btn").removeClass("hide");
            } else {
                $("#slack-details-area").addClass("hide");
                $("#test-slack-btn").addClass("hide");
            }
        });

        //flag to send a test message
        $("#test-slack-btn").click(function () {
            $("#send_a_test_message").val("1");
            $("#project-settings-form").trigger("submit");
        });
    });
</script>    