<div class="modal-body clearfix">
    <div class="container-fluid">
        <?php echo form_open(get_uri("estimate_requests/save_estimate_request_form"), array("id" => "estimate-form", "class" => "general-form", "role" => "form")); ?>
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
                        "style" => "height:150px;",
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="status" class=" col-md-3"><?php echo app_lang('status'); ?></label>
                <div class="col-md-9">
                    <?php
                    $status_dropdown = array("active" => app_lang("active"), "inactive" => app_lang("inactive"));
                    echo form_dropdown("status", $status_dropdown, $model_info->status, "class='select2'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="assigned_to" class="col-md-3"><?php echo app_lang('auto_assign_estimate_request_to') ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("assigned_to", $assigned_to_dropdown, $model_info->assigned_to, "class='select2'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="public" class="col-md-3"><?php echo app_lang('public'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_checkbox("public", "1", $model_info->public ? true : false, "id='public' class='form-check-input'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="enable_attachment" class="col-md-3"><?php echo app_lang('enable_attachment'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_checkbox("enable_attachment", "1", $model_info->enable_attachment ? true : false, "id='enable_attachment' class='form-check-input'");
                    ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
                <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {

        $("#estimate-form").appForm({
            onSuccess: function (result) {
                if (result.newData) {
                    window.location = "<?php echo site_url('estimate_requests/edit_estimate_form'); ?>/" + result.id;
                } else {
                    $("#estimate-form-main-table").appTable({newData: result.data, dataId: result.id});
                }
            }
        });

        $("#estimate-form .select2").select2();

    });

</script>