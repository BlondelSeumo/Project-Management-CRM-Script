<?php echo form_open(get_uri("settings/save_imap_settings"), array("id" => "imap-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>

<?php
$php_version_success = false;
$php_version_required = "7.0.0";
$current_php_version = PHP_VERSION;

//check required php version
if (version_compare($current_php_version, $php_version_required) >= 0) {
    $php_version_success = true;
}

//check imap extension existence
$imap_extension_success = extension_loaded("imap") ? true : false;
?>

<div class="card mb0">

    <?php if ($php_version_success && $imap_extension_success) { ?>

        <div class="card-body">

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <i data-feather='info' class="icon-16"></i> <?php echo app_lang("imap_help_message_1"); ?> <br />
                        <?php echo app_lang("imap_help_message_2") . " " . anchor(get_uri("email_templates"), ucfirst(app_lang("email_templates")) . " " . strtolower(app_lang("settings")), array("target" => "_blank")) . "."; ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="enable_email_piping" class="col-md-3">
                        <?php echo app_lang('enable_email_piping'); ?> 
                        <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('cron_job_required'); ?>"><i data-feather='help-circle' class="icon-16"></i></span>
                    </label>
                    <div class="col-md-9">
                        <?php
                        echo form_checkbox("enable_email_piping", "1", get_setting("enable_email_piping") ? true : false, "id='enable_email_piping' class='form-check-input ml15'");
                        ?>               
                    </div>
                </div>
            </div>
            <div id="imap-details" class="<?php echo get_setting("enable_email_piping") ? "" : "hide"; ?>">
                <div class="form-group">
                    <div class="row">
                        <label for="create_tickets_only_by_registered_emails" class="col-md-3"><?php echo app_lang('create_tickets_only_by_registered_emails'); ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_checkbox("create_tickets_only_by_registered_emails", "1", get_setting("create_tickets_only_by_registered_emails") ? true : false, "id='create_tickets_only_by_registered_emails' class='form-check-input ml15'");
                            ?>               
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="imap_ssl_enabled" class=" col-md-3"><?php echo app_lang('imap_ssl_enabled'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_dropdown(
                                    "imap_ssl_enabled", array("1" => app_lang("yes"), "0" => app_lang("no")), get_setting('imap_ssl_enabled'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="imap_host" class=" col-md-3"><?php echo app_lang('imap_host'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "imap_host",
                                "name" => "imap_host",
                                "value" => get_setting("imap_host"),
                                "class" => "form-control",
                                "placeholder" => app_lang('imap_host'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="imap_port" class=" col-md-3"><?php echo app_lang('imap_port'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "imap_port",
                                "name" => "imap_port",
                                "value" => get_setting("imap_port"),
                                "class" => "form-control",
                                "placeholder" => app_lang('imap_port'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="imap_email" class=" col-md-3"><?php echo app_lang("username") . "/" . app_lang('email'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "imap_email",
                                "name" => "imap_email",
                                "value" => get_setting("imap_email"),
                                "class" => "form-control",
                                "placeholder" => app_lang('email'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required")
                            ));
                            ?>
                            <span class="mt10 d-inline-block"><i data-feather='alert-triangle' class="icon-16 text-warning"></i> <?php echo app_lang("email_piping_help_message"); ?></span>     
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="imap_password" class=" col-md-3"><?php echo app_lang('password'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_password(array(
                                "id" => "imap_password",
                                "name" => "imap_password",
                                "class" => "form-control",
                                "value" => "",
                                "placeholder" => app_lang('password'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="status" class=" col-md-3"><?php echo app_lang('status'); ?></label>
                        <div class=" col-md-9">
                            <?php if (get_setting("imap_authorized")) { ?>
                                <span class="ml5 badge bg-success"><?php echo app_lang("authorized"); ?></span>
                            <?php } else { ?>
                                <span class="ml5 badge" style="background:#F9A52D;"><?php echo app_lang("unauthorized"); ?></span>
                            <?php } ?>

                            <?php if (get_setting("imap_failed_login_attempts_count")) { ?>
                                <span class="ml5 badge" style="background:#F9A52D;"><?php echo get_setting("imap_failed_login_attempts_count") . " " . app_lang("login_attempt_failed"); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button id="save-button" type="submit" class="btn btn-primary <?php echo get_setting("enable_email_piping") ? "hide" : "" ?>"><span data-feather='check-circle' class="icon-16"></span> <?php echo app_lang('save'); ?></button>
            <button id="save-and-authorize-button" type="submit" class="btn btn-primary ml5 <?php echo get_setting("enable_email_piping") ? "" : "hide" ?>"><span data-feather='check-circle' class="icon-16"></span> <?php echo app_lang('save_and_authorize'); ?></button>
        </div>

    <?php } else { ?>

        <div class="card-body">
            <i data-feather='alert-triangle' class="icon-16 text-danger"></i> 
            <?php
            if (!$php_version_success) {
                echo app_lang("please_upgrade_your_php_version") . " " . app_lang("current_version") . ": <b>" . $current_php_version . "</b> " . app_lang("required_version") . ": <b>" . $php_version_required . "+</b> ";
            } else {
                echo app_lang("imap_extension_error_help_message");
            }
            ?>
        </div>

    <?php } ?>

</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var $saveAndAuthorizeBtn = $("#save-and-authorize-button"),
                $saveBtn = $("#save-button"),
                $imapDetailsArea = $("#imap-details");

        $("#imap-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});

                //if imap is enabled, redirect to authorization system
                if ($saveBtn.hasClass("hide")) {
                    window.location.href = "<?php echo_uri('settings/authorize_imap'); ?>";
                }
            }
        });

        $("#imap-settings-form .select2").select2();

        //show/hide imap details area
        $("#enable_email_piping").click(function () {
            if ($(this).is(":checked")) {
                $imapDetailsArea.removeClass("hide");
                $saveAndAuthorizeBtn.removeClass("hide");
                $saveBtn.addClass("hide");
            } else {
                $imapDetailsArea.addClass("hide");
                $saveAndAuthorizeBtn.addClass("hide");
                $saveBtn.removeClass("hide");
            }
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>