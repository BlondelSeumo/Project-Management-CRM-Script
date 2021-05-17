<?php echo form_open(get_uri("settings/save_estimate_request_settings"), array("id" => "estimate-requests-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
<div>

    <div class="card-body">
        <div class="form-group">
            <div class="row">
                <label for="hidden_client_fields_on_public_estimate_requests" class=" col-md-2"><?php echo app_lang('hidden_client_fields_on_public_estimate_requests'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "hidden_client_fields_on_public_estimate_requests",
                        "name" => "hidden_client_fields_on_public_estimate_requests",
                        "value" => get_setting("hidden_client_fields_on_public_estimate_requests"),
                        "class" => "form-control",
                        "placeholder" => app_lang('hidden_client_fields')
                    ));
                    ?>
                    <span id="name_and_email_error_message" class="mt10 d-inline-block hide"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("estimate_request_name_email_error_message"); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" id="submit-btn" class="btn btn-primary"><span data-feather='check-circle' class='icon-16'></span> <?php echo app_lang('save'); ?></button>
    </div>

</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-requests-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#hidden_client_fields_on_public_estimate_requests").select2({
            multiple: true,
            data: <?php echo ($hidden_fields_dropdown); ?>
        }).on("change", function () {
            var fields = $(this).val(),
                    fieldsArray = fields.split(',');

            //show error message 
            //-1 = not exists
            if ((fieldsArray.indexOf("first_name") !== -1) && (fieldsArray.indexOf("last_name") !== -1) && (fieldsArray.indexOf("email") === -1)) {
                $("#name_and_email_error_message").removeClass("hide");
                $("#submit-btn").attr("disabled", true);
            } else {
                $("#name_and_email_error_message").addClass("hide");
                $("#submit-btn").removeAttr("disabled");
            }
        });
    });
</script>