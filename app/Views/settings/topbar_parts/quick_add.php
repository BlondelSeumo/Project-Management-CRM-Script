<?php
//get the array of hidden menu
$hidden_menu = explode(",", get_setting("hidden_client_menus"));
$permissions = $login_user->permissions;

$links = "";

if (($login_user->user_type == "staff" && ($login_user->is_admin || get_array_value($permissions, "can_manage_all_projects") == "1" || get_array_value($permissions, "can_create_tasks") == "1")) || ($login_user->user_type == "client" && get_setting("client_can_create_tasks"))) {
    //add tasks 
    $links .= modal_anchor(get_uri("projects/task_modal_form"), app_lang('add_task'), array("class" => "dropdown-item clearfix", "title" => app_lang('add_task'), "id" => "js-quick-add-task"));

    //add multiple tasks
    $links .= modal_anchor(get_uri("projects/task_modal_form"), app_lang('add_multiple_tasks'), array("class" => "dropdown-item clearfix", "title" => app_lang('add_multiple_tasks'), "data-post-add_type" => "multiple", "id" => "js-quick-add-multiple-task"));
}

//add project time
if ($login_user->user_type == "staff" && get_setting("module_project_timesheet") == "1") {
    $links .= modal_anchor(get_uri("projects/timelog_modal_form"), app_lang('add_project_time'), array("class" => "dropdown-item clearfix", "title" => app_lang('add_project_time'), "id" => "js-quick-add-project-time"));
}

//add event
if (get_setting("module_event") == "1" && (($login_user->user_type == "client" && !in_array("events", $hidden_menu)) || $login_user->user_type == "staff")) {
    $links .= modal_anchor(get_uri("events/modal_form"), app_lang('add_event'), array("class" => "dropdown-item clearfix", "title" => app_lang('add_event'), "data-post-client_id" => $login_user->user_type == "client" ? $login_user->client_id : "", "id" => "js-quick-add-event"));
}

//add note
if (get_setting("module_note") == "1" && $login_user->user_type == "staff") {
    $links .= modal_anchor(get_uri("notes/modal_form"), app_lang('add_note'), array("class" => "dropdown-item clearfix", "title" => app_lang('add_note'), "id" => "js-quick-add-note"));
}

//add todo
if (get_setting("module_todo") == "1") {
    $links .= modal_anchor(get_uri("todo/modal_form"), app_lang("add_to_do"), array("class" => "dropdown-item clearfix", "title" => app_lang('add_to_do'), "id" => "js-quick-add-to-do"));
}

//add ticket
if (get_setting("module_ticket") == "1" && ($login_user->is_admin || get_array_value($permissions, "ticket"))) {
    $links .= modal_anchor(get_uri("tickets/modal_form"), app_lang('add_ticket'), array("class" => "dropdown-item clearfix", "title" => app_lang('add_ticket'), "id" => "js-quick-add-ticket"));
}

if ($links) {
    ?>
    <li class="nav-item dropdown">
        <?php echo js_anchor("<i data-feather='plus-circle' class='icon'></i>", array("id" => "quick-add-icon", "class" => "nav-link dropdown-toggle", "data-bs-toggle" => "dropdown")); ?>

        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <?php echo $links; ?></li>
        </ul>
    </li>
    <?php
} 
