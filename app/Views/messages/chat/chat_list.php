<?php
if ($messages) {

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
} else {
    ?>

    <div class="chat-no-messages text-off text-center">
        <i data-feather="message-circle" height="4rem" width="4rem"></i><br />
        <?php echo app_lang("no_messages_text"); ?>
    </div>

<?php } ?>

<script>
    $(document).ready(function () {
        //trigger the users/clients list tab if there is no messages
<?php if (!$messages) { ?>
            setTimeout(function () {
                if ($("#chat-users-tab-button").length) {
                    $("#chat-users-tab-button a").trigger("click");
                } else {
                    $("#chat-clients-tab-button a").trigger("click");
                }
            }, 500);
<?php } ?>
    });
</script>