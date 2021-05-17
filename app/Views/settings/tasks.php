<?php echo form_open(get_uri("settings/save_task_settings"), array("id" => "task-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>

<div class="card mb0">
    <div class="card-body">

        <div class="form-group">
            <div class="row">
                <label for="enable_recurring_option_for_tasks" class="col-md-3"><?php echo app_lang('enable_recurring_option_for_tasks'); ?> <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('cron_job_required'); ?>"><i data-feather='help-circle' class="icon-16"></i></span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown(
                            "enable_recurring_option_for_tasks", array("1" => app_lang("yes"), "0" => app_lang("no")), get_setting('enable_recurring_option_for_tasks') ? get_setting('enable_recurring_option_for_tasks') : 0, "class='select2 mini' id='enable_recurring_option_for_tasks'"
                    );
                    ?>                     
                </div>
            </div>
        </div>

        <div id="create_recurring_tasks_before_area" class="form-group <?php echo get_setting("enable_recurring_option_for_tasks") ? "" : "hide"; ?>">
            <div class="row">
                <label for="create_recurring_tasks_before" class=" col-md-3"><?php echo app_lang('create_recurring_tasks_before'); ?> <span class="help" data-bs-toggle="tooltip" data-placement="left" title="<?php echo app_lang('cron_job_required'); ?>"><i data-feather='help-circle' class="icon-16"></i></span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown(
                            "create_recurring_tasks_before", array(
                        "" => " - ",
                        "1" => "1 " . app_lang("day"),
                        "2" => "2 " . app_lang("days"),
                        "3" => "3 " . app_lang("days")
                            ), get_setting('create_recurring_tasks_before'), "class='select2 mini'"
                    );
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="project_task_deadline_pre_reminder" class=" col-md-3"><?php echo app_lang('send_task_deadline_pre_reminder'); ?> <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('cron_job_required'); ?>"><i data-feather='help-circle' class="icon-16"></i></span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown(
                            "project_task_deadline_pre_reminder", array(
                        "" => " - ",
                        "1" => "1 " . app_lang("day"),
                        "2" => "2 " . app_lang("days"),
                        "3" => "3 " . app_lang("days"),
                        "5" => "5 " . app_lang("days"),
                        "7" => "7 " . app_lang("days"),
                        "10" => "10 " . app_lang("days"),
                        "14" => "14 " . app_lang("days"),
                        "15" => "15 " . app_lang("days"),
                        "20" => "20 " . app_lang("days"),
                        "30" => "30 " . app_lang("days"),
                            ), get_setting('project_task_deadline_pre_reminder'), "class='select2 mini'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="project_task_reminder_on_the_day_of_deadline" class="col-md-3"><?php echo app_lang('send_task_reminder_on_the_day_of_deadline'); ?> <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('cron_job_required'); ?>"><i data-feather='help-circle' class="icon-16"></i></span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown(
                            "project_task_reminder_on_the_day_of_deadline", array("1" => app_lang("yes"), "0" => app_lang("no")), get_setting('project_task_reminder_on_the_day_of_deadline'), "class='select2 mini'"
                    );
                    ?>                     
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="project_task_deadline_overdue_reminder" class=" col-md-3"><?php echo app_lang('send_task_deadline_overdue_reminder'); ?> <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('cron_job_required'); ?>"><i data-feather='help-circle' class="icon-16"></i></span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown(
                            "project_task_deadline_overdue_reminder", array(
                        "" => " - ",
                        "1" => "1 " . app_lang("day"),
                        "2" => "2 " . app_lang("days"),
                        "3" => "3 " . app_lang("days"),
                        "5" => "5 " . app_lang("days"),
                        "7" => "7 " . app_lang("days"),
                        "10" => "10 " . app_lang("days"),
                        "14" => "14 " . app_lang("days"),
                        "15" => "15 " . app_lang("days"),
                        "20" => "20 " . app_lang("days"),
                        "30" => "30 " . app_lang("days"),
                            ), get_setting('project_task_deadline_overdue_reminder'), "class='select2 mini'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="task_point_range" class="col-md-3"><?php echo app_lang('task_point_range'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown(
                            "task_point_range", array(
                        "5" => "5",
                        "10" => "10",
                        "15" => "15",
                        "20" => "20",
                            ), get_setting('task_point_range'), "class='select2 mini'"
                    );
                    ?>
                </div>
            </div>
        </div>

    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i data-feather='check-circle' class="icon-16"></i> <?php echo app_lang('save'); ?></button>
    </div>


</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#task-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        $("#task-settings-form .select2").select2();
        $('[data-bs-toggle="tooltip"]').tooltip();

        //show/hide recurring before area
        $("#enable_recurring_option_for_tasks").select2().on("change", function () {
            if ($(this).val() === "1") {
                $("#create_recurring_tasks_before_area").removeClass("hide");
            } else {
                $("#create_recurring_tasks_before_area").addClass("hide");
            }
        });
    });
</script>