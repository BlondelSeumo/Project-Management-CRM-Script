<?php echo form_open(get_uri("leave_types/save"), array("id" => "leave-type-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
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
                    <div class="mt15">
                        <?php echo view("includes/color_plate"); ?>
                    </div>
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
                <label for="status" class=" col-md-3"><?php echo app_lang('status'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "status_active",
                        "name" => "status",
                        "class" => "form-check-input",
                        "data-msg-required" => app_lang("field_required"),
                            ), "active", ($model_info->status === "active") ? true : (($model_info->status !== "inactive") ? true : false));
                    ?>
                    <label for="status_active" class="mr15"><?php echo app_lang('active'); ?></label>
                    <?php
                    echo form_radio(array(
                        "id" => "status_inactive",
                        "name" => "status",
                        "class" => "form-check-input",
                        "data-msg-required" => app_lang("field_required"),
                            ), "inactive", ($model_info->status === "inactive") ? true : false);
                    ?>
                    <label for="status_inactive" class=""><?php echo app_lang('inactive'); ?></label>
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
        $("#leave-type-form").appForm({
            onSuccess: function (result) {
                $("#leave-type-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        setTimeout(function () {
            $("#name").focus();
        }, 200);

    });
</script>    