<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "integration";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>
        <div class="col-sm-9 col-lg-10">

            <div class="card no-border clearfix ">

                <ul id="integration-tab" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
                    <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("integration"); ?></h4></li>
                    <li><a role="presentation"  href="<?php echo_uri("settings/re_captcha/"); ?>" data-bs-target="#integration-re-captcha">reCAPTCHA</a></li>
                    <li><a id="google_drive" role="presentation" href="<?php echo_uri("settings/google_drive/"); ?>" data-bs-target="#integration-google-drive">Google Drive</a></li>
                    <li><a role="presentation" class="" href="<?php echo_uri("settings/push_notification/"); ?>" data-bs-target="#integration-push-notification"><?php echo app_lang("pusher"); ?></a></li>
                    <li><a role="presentation" class="" href="<?php echo_uri("settings/slack/"); ?>" data-bs-target="#integration-slack">Slack</a></li>
                    <li><a role="presentation" class="" href="<?php echo_uri("settings/bitbucket/"); ?>" data-bs-target="#integration-bitbucket">Bitbucket</a></li>
                    <li><a role="presentation" class="" href="<?php echo_uri("settings/github/"); ?>" data-bs-target="#integration-github">GitHub</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade" id="integration-re-captcha"></div>
                    <div role="tabpanel" class="tab-pane fade" id="integration-google-drive"></div>
                    <div role="tabpanel" class="tab-pane fade" id="integration-push-notification"></div>
                    <div role="tabpanel" class="tab-pane fade" id="integration-slack"></div>
                    <div role="tabpanel" class="tab-pane fade" id="integration-bitbucket"></div>
                    <div role="tabpanel" class="tab-pane fade" id="integration-github"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "google_drive") {
                $("[data-bs-target='#integration-google-drive']").trigger("click");
            } else if (tab === "push_notification") {
                $("[data-bs-target='#integration-push-notification']").trigger("click");
            } else if (tab === "slack") {
                $("[data-bs-target='#integration-slack']").trigger("click");
            } else if (tab === "bitbucket") {
                $("[data-bs-target='#integration-bitbucket']").trigger("click");
            } else if (tab === "github") {
                $("[data-bs-target='#integration-github']").trigger("click");
            }
        }, 210);
    });

</script>