<div class="card mb0">
    <div class="page-title clearfix notificatio-plate-title-area">
        <span class="float-start"><strong><?php echo app_lang('notifications'); ?></strong></span>
        <span class="float-end"><?php echo get_team_member_profile_link($login_user->id . '/my_preferences', app_lang('settings')); ?></span>
        <span class="float-end dot">&CenterDot;</span>
        <span class="float-end"><?php echo js_anchor(app_lang("mark_all_as_read"), array("class" => "mark-all-as-read-button")); ?></span>
    </div>

    <div class="list-group" id="notificaion-popup-list" style="">
        <?php
        $view_data["notifications"] = $notifications;
        echo view("notifications/list_data", $view_data);
        ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //don't apply scrollbar for mobile devices
        if ($(window).width() > 640) {
            if ($('#notificaion-popup-list').height() >= 400) {
                initScrollbar('#notificaion-popup-list', {
                    setHeight: 400
                });
            } else {
                $('#notificaion-popup-list').css({"overflow-y": "auto"});
            }

        }

        //mark all notification as read
        $('body').on('click', '.mark-all-as-read-button', function (e) {
            appLoader.show();

            //stop default dropdown operation
            e.stopPropagation();
            e.preventDefault();

            $.ajax({
                url: "<?php echo get_uri('notifications/set_notification_status_as_read') ?>",
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        $(".unread-notification").removeClass("unread-notification");
                        appAlert.success(result.message, {duration: 10000});
                        appLoader.hide();
                    }
                }
            });
        });
    });
</script>
