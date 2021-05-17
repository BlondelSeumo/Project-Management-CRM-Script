<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "timesheets";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_timesheets_settings"), array("id" => "timesheet-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="card">
                <div class=" card-header">
                    <h4><?php echo app_lang("timesheet_settings"); ?></h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <label for="users_can_start_multiple_timers_at_a_time" class="col-md-3 col-xs-8 col-sm-4"><?php echo app_lang('users_can_start_multiple_timers_at_a_time'); ?></label>

                            <div class="col-md-9 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("users_can_start_multiple_timers_at_a_time", "1", get_setting("users_can_start_multiple_timers_at_a_time") ? true : false, "id='users_can_start_multiple_timers_at_a_time' class='form-check-input ml15'");
                                ?> 
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="users_can_input_only_total_hours_instead_of_period" class="col-md-3 col-xs-8 col-sm-4"><?php echo app_lang('users_can_input_only_total_hours_instead_of_period'); ?></label>
                            <div class="col-md-9 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("users_can_input_only_total_hours_instead_of_period", "1", get_setting("users_can_input_only_total_hours_instead_of_period") ? true : false, "id='users_can_input_only_total_hours_instead_of_period' class='form-check-input ml15'");
                                ?>
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

<script type="text/javascript">
    $(document).ready(function () {
        $("#timesheet-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
            }
        });
    });
</script>