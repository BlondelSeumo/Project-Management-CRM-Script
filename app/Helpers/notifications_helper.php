<?php

use App\Controllers\App_Controller;

/*
 * Define who are allowed to receive notifications
 * Using following terms:
 * team_members, team,
 * project_members, client_primary_contact, client_all_contacts, task_assignee, task_collaborators, comment_creator, leave_applicant, ticket_creator, ticket_assignee, post_creator
 */
if (!function_exists('get_notification_config')) {

    function get_notification_config($event = "", $key = "", $info_options = array()) {

        $task_link = function($options) {

            $url = "";
            $ajax_url = "";
            $id = "";

            if (isset($options->task_id)) {
                $ajax_url = get_uri("projects/task_view/");
                $id = $options->task_id;
                $url = get_uri("projects/task_view/" . $id);
            }

            if ((isset($options->task_id) && $options->task_id) || (isset($options->project_id) && $options->project_id)) {
                return array("url" => $url, "ajax_modal_url" => $ajax_url, "large_modal" => "1", "id" => $id);
            } else {
                //return all tasks link for reminder notifications
                return array("url" => get_uri("projects/all_tasks"));
            }
        };


        $project_link = function($options) {
            $url = "";
            if (isset($options->project_id)) {
                $url = get_uri("projects/view/" . $options->project_id);

                if ($options->event == "project_customer_feedback_added" || $options->event == "project_customer_feedback_replied") {
                    $url .= "/customer_feedback";
                } else if ($options->event == "project_comment_added" || $options->event == "project_comment_replied") {
                    $url .= "/comment";
                }
            }

            return array("url" => $url);
        };


        $project_file_link = function($options) {

            $url = "";
            $app_modal_url = "";
            $id = "";

            if (isset($options->project_id)) {
                $url = get_uri("projects/view/" . $options->project_id . "/files");
            }

            if (isset($options->project_file_id)) {
                $app_modal_url = get_uri("projects/view_file/" . $options->project_file_id);
                $id = $options->project_file_id;
            }

            return array("url" => $url, "app_modal_url" => $app_modal_url, "id" => $id);
        };


        $client_link = function($options) {
            $url = "";
            if (isset($options->client_id)) {
                $url = get_uri("clients/view/" . $options->client_id);
            }

            return array("url" => $url);
        };

        $leave_link = function($options) {
            $url = "";
            $ajax_url = "";
            $id = "";

            if (isset($options->leave_id)) {
                $url = get_uri("dashboard");
                $ajax_url = get_uri("leaves/application_details");
                $id = $options->leave_id;
            }

            return array("url" => $url, "ajax_modal_url" => $ajax_url, "id" => $id);
        };


        $ticket_link = function($options) {
            $url = "";
            if (isset($options->ticket_id)) {
                $url = get_uri("tickets/view/" . $options->ticket_id);
            }

            return array("url" => $url);
        };


        $invoice_link = function($options) {
            $url = "";
            if (isset($options->invoice_id)) {
                $url = get_uri("invoices/preview/" . $options->invoice_id);
            }

            return array("url" => $url);
        };

        $estimate_link = function($options) {
            $url = "";
            if (isset($options->estimate_id)) {
                $url = get_uri("estimates/preview/" . $options->estimate_id . "/1");
            }

            return array("url" => $url);
        };

        $order_link = function($options) {
            $url = "";
            if (isset($options->order_id)) {
                $url = get_uri("orders/preview/" . $options->order_id . "/1");
            }

            return array("url" => $url);
        };

        $estimate_request_link = function($options) {
            $url = "";
            if (isset($options->estimate_request_id)) {
                $url = get_uri("estimate_requests/view_estimate_request/" . $options->estimate_request_id);
            }

            return array("url" => $url);
        };

        $message_link = function($options) {
            $url = "";
            if (isset($options->actual_message_id)) {
                $message_id = isset($options->parent_message_id) && $options->parent_message_id ? $options->parent_message_id : $options->actual_message_id;
                $url = get_uri("messages/inbox/" . $message_id);
            }

            return array("url" => $url);
        };

        $announcement_link = function($options) {
            $url = "";
            if (isset($options->announcement_id)) {
                $url = get_uri("announcements/view/" . $options->announcement_id);
            }

            return array("url" => $url);
        };

        $event_link = function($options) {
            $url = "";
            $id = "";

            if (isset($options->event_id)) {
                $id = encode_id($options->event_id, "event_id");
                $url = get_uri("events/index/" . $id);
            }

            if (isset($options->task_id)) {
                $ajax_url = get_uri("events/view");
            }

            return array("url" => $url, "ajax_modal_url" => $ajax_url, "id" => $id);
        };

        $lead_link = function($options) {
            $url = "";
            if (isset($options->lead_id)) {
                $url = get_uri("leads/view/" . $options->lead_id);
            }

            return array("url" => $url);
        };

        $timeline_link = function($options) {
            $url = "";
            if (isset($options->post_id)) {
                $url = get_uri("timeline/post/" . $options->post_id);
            }
            return array("url" => $url);
        };


        $events = array(
            "project_created" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "team_members", "team"),
                "info" => $project_link
            ),
            "project_completed" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team"),
                "info" => $project_link
            ),
            "project_deleted" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team")
            ),
            "project_task_created" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "task_assignee", "task_collaborators", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_updated" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "task_assignee", "task_collaborators", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_assigned" => array(
                "notify_to" => array("project_members", "task_assignee", "task_collaborators", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_started" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_finished" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_reopened" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_commented" => array(
                "notify_to" => array("mentioned_members", "project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "task_assignee", "task_collaborators", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_deleted" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "task_assignee", "task_collaborators", "team_members", "team"),
            ),
            "project_member_added" => array(
                "notify_to" => array("project_members", "team_members", "team"),
                "info" => $project_link
            ),
            "project_member_deleted" => array(
                "notify_to" => array("project_members", "team_members", "team")
            ),
            "project_file_added" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team"),
                "info" => $project_file_link
            ),
            "project_file_deleted" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team")
            ),
            "project_file_commented" => array(
                "notify_to" => array("mentioned_members", "project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team"),
                "info" => $project_file_link
            ),
            "project_comment_added" => array(
                "notify_to" => array("mentioned_members", "project_members", "team_members", "team"),
                "info" => $project_link
            ),
            "project_comment_replied" => array(
                "notify_to" => array("mentioned_members", "project_members", "comment_creator", "team_members", "team"),
                "info" => $project_link
            ),
            "project_customer_feedback_added" => array(
                "notify_to" => array("mentioned_members", "project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "team_members", "team"),
                "info" => $project_link
            ),
            "project_customer_feedback_replied" => array(
                "notify_to" => array("mentioned_members", "project_members", "client_primary_contact", "client_all_contacts", "client_assigned_contacts", "comment_creator", "team_members", "team"),
                "info" => $project_link
            ),
            "client_signup" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $client_link
            ),
            "client_contact_requested_account_removal" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $client_link
            ),
            "invoice_online_payment_received" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $invoice_link
            ),
            "invoice_payment_confirmation" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts"),
                "info" => $invoice_link
            ),
            "recurring_invoice_created_vai_cron_job" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "team_members", "team"),
                "info" => $invoice_link
            ),
            "invoice_due_reminder_before_due_date" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "team_members", "team"),
                "info" => $invoice_link
            ),
            "invoice_overdue_reminder" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "team_members", "team"),
                "info" => $invoice_link
            ),
            "recurring_invoice_creation_reminder" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "team_members", "team"),
                "info" => $invoice_link
            ),
            "leave_application_submitted" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $leave_link
            ),
            "leave_approved" => array(
                "notify_to" => array("leave_applicant", "team_members", "team"),
                "info" => $leave_link
            ),
            "leave_assigned" => array(
                "notify_to" => array("leave_applicant", "team_members", "team"),
                "info" => $leave_link
            ),
            "leave_rejected" => array(
                "notify_to" => array("leave_applicant", "team_members", "team"),
                "info" => $leave_link
            ),
            "leave_canceled" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $leave_link
            ),
            "ticket_created" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "ticket_creator", "ticket_assignee", "team_members", "team"),
                "info" => $ticket_link
            ),
            "ticket_commented" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "ticket_creator", "ticket_assignee", "team_members", "team"),
                "info" => $ticket_link
            ),
            "ticket_closed" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "ticket_creator", "ticket_assignee", "team_members", "team"),
                "info" => $ticket_link
            ),
            "ticket_reopened" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "ticket_creator", "ticket_assignee", "team_members", "team"),
                "info" => $ticket_link
            ),
            "estimate_request_received" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $estimate_request_link
            ),
            "estimate_accepted" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $estimate_link
            ),
            "estimate_rejected" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $estimate_link
            ),
            "new_message_sent" => array(
                "notify_to" => array("recipient"),
                "info" => $message_link
            ),
            "message_reply_sent" => array(
                "notify_to" => array("recipient"),
                "info" => $message_link
            ),
            "new_event_added_in_calendar" => array(
                "notify_to" => array("recipient"),
                "info" => $event_link
            ),
            "calendar_event_modified" => array(
                "notify_to" => array("recipient"),
                "info" => $event_link
            ),
            "new_announcement_created" => array(
                "notify_to" => array("recipient"),
                "info" => $announcement_link
            ),
            "bitbucket_push_received" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "task_assignee", "task_collaborators", "team_members", "team"),
                "info" => $task_link
            ),
            "github_push_received" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "task_assignee", "task_collaborators", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_deadline_pre_reminder" => array(
                "notify_to" => array("task_assignee", "project_members", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_deadline_overdue_reminder" => array(
                "notify_to" => array("task_assignee", "project_members", "team_members", "team"),
                "info" => $task_link
            ),
            "project_task_reminder_on_the_day_of_deadline" => array(
                "notify_to" => array("task_assignee", "project_members", "team_members", "team"),
                "info" => $task_link
            ),
            "recurring_task_created_via_cron_job" => array(
                "notify_to" => array("project_members", "client_primary_contact", "client_all_contacts", "task_assignee", "task_collaborators", "team_members", "team"),
                "info" => $task_link
            ),
            "lead_created" => array(
                "notify_to" => array("owner", "team_members", "team"),
                "info" => $lead_link
            ),
            "client_created_from_lead" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $client_link
            ),
            "new_order_received" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $order_link
            ),
            "order_status_updated" => array(
                "notify_to" => array("client_primary_contact", "client_all_contacts", "order_creator_contact", "team_members", "team"),
                "info" => $order_link
            ),
            "timeline_post_commented" => array(
                "notify_to" => array("post_creator", "team_members", "team"),
                "info" => $timeline_link
            ),
            "created_a_new_post" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $timeline_link
            ),
            "invited_client_contact_signed_up" => array(
                "notify_to" => array("team_members", "team"),
                "info" => $client_link
            ),
            "ticket_assigned" => array(
                "notify_to" => array("ticket_assignee", "team_members", "team"),
                "info" => $ticket_link
            )
        );

        if ($event) {
            $result = get_array_value($events, $event);
            if ($key && $result) {
                $key_result = get_array_value($result, $key);
                if ($info_options && $key_result) {
                    return $key_result($info_options);
                } else {
                    return $key_result;
                }
            } else {
                return $result;
            }
        } else {
            return $events;
        }
    }

}



/*
 * Send notification emails
 */
if (!function_exists('send_notification_emails')) {

    function send_notification_emails($notification_id, $email_notify_to, $extra_data = array()) {

        $ci = new App_Controller();

        $notification = $ci->Notifications_model->get_email_notification($notification_id);

        if (!$notification) {
            return false;
        }

        $url = get_uri();
        $parser_data = array();
        $info = get_notification_config($notification->event, "info", $notification);

        $email_options = array();
        $attachement_url = null;

        if (is_array($info) && get_array_value($info, "url")) {
            $url = get_array_value($info, "url");
        }


        $parser_data["APP_TITLE"] = get_setting("app_title");
        $parser_data["COMPANY_NAME"] = get_setting("company_name");

        $notification_multiple_tasks_user_wise = get_array_value($extra_data, "notification_multiple_tasks_user_wise");

        if ($notification->category == "ticket" && $notification->event !== "ticket_assigned") {
            $email_template = $ci->Email_templates_model->get_final_template($notification->event);

            $parser_data["TICKET_ID"] = $notification->ticket_id;
            $parser_data["TICKET_TITLE"] = $notification->ticket_title;
            $parser_data["USER_NAME"] = $notification->user_name;
            $parser_data["TICKET_CONTENT"] = nl2br($notification->ticket_comment_description);
            $parser_data["TICKET_URL"] = $url;

            //add attachment
            $comments_options = array("id" => $notification->ticket_comment_id);
            $comment_info = $ci->Ticket_comments_model->get_details($comments_options)->getRow();
            if ($comment_info->files) {
                $email_options["attachments"] = prepare_attachment_of_files(get_setting("timeline_file_path"), $comment_info->files);
            }

            //add imap email as reply-to email address, if it's enabled
            if (get_setting("enable_email_piping") && get_setting("imap_authorized") && get_setting("imap_email")) {
                $email_options["reply_to"] = get_setting("imap_email");
            }

            //add custom variable data
            $custom_variables_data = get_custom_variables_data("tickets", $notification->ticket_id);
            if ($custom_variables_data) {
                $parser_data = array_merge($parser_data, $custom_variables_data);
            }
        } else if ($notification->event == "invoice_payment_confirmation") {
            $email_template = $ci->Email_templates_model->get_final_template("invoice_payment_confirmation");
            $parser_data["PAYMENT_AMOUNT"] = to_currency($notification->payment_amount, $notification->client_currency_symbol);
            $parser_data["INVOICE_ID"] = get_invoice_id($notification->payment_invoice_id);
            $parser_data["INVOICE_URL"] = $url;
        } else if ($notification->event == "new_message_sent" || $notification->event == "message_reply_sent") {
            $email_template = $ci->Email_templates_model->get_final_template("message_received");

            $message_info = $ci->Messages_model->get_details(array("id" => $notification->actual_message_id))->row;

            //reply? find the subject from the parent meessage
            if ($notification->event == "message_reply_sent") {
                $main_message_info = $ci->Messages_model->get_details(array("id" => $message_info->message_id))->row;
                $parser_data["SUBJECT"] = $main_message_info->subject;
            }

            $parser_data["SUBJECT"] = $message_info->subject;
            $parser_data["USER_NAME"] = $message_info->user_name;
            $parser_data["MESSAGE_CONTENT"] = nl2br($message_info->message);
            $parser_data["MESSAGE_URL"] = $url;

            if ($message_info->files) {
                $email_options["attachments"] = prepare_attachment_of_files(get_setting("timeline_file_path"), $message_info->files);
            }
        } else if ($notification->event == "recurring_invoice_created_vai_cron_job" || $notification->event == "invoice_due_reminder_before_due_date" || $notification->event == "invoice_overdue_reminder" || $notification->event == "recurring_invoice_creation_reminder") {

            //get the specific email template
            if ($notification->event == "recurring_invoice_created_vai_cron_job") {
                $email_template = $ci->Email_templates_model->get_final_template("send_invoice");
            } else if ($notification->event == "invoice_due_reminder_before_due_date") {
                $email_template = $ci->Email_templates_model->get_final_template("invoice_due_reminder_before_due_date");
            } else if ($notification->event == "invoice_overdue_reminder") {
                $email_template = $ci->Email_templates_model->get_final_template("invoice_overdue_reminder");
            } else if ($notification->event == "recurring_invoice_creation_reminder") {
                $email_template = $ci->Email_templates_model->get_final_template("recurring_invoice_creation_reminder");
            }

            $invoice_data = get_invoice_making_data($notification->invoice_id);
            $invoice_info = get_array_value($invoice_data, "invoice_info");
            $invoice_total_summary = get_array_value($invoice_data, "invoice_total_summary");

            $primary_contact = $ci->Clients_model->get_primary_contact($invoice_info->client_id, true);

            $parser_data["INVOICE_ID"] = $notification->invoice_id;
            $parser_data["CONTACT_FIRST_NAME"] = $primary_contact->first_name;
            $parser_data["CONTACT_LAST_NAME"] = $primary_contact->last_name;
            $parser_data["BALANCE_DUE"] = to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol);
            $parser_data["DUE_DATE"] = format_to_date($invoice_info->due_date, false);
            $parser_data["PROJECT_TITLE"] = $invoice_info->project_title;
            $parser_data["INVOICE_URL"] = $url;


            $attachement_url = prepare_invoice_pdf($invoice_data, "send_email");
            $email_options["attachments"] = array(array("file_path" => $attachement_url));

            if ($notification->event == "recurring_invoice_creation_reminder") {
                $parser_data["NEXT_RECURRING_DATE"] = format_to_date($invoice_info->next_recurring_date, false);
            }

            //if invoice is sending to client, change the invoice status and last email sent date.
            $notify_to_terms = get_array_value($extra_data, "notify_to_terms");
            if (array_search("client_all_contacts", $notify_to_terms) !== false || array_search("client_primary_contact", $notify_to_terms) !== false) {
                $invoice_status_data = array("status" => "not_paid");

                //chenge last email sending time, if there is any email to client
                if (get_array_value($extra_data, "email_sending_to_client")) {
                    $invoice_status_data["last_email_sent_date"] = get_my_local_time();
                }

                $ci->Invoices_model->ci_save($invoice_status_data, $notification->invoice_id);
            }
        } else if ($notification->category == "estimate") {
            if ($notification->event == "estimate_request_received") {
                $email_template = $ci->Email_templates_model->get_final_template("estimate_request_received");

                $estimate_request_info = $ci->Estimate_requests_model->get_one($notification->estimate_request_id);
                $primary_contact = $ci->Clients_model->get_primary_contact($estimate_request_info->client_id, true);

                $parser_data["CONTACT_FIRST_NAME"] = $primary_contact->first_name;
                $parser_data["CONTACT_LAST_NAME"] = $primary_contact->last_name;

                $parser_data["ESTIMATE_REQUEST_ID"] = $notification->estimate_request_id;
                $parser_data["ESTIMATE_REQUEST_URL"] = $url;
            } else {
                //attach a pdf copy of estimate
                $estimate_data = get_estimate_making_data($notification->estimate_id);
                $attachement_url = prepare_estimate_pdf($estimate_data, "send_email");
                $email_options["attachments"] = array(array("file_path" => $attachement_url));

                if ($notification->event == "estimate_rejected") {
                    $email_template = $ci->Email_templates_model->get_final_template("estimate_rejected");
                } else if ($notification->event == "estimate_accepted") {
                    $email_template = $ci->Email_templates_model->get_final_template("estimate_accepted");
                }

                $parser_data["ESTIMATE_ID"] = $notification->estimate_id;
                $parser_data["ESTIMATE_URL"] = $url;
            }
        } else if ($notification->category == "order") {
            if ($notification->event == "new_order_received") {
                $email_template = $ci->Email_templates_model->get_final_template("new_order_received");
            } else {
                $email_template = $ci->Email_templates_model->get_final_template("order_status_updated");
            }

            $order_info = $ci->Orders_model->get_one($notification->order_id);
            $primary_contact = $ci->Clients_model->get_primary_contact($order_info->client_id, true);

            $parser_data["CONTACT_FIRST_NAME"] = $primary_contact->first_name;
            $parser_data["CONTACT_LAST_NAME"] = $primary_contact->last_name;

            $parser_data["ORDER_ID"] = $notification->order_id;
            $parser_data["ORDER_URL"] = $url;

            //attach a pdf copy of order
            $order_data = get_order_making_data($notification->order_id);
            $attachement_url = prepare_order_pdf($order_data, "send_email");
            $email_options["attachments"] = array(array("file_path" => $attachement_url));
        } else {
            $email_template = $ci->Email_templates_model->get_final_template("general_notification");

            $parser_data["EVENT_TITLE"] = "<b>" . $notification->user_name . "</b> " . sprintf(app_lang("notification_" . $notification->event), $notification->to_user_name);
            $parser_data["NOTIFICATION_URL"] = $url;


            $view_data["notification"] = $notification;
            $parser_data["EVENT_DETAILS"] = view("notifications/notification_description", $view_data);
        }

        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["LOGO_URL"] = get_logo_url();
        $parser = \Config\Services::parser();
        $message = $parser->setData($parser_data)->renderString($email_template->message);

        $parser_data["EVENT_TITLE"] = $notification->user_name . " " . sprintf(app_lang("notification_" . $notification->event), $notification->to_user_name);
        $subject = $parser->setData($parser_data)->renderString($email_template->subject);

        // error_log("event: " . $notification->event . PHP_EOL, 3, "notification.txt");
        // error_log("subject: " . $subject . PHP_EOL, 3, "notification.txt");
        // error_log("message: " . $message . PHP_EOL, 3, "notification.txt");
        // 
        //for task reminder notifications, we've to send different emails to different users
        if ($notification_multiple_tasks_user_wise && ($notification->event == "project_task_deadline_pre_reminder" || $notification->event == "project_task_reminder_on_the_day_of_deadline" || $notification->event == "project_task_deadline_overdue_reminder")) {
            //task reminders
            $email_template = $ci->Email_templates_model->get_final_template("project_task_deadline_reminder");

            //get the deadline
            //all deadlines are same
            $task_deadline = reset($notification_multiple_tasks_user_wise); //get first user's tasks
            $task_deadline = get_array_value($task_deadline, 0); //first task
            $task_deadline = get_array_value($task_deadline, "task_id"); //task id
            $task_deadline = $ci->Tasks_model->get_one($task_deadline)->deadline;
            $parser_data["DEADLINE"] = format_to_date($task_deadline, false);

            foreach ($notification_multiple_tasks_user_wise as $user_id => $tasks) {
                //prepare all tasks of this user
                $table = view("projects/tasks/notification_multiple_tasks_table", array("tasks" => $tasks));

                $parser_data["TASKS_LIST"] = $table;
                $message = $parser->setData($parser_data)->renderString($email_template->message);
                $parser_data["EVENT_TITLE"] = $notification->user_name . " " . sprintf(app_lang("notification_" . $notification->event), $notification->to_user_name);
                $subject = $parser->setData($parser_data)->renderString($email_template->subject);

                $user_email_address = $ci->Users_model->get_one($user_id)->email;
                if ($user_email_address) {
                    send_app_mail($user_email_address, $subject, $message, $email_options);
                }
            }
        } else {
            if ($email_notify_to) {
                $email_notify_to_array = explode(",", $email_notify_to);
                foreach ($email_notify_to_array as $email_address) {
                    send_app_mail($email_address, $subject, $message, $email_options);
                }
            }
        }

        // delete the temp attachment
        if ($attachement_url && file_exists($attachement_url)) {
            unlink($attachement_url);
        }
    }

}

/*
 * Send push notifications
 */
if (!function_exists('send_push_notifications')) {

    function send_push_notifications($event, $push_notify_to, $user_id = 0, $notification_id = 0) {
        $ci = new App_Controller();

        //get credentials
        $pusher_app_id = get_setting("pusher_app_id");
        $pusher_key = get_setting("pusher_key");
        $pusher_secret = get_setting("pusher_secret");
        $pusher_cluster = get_setting("pusher_cluster");

        if ($pusher_app_id && $pusher_key && $pusher_secret && $pusher_cluster) {
            require_once(APPPATH . "ThirdParty/Pusher/vendor/autoload.php");

            $options = array(
                'cluster' => $pusher_cluster,
                'useTLS' => true
            );

            //authorize pusher
            $pusher = new Pusher\Pusher(
                    $pusher_key, $pusher_secret, $pusher_app_id, $options
            );

            //get notification message
            $message = app_lang("notification_" . $event);
            if ($notification_id) {
                $to_user_name = $ci->Notifications_model->get_to_user_name($notification_id);
                if ($to_user_name) {
                    $message = sprintf(app_lang("notification_" . $event), $to_user_name);
                }
            }

            //get notification url with indevudual attributes
            $url_attributes = "";
            if ($notification_id) {
                $notification_info = $ci->Notifications_model->get_one($notification_id);
                $url_attributes_array = get_notification_url_attributes($notification_info);
                $url_attributes = get_array_value($url_attributes_array, "url_attributes");
            }

            $user_info = $ci->Users_model->get_one($user_id);

            $data = array(
                "message" => $message,
                "title" => $user_id ? $user_info->first_name . " " . $user_info->last_name : get_setting('app_title'),
                "icon" => get_avatar($user_id ? $user_info->image : "system_bot"),
                "notification_id" => $notification_id,
                "url_attributes" => $url_attributes
            );

            $correct_credentials = false;

            //send events to pusher
            if ($push_notify_to) {
                $push_notify_to_array = explode(",", $push_notify_to);
                foreach ($push_notify_to_array as $user_id) {
                    if ($pusher->trigger('user_' . $user_id . '_channel', 'rise-pusher-event', $data)) {
                        $correct_credentials = true;
                    }
                }
            }

            return $correct_credentials;
        } else {
            return false;
        }
    }

}

/*
 * Get notification url attributes
 */
if (!function_exists('get_notification_url_attributes')) {

    function get_notification_url_attributes($notification) {
        $url = "#";
        $url_attributes = "href='$url'";

        $info = get_notification_config($notification->event, "info", $notification);
        if (is_array($info)) {
            $url = get_array_value($info, "url");
            $ajax_modal_url = get_array_value($info, "ajax_modal_url");
            $app_modal_url = get_array_value($info, "app_modal_url");
            $url_id = get_array_value($info, "id");

            if ($ajax_modal_url) {
                $url_attributes = "href='#' data-act='ajax-modal' data-action-url='$ajax_modal_url' data-post-id='$url_id' ";

                if (get_array_value($info, "large_modal")) {
                    $url_attributes .= " data-modal-lg = '1'";
                }
            } else if ($app_modal_url) {
                $url_attributes = "href='#' data-toggle='app-modal' data-url='$app_modal_url' ";
            } else {
                $url_attributes = "href='$url'";
            }
        }

        return array("url_attributes" => $url_attributes, "url" => $url);
    }

}

/*
 * Get notification multiple tasks
 */
if (!function_exists('get_notification_multiple_tasks_data')) {

    function get_notification_multiple_tasks_data($tasks, $event) {
        $ci = new App_Controller();
        $user_wise_tasks = array();

        //user whose are on the notify to team members or notify to team, will get all tasks
        //other users will get their assigned tasks if it enabled in notification setting
        $notify_to_users_from_settings = array();
        $notify_to_users_from_settings_result = $ci->Notification_settings_model->get_notify_to_users_of_event($event);
        foreach ($notify_to_users_from_settings_result->result as $notify_to_user_id) {
            array_push($notify_to_users_from_settings, $notify_to_user_id->id);
        }

        $notify_to_terms_array = explode(",", $notify_to_users_from_settings_result->notify_to_terms);

        $project_ids = array();
        foreach ($tasks as $task) {
            $task_data = array(
                "task_id" => $task->id,
                "task_title" => $task->title,
                "project_id" => $task->project_id,
                "project_title" => $task->project_title
            );

            //add all tasks to notify to users
            foreach ($notify_to_users_from_settings as $user_id) {
                $user_wise_tasks[$user_id][] = $task_data;
            }

            //add assigned task to related users
            if ($task->assigned_to && in_array("task_assignee", $notify_to_terms_array) && !in_array($task->assigned_to, $notify_to_users_from_settings)) {
                $user_wise_tasks[$task->assigned_to][] = $task_data;
            }

            //add project members 
            if (!in_array($task->project_id, $project_ids) && in_array("project_members", $notify_to_terms_array)) {
                $options = array("project_id" => $task->project_id);
                $project_members = $ci->Project_members_model->get_details($options)->getResult();
                foreach ($project_members as $project_member) {
                    $user_wise_tasks[$project_member->user_id][] = $task_data;
                }

                array_push($project_ids, $task->project_id);
            }
        }

        //prepare notify to user ids
        $notify_to_user_ids = array();
        foreach ($user_wise_tasks as $key => $value) {
            array_push($notify_to_user_ids, $key);
        }

        return array(
            "user_wise_tasks" => $user_wise_tasks,
            "notify_to_user_ids" => $notify_to_user_ids
        );
    }

}

if (!function_exists('send_slack_notification')) {

    function send_slack_notification($event, $user_id = 0, $notification_id = 0, $webhook_url = "") {
        if ($webhook_url) {
            $ci = new App_Controller();

            $message = app_lang("notification_" . $event);
            $notification_description = "";
            $url = "";

            if ($notification_id) {
                $to_user_name = $ci->Notifications_model->get_to_user_name($notification_id);
                if ($to_user_name) {
                    $message = sprintf(app_lang("notification_" . $event), $to_user_name);
                }

                //get notification url
                $notification_info = $ci->Notifications_model->get_email_notification($notification_id);
                $url_attributes_array = get_notification_url_attributes($notification_info);
                $url = get_array_value($url_attributes_array, "url");

                //prepare notification details
                $notification_description = view("notifications/notification_description_for_slack", array("notification" => $notification_info));
            }

            $user_info = $ci->Users_model->get_one($user_id);
            $title = $user_id ? ($user_info->first_name . " " . $user_info->last_name) : get_setting('app_title');
            $avatar = get_avatar($user_id ? $user_info->image : "system_bot");

            $data = array(
                "text" => "$title $message",
                "blocks" => array(
                    array(
                        "type" => "context",
                        "elements" => array(
                            array(
                                "type" => "image",
                                "image_url" => $avatar,
                                "alt_text" => $title
                            ),
                            array(
                                "type" => "mrkdwn",
                                "text" => "*$title* " . ($url ? "<$url|$message>" : $message)
                            )
                        )
                    )
                )
            );

            if ($notification_description) {
                //notification details
                $data["blocks"][] = array(
                    "type" => "context",
                    "elements" => array(
                        array(
                            "type" => "mrkdwn",
                            "text" => str_replace('<br />', '', $notification_description)
                        )
                    )
                );
            }

            $ch = curl_init($webhook_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            if ($result == "ok") {
                return true;
            }
        }
    }

}
