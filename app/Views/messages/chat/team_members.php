<?php if ($users) { ?>
    <div id="js-chat-team-members-list">
        <?php
        foreach ($users as $user) {
            $online = "";
            if ($user->last_online && is_online_user($user->last_online)) {
                $online = "<i class='online'></i>";
            }
            ?>
            <div class="message-row js-message-row-of-<?php echo $page_type; ?>" data-id="<?php echo $user->id; ?>" data-index="1" data-reply="">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <span class="avatar avatar-xs">
                            <img alt="..." src="<?php echo get_avatar($user->image); ?>">
                            <?php echo $online; ?>
                        </span>
                    </div>
                    <div class="w-100 ps-2">
                        <div class="mb5">
                            <strong> <?php echo $user->first_name . " " . $user->last_name; ?></strong>
                        </div>
                        <?php
                        $subline = $user->job_title;
                        if ($user->user_type === "client" && $user->company_name) {
                            $subline = $user->company_name;
                        }
                        ?>
                        <small class="text-off w200 d-block"><?php echo $subline; ?></small>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
<?php } else { ?>

    <div class="chat-no-messages text-off text-center">
        <i data-feather="frown" height="4rem" width="4rem"></i><br />
        <?php echo app_lang("no_users_found"); ?>
    </div>

<?php } ?>

