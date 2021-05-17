<?php

$quick_filters_dropdown = array(
    array("id" => "", "text" => "- " . app_lang("quick_filters") . " -"),
    array("id" => "recently_updated", "text" => app_lang("recently_updated"))
);

foreach ($task_statuses as $task_status) {
    $quick_filters_dropdown[] = array("id" => $task_status->id, "text" => $task_status->key_name ? app_lang("recently_moved_to") . " " . app_lang($task_status->key_name) : app_lang("recently_moved_to") . " " . $task_status->title);
}

$quick_filters_dropdown[] = array("id" => "recently_commented", "text" => app_lang("recently_commented"));
$quick_filters_dropdown[] = array("id" => "mentioned_me", "text" => app_lang("mentioned_me"));
$quick_filters_dropdown[] = array("id" => "recently_mentioned_me", "text" => app_lang("recently_mentioned_me"));
$quick_filters_dropdown[] = array("id" => "recently_meaning", "text" => "<i data-feather='settings' class='icon-16'></i> " . app_lang("recently_meaning"));

echo json_encode($quick_filters_dropdown);
?>