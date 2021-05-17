<div class="modal-body clearfix">
    <div class="container-fluid">
        <?php echo form_open(get_uri("estimate_requests/save_estimate_form_field"), array("id" => "estimate-form", "class" => "general-form", "role" => "form")); ?>

        <input type="hidden" name="estimate_form_id" value="<?php echo $estimate_form_id; ?>" />

        <?php echo view("custom_fields/form/input_fields"); ?>

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
                location.reload();
            }
        });

    });
</script>