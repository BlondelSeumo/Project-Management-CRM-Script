<?php

$quick_filters_dropdown = array(
    array("id" => "", "text" => "- " . app_lang("quick_filters") . " -"),
    array("id" => "has_open_projects", "text" => app_lang("has_open_projects")),
    array("id" => "has_completed_projects", "text" => app_lang("has_completed_projects")),
    array("id" => "has_any_hold_projects", "text" => app_lang("has_any_hold_projects")),
    array("id" => "has_unpaid_invoices", "text" => app_lang("has_unpaid_invoices")),
    array("id" => "has_overdue_invoices", "text" => app_lang("has_overdue_invoices")),
    array("id" => "has_partially_paid_invoices", "text" => app_lang("has_partially_paid_invoices"))
);
echo json_encode($quick_filters_dropdown);
?>