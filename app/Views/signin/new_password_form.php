<div class="card mb15">
    <div class="card-header text-center">
        <?php if (get_setting("show_logo_in_signin_page") === "yes") { ?>
            <img class="p20 mw100p" src="<?php echo get_logo_url(); ?>" />
        <?php } else { ?>
            <h2><?php echo app_lang('reset_password'); ?></h2>
        <?php } ?>
    </div>
    <div class="card-body p30 rounded-bottom">
        <?php echo form_open("signin/do_reset_password", array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); ?>
        <div class="form-group">
            <input type="hidden" name="key"  value="<?php echo isset($key) ? $key : ''; ?>" />
            <label for="password" class=""><?php echo app_lang('password'); ?></label>
            <div class="">
                <?php
                echo form_password(array(
                    "id" => "password",
                    "name" => "password",
                    "class" => "form-control p10",
                    "data-rule-required" => true,
                    "data-rule-minlength" => 6,
                    "data-msg-minlength" => app_lang("enter_minimum_6_characters"),
                    "autocomplete" => "off",
                    "style" => "z-index:auto;"
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="retype_password" class=""><?php echo app_lang('retype_password'); ?></label>
            <div class="">
                <?php
                echo form_password(array(
                    "id" => "retype_password",
                    "name" => "retype_password",
                    "class" => "form-control p10",
                    "autocomplete" => "off",
                    "style" => "z-index:auto;",
                    "data-rule-equalTo" => "#password",
                    "data-msg-equalTo" => app_lang("enter_same_value")
                ));
                ?>
            </div>
        </div>
        <div class="form-group mb0">
            <button class="w-100 btn btn-lg btn-primary btn-block" type="submit"><?php echo app_lang('reset_password'); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#reset-password-form").appForm({
            isModal: false,
            onSubmit: function () {
                appLoader.show();
            },
            onSuccess: function (result) {
                appLoader.hide();
                appAlert.success(result.message, {container: '.card-body', animate: false});
                $("#reset-password-form").remove();
            },
            onError: function (result) {
                appLoader.hide();
                appAlert.error(result.message, {container: '.card-body', animate: false});
                return false;
            }
        });
    });
</script>    