<?php echo form_open(get_uri("projects/save_timelog"), array("id" => "timelog-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <?php if (!$project_id) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="project_id" class=" col-md-3"><?php echo app_lang('project'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_dropdown("project_id", $projects_dropdown, array(), "class='select2 validate-hidden' id='project_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($team_members_info)) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="applicant_id" class=" col-md-3"><?php echo app_lang('team_member'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        $image_url = get_avatar($team_members_info->image);
                        echo "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span>" . $team_members_info->first_name . " " . $team_members_info->last_name;
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($show_porject_members_dropdown) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="user_id" class=" col-md-3"><?php echo app_lang('member'); ?></label>
                    <div class="col-md-9" id="dropdown-apploader-section">
                        <?php
                        echo form_input(array(
                            "id" => "user_id",
                            "name" => "user_id",
                            "value" => $model_info->user_id,
                            "class" => "form-control",
                            "placeholder" => app_lang('member')
                        ));
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ((get_setting("users_can_input_only_total_hours_instead_of_period") && (!$model_info->id || $model_info->hours)) || (!get_setting("users_can_input_only_total_hours_instead_of_period") && $model_info->hours)) { ?>
            <div class="row">
                <label for="date" class=" col-md-3 col-sm-3"><?php echo app_lang('date'); ?></label>
                <div class="col-md-4 col-sm-4 form-group">
                    <?php
                    $in_time = is_date_exists($model_info->start_time) ? convert_date_utc_to_local($model_info->start_time) : "";

                    echo form_input(array(
                        "id" => "date",
                        "name" => "date",
                        "value" => $in_time ? date("Y-m-d", strtotime($in_time)) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('date'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
                <label for="hours" class=" col-md-2 col-sm-2"><?php echo app_lang('hours'); ?></label>
                <div class=" col-md-3 col-sm-3 form-group">
                    <?php
                    echo form_input(array(
                        "id" => "hours",
                        "name" => "hours",
                        "value" => $model_info->hours ? convert_hours_to_humanize_data($model_info->hours) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('timesheet_hour_input_help_message'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

        <?php } else { ?>

            <div class="row">
                <label for="start_date" class=" col-md-3 col-sm-3"><?php echo app_lang('start_date'); ?></label>
                <div class="col-md-4 col-sm-4 form-group">
                    <?php
                    $in_time = is_date_exists($model_info->start_time) ? convert_date_utc_to_local($model_info->start_time) : "";

                    if ($time_format_24_hours) {
                        $in_time_value = $in_time ? date("H:i", strtotime($in_time)) : "";
                    } else {
                        $in_time_value = $in_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($in_time))) : "";
                    }

                    echo form_input(array(
                        "id" => "start_date",
                        "name" => "start_date",
                        "value" => $in_time ? date("Y-m-d", strtotime($in_time)) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('start_date'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
                <label for="in_time" class=" col-md-2 col-sm-2"><?php echo app_lang('start_time'); ?></label>
                <div class=" col-md-3 col-sm-3  form-group">
                    <?php
                    echo form_input(array(
                        "id" => "start_time",
                        "name" => "start_time",
                        "value" => $in_time_value,
                        "class" => "form-control",
                        "placeholder" => app_lang('start_time'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <div class="row">
                <label for="end_date" class=" col-md-3 col-sm-3"><?php echo app_lang('end_date'); ?></label>
                <div class=" col-md-4 col-sm-4 form-group">
                    <?php
                    $out_time = is_date_exists($model_info->end_time) ? convert_date_utc_to_local($model_info->end_time) : "";

                    if ($time_format_24_hours) {
                        $out_time_value = $in_time ? date("H:i", strtotime($out_time)) : "";
                    } else {
                        $out_time_value = $in_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($out_time))) : "";
                    }
                    echo form_input(array(
                        "id" => "end_date",
                        "name" => "end_date",
                        "value" => $out_time ? date("Y-m-d", strtotime($out_time)) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('end_date'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        "data-rule-greaterThanOrEqual" => "#start_date",
                        "data-msg-greaterThanOrEqual" => app_lang("end_date_must_be_equal_or_greater_than_start_date")
                    ));
                    ?>
                </div>
                <label for="end_time" class=" col-md-2 col-sm-2"><?php echo app_lang('end_time'); ?></label>
                <div class=" col-md-3 col-sm-3 form-group">
                    <?php
                    echo form_input(array(
                        "id" => "end_time",
                        "name" => "end_time",
                        "value" => $out_time_value,
                        "class" => "form-control",
                        "placeholder" => app_lang('end_time'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="note" class=" col-md-3"><?php echo app_lang('note'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "note",
                        "name" => "note",
                        "class" => "form-control",
                        "placeholder" => app_lang('note'),
                        "value" => $model_info->note,
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="task_id" class=" col-md-3"><?php echo app_lang('task'); ?></label>
                <div class="col-md-9" id="dropdown-apploader-section">
                    <?php
                    echo form_input(array(
                        "id" => "task_id",
                        "name" => "task_id",
                        "value" => $model_info->task_id,
                        "class" => "form-control",
                        "placeholder" => app_lang('task')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#timelog-form").appForm({
            onSuccess: function (result) {
                $("#project-timesheet-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#timelog-form .select2").select2();

        //load all related data of the selected project
        $("#project_id").select2().on("change", function () {
            var projectId = $(this).val();
            if (projectId) {
                $('#user_id').select2("destroy");
                $("#user_id").hide();
                $('#task_id').select2("destroy");
                $("#task_id").hide();
                appLoader.show({container: "#dropdown-apploader-section"});
                $.ajax({
                    url: "<?php echo get_uri('projects/get_all_related_data_of_selected_project_for_timelog') ?>" + "/" + projectId,
                    dataType: "json",
                    success: function (result) {
                        $("#user_id").show().val("");
                        $('#user_id').select2({data: result.project_members_dropdown});
                        $("#task_id").show().val("");
                        $('#task_id').select2({data: result.tasks_dropdown});
                        appLoader.hide();
                    }
                });
            }
        });

        //intialized select2 dropdown for first time
        $("#user_id").select2({data: <?php echo json_encode($project_members_dropdown); ?>});
        $("#task_id").select2({data: <?php echo $tasks_dropdown; ?>});

        setDatePicker("#start_date, #end_date, #date");
        setTimePicker("#start_time, #end_time");

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>