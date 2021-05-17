<?php echo form_open(get_uri("clients/send_invitation"), array("id" => "invitation-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <br />
        <div class="form-group mb15">
            <div class="row">
                <input type="hidden" name="client_id" value="<?php echo $client_info->id; ?>" />

                <label for="email" class=" col-md-12"><?php echo sprintf(app_lang('invite_an_user'), $client_info->company_name); ?></label>
                <div class="col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "email",
                        "name" => "email",
                        "class" => "form-control",
                        "placeholder" => app_lang('email'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        "data-rule-email" => true,
                        "data-msg-required" => app_lang("enter_valid_email")
                    ));
                    ?>
                </div>
            </div>
        </div>
        <br />
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> <?php echo app_lang('send'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#invitation-form").appForm({
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        setTimeout(function () {
            $("#email").focus();
        }, 200);
    });
</script>    