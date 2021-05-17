<?php

if ($notification->task_id && $notification->task_title) {
    echo "\n*" . app_lang("task") . ":* #$notification->task_id - " . $notification->task_title;
}

if ($notification->payment_invoice_id) {
    echo "\n" . to_currency($notification->payment_amount, $notification->client_currency_symbol) . "  -  " . get_invoice_id($notification->payment_invoice_id);
}

if ($notification->ticket_id && $notification->ticket_title) {
    echo "\n" . get_ticket_id($notification->ticket_id) . " - " . $notification->ticket_title;
}

if ($notification->leave_id && $notification->leave_start_date) {
    $leave_date = format_to_date($notification->leave_start_date, FALSE);
    if ($notification->leave_start_date != $notification->leave_end_date) {
        $leave_date = sprintf(app_lang('start_date_to_end_date_format'), format_to_date($notification->leave_start_date, FALSE), format_to_date($notification->leave_end_date, FALSE));
    }
    echo "\n*" . app_lang("date") . ":* " . $leave_date;
}

if ($notification->project_comment_id && $notification->project_comment_title && !strpos($notification->project_comment_title, "</a>")) {
    echo "\n*" . app_lang("comment") . ":* " . convert_mentions($notification->project_comment_title, false);
}

if ($notification->project_file_id && $notification->project_file_title) {
    echo "\n*" . app_lang("file") . ":* " . remove_file_prefix($notification->project_file_title);
}

if ($notification->project_id && $notification->project_title) {
    echo "\n*" . app_lang("project") . ":* " . $notification->project_title;
}

if ($notification->estimate_id) {
    echo "\n" . get_estimate_id($notification->estimate_id);
}

if ($notification->event_title) {
    echo "\n*" . app_lang("event") . ":* " . $notification->event_title;
}

if ($notification->announcement_title) {
    echo "\n*" . app_lang("title") . ":* " . $notification->announcement_title;
}
