<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo view('includes/head'); ?>
    </head>
    <body>
        <?php
        if (get_setting("show_background_image_in_signin_page") === "yes") {
            $background_url = get_file_from_setting("signin_page_background");
            ?>
            <style type="text/css">
                html, body {
                    background-image: url('<?php echo $background_url; ?>');
                    background-size:cover;
                }
            </style>
        <?php } ?>
        <div id="page-content" class="clearfix">
            <div class="scrollable-page">
                <div class="form-signin">
                    <div class="card bg-white clearfix">
                        <div class="card-header text-center">
                            <h2 class="form-signin-heading"><?php echo app_lang('signup'); ?></h2>
                            <p><?php echo $signup_message; ?></p>
                        </div>
                        <div class="card-body p30 rounded-bottom">
                            <?php
                            $action_url = ($signup_type == "send_verify_email") ? "signup/send_verification_mail" : "signup/create_account";
                            echo form_open($action_url, array("id" => "signup-form", "class" => "general-form", "role" => "form"));
                            ?>

                            <?php if ($signup_type == "send_verify_email") { ?>
                                <div class="form-group">
                                    <label for="email" class="col-md-12"><?php echo app_lang('input_your_email'); ?></label>
                                    <div class="col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "email",
                                            "name" => "email",
                                            "class" => "form-control p10",
                                            "autofocus" => true,
                                            "placeholder" => app_lang('email'),
                                            "data-rule-email" => true,
                                            "data-msg-email" => app_lang("enter_valid_email"),
                                            "data-rule-required" => true,
                                            "data-msg-required" => app_lang("field_required"),
                                        ));
                                        ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label for="name" class="col-md-12"><?php echo app_lang('first_name'); ?></label>
                                    <div class="col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "first_name",
                                            "name" => "first_name",
                                            "class" => "form-control",
                                            "autofocus" => true,
                                            "data-rule-required" => true,
                                            "data-msg-required" => app_lang("field_required"),
                                        ));
                                        ?>
                                    </div>
                                </div>

                                <input type="hidden" name="signup_key"  value="<?php echo isset($signup_key) ? $signup_key : ''; ?>" />
                                <div class="form-group">
                                    <label for="last_name" class="col-md-12"><?php echo app_lang('last_name'); ?></label>
                                    <div class="col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "last_name",
                                            "name" => "last_name",
                                            "class" => "form-control",
                                            "data-rule-required" => true,
                                            "data-msg-required" => app_lang("field_required"),
                                        ));
                                        ?>
                                    </div>
                                </div>

                                <?php if ($signup_type === "new_client" || $signup_type === "verify_email") { ?>
                                    <div class="form-group">
                                        <label for="company_name" class="col-md-12"><?php echo app_lang('company_name'); ?></label>
                                        <div class="col-md-12">
                                            <?php
                                            echo form_input(array(
                                                "id" => "company_name",
                                                "name" => "company_name",
                                                "class" => "form-control",
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($type === "staff") { ?>
                                    <div class="form-group">
                                        <label for="job_title" class="col-md-12"><?php echo app_lang('job_title'); ?></label>
                                        <div class="col-md-12">
                                            <?php
                                            echo form_input(array(
                                                "id" => "job_title",
                                                "name" => "job_title",
                                                "class" => "form-control"
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($signup_type === "new_client") { ?>
                                    <div class="form-group">
                                        <label for="email" class="col-md-12"><?php echo app_lang('email'); ?></label>
                                        <div class="col-md-12">
                                            <?php
                                            echo form_input(array(
                                                "id" => "email",
                                                "name" => "email",
                                                "class" => "form-control",
                                                "autofocus" => true,
                                                "data-rule-email" => true,
                                                "data-msg-email" => app_lang("enter_valid_email"),
                                                "data-rule-required" => true,
                                                "data-msg-required" => app_lang("field_required"),
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                <?php } else if ($signup_type === "verify_email" && isset($key)) { ?>
                                    <input type="hidden" name="verify_email_key"  value="<?php echo $key; ?>" />
                                <?php } ?>

                                <div class="form-group">
                                    <label for="password" class="col-md-12"><?php echo app_lang('password'); ?></label>
                                    <div class="col-md-12">
                                        <?php
                                        echo form_password(array(
                                            "id" => "password",
                                            "name" => "password",
                                            "class" => "form-control",
                                            "data-rule-required" => true,
                                            "data-msg-required" => app_lang("field_required"),
                                            "data-rule-minlength" => 6,
                                            "data-msg-minlength" => app_lang("enter_minimum_6_characters"),
                                            "autocomplete" => "off",
                                            "style" => "z-index:auto;"
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="retype_password" class="col-md-12"><?php echo app_lang('retype_password'); ?></label>
                                    <div class="col-md-12">
                                        <?php
                                        echo form_password(array(
                                            "id" => "retype_password",
                                            "name" => "retype_password",
                                            "class" => "form-control",
                                            "autocomplete" => "off",
                                            "style" => "z-index:auto;",
                                            "data-rule-equalTo" => "#password",
                                            "data-msg-equalTo" => app_lang("enter_same_value")
                                        ));
                                        ?>
                                    </div>
                                </div>

                                <?php if (get_setting("enable_gdpr") && get_setting("show_terms_and_conditions_in_client_signup_page") && get_setting("gdpr_terms_and_conditions_link")) { ?>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="i_accept_the_terms_and_conditions">
                                                <?php
                                                echo form_checkbox("i_accept_the_terms_and_conditions", "1", false, "id='i_accept_the_terms_and_conditions' class='float-start form-check-input' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'");
                                                ?>    
                                                <span class="ml10"><?php echo app_lang('i_accept_the_terms_and_conditions') . " " . anchor(get_setting("gdpr_terms_and_conditions_link"), app_lang("gdpr_terms_and_conditions") . ".", array("target" => "_blank")); ?> </span>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <?php if ($signup_type !== "verify_email") { ?>
                                <div class="col-md-12">
                                    <?php echo view("signin/re_captcha"); ?>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <button class="w-100 btn btn-lg btn-primary" type="submit"><?php echo $signup_type == "send_verify_email" ? app_lang("get_started") : app_lang('signup'); ?></button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div id="signin_link"><?php echo app_lang("already_have_an_account") . " " . anchor("signin", app_lang("signin")); ?></div>
                </div>
            </div>
        </div> <!-- /container -->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#signup-form").appForm({
                    isModal: false,
                    onSubmit: function () {
                        appLoader.show();
                    },
                    onSuccess: function (result) {
                        appLoader.hide();
                        appAlert.success(result.message, {container: '.card-body', animate: false});
                        $("#signup-form").remove();

<?php if ($signup_type !== "send_verify_email") { ?>
                            $("#signin_link").remove();
<?php } ?>
                    },
                    onError: function (result) {
                        appLoader.hide();
                        appAlert.error(result.message, {container: '.card-body', animate: false});
                        return false;
                    }
                });
            });
        </script>    
        <?php echo view("includes/footer"); ?>
    </body>
</html>