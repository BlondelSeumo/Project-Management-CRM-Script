<?php

use App\Controllers\Security_Controller;
use App\Libraries\Template;

/**
 * get clock in/ clock out widget
 * @return html
 */
if (!function_exists('clock_widget')) {

    function clock_widget() {
        $ci = new Security_Controller(false);
        $view_data["clock_status"] = $ci->Attendance_model->current_clock_in_record($ci->login_user->id);
        $template = new Template();
        return $template->view("attendance/clock_widget", $view_data);
    }

}

/**
 * activity logs widget for projects
 * @param array $params
 * @return html
 */
if (!function_exists('activity_logs_widget')) {

    function activity_logs_widget($params = array()) {
        $ci = new Security_Controller(false);

        $limit = get_array_value($params, "limit");
        $limit = $limit ? $limit : "20";
        $offset = get_array_value($params, "offset");
        $offset = $offset ? $offset : "0";

        $params["user_id"] = $ci->login_user->id;
        $params["is_admin"] = $ci->login_user->is_admin;
        $params["user_type"] = $ci->login_user->user_type;
        $params["client_id"] = $ci->login_user->client_id;

        //check if user has restriction to view only assigned tasks
        $params["show_assigned_tasks_only"] = get_array_value($ci->login_user->permissions, "show_assigned_tasks_only");

        $logs = $ci->Activity_logs_model->get_details($params);

        $view_data["activity_logs"] = $logs->result;
        $view_data["result_remaining"] = $logs->found_rows - $limit - $offset;
        $view_data["next_page_offset"] = $offset + $limit;

        $view_data["log_for"] = get_array_value($params, "log_for");
        $view_data["log_for_id"] = get_array_value($params, "log_for_id");
        $view_data["log_type"] = get_array_value($params, "log_type");
        $view_data["log_type_id"] = get_array_value($params, "log_type_id");

        echo $view_data["result_remaining"] = view("activity_logs/activity_logs_widget", $view_data);
    }

}

/**
 * get timeline widget
 * @param array $params
 * @return html
 */
if (!function_exists('timeline_widget')) {

    function timeline_widget($params = array()) {
        $limit = get_array_value($params, "limit");
        $limit = $limit ? $limit : "20";
        $offset = get_array_value($params, "offset");
        $offset = $offset ? $offset : "0";

        $is_first_load = get_array_value($params, "is_first_load");
        if ($is_first_load) {
            $view_data["is_first_load"] = true;
        } else {
            $view_data["is_first_load"] = false;
        }

        $Posts_model = model("App\Models\Posts_model");
        $logs = $Posts_model->get_details($params);
        $view_data["posts"] = $logs->result;
        $view_data['single_post'] = '';
        $view_data["result_remaining"] = $logs->found_rows - $limit - $offset;
        $view_data["next_page_offset"] = $offset + $limit;

        $user_id = get_array_value($params, "user_id");
        if ($user_id && !count($logs->result)) {
            //show a no post found message to user's wall for empty post list
            $template = new Template();
            return $template->view("timeline/no_post_message");
        } else {
            $template = new Template();
            return $template->view("timeline/post_list", $view_data);
        }
    }

}


/**
 * get announcement notice

 * @return html
 */
if (!function_exists('announcements_alert_widget')) {

    function announcements_alert_widget() {
        $ci = new Security_Controller(false);
        $announcements = $ci->Announcements_model->get_unread_announcements($ci->login_user->id, $ci->login_user->user_type)->getResult();
        $view_data["announcements"] = $announcements;
        $template = new Template();
        return $template->view("announcements/alert", $view_data);
    }

}


/**
 * get tasks widget of loged in user
 * 
 * @return html
 */
if (!function_exists('my_open_tasks_widget')) {

    function my_open_tasks_widget() {
        $ci = new Security_Controller(false);
        $view_data["total"] = $ci->Tasks_model->count_my_open_tasks($ci->login_user->id);
        $template = new Template();
        return $template->view("projects/tasks/open_tasks_widget", $view_data);
    }

}


/**
 * get tasks status widteg of loged in user
 * 
 * @return html
 */
if (!function_exists('my_task_stataus_widget')) {

    function my_task_stataus_widget($custom_class = "") {
        $ci = new Security_Controller(false);
        $view_data["task_statuses"] = $ci->Tasks_model->get_task_statistics(array("user_id" => $ci->login_user->id));
        $view_data["custom_class"] = $custom_class;

        $template = new Template();
        return $template->view("projects/tasks/my_task_status_widget", $view_data);
    }

}


/**
 * get todays event widget
 * 
 * @return html
 */
if (!function_exists('events_today_widget')) {

    function events_today_widget() {
        $ci = new Security_Controller(false);

        $options = array(
            "user_id" => $ci->login_user->id,
            "team_ids" => $ci->login_user->team_ids
        );

        if ($ci->login_user->user_type == "client") {
            $options["is_client"] = true;
        }

        $view_data["total"] = $ci->Events_model->count_events_today($options);
        $template = new Template();
        return $template->view("events/events_today", $view_data);
    }

}


/**
 * get new posts widget
 * 
 * @return html
 */
if (!function_exists('new_posts_widget')) {

    function new_posts_widget() {
        $Posts_model = model("App\Models\Posts_model");
        $view_data["total"] = $Posts_model->count_new_posts();
        $template = new Template();
        return $template->view("timeline/new_posts_widget", $view_data);
    }

}


/**
 * get event list widget
 * 
 * @return html
 */
if (!function_exists('events_widget')) {

    function events_widget() {
        $ci = new Security_Controller(false);

        $options = array("user_id" => $ci->login_user->id, "limit" => 10, "team_ids" => $ci->login_user->team_ids);

        if ($ci->login_user->user_type == "client") {
            $options["is_client"] = true;
        }

        $view_data["events"] = $ci->Events_model->get_upcomming_events($options);

        $template = new Template();
        return $template->view("events/events_widget", $view_data);
    }

}


/**
 * get event icons based on event sharing 
 * 
 * @return html
 */
if (!function_exists('get_event_icon')) {

    function get_event_icon($share_with = "") {
        $icon = "";
        if (!$share_with) {
            $icon = "lock";
        } else if ($share_with == "all") {
            $icon = "globe";
        } else {
            $icon = "at-sign";
        }
        return $icon;
    }

}


/**
 * get open timers widget
 * 
 * @return html
 */
if (!function_exists('has_my_open_timers')) {

    function has_my_open_timers() {
        $ci = new Security_Controller(false);
        $timers = $ci->Timesheets_model->get_open_timers($ci->login_user->id);
        return $timers->resultID->num_rows;
    }

}


/**
 * get income expense widget
 * 
 * @return html
 */
if (!function_exists('income_vs_expenses_widget')) {

    function income_vs_expenses_widget($custom_class = "") {
        $Expenses_model = model("App\Models\Expenses_model");
        $info = $Expenses_model->get_income_expenses_info();
        $view_data["income"] = $info->income ? $info->income : 0;
        $view_data["expenses"] = $info->expneses ? $info->expneses : 0;
        $view_data["custom_class"] = $custom_class;
        $template = new Template();
        return $template->view("expenses/income_expenses_widget", $view_data);
    }

}


/**
 * get ticket status widget
 * 
 * @return html
 */
if (!function_exists('ticket_status_widget')) {

    function ticket_status_widget() {
        $Tickets_model = model("App\Models\Tickets_model");
        $statuses = $Tickets_model->get_ticket_status_info()->getResult();

        $view_data["new"] = 0;
        $view_data["open"] = 0;
        $view_data["closed"] = 0;
        foreach ($statuses as $status) {
            if ($status->status === "new") {
                $view_data["new"] = $status->total;
            } else if ($status->status === "closed") {
                $view_data["closed"] = $status->total;
            } else {
                $view_data["open"] += $status->total;
            }
        }

        $template = new Template();
        return $template->view("tickets/ticket_status_widget", $view_data);
    }

}


/**
 * get invoice statistics widget
 * 
 * @return html
 */
if (!function_exists('invoice_statistics_widget')) {

    function invoice_statistics_widget($options = array()) {
        $ci = new Security_Controller(false);

        $currency_symbol = get_array_value($options, "currency");

        if ($ci->login_user->user_type == "client") {
            $options["client_id"] = $ci->login_user->client_id;
            $client_info = $ci->Clients_model->get_one($ci->login_user->client_id);
            $currency_symbol = $client_info->currency_symbol;
        }

        $currency_symbol = $currency_symbol ? $currency_symbol : get_setting("default_currency");

        $options["currency_symbol"] = $currency_symbol;
        $info = $ci->Invoices_model->invoice_statistics($options);

        $payments = array();
        $payments_array = array();

        $invoices = array();
        $invoices_array = array();

        for ($i = 1; $i <= 12; $i++) {
            $payments[$i] = 0;
            $invoices[$i] = 0;
        }

        foreach ($info->payments as $payment) {
            $payments[$payment->month] = $payment->total;
        }
        foreach ($info->invoices as $invoice) {
            $invoices[$invoice->month] = $invoice->total;
        }

        foreach ($payments as $key => $payment) {
            $payments_array[] = $payment;
        }

        foreach ($invoices as $key => $invoice) {
            $invoices_array[] = $invoice;
        }

        $view_data["payments"] = json_encode($payments_array);
        $view_data["invoices"] = json_encode($invoices_array);
        $view_data["currencies"] = $info->currencies;
        $view_data["currency_symbol"] = $currency_symbol;

        $template = new Template();
        return $template->view("invoices/invoice_statistics_widget/index", $view_data);
    }

}


/**
 * get projects statistics widget
 * 
 * @return html
 */
if (!function_exists('project_timesheet_statistics_widget')) {

    function project_timesheet_statistics_widget($type = "") {
        $ci = new Security_Controller(false);

        $timesheets = array();
        $timesheets_array = array();

        $ticks = array();

        $today = get_my_local_time("Y-m-d");
        $start_date = date("Y-m-", strtotime($today)) . "01";
        $end_date = date("Y-m-t", strtotime($today));

        $options = array("start_date" => $start_date, "end_date" => $end_date);

        if ($type == "my_timesheet_statistics") {
            $options["user_id"] = $ci->login_user->id;
        }

        $timesheets_result = $ci->Timesheets_model->get_timesheet_statistics($options)->getResult();


        $days_of_month = date("t", strtotime($today));

        for ($i = 0; $i <= $days_of_month; $i++) {
            $timesheets[$i] = 0;
        }

        foreach ($timesheets_result as $value) {
            $timesheets[$value->day * 1] = $value->total_sec / 60;
        }

        foreach ($timesheets as $value) {
            $timesheets_array[] = $value;
        }

        for ($i = 0; $i <= $days_of_month; $i++) {
            $ticks[] = $i;
        }

        $view_data["timesheets"] = json_encode($timesheets_array);
        $view_data["timesheet_type"] = $type;
        $view_data["ticks"] = json_encode($ticks);
        $template = new Template();
        return $template->view("projects/timesheets/timesheet_wedget", $view_data);
    }

}


/**
 * get timecard statistics
 * 
 * @return html
 */
if (!function_exists('timecard_statistics_widget')) {

    function timecard_statistics_widget() {
        $ci = new Security_Controller(false);

        $timecards = array();
        $timecards_array = array();

        $ticks = array();

        $today = get_my_local_time("Y-m-d");
        $start_date = date("Y-m-", strtotime($today)) . "01";
        $end_date = date("Y-m-t", strtotime($today));
        $options = array("start_date" => $start_date, "end_date" => $end_date, "user_id" => $ci->login_user->id);
        $timesheets_result = $ci->Attendance_model->get_timecard_statistics($options)->getResult();
        $days_of_month = date("t", strtotime($today));

        for ($i = 0; $i <= $days_of_month; $i++) {
            $timecards[$i] = 0;
        }

        foreach ($timesheets_result as $value) {
            $timecards[$value->day * 1] = $value->total_sec / 60;
        }

        foreach ($timecards as $value) {
            $timecards_array[] = $value;
        }

        for ($i = 0; $i <= $days_of_month; $i++) {
            $ticks[] = $i;
        }

        $view_data["timecards"] = json_encode($timecards_array);
        $view_data["ticks"] = json_encode($ticks);
        $template = new Template();
        return $template->view("attendance/timecard_statistics", $view_data);
    }

}


/**
 * get count of clocked in /out users widget
 * 
 * @return html
 */
if (!function_exists('count_clock_status_widget')) {

    function count_clock_status_widget() {
        $Attendance_model = model("App\Models\Attendance_model");
        $info = $Attendance_model->count_clock_status();
        $view_data["members_clocked_in"] = $info->members_clocked_in ? $info->members_clocked_in : 0;
        $view_data["members_clocked_out"] = $info->members_clocked_out ? $info->members_clocked_out : 0;
        $template = new Template();
        return $template->view("attendance/count_clock_status_widget", $view_data);
    }

}


/**
 * get project count status widteg
 * @param integer $user_id
 * 
 * @return html
 */
if (!function_exists('count_project_status_widget')) {

    function count_project_status_widget($user_id = 0) {
        $ci = new Security_Controller(false);
        $options = array(
            "user_id" => $user_id ? $user_id : $ci->login_user->id
        );
        $info = $ci->Projects_model->count_project_status($options);
        $view_data["project_open"] = $info->open;
        $view_data["project_completed"] = $info->completed;
        $template = new Template();
        return $template->view("projects/widgets/project_status_widget", $view_data);
    }

}


/**
 * count total time widget
 * @param integer $user_id
 * 
 * @return html
 */
if (!function_exists('count_total_time_widget')) {

    function count_total_time_widget($user_id = 0) {
        $ci = new Security_Controller(false);
        $options = array("user_id" => $user_id ? $user_id : $ci->login_user->id);
        $info = $ci->Timesheets_model->count_total_time($options);
        $view_data["total_hours_worked"] = to_decimal_format($info->timecard_total / 60 / 60);
        $view_data["total_project_hours"] = to_decimal_format($info->timesheet_total / 60 / 60);

        $permissions = $ci->login_user->permissions;

        $view_data["show_total_hours_worked"] = false;
        if (get_setting("module_attendance") == "1" && ($ci->login_user->is_admin || get_array_value($permissions, "attendance"))) {
            $view_data["show_total_hours_worked"] = true;
        }

        $view_data["show_projects_count"] = false;
        if ($ci->login_user->is_admin || get_array_value($permissions, "can_manage_all_projects") == "1") {
            $view_data["show_projects_count"] = true;
        }

        $view_data["show_total_project_hours"] = false;
        if (get_setting("module_project_timesheet") == "1" && ($ci->login_user->is_admin || get_array_value($permissions, "timesheet_manage_permission"))) {
            $view_data["show_total_project_hours"] = true;
        }

        $template = new Template();
        return $template->view("attendance/total_time_widget", $view_data);
    }

}


/**
 * count total time widget
 * @param integer $user_id
 * @param string $widget_type
 * 
 * @return html
 */
if (!function_exists('count_total_time_widget_small')) {

    function count_total_time_widget_small($user_id = 0, $widget_type = "") {
        $ci = new Security_Controller(false);
        $options = array("user_id" => $user_id ? $user_id : $ci->login_user->id);
        $info = $ci->Timesheets_model->count_total_time($options);
        $view_data["total_hours_worked"] = to_decimal_format($info->timecard_total / 60 / 60);
        $view_data["total_project_hours"] = to_decimal_format($info->timesheet_total / 60 / 60);
        $view_data["widget_type"] = $widget_type;
        $template = new Template();
        return $template->view("attendance/total_time_widget_small", $view_data);
    }

}


/**
 * get social links widget
 * @param object $weblinks
 * 
 * @return html
 */
if (!function_exists('social_links_widget')) {

    function social_links_widget($weblinks) {
        $view_data["weblinks"] = $weblinks;

        $template = new Template();
        return $template->view("users/social_links_widget", $view_data);
    }

}


/**
 * count unread messages
 * @return number
 */
if (!function_exists('count_unread_message')) {

    function count_unread_message() {
        $ci = new Security_Controller(false);
        return $ci->Messages_model->count_unread_message($ci->login_user->id);
    }

}


/**
 * count new tickets
 * @param string $ticket_types
 * @return number
 */
if (!function_exists('count_new_tickets')) {

    function count_new_tickets($ticket_types = "", $show_assigned_tickets_only_user_id = 0) {
        $Tickets_model = model("App\Models\Tickets_model");
        return $Tickets_model->count_new_tickets($ticket_types, $show_assigned_tickets_only_user_id);
    }

}


/**
 * get all tasks kanban widget
 * 
 * @return html
 */
if (!function_exists('all_tasks_kanban_widget')) {

    function all_tasks_kanban_widget() {
        $ci = new Security_Controller(false);

        $projects = $ci->Tasks_model->get_my_projects_dropdown_list($ci->login_user->id)->getResult();
        $projects_dropdown = array(array("id" => "", "text" => "- " . app_lang("project") . " -"));
        foreach ($projects as $project) {
            if ($project->project_id && $project->project_title) {
                $projects_dropdown[] = array("id" => $project->project_id, "text" => $project->project_title);
            }
        }

        $team_members_dropdown = array(array("id" => "", "text" => "- " . app_lang("team_member") . " -"));
        $assigned_to_list = $ci->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        foreach ($assigned_to_list as $key => $value) {

            if ($key == $ci->login_user->id) {
                $team_members_dropdown[] = array("id" => $key, "text" => $value, "isSelected" => true);
            } else {
                $team_members_dropdown[] = array("id" => $key, "text" => $value);
            }
        }

        $view_data['team_members_dropdown'] = json_encode($team_members_dropdown);
        $view_data['projects_dropdown'] = json_encode($projects_dropdown);

        $view_data['task_statuses'] = $ci->Task_status_model->get_details()->getResult();

        $template = new Template();
        return $template->view("projects/tasks/kanban/all_tasks_kanban_widget", $view_data);
    }

}


/**
 * get todo lists widget
 * 
 * @return html
 */
if (!function_exists('todo_list_widget')) {

    function todo_list_widget() {
        $template = new Template();
        return $template->view("todo/todo_lists_widget");
    }

}


/**
 * get invalid access widget
 * 
 * @return html
 */
if (!function_exists('invalid_access_widget')) {

    function invalid_access_widget() {
        $template = new Template();
        return $template->view("dashboards/custom_dashboards/invalid_access_widget");
    }

}


/**
 * get open projects widget
 * @param integer $user_id
 * 
 * @return html
 */
if (!function_exists('open_projects_widget')) {

    function open_projects_widget($user_id = 0) {
        $ci = new Security_Controller(false);
        $options = array(
            "user_id" => $user_id ? $user_id : $ci->login_user->id
        );
        $view_data["project_open"] = $ci->Projects_model->count_project_status($options)->open;
        $template = new Template();
        return $template->view("projects/widgets/open_projects_widget", $view_data);
    }

}


/**
 * get completed projects widget
 * @param integer $user_id
 * 
 * @return html
 */
if (!function_exists('completed_projects_widget')) {

    function completed_projects_widget($user_id = 0) {
        $ci = new Security_Controller(false);
        $options = array(
            "user_id" => $user_id ? $user_id : $ci->login_user->id
        );
        $view_data["project_completed"] = $ci->Projects_model->count_project_status($options)->completed;
        $template = new Template();
        return $template->view("projects/widgets/completed_projects_widget", $view_data);
    }

}


/**
 * get count of clocked in users widget
 * 
 * @return html
 */
if (!function_exists('count_clock_in_widget')) {

    function count_clock_in_widget() {
        $Attendance_model = model("App\Models\Attendance_model");
        $info = $Attendance_model->count_clock_status()->members_clocked_in;
        $view_data["members_clocked_in"] = $info ? $info : 0;
        $template = new Template();
        return $template->view("attendance/count_clock_in_widget", $view_data);
    }

}


/**
 * get count of clocked out users widget
 * 
 * @return html
 */
if (!function_exists('count_clock_out_widget')) {

    function count_clock_out_widget() {
        $Attendance_model = model("App\Models\Attendance_model");
        $info = $Attendance_model->count_clock_status()->members_clocked_out;
        $view_data["members_clocked_out"] = $info ? $info : 0;
        $template = new Template();
        return $template->view("attendance/count_clock_out_widget", $view_data);
    }

}


/**
 * get user's open project list widget
 * 
 * @return html
 */
if (!function_exists('my_open_projects_widget')) {

    function my_open_projects_widget($client_id = 0) {
        $ci = new Security_Controller(false);

        $options = array(
            "statuses" => "open",
            "user_id" => $ci->login_user->id
        );

        if ($ci->login_user->user_type == "client") {
            $options["client_id"] = $client_id;
        }

        $view_data["projects"] = $ci->Projects_model->get_details($options)->getResult();
        $template = new Template();
        return $template->view("projects/widgets/my_open_projects_widget", $view_data);
    }

}


/**
 * get user's starred project list widget
 * @param integer $user_id
 * 
 * @return html
 */
if (!function_exists('my_starred_projects_widget')) {

    function my_starred_projects_widget($user_id = 0) {
        $ci = new Security_Controller(false);

        $options = array(
            "user_id" => $user_id ? $user_id : $ci->login_user->id,
            "starred_projects" => true
        );

        $view_data["projects"] = $ci->Projects_model->get_details($options)->getResult();
        $template = new Template();
        return $template->view("projects/widgets/my_starred_projects_widget", $view_data);
    }

}


/**
 * get sticky note widget for logged in user
 * @param string $custom_class
 * 
 * @return html
 */
if (!function_exists('sticky_note_widget')) {

    function sticky_note_widget($custom_class = "") {
        $template = new Template();
        return $template->view("dashboards/sticky_note_widget", array("custom_class" => $custom_class));
    }

}


/**
 * get ticket status small widget for current logged in user
 * @param integer $user_id
 * @param string $type ($type should be new/open/closed)
 * 
 * @return html
 */
if (!function_exists('ticket_status_widget_small')) {

    function ticket_status_widget_small($data = array()) {
        $ci = new Security_Controller(false);
        $allowed_ticket_types = get_array_value($data, "allowed_ticket_types");
        $status = get_array_value($data, "status");

        $options = array("status" => $status);
        if ($ci->login_user->user_type == "staff") {
            $options["allowed_ticket_types"] = $allowed_ticket_types;
            $options["show_assigned_tickets_only_user_id"] = get_array_value($data, "show_assigned_tickets_only_user_id");
        } else {
            $options["client_id"] = $ci->login_user->client_id;
        }

        $view_data["total_tickets"] = $ci->Tickets_model->count_tickets($options);
        $view_data["status"] = $status;

        $template = new Template();
        return $template->view("tickets/ticket_status_widget_small", $view_data);
    }

}


/**
 * get all team members widget
 * 
 * @return html
 */
if (!function_exists('all_team_members_widget')) {

    function all_team_members_widget() {
        $Users_model = model("App\Models\Users_model");
        $options = array("status" => "active", "user_type" => "staff");
        $view_data["members"] = $Users_model->get_details($options)->getResult();
        $template = new Template();
        return $template->view("team_members/team_members_widget", $view_data);
    }

}


/**
 * get all clocked in team members widget
 * @param array $data containing access permissions
 * 
 * @return html
 */
if (!function_exists('clocked_in_team_members_widget')) {

    function clocked_in_team_members_widget($data = array()) {
        $ci = new Security_Controller(false);

        $options = array(
            "login_user_id" => $ci->login_user->id,
            "access_type" => get_array_value($data, "access_type"),
            "allowed_members" => get_array_value($data, "allowed_members"),
            "only_clocked_in_members" => true
        );

        $view_data["users"] = $ci->Attendance_model->get_details($options)->getResult();

        $template = new Template();
        return $template->view("team_members/clocked_in_team_members_widget", $view_data);
    }

}


/**
 * get all clocked out team members widget
 * @param array $data containing access permissions
 * 
 * @return html
 */
if (!function_exists('clocked_out_team_members_widget')) {

    function clocked_out_team_members_widget($data = array()) {
        $ci = new Security_Controller(false);

        $options = array(
            "login_user_id" => $ci->login_user->id,
            "access_type" => get_array_value($data, "access_type"),
            "allowed_members" => get_array_value($data, "allowed_members")
        );

        $view_data["users"] = $ci->Attendance_model->get_clocked_out_members($options)->getResult();
        $template = new Template();
        return $template->view("team_members/clocked_out_team_members_widget", $view_data);
    }

}


/**
 * get active members widget
 * 
 * @return html
 */
if (!function_exists('active_members_and_clients_widget')) {

    function active_members_and_clients_widget($user_type = "", $show_own_clients_only_user_id = "") {
        $ci = new Security_Controller(false);

        $options = array("user_type" => $user_type, "exclude_user_id" => $ci->login_user->id, "show_own_clients_only_user_id" => $show_own_clients_only_user_id);

        $view_data["users"] = $ci->Users_model->get_active_members_and_clients($options)->getResult();
        $view_data["user_type"] = $user_type;
        $template = new Template();
        return $template->view("team_members/active_members_and_clients_widget", $view_data);
    }

}


/**
 * get total invoices/payments/due value widget
 * @param string $type
 * 
 * @return html
 */
if (!function_exists('get_invoices_value_widget')) {

    function get_invoices_value_widget($type = "") {
        $Invoices_model = model("App\Models\Invoices_model");
        $view_data["invoices_info"] = $Invoices_model->get_invoices_total_and_paymnts();
        $view_data["type"] = $type;
        $template = new Template();
        return $template->view("invoices/total_invoices_value_widget", $view_data);
    }

}


/**
 * get my tasks list widget
 * 
 * @return html
 */
if (!function_exists('my_tasks_list_widget')) {

    function my_tasks_list_widget() {
        $Task_status_model = model("App\Models\Task_status_model");
        $view_data['task_statuses'] = $Task_status_model->get_details()->getResult();
        $template = new Template();
        return $template->view("projects/tasks/my_tasks_list_widget", $view_data);
    }

}

/**
 * get pending leave approval widget
 * 
 * @return html
 */
if (!function_exists('pending_leave_approval_widget')) {

    function pending_leave_approval_widget($data = array()) {
        $ci = new Security_Controller(false);

        $options = array(
            "login_user_id" => $ci->login_user->id,
            "access_type" => get_array_value($data, "access_type"),
            "allowed_members" => get_array_value($data, "allowed_members"),
            "status" => "pending"
        );
        $view_data["total"] = count($ci->Leave_applications_model->get_list($options)->getResult());

        $template = new Template();
        return $template->view("leaves/pending_leave_approval_widget", $view_data);
    }

}

/**
 * get draft invoices
 * 
 * @return html
 */
if (!function_exists('draft_invoices_widget')) {

    function draft_invoices_widget() {
        $Invoices_model = model("App\Models\Invoices_model");
        $view_data["draft_invoices"] = $Invoices_model->count_draft_invoices();
        $template = new Template();
        return $template->view("invoices/draft_invoices_widget", $view_data);
    }

}

/**
 * get total clients
 * 
 * @return html
 */
if (!function_exists('total_clients_widget')) {

    function total_clients_widget($show_own_clients_only_user_id = "") {
        $Clients_model = model("App\Models\Clients_model");
        $view_data["total"] = $Clients_model->count_total_clients($show_own_clients_only_user_id);
        $template = new Template();
        return $template->view("clients/total_clients_widget", $view_data);
    }

}

/**
 * get total client contacts
 * 
 * @return html
 */
if (!function_exists('total_contacts_widget')) {

    function total_contacts_widget($show_own_clients_only_user_id = "") {
        $Users_model = model("App\Models\Users_model");
        $view_data["total"] = $Users_model->count_total_contacts($show_own_clients_only_user_id);
        $template = new Template();
        return $template->view("clients/total_contacts_widget", $view_data);
    }

}

/**
 * get active members on projects widget
 * 
 * @return html
 */
if (!function_exists('active_members_on_projects_widget')) {

    function active_members_on_projects_widget() {
        $Timesheets_model = model("App\Models\Timesheets_model");
        $view_data["users_info"] = $Timesheets_model->active_members_on_projects()->getResult();

        $template = new Template();
        return $template->view("team_members/active_members_on_projects_widget", $view_data);
    }

}

/**
 * get open tickets list widget
 * 
 * @return html
 */
if (!function_exists('open_tickets_list_widget')) {

    function open_tickets_list_widget() {
        $ci = new Security_Controller(false);

        if ($ci->login_user->user_type == "client") {
            $view_data["client_id"] = $ci->login_user->client_id;
            $template = new Template();
            return $template->view("clients/tickets/open_tickets_list_widget", $view_data);
        } else {
            $template = new Template();
            return $template->view("tickets/open_tickets_list_widget");
        }
    }

}