<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "email";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_email_settings"), array("id" => "email-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="card">
                <div class="card-header">
                    <h4><?php echo app_lang("email_settings"); ?></h4>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <div class="row">
                            <label for="email_sent_from_address" class=" col-md-2"><?php echo app_lang('email_sent_from_address'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "email_sent_from_address",
                                    "name" => "email_sent_from_address",
                                    "value" => get_setting('email_sent_from_address'),
                                    "class" => "form-control",
                                    "placeholder" => "somemail@somedomain.com",
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="email_sent_from_name" class=" col-md-2"><?php echo app_lang('email_sent_from_name'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "email_sent_from_name",
                                    "name" => "email_sent_from_name",
                                    "value" => get_setting('email_sent_from_name'),
                                    "class" => "form-control",
                                    "placeholder" => "Company Name",
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="use_smtp" class=" col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('email_use_smtp'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox(
                                        "email_protocol", "smtp", get_setting('email_protocol') === "smtp" ? true : false, "id='use_smtp' class='form-check-input'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>

                    <div id="smtp_settings" class="<?php echo get_setting('email_protocol') === "smtp" ? "" : "hide"; ?>">
                        <div class="form-group">
                            <div class="row">
                                <label for="email_smtp_host" class=" col-md-2"><?php echo app_lang('email_smtp_host'); ?></label>
                                <div class="col-md-10">
                                    <?php
                                    echo form_input(array(
                                        "id" => "email_smtp_host",
                                        "name" => "email_smtp_host",
                                        "value" => get_setting('email_smtp_host'),
                                        "class" => "form-control",
                                        "placeholder" => app_lang('email_smtp_host'),
                                        "data-rule-required" => true,
                                        "data-msg-required" => app_lang("field_required"),
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="email_smtp_user" class=" col-md-2"><?php echo app_lang('email_smtp_user'); ?></label>
                                <div class="col-md-10">
                                    <?php
                                    echo form_input(array(
                                        "id" => "email_smtp_user",
                                        "name" => "email_smtp_user",
                                        "value" => get_setting('email_smtp_user'),
                                        "class" => "form-control",
                                        "placeholder" => app_lang('email_smtp_user'),
                                        "data-rule-required" => true,
                                        "data-msg-required" => app_lang("field_required"),
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="email_smtp_pass" class=" col-md-2"><?php echo app_lang('email_smtp_password'); ?></label>
                                <div class="col-md-10">
                                    <?php
                                    echo form_password(array(
                                        "id" => "email_smtp_pass",
                                        "name" => "email_smtp_pass",
                                        "value" => get_setting('email_smtp_pass') ? "******" : "",
                                        "class" => "form-control",
                                        "placeholder" => app_lang('email_smtp_password'),
                                        "data-rule-required" => true,
                                        "data-msg-required" => app_lang("field_required"),
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="email_smtp_port" class=" col-md-2"><?php echo app_lang('email_smtp_port'); ?></label>
                                <div class="col-md-10">
                                    <?php
                                    echo form_input(array(
                                        "id" => "email_smtp_port",
                                        "name" => "email_smtp_port",
                                        "value" => get_setting('email_smtp_port'),
                                        "class" => "form-control",
                                        "placeholder" => app_lang('email_smtp_port'),
                                        "data-rule-required" => true,
                                        "data-msg-required" => app_lang("field_required"),
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label for="email_smtp_security_type" class=" col-md-2"><?php echo app_lang('security_type'); ?></label>
                                <div class="col-md-10">
                                    <?php
                                    echo form_dropdown(
                                            "email_smtp_security_type", array(
                                        "none" => "-",
                                        "tls" => "TLS",
                                        "ssl" => "SSL"
                                            ), get_setting('email_smtp_security_type'), "class='select2 mini'"
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="send_test_mail_to" class=" col-md-2"><?php echo app_lang('send_test_mail_to'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "send_test_mail_to",
                                    "name" => "send_test_mail_to",
                                    "value" => get_setting('send_test_mail_to'),
                                    "class" => "form-control",
                                    "placeholder" => "Keep it blank if you are not interested to send test mail",
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#email-settings-form").appForm({
            isModal: false,
            onSubmit: function () {
                appLoader.show();
            },
            onSuccess: function (result) {
                appLoader.hide();
                appAlert.success(result.message, {duration: 10000});
            },
            onError: function (result) {
                appLoader.hide();
                appAlert.error(result.message);
            }
        });

        $("#use_smtp").click(function () {
            if ($(this).is(":checked")) {
                $("#smtp_settings").removeClass("hide");
            } else {
                $("#smtp_settings").addClass("hide");
            }
        });

        $("#email-settings-form .select2").select2();
    });
</script>