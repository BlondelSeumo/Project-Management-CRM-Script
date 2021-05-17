<?php echo view("includes/cropbox"); ?>
<div id="page-content" class="clearfix">
    <div class="bg-success clearfix">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="row p20">
                        <?php echo view("users/profile_image_section"); ?>
                    </div>
                </div>

                <div class="col-md-6 text-center cover-widget">
                    <div class="row p20">
                        <?php
                        if ($show_projects_count) {
                            echo count_project_status_widget($user_info->id);
                        }

                        echo count_total_time_widget($user_info->id);
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>


    <ul id="team-member-view-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs scrollable-tabs rounded-0" role="tablist">

        <?php if ($show_timeline) { ?>
            <li><a  role="presentation"  href="javascript:;" data-bs-target="#tab-timeline"> <?php echo app_lang('timeline'); ?></a></li>
        <?php } ?>

        <?php if ($show_general_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("team_members/general_info/" . $user_info->id); ?>" data-bs-target="#tab-general-info"> <?php echo app_lang('general_info'); ?></a></li>
        <?php } ?>

        <?php if ($show_general_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("team_members/social_links/" . $user_info->id); ?>" data-bs-target="#tab-social-links"> <?php echo app_lang('social_links'); ?></a></li>
        <?php } ?>

        <?php if ($show_job_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("team_members/job_info/" . $user_info->id); ?>" data-bs-target="#tab-job-info"> <?php echo app_lang('job_info'); ?></a></li>
        <?php } ?>

        <?php if ($show_account_settings) { ?>
            <li><a role="presentation" href="<?php echo_uri("team_members/account_settings/" . $user_info->id); ?>" data-bs-target="#tab-account-settings"> <?php echo app_lang('account_settings'); ?></a></li>
        <?php } ?>

        <?php if ($login_user->id == $user_info->id) { ?>
            <li><a role="presentation" href="<?php echo_uri("team_members/my_preferences/" . $user_info->id); ?>" data-bs-target="#tab-my-preferences"> <?php echo app_lang('my_preferences'); ?></a></li>
        <?php } ?>
        <?php if ($login_user->id == $user_info->id) { ?>
            <li><a role="presentation" href="<?php echo_uri("left_menus/index/user"); ?>" data-bs-target="#tab-user-left-menu"> <?php echo app_lang('left_menu'); ?></a></li>
        <?php } ?>

        <?php if ($show_general_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("team_members/files/" . $user_info->id); ?>" data-bs-target="#tab-files"> <?php echo app_lang('files'); ?></a></li>
        <?php } ?>

        <?php if ($show_projects) { ?>
            <li><a role="presentation" href="<?php echo_uri("team_members/projects_info/" . $user_info->id); ?>" data-bs-target="#tab-projects-info"><?php echo app_lang('projects'); ?></a></li>
        <?php } ?> 

        <?php if ($show_attendance) { ?>
            <li><a role="presentation" href="<?php echo_uri("team_members/attendance_info/" . $user_info->id); ?>" data-bs-target="#tab-attendance-info"> <?php echo app_lang('attendance'); ?></a></li>
        <?php } ?>

        <?php if ($show_leave) { ?>
            <li><a role="presentation" href="<?php echo_uri("team_members/leave_info/" . $user_info->id); ?>" data-bs-target="#tab-leave-info"><?php echo app_lang('leaves'); ?></a></li>
        <?php } ?>
        <?php if ($show_expense_info) { ?>
            <li><a role="presentation" href="<?php echo_uri("team_members/expense_info/" . $user_info->id); ?>" data-bs-target="#tab-expense-info"><?php echo app_lang('expenses'); ?></a></li>
        <?php } ?>

    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade active pl15 pr15 mb15" id="tab-timeline">
            <?php echo timeline_widget(array("limit" => 20, "offset" => 0, "is_first_load" => true, "user_id" => $user_info->id)); ?>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tab-general-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-social-links"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-job-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-account-settings"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-my-preferences"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-user-left-menu"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-projects-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-attendance-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-leave-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-expense-info"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".upload").change(function () {
            if (typeof FileReader == 'function' && !$(this).hasClass("hidden-input-file")) {
                showCropBox(this);
            } else {
                $("#profile-image-form").submit();
            }
        });
        $("#profile_image").change(function () {
            $("#profile-image-form").submit();
        });


        $("#profile-image-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "profile_image") {
                        var profile_image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = profile_image;
                    }
                });
            },
            onSuccess: function (result) {
                if (typeof FileReader == 'function' && !result.reload_page) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    location.reload();
                }
            }
        });

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "general") {
                $("[data-bs-target='#tab-general-info']").trigger("click");
            } else if (tab === "account") {
                $("[data-bs-target='#tab-account-settings']").trigger("click");
            } else if (tab === "social") {
                $("[data-bs-target='#tab-social-links']").trigger("click");
            } else if (tab === "job_info") {
                $("[data-bs-target='#tab-job-info']").trigger("click");
            } else if (tab === "my_preferences") {
                $("[data-bs-target='#tab-my-preferences']").trigger("click");
            } else if (tab === "left_menu") {
                $("[data-bs-target='#tab-user-left-menu']").trigger("click");
            }
        }, 210);

    });
</script>