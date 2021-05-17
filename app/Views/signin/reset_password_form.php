<div class="card bg-white mb-3">
    <div class="card-header text-center">
        <?php if (get_setting("show_logo_in_signin_page") === "yes") { ?>
            <img class="p20 mw100p" src="<?php echo get_logo_url(); ?>" />
        <?php } else { ?>
            <h2><?php echo app_lang('forgot_password'); ?></h2>
        <?php } ?>
    </div>
    <div class="card-body p30 rounded-bottom">
        <?php echo form_open("signin/send_reset_password_mail", array("id" => "request-password-form", "class" => "general-form", "role" => "form")); ?>

        <div class="form-group">
            <label for="email" class=""><?php echo app_lang("input_email_to_reset_password"); ?></label>
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "class" => "form-control p10",
                "placeholder" => app_lang('email'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
                "data-rule-email" => true,
                "data-msg-email" => app_lang("enter_valid_email")
            ));
            ?>
        </div>


        <?php echo view("signin/re_captcha"); ?>

        <button class="w-100 btn btn-lg btn-primary" type="submit"><?php echo app_lang('send'); ?></button>

        <?php echo form_close(); ?>
        <div class="mt5"><?php echo anchor("signin", app_lang("signin")); ?></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#request-password-form").appForm({
            isModal: false,
            onSubmit: function () {
                appLoader.show();
                $("#request-password-form").find('[type="submit"]').attr('disabled', 'disabled');
            },
            onSuccess: function (result) {
                appLoader.hide();
                appAlert.success(result.message, {container: '.card-body', animate: false});
                $("#request-password-form").remove();
            },
            onError: function (result) {
                $("#request-password-form").find('[type="submit"]').removeAttr('disabled');
                appLoader.hide();
                appAlert.error(result.message, {container: '.card-body', animate: false});
                return false;
            }
        });
    });
</script>    
