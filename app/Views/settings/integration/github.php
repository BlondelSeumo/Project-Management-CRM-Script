<div class="card no-border clearfix mb0">

    <?php echo form_open(get_uri("settings/save_github_settings"), array("id" => "github-form", "class" => "general-form dashed-row", "role" => "form")); ?>

    <div class="card-body">

        <div class="form-group">
            <div class="row">
                <label for="enable_github_commit_logs_in_tasks" class="col-md-3"><?php echo app_lang('enable_github_commit_logs_in_tasks'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_checkbox("enable_github_commit_logs_in_tasks", get_setting("enable_github_commit_logs_in_tasks"), get_setting("enable_github_commit_logs_in_tasks") ? true : false, "id='enable_github_commit_logs_in_tasks' class='form-check-input'");
                    ?>                       
                </div>
            </div>
        </div>

        <div class="github-details-area <?php echo get_setting("enable_github_commit_logs_in_tasks") ? "" : "hide" ?>">
            <div class="form-group">
                <div class="row">
                    <label for="" class=" col-md-12">
                        <?php echo app_lang("add_webhook_in_your_repository_at") . " " . anchor("https://github.com/", "GitHub", array("target" => "_blank")); ?>
                    </label>
                </div>
            </div>

            <div class="form-group clearfix">
                <div class="row">
                    <label for="webhook_listener_link_of_github" class=" col-md-3"><?php echo app_lang('webhook_listener_link'); ?></label>
                    <div class=" col-md-9">
                        <!--Don't add space between this spans. It'll make problem on copying code-->
                        <span id="webhook_listener_link_of_github"><?php echo get_uri("webhooks_listener/github") . "/" . get_setting("enable_github_commit_logs_in_tasks"); ?></span><span id="reset-key-github" class="p10 ml15 clickable"><i data-feather="refresh-cw" class="icon-16"></i></span>
                    </div>
                </div>
            </div>

            <div class="form-group clearfix">
                <div class="row">
                    <div class="col-md-12">
                        <i data-feather="alert-triangle" class="icon-16 text-danger"></i>
                        <span><?php echo app_lang("bitbucket_info_text"); ?></span>
                    </div>
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
        var githubUrl = "<?php echo get_uri('webhooks_listener/github'); ?>",
                $enableGithub = $("#enable_github_commit_logs_in_tasks"),
                $githubDetailsArea = $(".github-details-area");

        //for security purpose, add random string at the end of webhook listener link
        var setGithubUrl = function () {
            var randomString = getRandomAlphabet(20);
            $("#enable_github_commit_logs_in_tasks").val(randomString);
            $("#webhook_listener_link_of_github").html(githubUrl + "/" + randomString);
        };

        $("#github-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        //show/hide github details area
        $enableGithub.click(function () {
            if ($(this).is(":checked")) {
                $githubDetailsArea.removeClass("hide");
            } else {
                $githubDetailsArea.addClass("hide");
            }
        });

        //prepare url at first time
        if (!$enableGithub.val()) {
            setGithubUrl();
        }

        //reset url
        $("#reset-key-github").click(function () {
            setGithubUrl();
        });

    });
</script>