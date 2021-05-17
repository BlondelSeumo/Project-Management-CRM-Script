<div class="rise-chat-header box">
    <div class="box-content chat-back" id="js-back-to-team-members-tab">
        <i data-feather="chevron-left" class="icon-16"></i>
    </div>
    <div class="box-content chat-title">
        <div>
            <?php echo $user_name; ?>
        </div>
    </div>
</div>
<div id="js-single-user-chat-list" class="rise-chat-body full-height">

    <div class='clearfix p10 b-b'>
        <?php
        if (get_setting("module_chat")) {
            echo modal_anchor(get_uri("messages/modal_form/" . $user_id), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang("new_conversation"), array("class" => "btn btn-default col-md-12 col-sm-12 col-xs-12", "title" => app_lang('send_message')));
        }
        ?>
    </div>
    <div id="chatlist-of-user">
        <?php
        foreach ($messages as $message) {
            $online = "";
            if ($message->last_online && is_online_user($message->last_online)) {
                $online = "<i class='online'></i>";
            }

            $status = "";
            $last_message_from = $message->from_user_id;
            if ($message->last_from_user_id) {
                $last_message_from = $message->last_from_user_id;
            }

            if ($message->status === "unread" && $last_message_from != $login_user->id) {
                $status = "unread";
            }
            ?>
            <div class='js-message-row message-row <?php echo $status; ?>' data-id='<?php echo $message->id; ?>' data-index='<?php echo $message->id; ?>'>
                <div class="d-flex">
                    <div class='flex-shrink-0'>
                        <span class='avatar avatar-xs'>
                            <img src='<?php echo get_avatar($message->user_image); ?>' />
                            <?php echo $online; ?>
                        </span>
                    </div>
                    <div class='w-100 ps-2'>
                        <div class='mb5'>
                            <strong><?php echo $message->user_name; ?></strong>
                            <span class='text-off float-end time'><?php echo format_to_relative_time($message->message_time); ?></span>
                        </div>
                        <?php echo $message->subject; ?>
                    </div>
                </div>
            </div>

            <?php
        }
        ?>
    </div>
</div>

<script>
    $("#js-back-to-team-members-tab").click(function () {
        loadChatTabs("<?php echo $tab_type; ?>");
    });
</script>