<?php

namespace App\Controllers;

use App\Libraries\Imap;

class Settings extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        app_redirect('settings/general');
    }

    function general() {
        $tzlist = \DateTimeZone::listIdentifiers();
        $view_data['timezone_dropdown'] = array();
        foreach ($tzlist as $zone) {
            $view_data['timezone_dropdown'][$zone] = $zone;
        }

        $view_data['language_dropdown'] = get_language_list();

        $view_data["currency_dropdown"] = get_international_currency_code_dropdown();
        return $this->template->rander("settings/general", $view_data);
    }

    function save_general_settings() {
        $settings = array("site_logo", "favicon", "show_background_image_in_signin_page", "show_logo_in_signin_page", "app_title", "language", "timezone", "date_format", "time_format", "first_day_of_week", "weekends", "default_currency", "currency_symbol", "currency_position", "decimal_separator", "no_of_decimals", "accepted_file_formats", "rows_per_page", "item_purchase_code", "scrollbar", "enable_rich_text_editor", "rtl", "show_theme_color_changer", "default_theme_color");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if ($value || $value === "0") {
                if ($setting === "site_logo") {
                    $value = str_replace("~", ":", $value);
                    $value = serialize(move_temp_file("site-logo.png", get_setting("system_file_path"), "", $value));

                    //delete old file
                    delete_app_files(get_setting("system_file_path"), get_system_files_setting_value("site_logo"));
                } else if ($setting === "item_purchase_code" && $value === "******") {
                    $value = get_setting('item_purchase_code');
                } else if ($setting === "favicon") {
                    $value = str_replace("~", ":", $value);
                    $value = serialize(move_temp_file("favicon.png", get_setting("system_file_path"), "", $value));

                    //delete old file
                    if (get_setting("favicon")) {
                        delete_app_files(get_setting("system_file_path"), get_system_files_setting_value("favicon"));
                    }
                }


                $this->Settings_model->save_setting($setting, $value);
            }

            //save empty value too for weekends
            if ($setting === "weekends") {
                $this->Settings_model->save_setting($setting, $value);
            }
        }

        $reload_page = false;

        //save signin page background
        $files_data = move_files_from_temp_dir_to_permanent_dir(get_setting("system_file_path"), "system");
        $unserialize_files_data = unserialize($files_data);
        $sigin_page_background = get_array_value($unserialize_files_data, 0);
        if ($sigin_page_background) {
            delete_app_files(get_setting("system_file_path"), get_system_files_setting_value("signin_page_background"));
            $this->Settings_model->save_setting("signin_page_background", serialize($sigin_page_background));
            $reload_page = true;
        }

        if ($_FILES) {
            $files = array("site_logo_file", "favicon_file");

            foreach ($files as $file) {
                $file_data = get_array_value($_FILES, $file);

                if (!($file_data && is_array($file_data) && count($file_data))) {
                    continue;
                }

                $file_name = get_array_value($file_data, "tmp_name");
                if (!$file_name) {
                    continue;
                }

                $new_file_name = "site-logo.png";
                $setting_name = "site_logo";
                if ($file === "favicon_file") {
                    $new_file_name = "favicon.png";
                    $setting_name = "favicon";
                }

                $new_file_data = serialize(move_temp_file($new_file_name, get_setting("system_file_path"), "", $file_name));
                //delete old file
                delete_app_files(get_setting("system_file_path"), get_system_files_setting_value($setting_name));
                $this->Settings_model->save_setting($setting_name, $new_file_data);
            }

            $reload_page = true;
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated'), 'reload_page' => $reload_page));
    }

    function company() {
        return $this->template->rander("settings/company");
    }

    function save_company_settings() {
        $settings = array("company_name", "company_address", "company_phone", "company_email", "company_website", "company_vat_number");

        foreach ($settings as $setting) {
            $this->Settings_model->save_setting($setting, $this->request->getPost($setting));
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function email() {
        return $this->template->rander("settings/email");
    }

    function save_email_settings() {
        $settings = array("email_sent_from_address", "email_sent_from_name", "email_protocol", "email_smtp_host", "email_smtp_port", "email_smtp_user", "email_smtp_pass", "email_smtp_security_type");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (!$value) {
                $value = "";
            }

            if ($setting == "email_smtp_pass") {
                if ($value === "******") {
                    $value = get_setting('email_smtp_pass');
                } else {
                    $value = encode_id($value, "email_smtp_pass");
                }
            }


            $this->Settings_model->save_setting($setting, $value);
        }

        $test_email_to = $this->request->getPost("send_test_mail_to");
        if ($test_email_to) {
            $email_config = Array(
                'charset' => 'utf-8',
                'mailType' => 'html'
            );
            if ($this->request->getPost("email_protocol") === "smtp") {
                $email_config["protocol"] = "smtp";
                $email_config["SMTPHost"] = $this->request->getPost("email_smtp_host");
                $email_config["SMTPPort"] = $this->request->getPost("email_smtp_port");
                $email_config["SMTPUser"] = $this->request->getPost("email_smtp_user");

                $email_smtp_pass = $this->request->getPost("email_smtp_pass");
                if ($email_smtp_pass === "******") {
                    $email_smtp_pass = decode_password(get_setting('email_smtp_pass'), "email_smtp_pass");
                }
                $email_config["SMTPPass"] = $email_smtp_pass;
                $email_config["SMTPCrypto"] = $this->request->getPost("email_smtp_security_type");
                if ($email_config["SMTPCrypto"] === "none") {
                    $email_config["SMTPCrypto"] = "";
                }
            }

            $email = \CodeIgniter\Config\Services::email();
            $email->initialize($email_config);

            $email->setNewline("\r\n");
            $email->setCRLF("\r\n");
            $email->setFrom($this->request->getPost("email_sent_from_address"), $this->request->getPost("email_sent_from_name"));

            $email->setTo($test_email_to);
            $email->setSubject("Test message");
            $email->setMessage("This is a test message to check mail configuration.");

            if ($email->send()) {
                echo json_encode(array("success" => true, 'message' => app_lang('test_mail_sent')));
                return false;
            } else {
                log_message('error', $email->printDebugger());
                echo json_encode(array("success" => false, 'message' => app_lang('test_mail_send_failed')));
                return false;
            }
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function ip_restriction() {
        return $this->template->rander("settings/ip_restriction");
    }

    function save_ip_settings() {
        $this->Settings_model->save_setting("allowed_ip_addresses", $this->request->getPost("allowed_ip_addresses"));

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function db_backup() {
        return $this->template->rander("settings/db_backup");
    }

    function client_permissions() {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->getResult();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $hidden_menus = array(
            "announcements",
            "events",
            "estimates",
            "invoices",
            "knowledge_base",
            "projects",
            "payments",
            "store",
            "tickets"
        );

        $hidden_menu_dropdown = array();
        foreach ($hidden_menus as $hidden_menu) {
            $hidden_menu_dropdown[] = array("id" => $hidden_menu, "text" => app_lang($hidden_menu));
        }

        $view_data['hidden_menu_dropdown'] = json_encode($hidden_menu_dropdown);
        $view_data['members_dropdown'] = json_encode($members_dropdown);
        return $this->template->rander("settings/client_permissions", $view_data);
    }

    function save_client_settings() {
        $settings = array(
            "disable_client_login",
            "disable_client_signup",
            "client_message_users",
            "hidden_client_menus",
            "client_can_create_projects",
            "client_can_create_tasks",
            "client_can_edit_tasks",
            "client_can_view_tasks",
            "client_can_comment_on_tasks",
            "client_can_view_project_files",
            "client_can_add_project_files",
            "client_can_comment_on_files",
            "client_can_delete_own_files_in_project",
            "client_can_view_milestones",
            "client_can_view_overview",
            "client_can_view_gantt",
            "client_can_view_files",
            "client_can_add_files",
            "client_can_edit_projects",
            "client_can_view_activity",
            "client_message_own_contacts",
            "disable_user_invitation_option_by_clients",
            "client_can_access_store",
            "disable_access_favorite_project_option_for_clients",
            "disable_editing_left_menu_by_clients",
            "disable_topbar_menu_customization",
            "disable_dashboard_customization_by_clients",
            "verify_email_before_client_signup"
        );

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function invoices() {
        $last_invoice_id = $this->Invoices_model->get_last_invoice_id();

        $view_data["last_id"] = $last_invoice_id;

        return $this->template->rander("settings/invoices", $view_data);
    }

    function save_invoice_settings() {
        $settings = array("allow_partial_invoice_payment_from_clients", "invoice_color", "invoice_footer", "send_bcc_to", "invoice_prefix", "invoice_style", "invoice_logo", "send_invoice_due_pre_reminder", "send_invoice_due_after_reminder", "send_recurring_invoice_reminder_before_creation", "default_due_date_after_billing_date", "initial_number_of_the_invoice", "client_can_pay_invoice_without_login");
        $reload_page = false;

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            $saveable = true;

            if ($setting == "invoice_footer") {
                $value = decode_ajax_post_data($value);
            } else if ($setting === "invoice_logo" && $value) {
                $value = str_replace("~", ":", $value);
                $value = serialize(move_temp_file("invoice-logo.png", get_setting("system_file_path"), "", $value));
            }

            if (is_null($value)) {
                $value = "";
            }

            //process the file which has uploaded using manual file submit
            if ($setting === "invoice_logo" && $_FILES) {
                $invoice_logo_file = get_array_value($_FILES, "invoice_logo_file");
                $invoice_logo_file_name = get_array_value($invoice_logo_file, "tmp_name");
                if ($invoice_logo_file_name) {
                    $value = serialize(move_temp_file("invoice-logo.png", get_setting("system_file_path"), "", $invoice_logo_file_name));
                    $reload_page = true;
                }
            }

            //don't save blank image
            if ($setting === "invoice_logo" && !$value) {
                $saveable = false;
            }

            if ($saveable) {
                if ($setting === "invoice_logo") {
                    //delete old file
                    delete_app_files(get_setting("system_file_path"), get_system_files_setting_value("invoice_logo"));
                }

                $this->Settings_model->save_setting($setting, $value);
            }

            if ($setting === "initial_number_of_the_invoice") {
                $this->Invoices_model->save_initial_number_of_invoice($value);
            }
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated'), "reload_page" => $reload_page));
    }

    function events() {
        return $this->template->rander("settings/events");
    }

    function save_event_settings() {
        $enable_google_calendar_api = $this->request->getPost("enable_google_calendar_api");
        $enable_google_calendar_api = is_null($enable_google_calendar_api) ? "" : $enable_google_calendar_api;

        $this->Settings_model->save_setting("enable_google_calendar_api", $enable_google_calendar_api);

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function notifications() {
        $category_suggestions = array(
            array("id" => "", "text" => "- " . app_lang('category') . " -"),
            array("id" => "announcement", "text" => app_lang("announcement")),
            array("id" => "client", "text" => app_lang("client")),
            array("id" => "event", "text" => app_lang("event")),
            array("id" => "estimate", "text" => app_lang("estimate")),
            array("id" => "invoice", "text" => app_lang("invoice")),
            array("id" => "leave", "text" => app_lang("leave")),
            array("id" => "lead", "text" => app_lang("lead")),
            array("id" => "message", "text" => app_lang("message")),
            array("id" => "order", "text" => app_lang("order")),
            array("id" => "project", "text" => app_lang("project")),
            array("id" => "ticket", "text" => app_lang("ticket")),
            array("id" => "timeline", "text" => app_lang("timeline"))
        );

        $view_data['categories_dropdown'] = json_encode($category_suggestions);
        return $this->template->rander("settings/notifications/index", $view_data);
    }

    function notification_modal_form() {
        $id = $this->request->getPost("id");
        if ($id) {

            helper('notifications');

            $model_info = $this->Notification_settings_model->get_details(array("id" => $id))->getRow();
            $notify_to = get_notification_config($model_info->event, "notify_to");

            if (!$notify_to) {
                $notify_to = array();
            }

            $members_dropdown = array();
            $team_dropdown = array();

            //prepare team dropdown list
            if (in_array("team_members", $notify_to)) {
                $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->getResult();

                foreach ($team_members as $team_member) {
                    $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
                }
            }


            //prepare team member dropdown list
            if (in_array("team", $notify_to)) {
                $teams = $this->Team_model->get_all_where(array("deleted" => 0))->getResult();
                foreach ($teams as $team) {
                    $team_dropdown[] = array("id" => $team->id, "text" => $team->title);
                }
            }

            //prepare notify to terms
            if ($model_info->notify_to_terms) {
                $model_info->notify_to_terms = explode(",", $model_info->notify_to_terms);
            } else {
                $model_info->notify_to_terms = array();
            }

            $view_data['members_dropdown'] = json_encode($members_dropdown);
            $view_data['team_dropdown'] = json_encode($team_dropdown);

            $view_data["notify_to"] = $notify_to;
            $view_data["model_info"] = $model_info;

            return $this->template->view("settings/notifications/modal_form", $view_data);
        }
    }

    function notification_settings_list_data() {

        $options = array("category" => $this->request->getPost("category"));
        $list_data = $this->Notification_settings_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_notification_settings_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _notification_list_data($id) {
        $options = array("id" => $id);
        $data = $this->Notification_settings_model->get_details($options)->getRow();
        return $this->_make_notification_settings_row($data);
    }

    private function _make_notification_settings_row($data) {

        $yes = "<i data-feather='check-circle' class='icon-16'></i>";
        $no = "<i data-feather='check-circle' class='icon-16' style='opacity:0.2'></i>";

        $notify_to = "";

        if ($data->notify_to_terms) {
            $terms = explode(",", $data->notify_to_terms);
            foreach ($terms as $term) {
                if ($term) {
                    $notify_to .= "<li>" . app_lang($term) . "</li>";
                }
            }
        }

        if ($data->notify_to_team_members) {
            $notify_to .= "<li>" . app_lang("team_members") . ": " . $data->team_members_list . "</li>";
        }

        if ($data->notify_to_team) {
            $notify_to .= "<li>" . app_lang("team") . ": " . $data->team_list . "</li>";
        }

        if ($notify_to) {
            $notify_to = "<ul class='pl15'>" . $notify_to . "</ul>";
        }

        return array(
            $data->sort,
            app_lang($data->event),
            $notify_to,
            app_lang($data->category),
            $data->enable_email ? $yes : $no,
            $data->enable_web ? $yes : $no,
            $data->enable_slack ? $yes : $no,
            modal_anchor(get_uri("settings/notification_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('notification'), "data-post-id" => $data->id))
        );
    }

    function save_notification_settings() {
        $id = $this->request->getPost("id");

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $data = array(
            "enable_web" => $this->request->getPost("enable_web"),
            "enable_email" => $this->request->getPost("enable_email"),
            "enable_slack" => $this->request->getPost("enable_slack"),
            "notify_to_team" => "",
            "notify_to_team_members" => "",
            "notify_to_terms" => "",
        );


        //get post data and prepare notificaton terms
        $notify_to_terms_list = $this->Notification_settings_model->notify_to_terms();
        $notify_to_terms = "";

        foreach ($notify_to_terms_list as $key => $term) {

            if ($term == "team") {
                $data["notify_to_team"] = $this->request->getPost("team"); //set team
            } else if ($term == "team_members") {
                $data["notify_to_team_members"] = $this->request->getPost("team_members"); //set team members
            } else {
                //prepare comma separated terms
                $other_term = $this->request->getPost($term);

                if ($other_term) {
                    if ($notify_to_terms) {
                        $notify_to_terms .= ",";
                    }

                    $notify_to_terms .= $term;
                }
            }
        }


        $data["notify_to_terms"] = $notify_to_terms;


        $save_id = $this->Notification_settings_model->ci_save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_notification_list_data($save_id), 'id' => $save_id, 'message' => app_lang('settings_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function modules() {
        return $this->template->rander("settings/modules");
    }

    function save_module_settings() {
        $settings = array("module_timeline", "module_event", "module_todo", "module_note", "module_message", "module_chat", "module_invoice", "module_expense", "module_attendance", "module_leave", "module_estimate", "module_estimate_request", "module_lead", "module_ticket", "module_announcement", "module_project_timesheet", "module_help", "module_knowledge_base", "module_gantt", "module_order");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file */

    function validate_file() {
        return validate_post_file($this->request->getPost("file_name"));
    }

    /* show the cron job tab */

    function cron_job() {
        return $this->template->rander("settings/cron_job");
    }

    /* show the integration tab */

    function integration($tab = "") {
        $view_data["tab"] = $tab;
        return $this->template->rander("settings/integration/index", $view_data);
    }

    /* load content in reCAPTCHA tab */

    function re_captcha() {
        return $this->template->view("settings/integration/re_captcha");
    }

    /* save reCAPTCHA settings */

    function save_re_captcha_settings() {

        $settings = array("re_captcha_site_key", "re_captcha_secret_key");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    /* load content in bitbucket tab */

    function bitbucket() {
        return $this->template->view("settings/integration/bitbucket");
    }

    /* save bitbucket settings */

    function save_bitbucket_settings() {

        $settings = array("enable_bitbucket_commit_logs_in_tasks");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    /* show the ticket settings tab */

    function tickets() {
        return $this->template->view("settings/tickets/index");
    }

    /* save ticket settings */

    function save_ticket_settings() {

        $settings = array("show_recent_ticket_comments_at_the_top", "ticket_prefix", "project_reference_in_tickets", "auto_close_ticket_after", "auto_reply_to_tickets", "auto_reply_to_tickets_message", "enable_embedded_form_to_get_tickets");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    //show task settings
    function tasks() {
        return $this->template->view("settings/tasks");
    }

    /* show imap settings tab */

    function imap_settings() {
        return $this->template->view("settings/tickets/imap_settings");
    }

    /* push notification integration settings tab */

    function push_notification() {
        return $this->template->view("settings/integration/push_notification/index");
    }

    //save task settings
    function save_task_settings() {

        $settings = array("project_task_reminder_on_the_day_of_deadline", "project_task_deadline_pre_reminder", "project_task_deadline_overdue_reminder", "enable_recurring_option_for_tasks", "task_point_range", "create_recurring_tasks_before");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    /* save imap settings */

    function save_imap_settings() {
        $settings = array("enable_email_piping", "create_tickets_only_by_registered_emails", "imap_ssl_enabled", "imap_host", "imap_port", "imap_email", "imap_password");

        $enable_email_piping = $this->request->getPost("enable_email_piping");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            //if user change credentials, flag imap as unauthorized
            if (get_setting("imap_authorized") && $setting != "enable_email_piping" && $enable_email_piping && (($setting == "imap_password" && decode_password(get_setting('imap_password'), "imap_password") != $value) || get_setting($setting) != $value)) {
                $this->Settings_model->save_setting("imap_authorized", "0");
            }

            if ($setting == "imap_password") {
                $value = encode_id($value, "imap_password");
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    /* save push notification settings */

    function save_push_notification_settings() {
        $settings = array("enable_push_notification", "pusher_app_id", "pusher_key", "pusher_secret", "pusher_cluster", "enable_chat_via_pusher");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    /* show the google drive settings tab */

    function google_drive() {
        return $this->template->view("settings/integration/google_drive");
    }

    /* save google drive settings */

    function save_google_drive_settings() {
        $settings = array("enable_google_drive_api_to_upload_file", "google_drive_client_id", "google_drive_client_secret");

        $enable_google_drive = $this->request->getPost("enable_google_drive_api_to_upload_file");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            //if user change credentials, flag google drive as unauthorized
            if (get_setting("google_drive_authorized") && ($setting == "google_drive_client_id" || $setting == "google_drive_client_secret") && $enable_google_drive && get_setting($setting) != $value) {
                $this->Settings_model->save_setting("google_drive_authorized", "0");
            }

            $this->Settings_model->save_setting($setting, $value);
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    //authorize imap
    function authorize_imap() {
        if (get_setting("enable_email_piping")) {
            $imap = new Imap();

            if (!$imap->authorize_imap_and_get_inbox()) {
                $this->session->setFlashdata("error_message", app_lang("imap_error_credentials_message"));
            }
            app_redirect("ticket_types/index/imap");
        }
    }

    function estimates() {
        $estimate_info = $this->Estimates_model->get_estimate_last_id();
        $view_data["last_id"] = $estimate_info;

        return $this->template->rander("settings/estimates", $view_data);
    }

    function save_estimate_settings() {
        $settings = array("estimate_logo", "estimate_prefix", "estimate_color", "estimate_footer", "send_estimate_bcc_to", "initial_number_of_the_estimate", "create_new_projects_automatically_when_estimates_gets_accepted");
        $reload_page = false;

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            $saveable = true;

            if ($setting === "estimate_footer") {
                $value = decode_ajax_post_data($value);
            } else if ($setting === "estimate_logo" && $value) {
                $value = str_replace("~", ":", $value);
                $value = serialize(move_temp_file("estimate-logo.png", get_setting("system_file_path"), "", $value));
            }

            if (is_null($value)) {
                $value = "";
            }

            //process the file which has uploaded using manual file submit
            if ($setting === "estimate_logo" && $_FILES) {
                $estimate_logo_file = get_array_value($_FILES, "estimate_logo_file");
                $estimate_logo_file_name = get_array_value($estimate_logo_file, "tmp_name");
                if ($estimate_logo_file_name) {
                    $value = serialize(move_temp_file("estimate-logo.png", get_setting("system_file_path"), "", $estimate_logo_file_name));
                    $reload_page = true;
                }
            }


            //don't save blank image
            if ($setting === "estimate_logo" && !$value) {
                $saveable = false;
            }

            if ($saveable) {
                if ($setting === "estimate_logo") {
                    //delete old file
                    delete_app_files(get_setting("system_file_path"), get_system_files_setting_value("estimate_logo"));
                }

                $this->Settings_model->save_setting($setting, $value);
            }

            if ($setting === "initial_number_of_the_estimate") {
                $this->Estimates_model->save_initial_number_of_estimate($value);
            }
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated'), "reload_page" => $reload_page));
    }

    //show a demo push notification
    function test_push_notification() {
        helper('notifications');
        if (send_push_notifications("test_push_notification", $this->login_user->id, $this->login_user->id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('push_notification_error_message')));
        }
    }

    /* show timesheet settings tab */

    function timesheets() {
        return $this->template->rander("settings/timesheets");
    }

    /* save timesheet settings */

    function save_timesheets_settings() {
        $settings = array(
            "users_can_start_multiple_timers_at_a_time",
            "users_can_input_only_total_hours_instead_of_period"
        );

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function gdpr() {
        return $this->template->rander("settings/gdpr");
    }

    function save_gdpr_settings() {
        $settings = array("enable_gdpr", "allow_clients_to_export_their_data", "clients_can_request_account_removal", "show_terms_and_conditions_in_client_signup_page", "gdpr_terms_and_conditions_link");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function footer() {
        //check available menus
        $footer_menus_data = "";

        $footer_menus = unserialize(get_setting("footer_menus"));
        if ($footer_menus && is_array($footer_menus)) {
            foreach ($footer_menus as $footer) {
                $footer_menus_data .= $this->_make_footer_menu_item_data($footer->menu_name, $footer->url);
            }
        }

        $view_data["footer_menus"] = $footer_menus_data;

        return $this->template->rander("settings/footer/index", $view_data);
    }

    private function _make_footer_menu_item_data($menu_name, $url, $type = "") {
        $edit = modal_anchor(get_uri("settings/footer_item_edit_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "float-end mr5 footer-menu-edit-btn", "title" => app_lang('edit_footer_menu'), "data-post-menu_name" => $menu_name, "data-post-url" => $url));
        $delete = "<span class='footer-menu-delete-btn  float-end clickable'><i data-feather='x' class='icon-16'></i></span>";

        if ($type == "data") {
            return "<a href='$url' target='_blank'>$menu_name</a>" . $delete . $edit;
        } else {
            return "<div class='list-group-item footer-menu-item' data-footer_menu_temp_id='" . rand(2000, 400000000) . "'><a href='$url' target='_blank'>$menu_name</a>" . $delete . $edit . "</div>";
        }
    }

    function save_footer_settings() {
        $settings = array("enable_footer", "footer_menus", "footer_copyright_text");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            if ($setting == "footer_menus") {
                $value = json_decode($value);
                $value = $value ? serialize($value) : serialize(array());
            }

            $this->Settings_model->save_setting($setting, $value);
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function footer_item_edit_modal_form() {
        $model_info = new \stdClass();
        $model_info->menu_name = $this->request->getPost("menu_name");
        $model_info->url = $this->request->getPost("url");

        $view_data["model_info"] = $model_info;

        return $this->template->view("settings/footer/modal_form", $view_data);
    }

    function save_footer_menu() {
        $menu_name = $this->request->getPost("menu_name");
        $url = $this->request->getPost("url");
        $type = $this->request->getPost("type");

        if ($menu_name && $url) {
            echo json_encode(array("success" => true, 'data' => $this->_make_footer_menu_item_data($menu_name, $url, $type)));
        }
    }

    function estimate_request_settings() {
        $hidden_fields = array(
            "first_name",
            "last_name",
            "email",
            "address",
            "city",
            "state",
            "zip",
            "country",
            "phone"
        );

        $hidden_fields_dropdown = array();
        foreach ($hidden_fields as $hidden_field) {
            $hidden_fields_dropdown[] = array("id" => $hidden_field, "text" => app_lang($hidden_field));
        }

        $view_data['hidden_fields_dropdown'] = json_encode($hidden_fields_dropdown);
        return $this->template->view("settings/estimate_requests", $view_data);
    }

    function save_estimate_request_settings() {
        $settings = array(
            "hidden_client_fields_on_public_estimate_requests"
        );

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            //email can't be shown without first name or last name
            $value_array = explode(',', $value);
            if (in_array("first_name", $value_array) && in_array("last_name", $value_array) && !in_array("email", $value_array)) {
                echo json_encode(array("success" => false, 'message' => app_lang("estimate_request_name_email_error_message")));
                return false;
            }

            $this->Settings_model->save_setting($setting, $value);
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function orders() {
        $order_info = $this->Orders_model->get_order_last_id();
        $view_data["last_id"] = $order_info;
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));

        return $this->template->rander("settings/orders", $view_data);
    }

    function save_order_settings() {
        $settings = array("order_logo", "order_prefix", "order_color", "order_footer", "initial_number_of_the_order", "order_tax_id", "order_tax_id2");
        $reload_page = false;

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            $saveable = true;

            if ($setting === "order_footer") {
                $value = decode_ajax_post_data($value);
            } else if ($setting === "order_logo" && $value) {
                $value = str_replace("~", ":", $value);
                $value = serialize(move_temp_file("order-logo.png", get_setting("system_file_path"), "", $value));
            }

            if (is_null($value)) {
                $value = "";
            }

            //process the file which has uploaded using manual file submit
            if ($setting === "order_logo" && $_FILES) {
                $order_logo_file = get_array_value($_FILES, "order_logo_file");
                $order_logo_file_name = get_array_value($order_logo_file, "tmp_name");
                if ($order_logo_file_name) {
                    $value = serialize(move_temp_file("order-logo.png", get_setting("system_file_path"), "", $order_logo_file_name));
                    $reload_page = true;
                }
            }

            //don't save blank image
            if ($setting === "order_logo" && !$value) {
                $saveable = false;
            }

            if ($saveable) {
                if ($setting === "order_logo") {
                    //delete old file
                    delete_app_files(get_setting("system_file_path"), get_system_files_setting_value("order_logo"));
                }

                $this->Settings_model->save_setting($setting, $value);
            }

            if ($setting === "initial_number_of_the_order") {
                $this->Orders_model->save_initial_number_of_order($value);
            }
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated'), "reload_page" => $reload_page));
    }

    function projects() {
        $project_tabs = array(
            "overview",
            "tasks_list",
            "tasks_kanban",
            "milestones",
            "gantt",
            "notes",
            "files",
            "comments",
            "customer_feedback",
            "timesheets",
            "invoices",
            "payments",
            "expenses",
        );

        $project_tabs_dropdown = array();
        foreach ($project_tabs as $project_tab) {
            $project_tabs_dropdown[] = array("id" => $project_tab, "text" => app_lang($project_tab));
        }

        $view_data['project_tabs_dropdown'] = json_encode($project_tabs_dropdown);
        return $this->template->rander("settings/projects", $view_data);
    }

    function save_projects_settings() {
        $settings = array(
            "project_tab_order",
        );

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function slack() {
        return $this->template->view("settings/integration/slack");
    }

    function save_slack_settings() {
        $settings = array("enable_slack", "slack_webhook_url", "slack_dont_send_any_projects");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function client_projects() {
        $project_tabs = array(
            "overview",
            "tasks_list",
            "tasks_kanban",
            "files",
            "comments",
            "milestones",
            "gantt",
            "timesheets",
        );

        $project_tabs_dropdown = array();
        foreach ($project_tabs as $project_tab) {
            $project_tabs_dropdown[] = array("id" => $project_tab, "text" => app_lang($project_tab));
        }

        $view_data['project_tabs_dropdown'] = json_encode($project_tabs_dropdown);
        return $this->template->rander("settings/client_projects", $view_data);
    }

    function save_client_project_settings() {
        $settings = array(
            "project_tab_order_of_clients",
        );

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    /* load content in github tab */

    function github() {
        return $this->template->view("settings/integration/github");
    }

    /* save github settings */

    function save_github_settings() {

        $settings = array("enable_github_commit_logs_in_tasks");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    function test_slack_notification() {
        helper('notifications');
        if (send_slack_notification("test_slack_notification", $this->login_user->id, 0, get_setting("slack_webhook_url"))) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('slack_notification_error_message')));
        }
    }

}

/* End of file general_settings.php */
/* Location: ./app/controllers/general_settings.php */