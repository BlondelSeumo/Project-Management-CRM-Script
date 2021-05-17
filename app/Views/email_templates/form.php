<div class="card">
    <div class='card-header'>
        <i data-feather="mail" class='icon-16 mr10'></i><?php echo app_lang($model_info->template_name); ?>
    </div>
    <?php echo form_open(get_uri("email_templates/save"), array("id" => "email-template-form", "class" => "general-form", "role" => "form")); ?>
    <div class="modal-body clearfix">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class='row'>
            <div class="form-group">
                <div class=" col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "email_subject",
                        "name" => "email_subject",
                        "value" => $model_info->email_subject,
                        "class" => "form-control",
                        "placeholder" => app_lang('subject'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class=" col-md-12">
                    <?php
                    echo form_textarea(array(
                        "id" => "custom_message",
                        "name" => "custom_message",
                        "value" => $model_info->custom_message ? $model_info->custom_message : $model_info->default_message,
                        "class" => "form-control"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div><strong><?php echo app_lang("avilable_variables"); ?></strong>: <?php
            foreach ($variables as $variable) {
                echo "{" . $variable . "}, ";
            }
            ?></div>
        <hr />
        <div class="form-group m0">
            <button type="submit" class="btn btn-primary mr15"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
            <button id="restore_to_default" data-bs-toggle="popover" data-id="<?php echo $model_info->id; ?>" data-placement="top" type="button" class="btn btn-danger"><span data-feather="refresh-cw" class="icon-16"></span> <?php echo app_lang('restore_to_default'); ?></button>
        </div>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#email-template-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                var custom_message = encodeAjaxPostData(getWYSIWYGEditorHTML("#custom_message"));
                $.each(data, function (index, obj) {
                    if (obj.name === "custom_message") {
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

        initWYSIWYGEditor("#custom_message", {height: 480});


        $('#restore_to_default').click(function () {
            var $instance = $(this);
            $(this).appConfirmation({
                title: "<?php echo app_lang('are_you_sure'); ?>",
                btnConfirmLabel: "<?php echo app_lang('yes'); ?>",
                btnCancelLabel: "<?php echo app_lang('no'); ?>",
                onConfirm: function () {
                    $.ajax({
                        url: "<?php echo get_uri('email_templates/restore_to_default') ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {id: $instance.attr("data-id")},
                        success: function (result) {
                            if (result.success) {
                                $('#custom_message').summernote('code', result.data);
                                appAlert.success(result.message, {duration: 10000});
                            } else {
                                appAlert.error(result.message);
                            }
                        }
                    });

                }
            });

            return false;
        });
    });
</script>    