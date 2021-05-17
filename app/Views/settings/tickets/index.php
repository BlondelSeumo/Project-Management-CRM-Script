<?php echo form_open(get_uri("settings/save_ticket_settings"), array("id" => "ticket-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
<div class="card mb0">

    <div class="card-body">
        <div class="form-group">
            <div class="row">
                <label for="show_recent_ticket_comments_at_the_top" class="col-md-4 col-xs-8 col-sm-4"><?php echo app_lang('show_most_recent_ticket_comments_at_the_top'); ?></label>
                <div class="col-md-8 col-xs-4 col-sm-8">
                    <?php
                    echo form_checkbox("show_recent_ticket_comments_at_the_top", "1", get_setting("show_recent_ticket_comments_at_the_top") ? true : false, "id='show_recent_ticket_comments_at_the_top' class='form-check-input ml15'");
                    ?>                       
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="project_reference_in_tickets" class="col-md-4 col-xs-8 col-sm-4"><?php echo app_lang('project_reference_in_tickets'); ?></label>
                <div class="col-md-8 col-xs-4 col-sm-8">
                    <?php
                    echo form_checkbox("project_reference_in_tickets", "1", get_setting("project_reference_in_tickets") ? true : false, "id='project_reference_in_tickets' class='form-check-input ml15'");
                    ?>                       
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="ticket_prefix" class=" col-md-4"><?php echo app_lang('ticket_prefix'); ?></label>
                <div class=" col-md-8">
                    <?php
                    echo form_input(array(
                        "id" => "ticket_prefix",
                        "name" => "ticket_prefix",
                        "value" => get_setting("ticket_prefix"),
                        "class" => "form-control",
                        "placeholder" => app_lang('ticket_prefix')
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="auto_close_ticket_after" class="col-md-4"><?php echo app_lang('auto_close_ticket_after'); ?>  <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('cron_job_required'); ?>"><i data-feather='help-circle' class="icon-16"></i></span></label>
                <div class="col-md-8">
                    <?php
                    echo form_input(array(
                        "id" => "auto_close_ticket_after",
                        "name" => "auto_close_ticket_after",
                        "type" => "number",
                        "value" => get_setting("auto_close_ticket_after"),
                        "class" => "form-control mini float-start",
                        "min" => 0
                    ));
                    ?>
                    <label class="mt5 ml10 float-start"><?php echo app_lang('days'); ?></label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="auto_reply_to_tickets" class="col-md-4"><?php echo app_lang('auto_reply_to_tickets'); ?></label>
                <div class="col-md-8">
                    <?php
                    echo form_checkbox("auto_reply_to_tickets", "1", get_setting("auto_reply_to_tickets") ? true : false, "id='auto_reply_to_tickets' class='form-check-input ml15'");
                    ?>
                </div>
                <div class="ms-auto col-md-8 mt15 <?php echo get_setting('auto_reply_to_tickets') ? "" : "hide"; ?>" id="auto_reply_to_tickets_message">
                    <?php
                    echo form_textarea(array(
                        "id" => "auto_reply_to_tickets_message",
                        "name" => "auto_reply_to_tickets_message",
                        "value" => get_setting("auto_reply_to_tickets_message"),
                        "class" => "form-control",
                        "placeholder" => app_lang('write_a_comment'),
                        "data-rich-text-editor" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="enable_embedded_form_to_get_tickets" class="col-md-4"><?php echo app_lang('enable_embedded_form_to_get_tickets'); ?></label>
                <div class="col-md-8">
                    <?php
                    echo form_checkbox("enable_embedded_form_to_get_tickets", "1", get_setting("enable_embedded_form_to_get_tickets") ? true : false, "id='enable_embedded_form_to_get_tickets' class='form-check-input ml15'");
                    ?>
                    <span class="ml10 <?php echo get_setting('enable_embedded_form_to_get_tickets') ? "" : "hide"; ?>" id="external_form_embedded_url">
                        <?php echo modal_anchor(get_uri("external_tickets/embedded_code_modal_form"), "<i data-feather='code' class='icon-16'></i>", array("title" => app_lang('embed'), "class" => "edit external-tickets-embedded-code")) ?>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><span data-feather='check-circle' class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    </div>

</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ticket-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        //show/hide auto reply to new tickets message area
        $("#auto_reply_to_tickets").click(function () {
            if ($(this).is(":checked")) {
                $("#auto_reply_to_tickets_message").removeClass("hide");
            } else {
                $("#auto_reply_to_tickets_message").addClass("hide");
            }
        });

        //show/hide external tickets area
        $("#enable_embedded_form_to_get_tickets").click(function () {
            if ($(this).is(":checked")) {
                $("#external_form_embedded_url").removeClass("hide");
            } else {
                $("#external_form_embedded_url").addClass("hide");
            }
        });

        $('[data-bs-toggle="tooltip"]').tooltip();

    });
</script>