<?php foreach ($reply_list as $reply) { ?>
    <div id="prject-comment-reply-container-<?php echo $reply->id; ?>" class="d-flex mb15 b-l reply-container">
        <div class="flex-shrink-0 pl15">
            <span class="avatar avatar-xs">
                <img src="<?php echo get_avatar($reply->created_by_avatar); ?>" alt="..." />
            </span>
        </div>
        <div class="w-100 ps-2">
            <div class="mb5">
                <?php
                if ($reply->user_type === "staff") {
                    echo get_team_member_profile_link($reply->created_by, $reply->created_by_user, array("class" => "dark strong"));
                } else {
                    echo get_client_contact_profile_link($reply->created_by, $reply->created_by_user, array("class" => "dark strong"));
                }
                ?>
                <small><span class="text-off"><?php echo format_to_relative_time($reply->created_at); ?></span></small>


                <?php if ($login_user->is_admin || $reply->created_by == $login_user->id) { ?>
                    <span class="float-end dropdown reply-dropdown">
                        <div class="text-off dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true" >
                            <i data-feather="chevron-down" class="icon-16 clickable"></i>
                        </div>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation"><?php echo ajax_anchor(get_uri("projects/delete_comment/$reply->id"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("class" => "dropdown-item", "title" => app_lang('delete'), "data-fade-out-on-success" => "#prject-comment-reply-container-$reply->id")); ?> </li>
                        </ul>
                    </span>
                <?php } ?>

            </div>
            <p><?php echo convert_mentions($reply->description); ?></p>
            <div class="comment-image-box clearfix">
                <?php
                if ($reply->files) {
                    $files = unserialize($reply->files);
                    $total_files = count($files);
                    echo view("includes/timeline_preview", array("files" => $files));
                    if ($total_files) {
                        $download_caption = app_lang('download');
                        if ($total_files > 1) {
                            $download_caption = sprintf(app_lang('download_files'), $total_files);
                        }

                        echo "<i data-feather='paperclip' class='icon-16'></i>";
                        echo anchor(get_uri("projects/download_comment_files/" . $reply->id), $download_caption, array("class" => "float-end", "title" => $download_caption));
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>