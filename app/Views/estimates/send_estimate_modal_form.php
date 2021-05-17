<?php echo form_open(get_uri("estimates/send_estimate"), array("id" => "send-estimate-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $estimate_info->id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="contact_id" class=" col-md-3"><?php echo app_lang('to'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("contact_id", $contacts_dropdown, array(), "class='select2 validate-hidden' id='contact_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="estimate_cc" class=" col-md-3">CC</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "estimate_cc",
                        "name" => "estimate_cc",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => "CC"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="estimate_bcc" class=" col-md-3">BCC</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "estimate_bcc",
                        "name" => "estimate_bcc",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => "BCC"
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="subject" class=" col-md-3"><?php echo app_lang("subject"); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "subject",
                        "name" => "subject",
                        "value" => $subject,
                        "class" => "form-control",
                        "placeholder" => app_lang("subject")
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class=" col-md-12">
                    <?php
                    echo form_textarea(array(
                        "id" => "message",
                        "name" => "message",
                        "value" => $message,
                        "class" => "form-control"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group ml15">
            <i data-feather="check-circle" class='icon-16' style="color: #5CB85C;"></i> <?php echo app_lang('attached') . ' ' . anchor(get_uri("estimates/download_pdf/" . $estimate_info->id), app_lang("estimate") . "-$estimate_info->id.pdf", array("target" => "_blank")); ?> 
        </div>

    </div>
</div>


<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> <?php echo app_lang('send'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {

        $('#send-estimate-form .select2').select2();
        $("#send-estimate-form").appForm({
            beforeAjaxSubmit: function (data) {
                var custom_message = encodeAjaxPostData(getWYSIWYGEditorHTML("#message"));
                $.each(data, function (index, obj) {
                    if (obj.name === "message") {
                        data[index]["value"] = custom_message;
                    }
                });
            },
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        initWYSIWYGEditor("#message", {height: 400, toolbar: []});

    });
</script>