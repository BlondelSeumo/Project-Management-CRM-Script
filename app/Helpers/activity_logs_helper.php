<?php

use App\Controllers\Security_Controller;
use App\Controllers\App_Controller;

/**
 * dynamically generate the activity logs for projects
 *
 * @param string $log_type
 * @param string $field
 * @param string $value
 * @return html
 */
if (!function_exists('get_change_logs')) {

    function get_change_logs($log_type, $field, $value) {
        $log_type = $log_type;
        $from_value = $value['from'];
        $to_value = $value['to'];
        $changes = "";

        $ci = new App_Controller();
        $model_schema = array();
        if ($log_type === "task") {
            $model_schema = $ci->Tasks_model->schema();
        } else if ($log_type === "milestone") {
            $model_schema = $ci->Milestones_model->schema();
        } else if ($log_type === "project_comment") {
            $model_schema = $ci->Project_comments_model->schema();
        } else if ($log_type === "project_file") {
            $model_schema = $ci->Project_files_model->schema();
        } else if ($log_type === "file_comment") {
            $model_schema = $ci->Project_comments_model->schema();
        } else if ($log_type === "task_comment") {
            $model_schema = $ci->Project_comments_model->schema();
        }
        $schema_info = get_array_value($model_schema, $field) ? get_array_value($model_schema, $field) : get_change_logs_of_custom_fields($field);
        if ($schema_info) {
            //prepare change value
            if (get_array_value($schema_info, "type") === "int") {

                if ($field === "sort") {
                    if ($from_value > $to_value) {
                        $changes = app_lang("moved_up");
                    } else {
                        $changes = app_lang("moved_down");
                    }
                } else {
                    $changes = "<del>" . $from_value . "</del> <ins>" . $to_value . "</ins>";
                }
            } else if (get_array_value($schema_info, "type") === "text") {
                $from_value = mb_convert_encoding($from_value, 'HTML-ENTITIES', 'UTF-8');
                $to_value = mb_convert_encoding($to_value, 'HTML-ENTITIES', 'UTF-8');

                require_once(APPPATH . "ThirdParty/cogpowered-finediff/vendor/autoload.php");
                $granularity = new cogpowered\FineDiff\Granularity\Word;
                $finediff = new cogpowered\FineDiff\Diff($granularity);

                $changes = nl2br($finediff->render($from_value, $to_value));
                $changes = mb_convert_encoding($changes, 'ASCII', 'HTML-ENTITIES');
            } else if (get_array_value($schema_info, "type") === "foreign_key") {
                $linked_model = get_array_value($schema_info, "linked_model");
                if ($from_value && $linked_model) {

                    if (get_array_value($schema_info, "link_type") === "user_group_list") {
                        $info = $linked_model->user_group_names($from_value);
                    } else if (get_array_value($schema_info, "link_type") === "label_group_list") {
                        $info = $linked_model->label_group_list($from_value);
                    } else {
                        $info = $linked_model->get_one($from_value);
                    }

                    $label_fields = get_array_value($schema_info, "label_fields");
                    $from_value = "";

                    if ($log_type === "task" && $field === "status_id" && $info->key_name) {
                        //for task status, we have to check the language key
                        $from_value .= app_lang($info->key_name);
                    } else {

                        foreach ($label_fields as $label_field) {
                            if (isset($info->$label_field)) {
                                $from_value .= $info->$label_field . " ";
                            }
                        }
                    }
                }

                if ($to_value && $linked_model) {

                    if (get_array_value($schema_info, "link_type") === "user_group_list") {
                        $info = $linked_model->user_group_names($to_value);
                    } else if (get_array_value($schema_info, "link_type") === "label_group_list") {
                        $info = $linked_model->label_group_list($to_value);
                    } else {
                        $info = $linked_model->get_one($to_value);
                    }

                    $label_fields = get_array_value($schema_info, "label_fields");
                    $to_value = "";

                    if ($log_type === "task" && $field === "status_id" && $info->key_name) {
                        //for task status, we have to check the language key
                        $to_value .= app_lang($info->key_name);
                    } else {
                        foreach ($label_fields as $label_field) {
                            if (isset($info->$label_field)) {
                                $to_value .= $info->$label_field . " ";
                            }
                        }
                    }
                }

                $changes = "<del>" . $from_value . "</del> <ins>" . $to_value . "</ins>";
            } else if (get_array_value($schema_info, "type") === "language_key") {
                $changes = "<del>" . app_lang($from_value) . "</del> <ins>" . app_lang($to_value) . "</ins>";
            } else if (get_array_value($schema_info, "type") === "date") {
                if (is_date_exists($from_value)) {
                    $from_value = format_to_date($from_value, false);
                }

                if (is_date_exists($to_value)) {
                    $to_value = format_to_date($to_value, false);
                }


                $changes = "<del>" . $from_value . "</del> <ins>" . $to_value . "</ins>";
            } else if (get_array_value($schema_info, "type") === "time") {
                $changes = "<del>" . $from_value . "</del> <ins>" . $to_value . "</ins>";
            } else if (get_array_value($schema_info, "type") === "date_time") {
                $changes = "<del>" . $from_value . "</del> <ins>" . $to_value . "</ins>";
            } else {
                $changes = "<del>" . $from_value . "</del> <ins>" . $to_value . "</ins>";
            }

            return get_array_value($schema_info, "label") . ": " . $changes;
        } else {
            return false;
        }
    }

}



/**
 * get change logs of custom fields
 *
 * @param string $log_type
 * @return array
 */
if (!function_exists('get_change_logs_of_custom_fields')) {

    function get_change_logs_of_custom_fields($field, $is_notification = false) {
        $ci = new Security_Controller(false);

        $start = strpos($field, '[:');
        $end = strpos($field, ':]', $start + 1);
        $length = $end - $start;

        $custom_field_data = substr($field, $start + 2, $length - 2);
        $custom_field_label = preg_replace('~\[:.*\:]~', "", $field);

        $explode_custom_fields_data = explode(",", $custom_field_data);

        $custom_field_type = get_array_value($explode_custom_fields_data, "1");
        $visible_to_admins_only = get_array_value($explode_custom_fields_data, "2");
        $hide_from_clients = get_array_value($explode_custom_fields_data, "3");

        if ($is_notification && !$visible_to_admins_only) {
            return "all";
        } else if ($is_notification && $visible_to_admins_only) {
            return "admins_only";
        } else {
            //we have to check if there has any restriction
            if (($visible_to_admins_only && !$ci->login_user->is_admin) || ($hide_from_clients && !$visible_to_admins_only && $ci->login_user->user_type == "client")) {
                return false;
            } else {
                if ($custom_field_type == "date") {
                    return array(
                        "label" => $custom_field_label,
                        "type" => "date"
                    );
                } else {
                    return array(
                        "label" => $custom_field_label,
                        "type" => "text"
                    );
                }
            }
        }
    }

}


/**
 * get logs of bitbucket/github commit
 *
 * @param $changes array
 * @return html
 */
if (!function_exists('get_logs_of_bitbucket_or_github_commit')) {

    function get_logs_of_bitbucket_or_github_commit($changes = array(), $is_notification = false, $service_type = "bitbucket") {
        $commit_info = get_array_value(get_array_value($changes, $service_type), "to");
        $repository_name = get_array_value($commit_info, "repository_name");
        $branch_name = get_array_value($commit_info, "branch_name");
        $author_name = get_array_value($commit_info, "author_name");
        $author_link = anchor(get_array_value($commit_info, "author_link"), get_array_value($commit_info, "author_name"), array("target" => "_blank"));
        $branch_name_with_repository = $repository_name . "/" . $branch_name;
        $commits = get_array_value($commit_info, "commits");

        $commits_count = count($commits);
        $commits_amount = "0";
        $commits_text = "";

        if ($commits_count) {
            if ($commits_count > 5) {
                $commits_amount = "5+";
            } else {
                $commits_amount = $commits_count;
            }
            if ($commits_count > 1) {
                $commits_text = strtolower(app_lang("new_commits"));
            } else {
                $commits_text = strtolower(app_lang("new_commit"));
            }
        }

        $changes_array = array();

        //first value is commit details
        if ($is_notification) {
            array_push($changes_array, "[" . $branch_name_with_repository . "] " . $commits_amount . " " . $commits_text . " " . strtolower(app_lang("pushed_by")) . " " . $author_name);
        } else {
            array_push($changes_array, "[<strong>" . $branch_name_with_repository . "</strong>] " . $commits_amount . " " . $commits_text . " " . strtolower(app_lang("pushed_by")) . " <strong>" . ($is_notification ? $author_name : $author_link) . "</strong>");
        }

        foreach ($commits as $commit) {
            $commit_message = get_array_value($commit, "commit_message");
            array_push($changes_array, "<li>" . ($is_notification ? $commit_message : anchor(get_array_value($commit, "commit_url"), $commit_message, array("target" => "_blank"))) . "</li>");
        }


        return $changes_array;
    }

}

/*
 * get the array of change logs
 * 
 * @param array $changes
 * @param string $log_type
 * @param string $action
 * @return array
 */
if (!function_exists('get_change_logs_array')) {

    function get_change_logs_array($changes, $log_type, $action = "", $is_notification = false) {
        $changes_array = array();

        if ($changes !== "") {
            $changes = unserialize($changes);

            if (is_array($changes)) {
                if ($action === "bitbucket_notification_received" || $action === "bitbucket_push_received") {
                    $changes_array = get_logs_of_bitbucket_or_github_commit($changes, $is_notification);
                } else if ($action === "github_notification_received" || $action === "github_push_received") {
                    $changes_array = get_logs_of_bitbucket_or_github_commit($changes, $is_notification, "github");
                } else if ($action === "all" || $action === "updated") {
                    foreach ($changes as $field => $value) {
                        if ($is_notification) {
                            array_push($changes_array, get_change_logs_of_custom_fields($field, $is_notification));
                        } else {
                            $change_log = get_change_logs($log_type, $field, $value);
                            if ($change_log) {
                                array_push($changes_array, "<li>" . $change_log . "</li>");
                            }
                        }
                    }
                }
            }
        }

        return $changes_array;
    }

}
