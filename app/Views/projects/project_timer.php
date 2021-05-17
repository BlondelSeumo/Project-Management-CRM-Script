<?php

if ($disable_timer) {
    $start_timer = js_anchor("<i data-feather='clock' class='icon-16'></i> " . app_lang('start_timer'), array('title' => app_lang('start_timer'), "class" => "btn btn-success disabled", "disabled" => "true", "data-action-url" => get_uri("projects/timer/" . $project_info->id . "/start"), "data-reload-on-success" => "1"));
} else {
    $start_timer = ajax_anchor(get_uri("projects/timer/" . $project_info->id . "/start"), "<i data-feather='clock' class='icon-16'></i> " . app_lang('start_timer'), array("class" => "btn btn-success", "id" => "start_timer", "title" => app_lang('start_timer'), "data-reload-on-success" => "1"));
}

$stop_timer = modal_anchor(get_uri("projects/stop_timer_modal_form/" . $project_info->id), "<i data-feather='clock' class='icon-16'></i> " . app_lang('stop_timer'), array("class" => "btn btn-danger", "title" => app_lang('stop_timer')));

if ($timer_status === "open") {
    echo $stop_timer;
} else {
    echo $start_timer;
}
?>