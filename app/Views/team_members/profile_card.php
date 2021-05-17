<div id="page-content" class="page-wrapper clearfix">
    <div class="page-title clearfix mb20 no-border rounded">
        <h1><?php echo app_lang('team_members'); ?></h1>
        <div class="title-button-group">
            <?php
            echo anchor(get_uri("team_members"), '<i data-feather="menu" class="icon-16"></i>', array("class" => "btn btn-default btn-sm me-0", "title" => app_lang('list_view')));
            echo js_anchor("<i data-feather='grid' class='icon-16'></i>", array("class" => "btn btn-default btn-sm active ms-0"));

            if ($login_user->is_admin) {
                echo modal_anchor(get_uri("team_members/invitation_modal"), "<i data-feather='mail' class='icon-16'></i> " . app_lang('send_invitation'), array("class" => "btn btn-default", "title" => app_lang('send_invitation')));
                echo modal_anchor(get_uri("team_members/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_team_member'), array("class" => "btn btn-default", "title" => app_lang('add_team_member')));
            }
            ?>
        </div>
    </div>

    <div class="row">
        <?php foreach ($team_members as $team_member) { ?>
            <div class="col-md-3 col-sm-6">
                <div class="card  text-center ">
                    <div class="card-body">
                        <span class="avatar avatar-md mt15"><img src="<?php echo get_avatar($team_member->image); ?>" alt="..."></span> 
                        <h4><?php echo $team_member->first_name . " " . $team_member->last_name; ?></h4>
                        <p class="text-off"><?php echo $team_member->job_title ? $team_member->job_title : "Untitled"; ?></p>
                    </div>
                    <div class="card-footer bg-info p15 no-border">
                        <?php echo get_team_member_profile_link($team_member->id, app_lang("view_details"), array("class" => "btn btn-xs btn-info text-white")); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>