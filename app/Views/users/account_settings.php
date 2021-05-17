<div class="tab-content">
    <?php
    $url = "team_members";
    $show_submit = true;
    if ($user_info->user_type === "client") {
        $url = "clients";
        if (isset($can_edit_clients) && !$can_edit_clients) {
            $show_submit = false;
        }
    }
    echo form_open(get_uri($url . "/save_account_settings/" . $user_info->id), array("id" => "account-info-form", "class" => "general-form dashed-row white", "role" => "form"));
    ?>
    <div class="card">
        <div class=" card-header">
            <h4><?php echo app_lang('account_settings'); ?></h4>
        </div>
        <div class="card-body">
            <input type="hidden" name="first_name" value="<?php echo $user_info->first_name; ?>" />
            <input type="hidden" name="last_name" value="<?php echo $user_info->last_name; ?>" />
            <div class="form-group">
                <div class="row">
                    <label for="email" class=" col-md-2"><?php echo app_lang('email'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "email",
                            "name" => "email",
                            "value" => $user_info->email,
                            "class" => "form-control",
                            "placeholder" => app_lang('email'),
                            "autocomplete" => "off",
                            "data-rule-email" => true,
                            "data-msg-email" => app_lang("enter_valid_email"),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="password" class=" col-md-2"><?php echo app_lang('password'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_password(array(
                            "id" => "password",
                            "name" => "password",
                            "class" => "form-control",
                            "placeholder" => app_lang('password'),
                            "autocomplete" => "off",
                            "data-rule-minlength" => 6,
                            "data-msg-minlength" => app_lang("enter_minimum_6_characters"),
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="retype_password" class=" col-md-2"><?php echo app_lang('retype_password'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_password(array(
                            "id" => "retype_password",
                            "name" => "retype_password",
                            "class" => "form-control",
                            "placeholder" => app_lang('retype_password'),
                            "autocomplete" => "off",
                            "data-rule-equalTo" => "#password",
                            "data-msg-equalTo" => app_lang("enter_same_value")
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <?php if ($user_info->user_type === "staff" && $login_user->is_admin) { ?>
                <div class="form-group">
                    <div class="row">
                        <label for="role" class=" col-md-2"><?php echo app_lang('role'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            if ($login_user->id == $user_info->id) {
                                echo "<div class='ml15'>" . app_lang("admin") . "</div>";
                            } else {
                                echo form_dropdown("role", $role_dropdown, array($user_info->role_id), "class='select2' id='user-role'");
                                ?>
                                <div id="user-role-help-block" class="help-block ml10 <?php echo $user_info->role_id === "admin" ? "" : "hide" ?>"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("admin_user_has_all_power"); ?></div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ($login_user->is_admin && $user_info->id !== $login_user->id) { ?>
                <div class="form-group">
                    <div class="row">
                        <label for="disable_login" class="col-md-2"><?php echo app_lang('disable_login'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("disable_login", "1", $user_info->disable_login ? true : false, "id='disable_login' class='ml15 form-check-input mt-2'");
                            ?>
                            <span id="disable-login-help-block" class="ml10 <?php echo $user_info->disable_login ? "" : "hide" ?>"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("disable_login_help_message"); ?></span>
                        </div>
                    </div>
                </div>

                <?php if ($user_info->user_type === "staff") { ?>
                    <div class="form-group">
                        <div class="row">
                            <label for="user_status" class="col-md-2"><?php echo app_lang('mark_as_inactive'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_checkbox("status", "inactive", $user_info->status === "inactive" ? true : false, "id='user_status' class='ml15 form-check-input mt-2'");
                                ?>
                                <span id="user-status-help-block" class="ml10 <?php echo $user_info->status === "inactive" ? "" : "hide" ?>"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("mark_as_inactive_help_message"); ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            <?php } ?>

            <?php if ($user_info->user_type === "client" && $login_user->is_admin) { ?>
                <div class="form-group hide" id="resend_login_details_section">
                    <div class="row">
                        <label for="email_login_details" class="col-md-2"><?php echo app_lang('email_login_details'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("email_login_details", "1", false, "id='email_login_details' class='ml15 form-check-input mt-2'");
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if ($show_submit) { ?>
            <div class="card-footer rounded-0">
                <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
            </div>
        <?php } ?>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#account-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $("#account-info-form .select2").select2();


        //show/hide asmin permission help message
        $("#user-role").change(function () {
            if ($(this).val() === "admin") {
                $("#user-role-help-block").removeClass("hide");
            } else {
                $("#user-role-help-block").addClass("hide");
            }
        });

        //show/hide disable login help message
        $("#disable_login").click(function () {
            if ($(this).is(":checked")) {
                $("#disable-login-help-block").removeClass("hide");
            } else {
                $("#disable-login-help-block").addClass("hide");
            }
        });

        //show/hide user status help message
        $("#user_status").click(function () {
            if ($(this).is(":checked")) {
                $("#user-status-help-block").removeClass("hide");
            } else {
                $("#user-status-help-block").addClass("hide");
            }
        });

        //the checkbox will be enable if anyone enter the password
        $("#password").change(function () {
            var password = $("#password").val();
            if (password) {
                $("#resend_login_details_section").removeClass("hide");
            } else {
                $("#resend_login_details_section").addClass("hide");
            }
        });
    });
</script>    