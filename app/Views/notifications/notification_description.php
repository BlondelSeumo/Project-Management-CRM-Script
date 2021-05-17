<?php

if ($notification->task_id && $notification->task_title) {
    echo "<div>" . app_lang("task") . ": #$notification->task_id - " . $notification->task_title . "</div>";
}

if ($notification->activity_log_changes !== "") {
    $final_changes_array = isset($changes_array) ? $changes_array : array();

    if (!count($final_changes_array)) {
        if ($notification->event === "bitbucket_push_received" || $notification->event === "github_push_received") {
            $final_changes_array = get_change_logs_array($notification->activity_log_changes, $notification->activity_log_type, $notification->event, true);
        } else {
            $final_changes_array = get_change_logs_array($notification->activity_log_changes, $notification->activity_log_type, "all");
        }
    }

    if (count($final_changes_array)) {
        if ($notification->event === "bitbucket_push_received" || $notification->event === "github_push_received") {
            echo get_array_value($final_changes_array, 0);
            unset($final_changes_array[0]);
        }

        echo "<ul>";
        foreach ($final_changes_array as $change) {
            //don't show the change log if there is any anchor tag
            if (!strpos($change, "</a>")) {
                echo $change;
            }
        }
        echo "</ul>";
    }
}

if ($notification->payment_invoice_id) {
    echo "<div>" . to_currency($notification->payment_amount, $notification->client_currency_symbol) . "  -  " . get_invoice_id($notification->payment_invoice_id) . "</div>";
}

if ($notification->ticket_id && $notification->ticket_title) {
    echo "<div>" . get_ticket_id($notification->ticket_id) . " - " . $notification->ticket_title . "</div>";
}

if ($notification->leave_id && $notification->leave_start_date) {
    $leave_date = format_to_date($notification->leave_start_date, FALSE);
    if ($notification->leave_start_date != $notification->leave_end_date) {
        $leave_date = sprintf(app_lang('start_date_to_end_date_format'), format_to_date($notification->leave_start_date, FALSE), format_to_date($notification->leave_end_date, FALSE));
    }
    echo "<div>" . app_lang("date") . ": " . $leave_date . "</div>";
}

if ($notification->project_comment_id && $notification->project_comment_title && !strpos($notification->project_comment_title, "</a>")) {
    echo "<div>" . app_lang("comment") . ": " . convert_mentions($notification->project_comment_title, false) . "</div>";
}

if ($notification->project_file_id && $notification->project_file_title) {
    echo "<div>" . app_lang("file") . ": " . remove_file_prefix($notification->project_file_title) . "</div>";
}


if ($notification->project_id && $notification->project_title) {
    echo "<div>" . app_lang("project") . ": " . $notification->project_title . "</div>";
}

if ($notification->estimate_id) {
    echo "<div>" . get_estimate_id($notification->estimate_id) . "</div>";
}

if ($notification->order_id) {
    echo "<div>" . get_order_id($notification->order_id) . "</div>";
}

if ($notification->event_title) {
    echo "<div>" . app_lang("event") . ": " . $notification->event_title . "</div>";
}

if ($notification->announcement_title) {
    echo "<div>" . app_lang("title") . ": " . $notification->announcement_title . "</div>";
}

if ($notification->post_id && $notification->posts_title) {
    echo "<div>" . app_lang("comment") . ": " . $notification->posts_title . "</div>";
}