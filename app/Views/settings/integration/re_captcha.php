<div class="card no-border clearfix mb0">

    <?php echo form_open(get_uri("settings/save_re_captcha_settings"), array("id" => "re-captcha-form", "class" => "general-form dashed-row", "role" => "form")); ?>

    <div class="card-body">

        <div class="form-group">
            <div class="row">
                <label class=" col-md-12">
                    <?php echo app_lang("get_your_key_from_here") . " " . anchor("https://www.google.com/recaptcha/admin", "Google reCAPTCHA", array("target" => "_blank")); ?>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="re_captcha_site_key" class=" col-md-2"><?php echo app_lang('re_captcha_site_key'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "re_captcha_site_key",
                        "name" => "re_captcha_site_key",
                        "value" => get_setting("re_captcha_site_key"),
                        "class" => "form-control",
                        "placeholder" => app_lang('re_captcha_site_key')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="re_captcha_secret_key" class=" col-md-2"><?php echo app_lang('re_captcha_secret_key'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "re_captcha_secret_key",
                        "name" => "re_captcha_secret_key",
                        "value" => get_setting("re_captcha_secret_key"),
                        "class" => "form-control",
                        "placeholder" => app_lang('re_captcha_secret_key')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <i data-feather="info" class="icon-16"></i>
                    <span><?php echo app_lang("re_captcha_info_text"); ?></span>
                </div>
            </div>
        </div>

    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    </div>
    <?php echo form_close(); ?>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#re-captcha-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

    });
</script>