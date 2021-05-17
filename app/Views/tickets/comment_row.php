<div class="d-flex b-b p10 m0 bg-white">
    <div class="flex-shrink-0 pl10 mr10">
        <span class="avatar avatar-sm">
            <?php if (!$comment->created_by || $comment->created_by == 999999999) { ?>
                <img src="<?php echo get_avatar("system_bot"); ?>" alt="..." />
            <?php } else { ?>
                <img src="<?php echo get_avatar($comment->created_by_avatar); ?>" alt="..." />
                <?php
            }
            ?>
        </span>
    </div>
    <div>
        <div>
            <?php
            if ($comment->created_by == 999999999) {
                //user is an app boot for auto reply tickets
                echo "<span class='dark strong'>" . get_setting('app_title') . "</span>";
            } else if (!$comment->created_by && $comment->creator_email) {
                //user is an undefined client from email
                echo "<span class='dark strong'>" . $comment->creator_name . " [" . app_lang("unknown_client") . "]" . "</span>";
            } else {
                if ($comment->user_type === "staff") {
                    echo get_team_member_profile_link($comment->created_by, $comment->created_by_user, array("class" => "dark strong"));
                } else {
                    echo get_client_contact_profile_link($comment->created_by, $comment->created_by_user, array("class" => "dark strong"));
                }
            }
            ?>
            <small><span class="text-off"><?php echo format_to_relative_time($comment->created_at); ?></span></small>

            <?php if (!$comment->created_by && $comment->creator_email) { ?>
                <div class="block text-off"><?php echo $comment->creator_email; ?></div>
            <?php } ?>
        </div>
        <p><?php echo nl2br(link_it($comment->description)); ?></p>
        <div class="comment-image-box clearfix">

            <?php
            $files = unserialize($comment->files);
            $total_files = count($files);
            echo view("includes/timeline_preview", array("files" => $files));


            if ($total_files) {
                $download_caption = app_lang('download');
                if ($total_files > 1) {
                    $download_caption = sprintf(app_lang('download_files'), $total_files);
                }
                echo "<i data-feather='paperclip' class='icon-16'></i>";
                echo anchor(get_uri("tickets/download_comment_files/" . $comment->id), $download_caption, array("class" => "float-end", "title" => $download_caption));
            }
            ?>
        </div>
    </div>
</div>