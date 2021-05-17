<?php

$estimate_statuses_dropdown = array(
    array("id" => "", "text" => "- " . app_lang("status") . " -"),
    array("id" => "draft", "text" => app_lang("draft")),
    array("id" => "sent", "text" => app_lang("sent")),
    array("id" => "accepted", "text" => app_lang("accepted")),
    array("id" => "declined", "text" => app_lang("declined"))
);
echo json_encode($estimate_statuses_dropdown);
