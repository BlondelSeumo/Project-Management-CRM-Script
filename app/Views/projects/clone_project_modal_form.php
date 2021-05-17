<?php echo form_open(get_uri("projects/save_cloned_project"), array("id" => "project-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="project_id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="title" class=" col-md-3"><?php echo app_lang('title'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => $model_info->title,
                        "class" => "form-control",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="client_id" class=" col-md-3"><?php echo app_lang('client'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="description" class=" col-md-3"><?php echo app_lang('description'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "description",
                        "name" => "description",
                        "value" => $model_info->description,
                        "class" => "form-control",
                        "placeholder" => app_lang('description'),
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="start_date" class=" col-md-3"><?php echo app_lang('start_date'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "start_date",
                        "name" => "start_date",
                        "value" => is_date_exists($model_info->start_date) ? $model_info->start_date : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('start_date'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="deadline" class=" col-md-3"><?php echo app_lang('deadline'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "deadline",
                        "name" => "deadline",
                        "value" => is_date_exists($model_info->deadline) ? $model_info->deadline : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('deadline'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="price" class=" col-md-3"><?php echo app_lang('price'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "price",
                        "name" => "price",
                        "value" => $model_info->price ? to_decimal_format($model_info->price) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('price')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="project_labels" class=" col-md-3"><?php echo app_lang('labels'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "project_labels",
                        "name" => "labels",
                        "value" => $model_info->labels,
                        "class" => "form-control",
                        "placeholder" => app_lang('labels')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

        <div class="form-group">
            <label for="copy_project_members"class=" col-md-12">
                <?php
                echo form_checkbox("copy_project_members", "1", true, "id='copy_project_members' disabled='disabled' class='float-start mr15 form-check-input'");
                ?>    
                <?php echo app_lang('copy_project_members'); ?>
            </label>
        </div>

        <div class="form-group">
            <label for="copy_tasks"class=" col-md-12">
                <?php
                echo form_checkbox("copy_tasks", "1", true, "id='copy_tasks' disabled='disabled' class='float-start form-check-input'");
                ?>    
                <span class="float-start ml15"> <?php echo app_lang('copy_tasks'); ?> (<?php echo app_lang("task_comments_will_not_be_included"); ?>) </span>
            </label>
        </div>

        <div class="form-group">
            <label for="copy_same_assignee_and_collaborators"class=" col-md-12">
                <?php
                echo form_checkbox("copy_same_assignee_and_collaborators", "1", true, "id='copy_same_assignee_and_collaborators'  class='float-start form-check-input'");
                ?>    
                <span class="float-start ml15"> <?php echo app_lang('copy_same_assignee_and_collaborators'); ?> </span>
            </label>
        </div>
        <div class="form-group">
            <label for="copy_tasks_start_date_and_deadline"class=" col-md-12">
                <?php
                echo form_checkbox("copy_tasks_start_date_and_deadline", "1", false, "id='copy_tasks_start_date_and_deadline'  class='float-start form-check-input'");
                ?>    
                <span class="float-start ml15"> <?php echo app_lang('copy_tasks_start_date_and_deadline'); ?> </span>
            </label>
        </div>

        <div class="form-group">
            <label for="copy_milestones"class=" col-md-12">
                <?php
                echo form_checkbox("copy_milestones", "1", false, "id='copy_milestones'  class='float-start form-check-input'");
                ?>    
                <span class="float-start ml15"> <?php echo app_lang('copy_milestones'); ?> </span>
            </label>
        </div>

        <div class="form-group">
            <label for="move_all_tasks_to_to_do"class=" col-md-12">
                <?php
                echo form_checkbox("move_all_tasks_to_to_do", "1", false, "id='move_all_tasks_to_to_do'  class='float-start form-check-input'");
                ?>    
                <span class="float-start ml15"> <?php echo app_lang('move_all_tasks_to_to_do'); ?> </span>
            </label>
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
        $("#project-form").appForm({
            onSuccess: function (result) {
                appAlert.success(result.message);
                setTimeout(function () {
                    window.location = "<?php echo site_url('projects/view'); ?>/" + result.id;
                }, 2000);
            }
        });
        setTimeout(function () {
            $("#title").focus();
        }, 200);
        $("#project-form .select2").select2();

        setDatePicker("#start_date, #deadline");

        $("#project_labels").select2({
            tags: <?php echo json_encode($label_suggestions); ?>
        });
    });
</script>    