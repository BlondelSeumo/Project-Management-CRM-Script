<div class="list-group">
    <?php
    if (count($notifications)) {
        foreach ($notifications as $notification) {
            ?>
            <a class="list-group-item d-flex" href="<?php
            $message_id = $notification->message_id ? $notification->message_id : $notification->id;
            echo get_uri("messages/inbox/" . $message_id)
            ?>">
                <div class="flex-shrink-0">
                    <span class="avatar avatar-xs">
                        <img src="<?php echo get_avatar($notification->user_image); ?>" alt="..." />
                    </span>
                </div>
                <div class="w-100 ps-2">
                    <div class="mb5">
                        <strong><?php echo $notification->user_name; ?></strong>
                        <span class="text-off float-end"><small><?php echo format_to_relative_time($notification->created_at); ?></small></span>
                    </div>
                    <div><?php echo app_lang("sent_you_a_message"); ?></div>
                </div>
            </a>
            <?php
        }
    } else {
        ?>
        <span class="list-group-item"><?php echo app_lang("no_new_messages"); ?></span>               
    <?php } ?>
</div>
