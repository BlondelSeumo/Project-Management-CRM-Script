<?php
if ($can_edit_timesheet_settings || $can_edit_slack_settings) {
    echo modal_anchor(get_uri("projects/settings_modal_form"), "<i data-feather='settings' class='icon-16'></i> " . app_lang('settings'), array("class" => "btn btn-light", "title" => app_lang('settings'), "data-post-project_id" => $project_info->id));
}
?>
<?php if ($show_actions_dropdown) { ?>
    <div class="dropdown btn-group">
        <button class="btn btn-secondary dropdown-toggle caret" type="button" data-bs-toggle="dropdown" aria-expanded="true">
            <i data-feather="tool" class="icon-16"></i> <?php echo app_lang('actions'); ?>
        </button>
        <ul class="dropdown-menu" role="menu">
            <?php if ($project_info->status == "open") { ?>
                <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/completed"), "<i data-feather='check-circle' class='icon-16'></i> " . app_lang('mark_project_as_completed'), array("class" => "dropdown-item", "title" => app_lang('mark_project_as_completed'), "data-reload-on-success" => "1")); ?> </li>
                <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/hold"), "<i data-feather='pause-circle' class='icon-16'></i> " . app_lang('mark_project_as_hold'), array("class" => "dropdown-item", "title" => app_lang('mark_project_as_hold'), "data-reload-on-success" => "1")); ?> </li>
                <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/canceled"), "<i data-feather='x-circle' class='icon-16'></i> " . app_lang('mark_project_as_canceled'), array("class" => "dropdown-item", "title" => app_lang('mark_project_as_canceled'), "data-reload-on-success" => "1")); ?> </li>
            <?php } ?>
            <?php if ($project_info->status != "open") { ?>
                <li role="presentation"><?php echo ajax_anchor(get_uri("projects/change_status/$project_info->id/open"), "<i data-feather='grid' class='icon-16'></i> " . app_lang('mark_project_as_open'), array("class" => "dropdown-item", "title" => app_lang('mark_project_as_open'), "data-reload-on-success" => "1")); ?> </li>
                <?php
            }
            if ($login_user->user_type == "staff") {
                echo "<li role='presentation'>" . modal_anchor(get_uri("projects/clone_project_modal_form"), "<i data-feather='copy' class='icon-16'></i> " . app_lang('clone_project'), array("class" => "dropdown-item", "data-post-id" => $project_info->id, "title" => app_lang('clone_project'))) . " </li>";
            }
            echo "<li role='presentation'>" . modal_anchor(get_uri("projects/modal_form"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit_project'), array("class" => "dropdown-item edit", "title" => app_lang('edit_project'), "data-post-id" => $project_info->id)) . " </li>";
            ?>

        </ul>
    </div>
<?php } ?>
<?php
if ($show_timmer) {
    echo view("projects/project_timer");
}
?>