<?php echo form_open(get_uri("dashboard/save"), array("id" => "dashboard-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" name="data" value='<?php echo json_encode(unserialize($model_info->data)); ?>' />
        <div class="form-group">
            <div class="row">
                <label for="title" class=" col-md-3"><?php echo app_lang('title'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "class" => "form-control",
                        "placeholder" => app_lang("title"),
                        "value" => $model_info->title,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-9 ms-auto">
                    <?php echo view("includes/color_plate"); ?>
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




<script>
    $(document).ready(function () {
        setTimeout(function () {
            $("#title").focus();
        }, 200);

        $("#dashboard-form").appForm({
            onSuccess: function (result) {
                if (window.dashboardTitleEditMode) {
                    window.dashboardTitleEditMode = false;
                    location.reload();
                } else {
                    window.location = "<?php echo get_uri("dashboard/edit_dashboard"); ?>/" + result.dashboard_id;
                }
            }
        });
    });
</script>    