<?php

namespace App\Controllers;

class Webhooks_listener extends App_Controller {

    function bitbucket($key) {
        //save bitbucket commit as a activity log of tasks by bitbucket webhook
        //the commit message should be ending with #task_id. ex: Added webhook #233
        $payloads = json_decode(file_get_contents('php://input'));
        if (!$this->_is_valid_payloads_of_bitbucket($payloads, $key)) {
            app_redirect("forbidden");
        }

        $final_commits_array = $this->_get_final_commits_of_bitbucket($payloads);

        if ($final_commits_array) {
            foreach ($final_commits_array as $commit) {
                $task_id = get_array_value($commit, "task_id");
                $task_info = $this->Tasks_model->get_one($task_id);
                if ($task_info->id) {
                    $log_data = array(
                        "action" => "bitbucket_notification_received",
                        "log_type" => "task",
                        "log_type_title" => $task_info->title,
                        "log_type_id" => $task_id,
                        "changes" => serialize(array("bitbucket" => array("from" => "", "to" => $commit))),
                        "log_for" => "project",
                        "log_for_id" => $task_info->project_id,
                    );

                    $save_id = $this->Activity_logs_model->ci_save($log_data, true);

                    if ($save_id) {
                        //send notification
                        $notification_options = array("project_id" => $task_info->project_id, "task_id" => $task_id, "activity_log_id" => $save_id, "user_id" => "999999998");
                        log_notification("bitbucket_push_received", $notification_options);
                    }
                }
            }
        }
    }

    private function _is_valid_payloads_of_bitbucket($payloads, $key) {
        $settings_key = get_setting("enable_bitbucket_commit_logs_in_tasks");
        if ($settings_key && $settings_key == $key && $payloads && $payloads->push) {
            return true;
        } else {
            return false;
        }
    }

    private function _get_final_commits_of_bitbucket($payloads) {
        $changes = get_array_value($payloads->push->changes, 0);
        if ($changes) {
            $repository_name = $payloads->repository->name;
            $branch_name = $changes->new->name;
            $author_name = $changes->new->target->author->user->display_name;
            $author_link = $changes->new->target->author->user->links->html->href;
            $commits = $changes->commits;

            $commits_description = array();
            foreach ($commits as $commit) {
                $commit_url = $commit->links->html->href;
                $commit_message = $commit->message;

                //get the task id 
                $position = strpos($commit_message, "#");
                $task_id = (int) substr($commit_message, $position + 1, strlen($commit_message));

                if (is_int($task_id) && $task_id) {
                    array_push($commits_description, array(
                        "task_id" => $task_id,
                        "commit_url" => $commit_url,
                        "commit_message" => $commit_message
                    ));
                }
            }

            $final_commits_array = array();
            foreach ($commits_description as $key => $value) {
                $task_id = (int) get_array_value($value, "task_id");
                if (is_int($task_id) && $task_id) {
                    if (!in_array($task_id, array_column($final_commits_array, 'task_id'))) {
                        array_push($final_commits_array, array(
                            "repository_name" => $repository_name,
                            "branch_name" => $branch_name,
                            "author_name" => $author_name,
                            "author_link" => $author_link,
                            "task_id" => $task_id,
                            "commits" => array(
                                array(
                                    "commit_url" => get_array_value($value, "commit_url"),
                                    "commit_message" => get_array_value($value, "commit_message")
                                )
                            )
                        ));
                    } else {
                        $commit = array_search($task_id, array_column($final_commits_array, 'task_id'));
                        array_push($final_commits_array[$commit]["commits"], array(
                            "commit_url" => get_array_value($value, "commit_url"),
                            "commit_message" => get_array_value($value, "commit_message")
                        ));
                    }
                }
            }

            return $final_commits_array;
        }
    }

    function github($key) {
        //save github commit as a activity log of tasks by github webhook
        //the commit message should be ending with #task_id. ex: Added webhook #233
        $payloads = json_decode(file_get_contents('php://input'));
        if (!$this->_is_valid_payloads_of_github($payloads, $key)) {
            app_redirect("forbidden");
        }

        $final_commits_array = $this->_get_final_commits_of_github($payloads);

        if ($final_commits_array) {
            foreach ($final_commits_array as $commit) {
                $task_id = get_array_value($commit, "task_id");
                $task_info = $this->Tasks_model->get_one($task_id);
                if ($task_info->id) {
                    $log_data = array(
                        "action" => "github_notification_received",
                        "log_type" => "task",
                        "log_type_title" => $task_info->title,
                        "log_type_id" => $task_id,
                        "changes" => serialize(array("github" => array("from" => "", "to" => $commit))),
                        "log_for" => "project",
                        "log_for_id" => $task_info->project_id,
                    );

                    $save_id = $this->Activity_logs_model->ci_save($log_data, true);

                    if ($save_id) {
                        //send notification
                        $notification_options = array("project_id" => $task_info->project_id, "task_id" => $task_id, "activity_log_id" => $save_id, "user_id" => "999999997");
                        log_notification("github_push_received", $notification_options);
                    }
                }
            }
        }
    }

    private function _is_valid_payloads_of_github($payloads, $key) {
        $settings_key = get_setting("enable_github_commit_logs_in_tasks");
        if ($settings_key && $settings_key == $key && $payloads) {
            return true;
        } else {
            return false;
        }
    }

    private function _get_final_commits_of_github($payloads) {
        $changes = $payloads->commits;
        if ($changes) {
            $repository_name = $payloads->repository->name;

            $branch_name = $payloads->ref;
            $branch_name = explode('/', $branch_name);
            $branch_name = end($branch_name);

            $first_commit = get_array_value($payloads->commits, 0);
            $author_name = $first_commit->author->name;
            $author_link = $payloads->sender->html_url;
            $commits = $changes;

            $commits_description = array();
            foreach ($commits as $commit) {
                $commit_url = $commit->url;
                $commit_message = $commit->message;

                //get the task id 
                $position = strpos($commit_message, "#");
                $task_id = (int) substr($commit_message, $position + 1, strlen($commit_message));

                if (is_int($task_id) && $task_id) {
                    array_push($commits_description, array(
                        "task_id" => $task_id,
                        "commit_url" => $commit_url,
                        "commit_message" => $commit_message
                    ));
                }
            }

            $final_commits_array = array();
            foreach ($commits_description as $key => $value) {
                $task_id = (int) get_array_value($value, "task_id");
                if (is_int($task_id) && $task_id) {
                    if (!in_array($task_id, array_column($final_commits_array, 'task_id'))) {
                        array_push($final_commits_array, array(
                            "repository_name" => $repository_name,
                            "branch_name" => $branch_name,
                            "author_name" => $author_name,
                            "author_link" => $author_link,
                            "task_id" => $task_id,
                            "commits" => array(
                                array(
                                    "commit_url" => get_array_value($value, "commit_url"),
                                    "commit_message" => get_array_value($value, "commit_message")
                                )
                            )
                        ));
                    } else {
                        $commit = array_search($task_id, array_column($final_commits_array, 'task_id'));
                        array_push($final_commits_array[$commit]["commits"], array(
                            "commit_url" => get_array_value($value, "commit_url"),
                            "commit_message" => get_array_value($value, "commit_message")
                        ));
                    }
                }
            }

            return $final_commits_array;
        }
    }

}

/* End of file Webhooks_listener.php */
/* Location: ./app/controllers/Webhooks_listener.php */    