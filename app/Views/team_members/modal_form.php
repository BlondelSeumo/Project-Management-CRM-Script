<?php echo form_open(get_uri("team_members/add_team_member"), array("id" => "team_member-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">

        <div class="form-widget">
            <div class="widget-title clearfix">
                <div class="row">
                    <div id="general-info-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('general_info'); ?></strong></div>
                    <div id="job-info-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong>  <?php echo app_lang('job_info'); ?></strong></div>
                    <div id="account-info-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong>  <?php echo app_lang('account_settings'); ?></strong></div> 
                </div>
            </div>

            <div class="progress ml15 mr15">
                <div id="form-progress-bar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                </div>
            </div>
        </div>

        <div class="tab-content mt15">
            <div role="tabpanel" class="tab-pane active" id="general-info-tab">
                <div class="form-group">
                    <div class="row">
                        <label for="first_name" class=" col-md-3"><?php echo app_lang('first_name'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "first_name",
                                "name" => "first_name",
                                "class" => "form-control",
                                "placeholder" => app_lang('first_name'),
                                "autofocus" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="last_name" class=" col-md-3"><?php echo app_lang('last_name'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "last_name",
                                "name" => "last_name",
                                "class" => "form-control",
                                "placeholder" => app_lang('last_name'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="address" class=" col-md-3"><?php echo app_lang('mailing_address'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_textarea(array(
                                "id" => "address",
                                "name" => "address",
                                "class" => "form-control",
                                "placeholder" => app_lang('mailing_address')
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="phone" class=" col-md-3"><?php echo app_lang('phone'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "phone",
                                "name" => "phone",
                                "class" => "form-control",
                                "placeholder" => app_lang('phone')
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="gender" class=" col-md-3"><?php echo app_lang('gender'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_radio(array(
                                "id" => "gender_male",
                                "name" => "gender",
                                "class" => "form-check-input",
                                    ), "male", true);
                            ?>
                            <label for="gender_male" class="mr15"><?php echo app_lang('male'); ?></label> <?php
                            echo form_radio(array(
                                "id" => "gender_female",
                                "name" => "gender",
                                "class" => "form-check-input",
                                    ), "female", false);
                            ?>
                            <label for="gender_female" class=""><?php echo app_lang('female'); ?></label>
                        </div>
                    </div>
                </div>


                <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

            </div>
            <div role="tabpanel" class="tab-pane" id="job-info-tab">
                <div class="form-group">
                    <div class="row">
                        <label for="job_title" class=" col-md-3"><?php echo app_lang('job_title'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "job_title",
                                "name" => "job_title",
                                "class" => "form-control",
                                "placeholder" => app_lang('job_title'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="salary" class=" col-md-3"><?php echo app_lang('salary'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "salary",
                                "name" => "salary",
                                "class" => "form-control",
                                "placeholder" => app_lang('salary')
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="salary_term" class=" col-md-3"><?php echo app_lang('salary_term'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "salary_term",
                                "name" => "salary_term",
                                "class" => "form-control",
                                "placeholder" => app_lang('salary_term')
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="date_of_hire" class=" col-md-3"><?php echo app_lang('date_of_hire'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "date_of_hire",
                                "name" => "date_of_hire",
                                "class" => "form-control",
                                "placeholder" => app_lang('date_of_hire'),
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="account-info-tab">
                <div class="form-group">
                    <div class="row">
                        <label for="email" class=" col-md-3"><?php echo app_lang('email'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "email",
                                "name" => "email",
                                "class" => "form-control",
                                "placeholder" => app_lang('email'),
                                "autofocus" => true,
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
                        <label for="password" class="col-md-3"><?php echo app_lang('password'); ?></label>
                        <div class=" col-md-8">
                            <div class="input-group">
                                <?php
                                echo form_password(array(
                                    "id" => "password",
                                    "name" => "password",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('password'),
                                    "autocomplete" => "off",
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                    "data-rule-minlength" => 6,
                                    "data-msg-minlength" => app_lang("enter_minimum_6_characters"),
                                    "autocomplete" => "off",
                                    "style" => "z-index:auto;"
                                ));
                                ?>
                                <button type="button" class="input-group-text clickable" id="generate_password"><span data-feather="key" class="icon-16"></span> <?php echo app_lang('generate'); ?></button>
                            </div>
                        </div>
                        <div class="col-md-1 p0">
                            <a href="#" id="show_hide_password" class="btn btn-default" title="<?php echo app_lang('show_text'); ?>"><span data-feather="eye" class="icon-16"></span></a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="role" class="col-md-3"><?php echo app_lang('role'); ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("role", $role_dropdown, array(), "class='select2' id='user-role'");
                            ?>
                            <div id="user-role-help-block" class="help-block ml10 hide"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("admin_user_has_all_power"); ?></div>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-md-12">  
                        <?php
                        echo form_checkbox("email_login_details", "1", true, "id='email_login_details' class='form-check-input'");
                        ?> <label for="email_login_details"><?php echo app_lang('email_login_details'); ?></label>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="modal-footer">
    <button class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button id="form-previous" type="button" class="btn btn-default hide"><span data-feather="arrow-left-circle" class="icon-16"></span> <?php echo app_lang('previous'); ?></button>
    <button id="form-next" type="button" class="btn btn-info text-white"><span data-feather="arrow-right-circle" class="icon-16"></span> <?php echo app_lang('next'); ?></button>
    <button id="form-submit" type="button" class="btn btn-primary hide"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#team_member-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    $("#team_member-table").appTable({newData: result.data, dataId: result.id});
                }
            },
            onSubmit: function () {
                $("#form-previous").attr('disabled', 'disabled');
            },
            onAjaxSuccess: function () {
                $("#form-previous").removeAttr('disabled');
            }
        });

        $("#team_member-form input").keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                if ($('#form-submit').hasClass('hide')) {
                    $("#form-next").trigger('click');
                } else {
                    $("#team_member-form").trigger('submit');
                }
            }
        });
        setTimeout(function () {
            $("#first_name").focus();
        }, 200);
        $("#team_member-form .select2").select2();

        setDatePicker("#date_of_hire");

        $("#form-previous").click(function () {
            var $generalTab = $("#general-info-tab"),
                    $jobTab = $("#job-info-tab"),
                    $accountTab = $("#account-info-tab"),
                    $previousButton = $("#form-previous"),
                    $nextButton = $("#form-next"),
                    $submitButton = $("#form-submit");

            if ($accountTab.hasClass("active")) {
                $accountTab.removeClass("active");
                $jobTab.addClass("active");
                $nextButton.removeClass("hide");
                $submitButton.addClass("hide");
            } else if ($jobTab.hasClass("active")) {
                $jobTab.removeClass("active");
                $generalTab.addClass("active");
                $previousButton.addClass("hide");
                $nextButton.removeClass("hide");
                $submitButton.addClass("hide");
            }
        });

        $("#form-next").click(function () {
            var $generalTab = $("#general-info-tab"),
                    $jobTab = $("#job-info-tab"),
                    $accountTab = $("#account-info-tab"),
                    $previousButton = $("#form-previous"),
                    $nextButton = $("#form-next"),
                    $submitButton = $("#form-submit");
            if (!$("#team_member-form").valid()) {
                return false;
            }
            if ($generalTab.hasClass("active")) {
                $generalTab.removeClass("active");
                $jobTab.addClass("active");
                $previousButton.removeClass("hide");
                $("#form-progress-bar").width("35%");
                $("#general-info-label").find("svg").remove();
                $("#general-info-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
                feather.replace();
                $("#team_member_id").focus();
            } else if ($jobTab.hasClass("active")) {
                $jobTab.removeClass("active");
                $accountTab.addClass("active");
                $previousButton.removeClass("hide");
                $nextButton.addClass("hide");
                $submitButton.removeClass("hide");
                $("#form-progress-bar").width("72%");
                $("#job-info-label").find("svg").remove();
                $("#job-info-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
                feather.replace();
                $("#username").focus();
            }
        });

        $("#form-submit").click(function () {
            $("#team_member-form").trigger('submit');
        });

        $("#generate_password").click(function () {
            $("#password").val(getRndomString(8));
        });

        $("#show_hide_password").click(function () {
            var $target = $("#password"),
                    type = $target.attr("type");
            if (type === "password") {
                $(this).attr("title", "<?php echo app_lang("hide_text"); ?>");
                $(this).html("<span data-feather='eye-off' class='icon-16'></span>");
                feather.replace();
                $target.attr("type", "text");
            } else if (type === "text") {
                $(this).attr("title", "<?php echo app_lang("show_text"); ?>");
                $(this).html("<span data-feather='eye' class='icon-16'></span>");
                feather.replace();
                $target.attr("type", "password");
            }
        });

        $("#user-role").change(function () {
            if ($(this).val() === "admin") {
                $("#user-role-help-block").removeClass("hide");
            } else {
                $("#user-role-help-block").addClass("hide");
            }
        });
    });
</script>