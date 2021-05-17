<div class="modal-body clearfix">
    <div class="container-fluid">
        <?php echo form_open(get_uri("estimate_requests/update_estimate_request"), array("id" => "estimate-request-update-form", "class" => "general-form", "role" => "form")); ?>
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="assigned_to" class=" col-md-3"><?php echo app_lang('assign_to'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("assigned_to", $assigned_to_dropdown, $model_info->assigned_to, "class='select2'");
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

        $("#estimate-request-update-form").appForm({
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                location.reload();
            }
        });

        $("#estimate-request-update-form .select2").select2();

    });

</script>