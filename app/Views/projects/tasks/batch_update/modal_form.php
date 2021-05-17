<?php echo form_open(get_uri("projects/save_batch_update"), array("id" => "batch-update-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="task_ids" value="<?php echo $task_ids; ?>" />
        <input type="hidden" name="batch_fields" value="" id="batch_fields" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    <?php
                    echo form_checkbox("", "1", false, "class='batch-update-checkbox form-check-input'");
                    ?>                       
                </div>
                <label for="milestone_id" class=" col-md-2 text-off"><?php echo app_lang('milestone'); ?></label>
                <div class="col-md-9" id="dropdown-apploader-section">
                    <?php
                    echo form_input(array(
                        "id" => "milestone_id",
                        "name" => "milestone_id",
                        "class" => "form-control",
                        "placeholder" => app_lang('milestone')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    <?php
                    echo form_checkbox("", "1", false, "class='batch-update-checkbox form-check-input'");
                    ?>                       
                </div>
                <label for="assigned_to" class=" col-md-2 text-off"><?php echo app_lang('assign_to'); ?></label>
                <div class="col-md-9" id="dropdown-apploader-section">
                    <?php
                    echo form_input(array(
                        "id" => "assigned_to",
                        "name" => "assigned_to",
                        "class" => "form-control",
                        "placeholder" => app_lang('assign_to')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    <?php
                    echo form_checkbox("", "1", false, "class='batch-update-checkbox form-check-input'");
                    ?>                       
                </div>
                <label for="collaborators" class=" col-md-2 text-off"><?php echo app_lang('collaborators'); ?></label>
                <div class="col-md-9" id="dropdown-apploader-section">
                    <?php
                    echo form_input(array(
                        "id" => "collaborators",
                        "name" => "collaborators",
                        "class" => "form-control",
                        "placeholder" => app_lang('collaborators')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    <?php
                    echo form_checkbox("", "1", false, "class='batch-update-checkbox form-check-input'");
                    ?>                       
                </div>
                <label for="status_id" class=" col-md-2 text-off"><?php echo app_lang('status'); ?></label>
                <div class="col-md-9">
                    <?php
                    foreach ($statuses as $status) {
                        $task_status[$status->id] = $status->key_name ? app_lang($status->key_name) : $status->title;
                    }

                    echo form_dropdown("status_id", $task_status, "", "class='select2'");
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    <?php
                    echo form_checkbox("", "1", false, "class='batch-update-checkbox form-check-input'");
                    ?>                       
                </div>
                <label for="labels" class=" col-md-2 text-off"><?php echo app_lang('labels'); ?></label>
                <div class=" col-md-9" id="dropdown-apploader-section">
                    <?php
                    echo form_input(array(
                        "id" => "project_labels",
                        "name" => "labels",
                        "class" => "form-control",
                        "placeholder" => app_lang('labels')
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    <?php
                    echo form_checkbox("", "1", false, "class='batch-update-checkbox form-check-input'");
                    ?>                       
                </div>
                <label for="start_date" class=" col-md-2 text-off"><?php echo app_lang('start_date'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "start_date",
                        "name" => "start_date",
                        "class" => "form-control",
                        "placeholder" => "YYYY-MM-DD",
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    <?php
                    echo form_checkbox("", "1", false, "class='batch-update-checkbox form-check-input'");
                    ?>                       
                </div>
                <label for="deadline" class=" col-md-2 text-off"><?php echo app_lang('deadline'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "deadline",
                        "name" => "deadline",
                        "class" => "form-control",
                        "placeholder" => "YYYY-MM-DD",
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
        </div> 

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        //store all checked field name to an input field
        var batchFields = [];

        $("#batch-update-form").appForm({
            beforeAjaxSubmit: function (data) {
                var batchFieldsIndex = 0;

                $.each(data, function (index, obj) {
                    var $checkBox = $("[name='" + obj.name + "']").closest(".form-group").find("input.batch-update-checkbox");
                    if ($checkBox && $checkBox.is(":checked")) {
                        batchFields.push(obj.name);
                    }

                    if (obj.name === "batch_fields") {
                        batchFieldsIndex = index;
                    }
                });

                var serializeOfArray = batchFields.join("-");
                data[batchFieldsIndex]["value"] = serializeOfArray;
            },
            onSuccess: function (result) {
                hideBatchTasksBtn();
                batchFields = [];

                if (result.success) {
                    if ($(".dataTable:visible").attr("id")) {
                        //update data of tasks table 
                        $("#" + $(".dataTable:visible").attr("id")).appTable({reload: true});
                    } else {
                        //reload kanban
                        $("#reload-kanban-button:visible").trigger("click");
                    }

                    appAlert.success(result.message, {duration: 10000});
                }
            }
        });

        $("#batch-update-form .select2").select2();
        setDatePicker("#start_date, #deadline");

        //toggle checkbox and label
        $(".form-group .col-md-9 input, select").on('change', function () {
            var checkBox = $(this).closest(".form-group").find("input.batch-update-checkbox"),
                    label = $(this).closest(".form-group").find("label");

            if ($(this).val()) {
                if (!checkBox.is(":checked")) {
                    checkBox.trigger('click');
                    label.removeClass("text-off");
                }
            } else {
                checkBox.removeAttr("checked");
                label.addClass("text-off");
            }
        });

        //toggle labels
        $(".batch-update-checkbox").click(function () {
            var label = $(this).closest(".form-group").find("label");

            if ($(this).is(":checked")) {
                label.removeClass("text-off");
            } else {
                label.addClass("text-off");
            }
        });
    });
</script>    

<?php echo view("projects/tasks/get_related_data_of_project_script"); ?>