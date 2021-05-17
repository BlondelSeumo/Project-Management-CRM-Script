<?php

$invoice_statuses_dropdown = array(
    array("id" => "", "text" => "- " . app_lang("status") . " -"),
    array("id" => "overdue", "text" => app_lang("overdue")),
    array("id" => "draft", "text" => app_lang("draft")),
    array("id" => "not_paid", "text" => app_lang("not_paid")),
    array("id" => "partially_paid", "text" => app_lang("partially_paid")),
    array("id" => "fully_paid", "text" => app_lang("fully_paid")),
    array("id" => "cancelled", "text" => app_lang("cancelled"))
);
echo json_encode($invoice_statuses_dropdown);
?>