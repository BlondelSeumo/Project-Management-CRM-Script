<?php foreach ($reply_list as $reply) { ?>
    <div id="reply-content-container-<?php echo $reply->id; ?>"  class="d-flex mb15 b-l reply-container">
        <div class="flex-shrink-0 pl15 pr10">
            <span class="avatar avatar-xs">
                <img src="<?php echo get_avatar($reply->created_by_avatar); ?>" alt="..." />
            </span>
        </div>
        <div class="w-100">
            <div class="mb5">
                <?php echo get_team_member_profile_link($reply->created_by, $reply->created_by_user, array("class" => "dark strong")); ?>
                <small><span class="text-off"><?php echo format_to_relative_time($reply->created_at); ?></span></small>


                <?php if ($login_user->is_admin || $reply->created_by == $login_user->id) { ?>
                    <span class="float-end dropdown reply-dropdown">
                        <div class="text-off dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true" >
                            <small class="p5"> <i data-feather="chevron-down" class="icon-16 clickable"></i></small>
                        </div>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation"><?php echo ajax_anchor(get_uri("timeline/delete/$reply->id"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("class" => "dropdown-item", "title" => app_lang('delete'), "data-fade-out-on-success" => "#reply-content-container-$reply->id")); ?> </li>
                        </ul>
                    </span>
                <?php } ?>
            </div>

            <p><?php echo nl2br(link_it($reply->description)); ?></p>
        </div>
    </div>
<?php } ?>