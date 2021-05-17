<?php

if ($disable_timer) {
    $start_timer = js_anchor("<i data-feather='clock' class='icon-16'></i> " . app_lang('start_timer'), array('title' => app_lang('start_timer'), "class" => "btn btn-success disabled", "disabled" => "true", "data-action-url" => get_uri("projects/timer/" . $project_id . "/start"), "data-reload-on-success" => "1", "data-post-task_id" => $model_info->id));
} else {
    $start_timer = ajax_anchor(get_uri("projects/timer/" . $project_id . "/start"), "<i data-feather='clock' class='icon-16'></i> " . app_lang('start_timer'), array("class" => "btn btn-success", "title" => app_lang('start_timer'), "data-post-task_id" => $model_info->id, "data-real-target" => "#start-timer-btn-$model_info->id", "data-post-task_timer" => true));
}

$stop_timer = modal_anchor(get_uri("projects/stop_timer_modal_form/" . $project_id), "<i data-feather='clock' class='icon-16'></i> " . app_lang('stop_timer'), array("class" => "btn btn-danger", "title" => app_lang('stop_timer'), "data-post-task_id" => $model_info->id));

if ($timer_status === "open") {
    echo $stop_timer;
} else {
    echo "<span id='start-timer-btn-$model_info->id'>" . $start_timer . "</span>";
}
?>