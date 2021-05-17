<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "events";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_event_settings"), array("id" => "event-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="card">
                <div class="card-header">
                    <h4><?php echo app_lang("event_settings"); ?></h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <label for="enable_google_calendar_api" class=" col-md-3"><?php echo app_lang('enable_google_calendar_api'); ?> <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('cron_job_required'); ?>"><i data-feather='help-circle' class="icon-16"></i></span></label>

                            <div class="col-md-9">
                                <?php
                                echo form_checkbox("enable_google_calendar_api", "1", get_setting("enable_google_calendar_api") ? true : false, "id='enable_google_calendar_api' class='form-check-input ml15'");
                                ?> 
                                <span class="google-calendar-show-hide-text ml10 <?php echo get_setting("enable_google_calendar_api") ? "" : "hide" ?>"><i data-feather='alert-triangle' class="icon-16 text-warning"></i> <?php echo app_lang("now_every_user_can_integrate_with_their_google_calendar"); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather='check-circle' class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#event-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        $('[data-bs-toggle="tooltip"]').tooltip();

        //show/hide google calendar help text area
        $("#enable_google_calendar_api").click(function () {
            if ($(this).is(":checked")) {
                $(".google-calendar-show-hide-text").removeClass("hide");
            } else {
                $(".google-calendar-show-hide-text").addClass("hide");
            }
        });
    });
</script>